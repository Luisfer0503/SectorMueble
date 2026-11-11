<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;
use Tests\TestCase;

class StripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private array $sessions = [];
    private array $cart;
    private int $variant;
    private array $address = [
        'nombre_cliente' => 'Cliente Prueba', 'correo_cliente' => 'cliente@example.test',
        'telefono_cliente' => '2225550000', 'direccion_envio' => 'Calle 1',
        'ciudad' => 'Cholula', 'codigo_postal' => '72760',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.stripe.secret' => 'sk_test_fake', 'services.stripe.webhook_secret' => 'whsec_fixture']);
        Notification::fake();
        $this->actingAs(User::factory()->create());
        $product = DB::table('productos')->insertGetId([
            'nombre' => 'Silla', 'slug' => 'silla-prueba', 'descripcion' => 'Silla',
            'imagen_url' => 'silla.jpg', 'categoria' => 'Sillas', 'activo' => true,
        ]);
        $this->variant = DB::table('producto_detalles')->insertGetId([
            'producto_id' => $product, 'nombre' => 'Roble', 'precio' => 1000, 'stock' => 1, 'activo' => true,
        ]);
        $this->cart = [['producto_id' => $product, 'subarticulo_id' => $this->variant, 'cantidad' => 1, 'precio' => 1000]];
        $http = $this->createStub(ClientInterface::class);
        $http->method('request')->willReturnCallback(function ($method, $url, $headers, $params) {
            if ($method === 'post' && str_ends_with($url, '/sessions')) {
                $id = 'cs_test_' . (count($this->sessions) + 1);
                $total = array_sum(array_map(fn ($i) => $i['price_data']['unit_amount'] * $i['quantity'], $params['line_items']));
                $this->sessions[$id] = [
                    'object' => 'checkout.session', 'id' => $id, 'url' => 'https://example.test/' . $id,
                    'status' => 'open', 'mode' => 'payment', 'payment_status' => 'unpaid', 'currency' => 'mxn',
                    'amount_total' => $total, 'metadata' => $params['metadata'], 'client_reference_id' => $params['client_reference_id'],
                    'created' => time(),
                ];
            } elseif (str_ends_with($url, '/expire')) {
                $id = basename(dirname($url));
                $this->sessions[$id]['status'] = 'expired';
            } else {
                $id = basename($url);
            }
            return [json_encode($this->sessions[$id]), 200, []];
        });
        ApiRequestor::setHttpClient($http);
    }

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(null);
        parent::tearDown();
    }

    private function checkout()
    {
        return $this->withSession(['carrito' => $this->cart])->postJson('/checkout/crear-sesion-pago', $this->address);
    }

    private function event(string $type, array $session, bool $signed = true)
    {
        $payload = json_encode(['object' => 'event', 'id' => 'evt_fixture', 'type' => $type, 'data' => ['object' => $session]]);
        $time = time();
        $signature = hash_hmac('sha256', "$time.$payload", 'whsec_fixture');
        return $this->call('POST', '/stripe/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => $signed ? "t=$time,v1=$signature" : '',
        ], $payload);
    }

    public function test_retry_reuses_session_and_changed_address_replaces_it(): void
    {
        $this->checkout()->assertOk();
        $this->checkout()->assertOk()->assertJsonPath('checkout_url', $this->sessions['cs_test_1']['url']);
        $this->assertDatabaseCount('pedidos', 1);
        $this->address['direccion_envio'] = 'Calle 2';
        $this->checkout()->assertOk();
        $this->assertSame('expired', $this->sessions['cs_test_1']['status']);
        $this->assertDatabaseHas('pedidos', ['stripe_session_id' => 'cs_test_1', 'estado' => 'cancelado', 'direccion_envio' => 'Calle 1']);
        $this->assertDatabaseHas('producto_detalles', ['id' => $this->variant, 'stock' => 0]);
    }

    public function test_last_unit_is_reserved_until_signed_expiration(): void
    {
        $this->checkout()->assertOk();
        $session = $this->sessions['cs_test_1'];
        $this->actingAs(User::factory()->create());
        $this->checkout()->assertUnprocessable();
        $this->get('/checkout/pago-cancelado?pedido_id=1')->assertRedirect();
        $this->event('checkout.session.expired', $session, false)->assertBadRequest();
        $this->assertDatabaseHas('pedidos', ['estado' => 'pendiente', 'stock_reservado' => 1]);
        $this->event('checkout.session.expired', $session)->assertOk();
        $this->event('checkout.session.expired', $session)->assertOk();
        $this->assertDatabaseHas('producto_detalles', ['stock' => 1]);
        $this->checkout()->assertOk();
    }

    public function test_webhook_and_return_confirm_once_and_preserve_paid_state(): void
    {
        $this->checkout()->assertOk();
        $session = &$this->sessions['cs_test_1'];
        $this->event('checkout.session.completed', $session)->assertOk();
        $this->assertDatabaseHas('pedidos', ['estado' => 'pendiente']);
        $session['payment_status'] = 'paid';
        $session['status'] = 'complete';
        $this->event('checkout.session.completed', $session)->assertOk();
        $this->checkout()->assertOk()->assertJsonPath('checkout_url', route('checkout.stripe.exito', ['session_id' => $session['id']]));
        $this->assertDatabaseCount('pedidos', 1);
        $this->event('checkout.session.async_payment_succeeded', $session)->assertOk();
        $this->event('checkout.session.expired', $session)->assertOk();
        $this->get('/checkout/pago-exito?session_id=cs_test_1')->assertRedirect(route('pedido.confirmado', 1));
        Notification::assertCount(1);
        $this->assertDatabaseHas('pedidos', ['estado' => 'procesando', 'stock_reservado' => 0]);
        $this->assertDatabaseHas('producto_detalles', ['stock' => 0]);
        $session['payment_status'] = 'unpaid';
        Pedido::firstOrFail()->obtenerDiagnosticoStripe();
        $this->assertDatabaseHas('pedidos', ['estado' => 'procesando']);
    }

    public function test_async_failure_releases_reservation_and_works_without_webhook_secret(): void
    {
        $this->checkout()->assertOk();
        $this->assertDatabaseHas('producto_detalles', ['id' => $this->variant, 'stock' => 0, 'agotado' => 1]);
        $this->event('checkout.session.async_payment_failed', $this->sessions['cs_test_1'])->assertOk();
        $this->assertDatabaseHas('producto_detalles', ['id' => $this->variant, 'stock' => 1, 'agotado' => 0]);

        // Incluso sin STRIPE_WEBHOOK_SECRET configurado en .env, el checkout permite pagar usando STRIPE_SECRET
        config(['services.stripe.webhook_secret' => null]);
        $this->checkout()->assertOk();
        $this->assertDatabaseCount('pedidos', 2);

        // Si falta STRIPE_SECRET por completo, sí bloquea el checkout con 422
        config(['services.stripe.secret' => null]);
        $this->checkout()->assertUnprocessable();
        $this->event('checkout.session.expired', $this->sessions['cs_test_2'])->assertStatus(503);
    }

    public function test_return_after_3d_secure_without_cookie_succeeds_and_expired_reservation_auto_releases(): void
    {
        $this->checkout()->assertOk();
        $session = &$this->sessions['cs_test_1'];
        $session['payment_status'] = 'paid';
        $session['status'] = 'complete';

        // Un usuario distinto autenticado recibe 403
        $this->actingAs(User::factory()->create());
        $this->get('/checkout/pago-exito?session_id=cs_test_1')->assertForbidden();

        // Si el navegador perdió la sesión tras 3D Secure (guest), el pago válido en Stripe sí se confirma
        auth()->logout();
        $this->get('/checkout/pago-exito?session_id=cs_test_1')->assertRedirect(route('pedido.confirmado', 1));
        $this->assertDatabaseHas('pedidos', ['id' => 1, 'estado' => 'procesando', 'stock_reservado' => 0]);
    }

    public function test_paid_event_with_wrong_amount_currency_or_session_cannot_confirm(): void
    {
        $this->checkout()->assertOk();
        $session = $this->sessions['cs_test_1'];
        $session['payment_status'] = 'paid';
        foreach ([['amount_total' => 1], ['currency' => 'usd'], ['id' => 'cs_other']] as $change) {
            $this->event('checkout.session.completed', array_replace($session, $change))->assertServerError();
            $this->assertDatabaseHas('pedidos', ['estado' => 'pendiente', 'stock_reservado' => 1]);
        }
    }
}
