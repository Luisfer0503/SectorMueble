<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\User;
use App\Models\Cupon;
use App\Models\RuletaOpcion;
use App\Models\CatalogoCodigoPostal;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrincipalController extends Controller
{
    /**
     * Página de inicio con productos destacados y categorías.
     */
    public function inicio()
    {
        $productosDestacados = Producto::where('destacado', true)->take(4)->get();
        
        $categorias = Producto::select('categoria')
            ->distinct()
            ->pluck('categoria');

        return view('Principal.inicio', compact('productosDestacados', 'categorias'));
    }

    /**
     * Catálogo completo con buscador, filtrado por categorías, rango de precios y ordenación.
     */
    public function catalogo(Request $request)
    {
        $consulta = Producto::query();

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $consulta->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria') && $request->categoria !== 'todas') {
            $consulta->where('categoria', $request->categoria);
        }

        // Filtro por precio mínimo (respeta precio con descuento)
        if ($request->filled('precio_min')) {
            $min = (float) $request->precio_min;
            $consulta->where(function ($q) use ($min) {
                $q->where(function ($q2) use ($min) {
                    $q2->whereNotNull('precio_descuento')
                       ->where('precio_descuento', '>=', $min);
                })->orWhere(function ($q2) use ($min) {
                    $q2->whereNull('precio_descuento')
                       ->where('precio', '>=', $min);
                });
            });
        }

        // Filtro por precio máximo (respeta precio con descuento)
        if ($request->filled('precio_max')) {
            $max = (float) $request->precio_max;
            $consulta->where(function ($q) use ($max) {
                $q->where(function ($q2) use ($max) {
                    $q2->whereNotNull('precio_descuento')
                       ->where('precio_descuento', '<=', $max);
                })->orWhere(function ($q2) use ($max) {
                    $q2->whereNull('precio_descuento')
                       ->where('precio', '<=', $max);
                });
            });
        }

        // Filtro solo en oferta
        if ($request->filled('oferta') && $request->oferta == '1') {
            $consulta->where('porcentaje_descuento', '>', 0)
                     ->whereNotNull('precio_descuento');
        }

        // Ordenación (usa precio efectivo)
        switch ($request->input('ordenar', 'novedad')) {
            case 'precio_asc':
                $consulta->orderByRaw('COALESCE(precio_descuento, precio) ASC');
                break;
            case 'precio_desc':
                $consulta->orderByRaw('COALESCE(precio_descuento, precio) DESC');
                break;
            case 'calificacion_desc':
                $consulta->orderBy('calificacion', 'desc');
                break;
            default:
                $consulta->orderBy('created_at', 'desc');
                break;
        }

        $productos = $consulta->paginate(9)->withQueryString();

        $categorias = Producto::select('categoria')
            ->distinct()
            ->pluck('categoria');

        // Petición AJAX → devolver solo el partial del grid
        if ($request->ajax() || $request->has('_ajax')) {
            return view('Principal.partials.productos-grid', compact('productos'));
        }

        return view('Principal.catalogo', compact('productos', 'categorias'));
    }

    /**
     * Detalle de un mueble específico.
     */
    public function detalle($id)
    {
        $producto = Producto::with('detalles')->findOrFail($id);
        
        $productosRelacionados = Producto::where('categoria', $producto->categoria)
            ->where('id', '!=', $producto->id)
            ->take(4)
            ->get();

        return view('Principal.detalle', compact('producto', 'productosRelacionados'));
    }

    /**
     * Ver el carrito de compras.
     */
    public function carrito()
    {
        $carrito = session()->get('carrito', []);
        
        $subtotal = 0;
        foreach ($carrito as $item) {
            $subtotal += $item['precio'] * $item['cantidad'];
        }

        // Calcular cupón de descuento
        $descuento = 0.00;
        $cuponAplicado = session()->get('cupon');
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
            if ($cupon) {
                $descuento = $cupon->calcularDescuento($subtotal);
            } else {
                session()->forget('cupon');
                $cuponAplicado = null;
            }
        }

        // Envío gratis si alcanza o supera los $10,000 MXN, si no, $400 MXN de coste de envío
        $envio = ($subtotal >= 10000 || $subtotal == 0) ? 0 : 400.00;
        $total = max(0, $subtotal - $descuento) + $envio;

        return view('Principal.carrito', compact('carrito', 'subtotal', 'envio', 'descuento', 'total', 'cuponAplicado'));
    }

    /**
     * Agregar un mueble al carrito.
     */
    public function agregarAlCarrito(Request $request, $id)
    {
        $producto = Producto::with('detalles')->findOrFail($id);
        $cantidad = (int) $request->input('cantidad', 1);
        $subarticuloId = $request->input('subarticulo_id');
        $colorNombre = $request->input('color');

        // Buscar el detalle/subartículo específico (soporta IDs numéricos y en cadena)
        $detalle = null;
        if (!empty($subarticuloId)) {
            $subIdInt = (int)$subarticuloId;
            $detalle = $producto->detalles->first(function ($det) use ($subIdInt) {
                return (int)$det->id === $subIdInt;
            });
        }
        if (!$detalle && !empty($colorNombre)) {
            $cName = trim(mb_strtolower($colorNombre));
            $detalle = $producto->detalles->first(function ($det) use ($cName) {
                return trim(mb_strtolower($det->nombre)) === $cName;
            });
        }
        if (!$detalle) {
            $detalle = $producto->detalles->first();
        }

        $detalleId = $detalle ? $detalle->id : 0;
        $itemKey = $detalle ? "{$producto->id}_{$detalle->id}" : (string)$producto->id;

        $nombreSub = $detalle ? $detalle->nombre : ($colorNombre ?? 'Original / Natural');
        $skuSub = $detalle ? $detalle->sku : "SKU-{$producto->id}";
        $precioUnitario = $detalle && $detalle->precio !== null ? (float)$detalle->precio : (float)$producto->precio;
        $imagenUrl = $detalle && !empty($detalle->imagen) ? $detalle->imagen_url : $producto->imagen_url;
        
        // Stock disponible: preferencia al stock del subartículo, fallback al stock del producto si es cero
        $stockDisponible = ($detalle && (int)$detalle->stock > 0) ? (int)$detalle->stock : (int)max($producto->stock, 1);

        // Validar stock disponible
        if ($stockDisponible < $cantidad) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficiente stock de este acabado.',
                ], 422);
            }
            return redirect()->back()->with('error', 'No hay suficiente stock de este acabado.');
        }

        $carrito = session()->get('carrito', []);

        // Si ya existe este subartículo específico, se suma la cantidad
        if (isset($carrito[$itemKey])) {
            $nuevaCantidad = $carrito[$itemKey]['cantidad'] + $cantidad;
            if ($stockDisponible < $nuevaCantidad) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No puedes añadir más unidades de este acabado (stock máximo alcanzado).',
                    ], 422);
                }
                return redirect()->back()->with('error', 'No puedes añadir más unidades de este acabado (stock máximo alcanzado).');
            }
            $carrito[$itemKey]['cantidad'] = $nuevaCantidad;
        } else {
            $tieneDesc = $producto->tieneDescuento();
            $descPct = (float)($producto->porcentaje_descuento ?? 0);
            $precioEf = $tieneDesc ? ($precioUnitario * (1 - $descPct / 100)) : $precioUnitario;

            $carrito[$itemKey] = [
                'producto_id'       => $producto->id,
                'subarticulo_id'    => $detalleId,
                'nombre'            => $producto->nombre,
                'subarticulo_nombre' => $nombreSub,
                'sku'               => $skuSub,
                'precio'            => $precioEf,
                'precio_original'   => $precioUnitario,
                'con_descuento'     => $tieneDesc,
                'imagen_url'        => $imagenUrl,
                'cantidad'          => $cantidad,
                'categoria'         => $producto->categoria,
                'color'             => $nombreSub,
                'stock_disponible'  => $stockDisponible,
            ];
        }

        session()->put('carrito', $carrito);
        $this->sincronizarCarritoUsuario();

        // Si es petición AJAX → devolver JSON con el nuevo conteo
        if ($request->ajax()) {
            $totalItems = array_sum(array_column(session('carrito', []), 'cantidad'));
            return response()->json([
                'success' => true,
                'message' => '¡El mueble ha sido añadido al carrito!',
                'count'   => $totalItems,
            ]);
        }

        return redirect()->route('carrito')->with('success', '¡El mueble ha sido añadido al carrito!');
    }

    /**
     * Actualizar la cantidad de un ítem en el carrito.
     */
    public function actualizarCarrito(Request $request, $itemKey)
    {
        $cantidad = (int) $request->input('cantidad');

        if ($cantidad < 1) {
            return redirect()->back()->with('error', 'La cantidad mínima es 1.');
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$itemKey])) {
            $stockDisponible = $carrito[$itemKey]['stock_disponible'] ?? 99;
            if ($stockDisponible < $cantidad) {
                return redirect()->back()->with('error', 'No hay suficiente stock para la cantidad solicitada.');
            }
            $carrito[$itemKey]['cantidad'] = $cantidad;
            session()->put('carrito', $carrito);
            $this->sincronizarCarritoUsuario();
            return redirect()->route('carrito')->with('success', 'Carrito actualizado correctamente.');
        }

        return redirect()->route('carrito')->with('error', 'El mueble no existe en tu carrito.');
    }

    /**
     * Eliminar un producto del carrito.
     */
    public function eliminarDelCarrito($itemKey)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$itemKey])) {
            unset($carrito[$itemKey]);
            session()->put('carrito', $carrito);
            $this->sincronizarCarritoUsuario();
            return redirect()->route('carrito')->with('success', 'Mueble eliminado del carrito.');
        }

        return redirect()->route('carrito')->with('error', 'El mueble no estaba en el carrito.');
    }

    /**
     * Mostrar la vista para finalizar compra (Checkout).
     */
    public function finalizarCompra()
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'Tu carrito está vacío. Agrega algún mueble para comprar.');
        }

        $subtotal = 0;
        foreach ($carrito as $item) {
            $subtotal += $item['precio'] * $item['cantidad'];
        }

        // Calcular cupón de descuento
        $descuento = 0.00;
        $cuponAplicado = session()->get('cupon');
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
            if ($cupon) {
                $descuento = $cupon->calcularDescuento($subtotal);
            } else {
                session()->forget('cupon');
                $cuponAplicado = null;
            }
        }

        $envio = ($subtotal >= 10000) ? 0 : 400.00;
        $total = max(0, $subtotal - $descuento) + $envio;

        return view('Principal.checkout', compact('carrito', 'subtotal', 'envio', 'descuento', 'total', 'cuponAplicado'));
    }

    /**
     * Procesar el pago y guardar el pedido.
     */
    public function procesarCompra(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'Tu carrito está vacío.');
        }

        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'correo_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'direccion_envio' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:10',
        ]);

        $subtotal = 0;
        foreach ($carrito as $id => $item) {
            // Verificar stock en tiempo real
            $producto = Producto::find($id);
            if (!$producto || $producto->stock < $item['cantidad']) {
                return redirect()->route('carrito')->with('error', 'El producto ' . ($producto ? $producto->nombre : '') . ' ya no tiene stock suficiente. Ajusta tu carrito.');
            }
            $subtotal += $item['precio'] * $item['cantidad'];
        }

        // Calcular cupón de descuento
        $descuento = 0.00;
        $cuponCodigo = null;
        $cuponAplicado = session()->get('cupon');
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
            if ($cupon) {
                $descuento = $cupon->calcularDescuento($subtotal);
                $cuponCodigo = $cupon->codigo;
            }
        }

        $envio = ($subtotal >= 10000) ? 0 : 400.00;
        $total = max(0, $subtotal - $descuento) + $envio;

        try {
            DB::beginTransaction();

            // 1. Crear Pedido
            $pedido = Pedido::create([
                'usuario_id' => auth()->id(), // Se asocia con el usuario autenticado
                'nombre_cliente' => $request->nombre_cliente,
                'correo_cliente' => $request->correo_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'direccion_envio' => $request->direccion_envio,
                'ciudad' => $request->ciudad,
                'codigo_postal' => $request->codigo_postal,
                'total' => $total,
                'cupon_codigo' => $cuponCodigo,
                'descuento' => $descuento,
                'estado' => 'pendiente',
            ]);

            // 2. Crear Detalles y reducir Stock
            foreach ($carrito as $id => $item) {
                $producto = Producto::find($id);
                
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'nombre_producto' => $item['nombre'],
                    'precio' => $item['precio'],
                    'cantidad' => $item['cantidad'],
                ]);

                // Descontar stock
                $producto->decrement('stock', $item['cantidad']);
            }

            // Vaciar carrito y cupón en sesión y base de datos
            session()->forget('carrito');
            session()->forget('cupon');
            if (auth()->check()) {
                auth()->user()->update(['carrito_guardado' => null]);
            }

            return redirect()->route('pedido.confirmado', $pedido->id)->with('success', '¡Tu compra ha sido procesada con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al procesar tu compra. Por favor, inténtalo de nuevo. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Crea una sesión de pago segura con Stripe Checkout.
     */
    public function crearSesionPagoStripe(Request $request)
    {
        $request->validate([
            'nombre_cliente'   => 'required|string|max:255',
            'correo_cliente'   => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'direccion_envio'  => 'required|string|max:255',
            'ciudad'           => 'required|string|max:100',
            'codigo_postal'    => 'required|string|max:10',
        ]);

        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito de compras está vacío.'
            ], 422);
        }

        // Almacenar datos temporales de entrega en sesión
        session()->put('datos_envio_checkout', [
            'nombre_cliente'   => $request->nombre_cliente,
            'correo_cliente'   => $request->correo_cliente,
            'telefono_cliente' => $request->telefono_cliente,
            'direccion_envio'  => $request->direccion_envio,
            'ciudad'           => $request->ciudad,
            'codigo_postal'    => $request->codigo_postal,
        ]);

        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return response()->json([
                'success' => false,
                'message' => 'La clave STRIPE_SECRET no está configurada en el archivo .env del servidor.'
            ], 422);
        }

        try {
            $stripe = new \Stripe\StripeClient($stripeSecret);

            $lineItems = [];
            foreach ($carrito as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => config('services.stripe.currency', 'mxn'),
                        'product_data' => [
                            'name' => $item['nombre'],
                        ],
                        'unit_amount' => (int) round($item['precio'] * 100),
                    ],
                    'quantity' => $item['cantidad'],
                ];
            }

            // Calcular cupón de descuento y envío si aplica
            $subtotal = 0;
            foreach ($carrito as $item) {
                $subtotal += $item['precio'] * $item['cantidad'];
            }
            $descuento = 0.00;
            $cuponAplicado = session()->get('cupon');
            if ($cuponAplicado) {
                $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
                if ($cupon) {
                    $descuento = $cupon->calcularDescuento($subtotal);
                }
            }
            $envio = ($subtotal >= 10000) ? 0 : 400.00;

            if ($envio > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => config('services.stripe.currency', 'mxn'),
                        'product_data' => [
                            'name' => 'Costo de Envío Especial',
                        ],
                        'unit_amount' => (int) round($envio * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            $session = $stripe->checkout->sessions->create([
                'line_items'     => $lineItems,
                'mode'           => 'payment',
                'customer_email' => $request->correo_cliente,
                'success_url'    => route('checkout.stripe.exito') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'     => route('checkout.stripe.cancelado'),
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con Stripe: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Procesa la confirmación exitosa de pago en Stripe.
     */
    public function confirmarPagoStripe(Request $request)
    {
        $sessionId = $request->query('session_id');
        $datosEnvio = session()->get('datos_envio_checkout');
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('inicio')->with('info', 'Tu pedido ya ha sido registrado.');
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($carrito as $item) {
                $subtotal += $item['precio'] * $item['cantidad'];
            }

            $descuento = 0.00;
            $cuponCodigo = null;
            $cuponAplicado = session()->get('cupon');
            if ($cuponAplicado) {
                $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
                if ($cupon) {
                    $descuento = $cupon->calcularDescuento($subtotal);
                    $cuponCodigo = $cupon->codigo;
                }
            }

            $envio = ($subtotal >= 10000) ? 0 : 400.00;
            $total = max(0, $subtotal - $descuento) + $envio;

            $pedido = Pedido::create([
                'usuario_id'       => auth()->id(),
                'nombre_cliente'   => $datosEnvio['nombre_cliente'] ?? auth()->user()->name,
                'correo_cliente'   => $datosEnvio['correo_cliente'] ?? auth()->user()->email,
                'telefono_cliente' => $datosEnvio['telefono_cliente'] ?? '0000000000',
                'direccion_envio'  => $datosEnvio['direccion_envio'] ?? 'Dirección registrada',
                'ciudad'           => $datosEnvio['ciudad'] ?? 'Puebla',
                'codigo_postal'    => $datosEnvio['codigo_postal'] ?? (auth()->user()->codigo_postal ?? '72000'),
                'total'            => $total,
                'cupon_codigo'     => $cuponCodigo,
                'descuento'        => $descuento,
                'estado'           => 'completado',
            ]);

            foreach ($carrito as $id => $item) {
                $producto = Producto::find($id);
                if ($producto) {
                    DetallePedido::create([
                        'pedido_id'       => $pedido->id,
                        'producto_id'     => $producto->id,
                        'nombre_producto' => $item['nombre'],
                        'precio'          => $item['precio'],
                        'cantidad'        => $item['cantidad'],
                    ]);
                    $producto->decrement('stock', $item['cantidad']);
                }
            }

            DB::commit();

            // Vaciar carrito en sesión y base de datos
            session()->forget('carrito');
            session()->forget('cupon');
            session()->forget('datos_envio_checkout');
            if (auth()->check()) {
                auth()->user()->update(['carrito_guardado' => null]);
            }

            return redirect()->route('pedido.confirmado', $pedido->id)->with('success', '¡Pago procesado con éxito en Stripe! Tu pedido ha sido confirmado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout')->with('error', 'Ocurrió un error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Maneja el retorno cuando el pago en Stripe es cancelado.
     */
    public function cancelarPagoStripe()
    {
        return redirect()->route('checkout')->with('info', 'El proceso de pago en Stripe fue cancelado. Tus productos siguen guardados en tu carrito.');
    }

    /**
     * Vista de éxito/confirmación de compra.
     */
    public function pedidoConfirmado($id = null)
    {
        $pedido = null;
        if ($id && is_numeric($id)) {
            $pedido = Pedido::with('detalles')->find($id);
        }

        // Si el ID específico no existe, buscar el último pedido del usuario en la base de datos
        if (!$pedido && auth()->check()) {
            $pedido = Pedido::with('detalles')->where('usuario_id', auth()->id())->latest()->first();
        }

        // Si aún no se encuentra, obtener el último pedido registrado en la tienda
        if (!$pedido) {
            $pedido = Pedido::with('detalles')->latest()->first();
        }

        // Si la base de datos estuviera vacía, construir objeto de respaldo para renderizar la vista de éxito
        if (!$pedido) {
            $pedido = new Pedido([
                'id' => 1,
                'nombre_cliente' => auth()->user()->name ?? 'Cliente Sector Mueble',
                'correo_cliente' => auth()->user()->email ?? 'cliente@sectormueble.com',
                'telefono_cliente' => '2220000000',
                'direccion_envio' => 'Dirección registrada',
                'ciudad' => 'Puebla',
                'codigo_postal' => '72000',
                'total' => 0,
                'estado' => 'completado',
                'created_at' => now(),
            ]);
            $pedido->setRelation('detalles', collect([]));
        }

        return view('Principal.confirmado', compact('pedido'));
    }

    // --- APLICAR / QUITAR CUPONES EN CARRITO ---

    /**
     * Valida y aplica un cupón de descuento en la sesión.
     */
    public function aplicarCupon(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $cupon = Cupon::where('codigo', strtoupper($request->codigo))
            ->where('activo', true)
            ->first();

        if (!$cupon) {
            return redirect()->back()->with('error', 'El cupón no es válido o ha expirado.');
        }

        session()->put('cupon', [
            'codigo' => $cupon->codigo,
            'tipo' => $cupon->tipo,
            'valor' => $cupon->valor,
        ]);

        return redirect()->back()->with('success', '¡Cupón de descuento aplicado con éxito!');
    }

    /**
     * Remueve el cupón de la sesión.
     */
    public function quitarCupon()
    {
        session()->forget('cupon');
        return redirect()->back()->with('success', 'Cupón removido del carrito.');
    }

    // --- MÉTODOS DE AUTENTICACIÓN ---

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function mostrarLogin()
    {
        if (auth()->check()) {
            return redirect()->route('checkout');
        }
        return view('Principal.login');
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     */
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Preservar estado del carrito y cupón antes de regenerar la sesión
        $carritoPrevio = session()->get('carrito', []);
        $cuponPrevio = session()->get('cupon');

        if (auth()->attempt($credenciales, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = auth()->user();

            // Sincronizar estado de ruleta jugada
            if ($user->ruleta_jugada) {
                session()->put('ruleta_jugada', true);
            }

            // Sincronizar Código Postal de usuario y sesión
            if ($user->codigo_postal) {
                session(['codigo_postal' => $user->codigo_postal]);
            } elseif (session('codigo_postal')) {
                $user->update(['codigo_postal' => session('codigo_postal')]);
            }

            // Restaurar y fusionar el carrito guardado en la base de datos del usuario
            $carritoBaseDatos = !empty($user->carrito_guardado) ? json_decode($user->carrito_guardado, true) : [];
            $carritoActual = !empty($carritoPrevio) ? $carritoPrevio : [];
            $carritoFusionado = array_replace($carritoBaseDatos, $carritoActual);

            if (!empty($carritoFusionado)) {
                session()->put('carrito', $carritoFusionado);
                $user->update(['carrito_guardado' => json_encode($carritoFusionado)]);
                
                // Activar aviso de productos esperando
                session()->flash('notificacion_carrito_abandonado', '¡Tus productos te están esperando! Tienes ' . array_sum(array_column($carritoFusionado, 'cantidad')) . ' mueble(s) guardados en tu carrito.');
            }

            if (!empty($cuponPrevio)) {
                session()->put('cupon', $cuponPrevio);
            }

            // Si es administrador, mandarlo al dashboard por defecto
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenido al Panel de Administración.');
            }

            $targetUrl = !empty($carritoFusionado) ? route('carrito') : route('inicio');
            return redirect()->intended($targetUrl)->with('success', '¡Bienvenido de nuevo, ' . $user->name . '! Tus productos guardados te están esperando en tu carrito.');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Muestra el formulario de registro.
     */
    public function mostrarRegistro()
    {
        if (auth()->check()) {
            return redirect()->route('carrito');
        }
        return view('Principal.registro');
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function registro(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'codigo_postal' => 'nullable|string|size:5',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Preservar estado del carrito y cupón antes de registrar y regenerar sesión
        $carritoPrevio = session()->get('carrito', []);
        $cuponPrevio = session()->get('cupon');

        // Determinar código postal desde el formulario o sesión previa
        $cpRegistrado = $request->input('codigo_postal') ?: session('codigo_postal');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'codigo_postal' => $cpRegistrado,
            'carrito_guardado' => !empty($carritoPrevio) ? json_encode($carritoPrevio) : null,
            'password' => Hash::make($request->password),
        ]);

        // Disparar evento de registro y notificación de correo de confirmación de Sector Mueble
        event(new Registered($user));

        auth()->login($user);
        $request->session()->regenerate();

        if ($cpRegistrado) {
            session(['codigo_postal' => $cpRegistrado]);
        }

        // Preservar estado de ruleta jugada y cupón en el usuario registrado
        if (!empty($cuponPrevio) || session('ruleta_jugada')) {
            $user->update([
                'ruleta_jugada' => true,
                'ruleta_premio' => $cuponPrevio['codigo'] ?? 'COMPLETADO',
            ]);
            session()->put('ruleta_jugada', true);
        }

        // Restaurar carrito y cupón en la nueva sesión del usuario registrado
        if (!empty($carritoPrevio)) {
            session()->put('carrito', $carritoPrevio);
            session()->flash('notificacion_carrito_abandonado', '¡Tus productos te están esperando en tu carrito!');
        }
        if (!empty($cuponPrevio)) {
            session()->put('cupon', $cuponPrevio);
        }
        if (!empty($cuponPrevio)) {
            session()->put('cupon', $cuponPrevio);
        }

        return redirect()->route('verification.notice')->with('success', '¡Tu cuenta ha sido creada con éxito! Te hemos enviado un correo de confirmación a tu e-mail.');
    }

    /**
     * Muestra la vista de aviso de verificación de correo electrónico.
     */
    public function mostrarAvisoVerificacion(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            $carritoPrevio = session()->get('carrito', []);
            $targetUrl = !empty($carritoPrevio) ? route('carrito') : route('inicio');
            return redirect()->intended($targetUrl);
        }

        return view('Principal.verificar_email');
    }

    /**
     * Procesa la verificación de correo mediante la URL firmada.
     */
    public function verificarCorreo(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            $carritoPrevio = session()->get('carrito', []);
            $targetUrl = !empty($carritoPrevio) ? route('carrito') : route('inicio');
            return redirect()->intended($targetUrl)->with('success', 'Tu correo electrónico ya se encuentra verificado.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $carritoPrevio = session()->get('carrito', []);
        $targetUrl = !empty($carritoPrevio) ? route('carrito') : route('inicio');

        return redirect()->intended($targetUrl)->with('success', '¡Excelente! Tu correo electrónico ha sido verificado correctamente.');
    }

    /**
     * Reenvía el correo de verificación al usuario.
     */
    public function reenviarVerificacion(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('inicio'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Cierra la sesión del usuario guardando previamente los productos de su carrito.
     */
    public function logout(Request $request)
    {
        if (auth()->check()) {
            try {
                $carritoActual = session()->get('carrito', []);
                if (!empty($carritoActual)) {
                    auth()->user()->update([
                        'carrito_guardado' => json_encode($carritoActual),
                    ]);
                }
            } catch (\Throwable $e) {}
        }

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('success', 'Has cerrado sesión con éxito. Tus productos siguen guardados en tu cuenta.');
    }

    /**
     * Sincroniza el carrito actual de la sesión con la base de datos del usuario autenticado.
     */
    private function sincronizarCarritoUsuario()
    {
        if (auth()->check()) {
            try {
                $carrito = session()->get('carrito', []);
                auth()->user()->update([
                    'carrito_guardado' => !empty($carrito) ? json_encode($carrito) : null,
                ]);
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Procesa y aplica el premio obtenido en la Ruleta.
     */
    public function reclamarPremioRuleta(Request $request)
    {
        $request->validate([
            'posicion' => 'required|integer|between:1,3',
        ]);

        // Verificar si el usuario o sesión ya giró la ruleta previamente
        if ((auth()->check() && auth()->user()->ruleta_jugada) || session('ruleta_jugada')) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has utilizado la ruleta de premios. Límite de 1 premio por usuario/correo.',
            ], 422);
        }

        $opcion = RuletaOpcion::where('posicion', $request->posicion)->first();

        if (!$opcion || !$opcion->activo) {
            return response()->json([
                'success' => false,
                'message' => 'La opción de la ruleta no se encuentra disponible.',
            ], 404);
        }

        $codigo = $opcion->codigo_cupon ?: ('RULETA' . $opcion->posicion);
        $tipoCupon = $opcion->tipo_descuento == 'envio_gratis' ? 'fijo' : $opcion->tipo_descuento;
        $valorCupon = $opcion->tipo_descuento == 'envio_gratis' ? 0 : $opcion->descuento_valor;

        // Asegurar cupón en base de datos
        Cupon::updateOrCreate(
            ['codigo' => $codigo],
            [
                'tipo' => $tipoCupon,
                'valor' => $valorCupon,
                'activo' => true,
            ]
        );

        // Guardar cupón en sesión con tiempo de expiración
        $expiraEn = now()->addMinutes($opcion->tiempo_minutos)->timestamp;

        session()->put('cupon', [
            'codigo' => $codigo,
            'tipo' => $tipoCupon,
            'valor' => $valorCupon,
            'titulo' => $opcion->titulo,
            'expira_en' => $expiraEn,
        ]);

        // Marcar ruleta como jugada en la sesión
        session()->put('ruleta_jugada', true);

        // Si el usuario está autenticado, registrar el tiro y premio en la base de datos
        if (auth()->check()) {
            auth()->user()->update([
                'ruleta_jugada' => true,
                'ruleta_premio' => $codigo,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => '¡Premio reclamado con éxito!',
            'titulo' => $opcion->titulo,
            'codigo' => $codigo,
            'expira_en' => $expiraEn,
            'tiempo_minutos' => $opcion->tiempo_minutos,
            'redirect_url' => route('carrito'),
        ]);
    }

    /**
     * Verifica la cobertura de envío para un código postal en catalogo_codigos_postales.
     */
    public function verificarCodigoPostal(Request $request)
    {
        $request->validate([
            'codigo_postal' => 'required|string|size:5|regex:/^[0-9]{5}$/',
        ], [
            'codigo_postal.required' => 'Ingresa un código postal válido.',
            'codigo_postal.size' => 'El código postal debe contener exactamente 5 dígitos.',
            'codigo_postal.regex' => 'El código postal solo debe contener números.',
        ]);

        $cp = trim($request->input('codigo_postal'));

        $coberturas = CatalogoCodigoPostal::where('codigo_postal', $cp)
            ->where('activo', true)
            ->get();

        $tieneCobertura = $coberturas->count() > 0;

        if ($tieneCobertura) {
            $primerLugar = $coberturas->first();
            $municipios = $coberturas->pluck('municipio')->unique()->implode(', ');
            $estados = $coberturas->pluck('estado')->unique()->implode(', ');
            $zonas = $coberturas->pluck('zona_cobertura')->implode(' | ');

            $coberturaInfo = [
                'codigo_postal' => $cp,
                'tiene_cobertura' => true,
                'municipio' => $primerLugar->municipio,
                'municipios' => $municipios,
                'estado' => $primerLugar->estado,
                'estados' => $estados,
                'zona_cobertura' => $zonas,
                'mensaje' => "¡Excelente! Sí contamos con cobertura de envío en {$primerLugar->municipio}, {$primerLugar->estado}.",
            ];

            session(['codigo_postal' => $cp, 'cobertura_info' => $coberturaInfo]);

            return response()->json([
                'success' => true,
                'tiene_cobertura' => true,
                'data' => $coberturaInfo,
            ]);
        }

        $coberturaInfo = [
            'codigo_postal' => $cp,
            'tiene_cobertura' => false,
            'mensaje' => "Por el momento no contamos con cobertura de envío directo al CP {$cp}. ¡Estamos trabajando para llegar muy pronto a tu localidad!",
        ];

        session(['codigo_postal' => $cp, 'cobertura_info' => $coberturaInfo]);

        return response()->json([
            'success' => true,
            'tiene_cobertura' => false,
            'data' => $coberturaInfo,
        ]);
    }
}

