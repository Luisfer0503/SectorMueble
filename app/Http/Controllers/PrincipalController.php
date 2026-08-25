<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\User;
use App\Models\Cupon;
use App\Models\RuletaOpcion;
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
        $producto = Producto::findOrFail($id);
        
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

        // Envío gratis si supera los $8,000 MXN, si no, $400 MXN de coste de envío
        $envio = ($subtotal > 8000 || $subtotal == 0) ? 0 : 400.00;
        $total = max(0, $subtotal - $descuento) + $envio;

        return view('Principal.carrito', compact('carrito', 'subtotal', 'envio', 'descuento', 'total', 'cuponAplicado'));
    }

    /**
     * Agregar un mueble al carrito.
     */
    public function agregarAlCarrito(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $cantidad = $request->input('cantidad', 1);

        // Validar stock disponible
        if ($producto->stock < $cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock de este producto.');
        }

        $carrito = session()->get('carrito', []);

        // Si ya existe, se suma la cantidad
        if (isset($carrito[$id])) {
            $nuevaCantidad = $carrito[$id]['cantidad'] + $cantidad;
            if ($producto->stock < $nuevaCantidad) {
                return redirect()->back()->with('error', 'No puedes añadir más unidades de este producto (stock máximo alcanzado).');
            }
            $carrito[$id]['cantidad'] = $nuevaCantidad;
        } else {
            $carrito[$id] = [
                'nombre'            => $producto->nombre,
                'precio'            => $producto->precioEfectivo(),
                'precio_original'   => (float) $producto->precio,
                'con_descuento'     => $producto->tieneDescuento(),
                'imagen_url'        => $producto->imagen_url,
                'cantidad'          => $cantidad,
                'categoria'         => $producto->categoria,
                'stock_disponible'  => $producto->stock
            ];
        }

        session()->put('carrito', $carrito);

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
    public function actualizarCarrito(Request $request, $id)
    {
        $cantidad = $request->input('cantidad');
        $producto = Producto::findOrFail($id);

        if ($cantidad < 1) {
            return redirect()->back()->with('error', 'La cantidad mínima es 1.');
        }

        if ($producto->stock < $cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock para la cantidad solicitada.');
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] = $cantidad;
            session()->put('carrito', $carrito);
            return redirect()->route('carrito')->with('success', 'Carrito actualizado correctamente.');
        }

        return redirect()->route('carrito')->with('error', 'El producto no existe en tu carrito.');
    }

    /**
     * Eliminar un producto del carrito.
     */
    public function eliminarDelCarrito($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
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

        $envio = ($subtotal > 8000) ? 0 : 400.00;
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

        $envio = ($subtotal > 8000) ? 0 : 400.00;
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

            DB::commit();

            // Vaciar carrito y cupón
            session()->forget('carrito');
            session()->forget('cupon');

            return redirect()->route('pedido.confirmado', $pedido->id)->with('success', '¡Tu compra ha sido procesada con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al procesar tu compra. Por favor, inténtalo de nuevo. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Vista de éxito/confirmación de compra.
     */
    public function pedidoConfirmado($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);

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

            // Sincronizar estado de ruleta jugada
            if (auth()->user()->ruleta_jugada) {
                session()->put('ruleta_jugada', true);
            }

            // Restaurar carrito y cupón en la sesión autenticada
            if (!empty($carritoPrevio)) {
                session()->put('carrito', $carritoPrevio);
            }
            if (!empty($cuponPrevio)) {
                session()->put('cupon', $cuponPrevio);
            }

            // Si es administrador, mandarlo al dashboard por defecto
            if (auth()->user()->is_admin) {
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenido al Panel de Administración.');
            }

            $targetUrl = !empty($carritoPrevio) ? route('carrito') : route('inicio');
            return redirect()->intended($targetUrl)->with('success', '¡Has iniciado sesión con éxito! Tus productos siguen en tu carrito.');
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
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Preservar estado del carrito y cupón antes de registrar y regenerar sesión
        $carritoPrevio = session()->get('carrito', []);
        $cuponPrevio = session()->get('cupon');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Disparar evento de registro y notificación de correo de confirmación de Sector Mueble
        event(new Registered($user));

        auth()->login($user);
        $request->session()->regenerate();

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
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('success', 'Has cerrado sesión con éxito.');
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
}
