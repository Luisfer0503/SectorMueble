<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\ProductoDetalle;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\User;
use App\Models\Cupon;
use App\Models\RuletaOpcion;
use App\Models\CatalogoCodigoPostal;
use App\Models\TerminoCondicion;
use App\Models\AvisoPrivacidad;
use App\Models\PoliticaEnvio;
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
        $productosDestacados = Producto::activo()->with(['detalles' => function($q) {
            $q->where('activo', true);
        }])->where('destacado', true)->take(4)->get();

        $hotspotsIds = [39, 47, 22];
        $productosHotspots = Producto::activo()->whereIn('id', $hotspotsIds)->get()->keyBy('id');
        
        $categorias = Producto::activo()->select('categoria')
            ->distinct()
            ->pluck('categoria');

        return view('Principal.inicio', compact('productosDestacados', 'productosHotspots', 'categorias'));
    }

    /**
     * Catálogo completo con buscador, filtrado por categorías, rango de precios y ordenación.
     */
    public function catalogo(Request $request)
    {
        $consulta = Producto::activo()->with(['detalles' => function($q) {
            $q->where('activo', true);
        }]);

        // Categorías oficiales según Admin/productos/crear.blade.php
        $oficialesMap = [
            'Salas'            => ['Salas', 'Salón', 'Salon', 'Muebles Auxiliares', 'Auxiliares'],
            'Dormitorio'       => ['Dormitorio', 'Dormitorios', 'Recámaras', 'Recamaras', 'Recámara', 'Recamara'],
            'Comedor'          => ['Comedor', 'Comedores'],
            'Sillas y Bancos'  => ['Sillas y Bancos', 'Sillas', 'Bancos', 'Silla', 'Banco'],
            'Oficina'          => ['Oficina', 'Oficinas'],
            'Exterior'         => ['Exterior', 'Exteriores', 'Jardín', 'Jardin'],
        ];

        // Mapeo inverso de alias a nombres oficiales
        $catAliasMap = [];
        foreach ($oficialesMap as $oficial => $variaciones) {
            foreach ($variaciones as $var) {
                $catAliasMap[mb_strtolower($var)] = $oficial;
            }
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $consulta->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // Filtro por categoría con normalización de alias
        $categoriaReq = $request->input('categoria');
        $categoriaSeleccionada = 'todas';

        if (!empty($categoriaReq) && $categoriaReq !== 'todas') {
            $catKey = mb_strtolower(trim($categoriaReq));
            $categoriaSeleccionada = $catAliasMap[$catKey] ?? $categoriaReq;

            if (isset($oficialesMap[$categoriaSeleccionada])) {
                $consulta->whereIn('categoria', $oficialesMap[$categoriaSeleccionada]);
            } else {
                $consulta->where('categoria', $categoriaSeleccionada);
            }
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

        $totalTodos = Producto::activo()->count();

        // Conteo por categoría oficial (respetando las 6 categorías fijas)
        $rawCounts = Producto::activo()
            ->select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        $categoriasConConteo = collect();
        foreach ($oficialesMap as $nombreOficial => $variaciones) {
            $total = 0;
            foreach ($rawCounts as $rawCat => $count) {
                foreach ($variaciones as $v) {
                    if (mb_strtolower(trim($rawCat)) === mb_strtolower(trim($v))) {
                        $total += $count;
                        break;
                    }
                }
            }
            $categoriasConConteo->put($nombreOficial, $total);
        }

        $categorias = $categoriasConConteo->keys();

        // Petición AJAX → devolver solo el partial del grid
        if ($request->ajax() || $request->has('_ajax')) {
            return view('Principal.partials.productos-grid', compact('productos', 'categoriaSeleccionada'));
        }

        return view('Principal.catalogo', compact('productos', 'categorias', 'categoriasConConteo', 'totalTodos', 'categoriaSeleccionada'));
    }

    /**
     * Detalle de un mueble específico.
     */
    public function detalle($id)
    {
        $producto = Producto::activo()->with(['detalles' => function($q) {
            $q->where('activo', true);
        }])->findOrFail($id);
        
        $productosRelacionados = Producto::activo()->where('categoria', $producto->categoria)
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
        // Cargar favoritos guardados de BD si el usuario inició sesión y no los tiene en sesión
        if (auth()->check() && !session()->has('favoritos') && !empty(auth()->user()->favoritos_guardados)) {
            $favDb = json_decode(auth()->user()->favoritos_guardados, true);
            if (is_array($favDb)) {
                session()->put('favoritos', $favDb);
            }
        }

        $carrito = session()->get('carrito', []);
        $favoritos = session()->get('favoritos', []);
        $promoActiva = Producto::isCampanaPromocionalActiva();
        
        $subtotal = 0;
        $subtotalContado = 0;
        $subtotalMsi = 0;
        foreach ($carrito as $id => $item) {
            $base = (float)($item['precio_original'] ?? $item['precio']);
            $isEspecial = !empty($item['es_promo_especial']);
            $prodModel = Producto::find($item['producto_id'] ?? $id);
            if ($prodModel) {
                $isEspecial = $prodModel->isPromoEspecial();
            }

            if ($promoActiva) {
                $pContado = round($base * ($isEspecial ? 0.50 : 0.60), 2);
                $pMsi = round($base * ($isEspecial ? 0.65 : 0.70), 2);
            } else {
                $pContado = (float)($item['precio'] ?? $base);
                $pMsi = $pContado;
            }

            $subtotalContado += $pContado * $item['cantidad'];
            $subtotalMsi += $pMsi * $item['cantidad'];
        }

        $tipoPagoSeleccionado = $promoActiva ? session()->get('tipo_pago_seleccionado', 'contado') : 'contado';
        if ($promoActiva && request()->has('tipo_pago') && in_array(request()->query('tipo_pago'), ['contado', 'msi'])) {
            $tipoPagoSeleccionado = request()->query('tipo_pago');
            session()->put('tipo_pago_seleccionado', $tipoPagoSeleccionado);
        }

        $mesesMsiSeleccionado = (int) session()->get('meses_msi_seleccionado', 12);
        if ($promoActiva && request()->has('meses_msi') && in_array((int)request()->query('meses_msi'), [3, 6, 9, 12])) {
            $mesesMsiSeleccionado = (int) request()->query('meses_msi');
            session()->put('meses_msi_seleccionado', $mesesMsiSeleccionado);
        }
        if (!in_array($mesesMsiSeleccionado, [3, 6, 9, 12])) {
            $mesesMsiSeleccionado = 12;
        }

        // Calcular cupón de descuento
        $descuento = 0.00;
        $cuponAplicado = session()->get('cupon');
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
            if ($cupon) {
                $subtotalParaCupon = ($tipoPagoSeleccionado === 'msi') ? $subtotalMsi : $subtotalContado;
                $descuento = $cupon->calcularDescuento($subtotalParaCupon);
            } else {
                session()->forget('cupon');
                $cuponAplicado = null;
            }
        }

        // Envío gratis si alcanza o supera los $10,000 MXN, si no, $600 MXN de costo de envío dentro del radar de cobertura
        $envioContado = ($subtotalContado >= 10000 || $subtotalContado == 0) ? 0 : 600.00;
        $envioMsi = ($subtotalMsi >= 10000 || $subtotalMsi == 0) ? 0 : 600.00;

        $totalContado = max(0, $subtotalContado - $descuento) + $envioContado;
        $totalMsi = max(0, $subtotalMsi - $descuento) + $envioMsi;

        $cuotaMsi12 = $totalMsi > 0 ? round($totalMsi / 12, 2) : 0;
        $cuotaMsi9 = $totalMsi > 0 ? round($totalMsi / 9, 2) : 0;
        $cuotaMsi6 = $totalMsi > 0 ? round($totalMsi / 6, 2) : 0;
        $cuotaMsi3 = $totalMsi > 0 ? round($totalMsi / 3, 2) : 0;

        $subtotal = ($tipoPagoSeleccionado === 'msi') ? $subtotalMsi : $subtotalContado;
        $envio = ($tipoPagoSeleccionado === 'msi') ? $envioMsi : $envioContado;
        $total = ($tipoPagoSeleccionado === 'msi') ? $totalMsi : $totalContado;

        // Muebles frecuentemente comprados juntos / recomendados
        $frecuentementeComprados = Producto::activo()->with(['detalles' => function($q) {
            $q->where('activo', true);
        }])->inRandomOrder()->take(3)->get();

        return view('Principal.carrito', compact(
            'carrito', 'favoritos', 'subtotal', 'subtotalContado', 'subtotalMsi',
            'totalContado', 'totalMsi', 'cuotaMsi12', 'cuotaMsi9', 'cuotaMsi6', 'cuotaMsi3',
            'envio', 'envioContado', 'envioMsi', 'descuento', 'total', 'cuponAplicado',
            'frecuentementeComprados', 'tipoPagoSeleccionado', 'mesesMsiSeleccionado', 'promoActiva'
        ));
    }

    /**
     * Guardar la modalidad de pago seleccionada por el usuario (contado o msi y número de meses).
     */
    public function seleccionarTipoPago(Request $request)
    {
        $tipo = $request->input('tipo_pago', 'contado');
        if (!in_array($tipo, ['contado', 'msi'])) {
            $tipo = 'contado';
        }
        session()->put('tipo_pago_seleccionado', $tipo);

        $mesesMsi = (int) $request->input('meses_msi', 12);
        if (in_array($mesesMsi, [3, 6, 9, 12])) {
            session()->put('meses_msi_seleccionado', $mesesMsi);
        }

        return response()->json([
            'success'   => true,
            'tipo_pago' => $tipo,
            'meses_msi' => $mesesMsi,
        ]);
    }

    /**
     * Agregar un mueble al carrito.
     */
    public function agregarAlCarrito(Request $request, $id)
    {
        $producto = Producto::activo()->with(['detalles' => function($q) {
            $q->where('activo', true);
        }])->findOrFail($id);
        $cantidad = (int) $request->input('cantidad', 1);
        $subarticuloId = $request->input('subarticulo_id');
        $colorNombre = $request->input('color');

        // Buscar el detalle/subartículo específico (soporta IDs numéricos, nombre o fallback al primer subartículo disponible)
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
            $detalle = $producto->detalles->where('activo', true)->where('agotado', false)->first()
                ?? $producto->detalles->first();
        }

        if (!$detalle || !$detalle->activo) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este producto o acabado no se encuentra disponible.',
                ], 422);
            }
            return redirect()->back()->with('error', 'Este producto o acabado no se encuentra disponible.');
        }

        $detalleId = $detalle ? $detalle->id : 0;
        $itemKey = $detalle ? "{$producto->id}_{$detalle->id}" : (string)$producto->id;

        $nombreSub = $detalle ? $detalle->nombre : ($colorNombre ?? 'Original / Natural');
        $skuSub = $detalle ? $detalle->sku : "SKU-{$producto->id}";
        $precioUnitario = $detalle && $detalle->precio !== null ? (float)$detalle->precio : (float)$producto->precio;
        $imagenUrl = $detalle && !empty($detalle->imagen) ? $detalle->imagen_url : $producto->imagen_url;
        
        $stockDisponible = ($detalle && $detalle->activo && !$detalle->agotado) ? (int) $detalle->stock : 0;

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
            $promoActiva = Producto::isCampanaPromocionalActiva();
            $isEspecial = $producto->isPromoEspecial();
            $precioBase = $precioUnitario;

            if ($promoActiva) {
                $pctContado = $isEspecial ? 50 : 40;
                $pctMsi = $isEspecial ? 35 : 30;
                $precioContado = round($precioBase * (1 - $pctContado / 100), 2);
                $precioMsi = round($precioBase * (1 - $pctMsi / 100), 2);
                $conDescuento = true;
                $precioEfectivo = $precioContado;
            } else {
                $pctContado = (int)($detalle?->porcentaje_descuento ?? $producto->porcentaje_descuento ?? 0);
                $pctMsi = 0;
                $conDescuento = $pctContado > 0;
                $precioEfectivo = $conDescuento ? round($precioBase * (1 - $pctContado / 100), 2) : $precioBase;
                $precioContado = $precioEfectivo;
                $precioMsi = $precioEfectivo;
            }

            $carrito[$itemKey] = [
                'producto_id'        => $producto->id,
                'subarticulo_id'     => $detalleId,
                'nombre'             => $producto->nombre,
                'subarticulo_nombre' => $nombreSub,
                'sku'                => $skuSub,
                'precio'             => $precioEfectivo,
                'precio_original'    => $precioBase,
                'precio_contado'     => $precioContado,
                'precio_msi'         => $precioMsi,
                'pct_contado'        => $pctContado,
                'pct_msi'            => $pctMsi,
                'es_promo_especial'  => $isEspecial,
                'con_descuento'      => $conDescuento,
                'imagen_url'         => $imagenUrl,
                'cantidad'           => $cantidad,
                'categoria'          => $producto->categoria,
                'color'              => $nombreSub,
                'stock_disponible'   => $stockDisponible,
            ];
        }

        $tipoPago = $request->input('tipo_pago');
        if ($tipoPago && in_array($tipoPago, ['contado', 'msi'])) {
            session()->put('tipo_pago_seleccionado', $tipoPago);
        }
        $mesesMsi = (int) $request->input('meses_msi');
        if ($mesesMsi && in_array($mesesMsi, [3, 6, 9, 12])) {
            session()->put('meses_msi_seleccionado', $mesesMsi);
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
     * Guardar un artículo para después (mover de carrito a favoritos).
     */
    public function guardarParaDespues(Request $request, $itemKey)
    {
        $carrito = session()->get('carrito', []);
        $favoritos = session()->get('favoritos', []);

        if (isset($carrito[$itemKey])) {
            $favoritos[$itemKey] = $carrito[$itemKey];
            unset($carrito[$itemKey]);

            session()->put('carrito', $carrito);
            session()->put('favoritos', $favoritos);

            $this->sincronizarCarritoUsuario();
            $this->sincronizarFavoritosUsuario();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mueble guardado para después en tu lista.',
                ]);
            }

            return redirect()->route('carrito')->with('success', 'Mueble guardado para después en tu lista.');
        }

        return redirect()->route('carrito')->with('error', 'El mueble no se encuentra en tu carrito.');
    }

    /**
     * Mover un artículo guardado para después de vuelta al carrito.
     */
    public function moverAlCarrito(Request $request, $itemKey)
    {
        $carrito = session()->get('carrito', []);
        $favoritos = session()->get('favoritos', []);

        if (isset($favoritos[$itemKey])) {
            $carrito[$itemKey] = $favoritos[$itemKey];
            unset($favoritos[$itemKey]);

            session()->put('carrito', $carrito);
            session()->put('favoritos', $favoritos);

            $this->sincronizarCarritoUsuario();
            $this->sincronizarFavoritosUsuario();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Mueble añadido nuevamente a tu carrito!',
                ]);
            }

            return redirect()->route('carrito')->with('success', '¡Mueble añadido nuevamente a tu carrito!');
        }

        return redirect()->route('carrito')->with('error', 'El mueble no se encuentra en tus guardados.');
    }

    /**
     * Eliminar un producto de la lista de guardados/favoritos.
     */
    public function eliminarGuardado($itemKey)
    {
        $favoritos = session()->get('favoritos', []);

        if (isset($favoritos[$itemKey])) {
            unset($favoritos[$itemKey]);
            session()->put('favoritos', $favoritos);
            $this->sincronizarFavoritosUsuario();
            return redirect()->route('carrito')->with('success', 'Mueble eliminado de tu lista de guardados.');
        }

        return redirect()->route('carrito')->with('error', 'El mueble no estaba en tu lista.');
    }

    /**
     * Guardar o actualizar la solicitud especial / notas del ítem en el carrito.
     */
    public function guardarSolicitudEspecial(Request $request, $itemKey)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$itemKey])) {
            $carrito[$itemKey]['solicitud_especial'] = trim($request->input('solicitud_especial', ''));
            session()->put('carrito', $carrito);
            $this->sincronizarCarritoUsuario();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud especial guardada correctamente.',
                ]);
            }

            return redirect()->route('carrito')->with('success', 'Solicitud especial guardada.');
        }

        return redirect()->route('carrito')->with('error', 'No se encontró el mueble en tu carrito.');
    }

    /**
     * Mostrar la página pública de Términos y Condiciones.
     */
    public function terminosCondiciones()
    {
        $secciones = TerminoCondicion::obtenerSecciones();
        $contenidoText = TerminoCondicion::obtenerContenido();
        return view('Principal.terminos', compact('secciones', 'contenidoText'));
    }

    /**
     * Mostrar la página pública de Aviso de Privacidad.
     */
    public function avisoPrivacidad()
    {
        $secciones = AvisoPrivacidad::obtenerSecciones();
        $contenidoText = AvisoPrivacidad::obtenerContenido();
        return view('Principal.privacidad', compact('secciones', 'contenidoText'));
    }

    /**
     * Mostrar la página pública de Políticas de Envío.
     */
    public function politicasEnvio()
    {
        $secciones = PoliticaEnvio::obtenerSecciones();
        $contenidoText = PoliticaEnvio::obtenerContenido();
        return view('Principal.politicas_envio', compact('secciones', 'contenidoText'));
    }

    /**
     * Mostrar la vista para finalizar compra (Checkout).
     */
    public function finalizarCompra(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'Tu carrito está vacío. Agrega algún mueble para comprar.');
        }

        $promoActiva = Producto::isCampanaPromocionalActiva();

        $tipoPagoInicial = $promoActiva ? $request->query('tipo_pago', session('tipo_pago_seleccionado', 'contado')) : 'contado';
        if (!in_array($tipoPagoInicial, ['contado', 'msi'])) {
            $tipoPagoInicial = 'contado';
        }
        session()->put('tipo_pago_seleccionado', $tipoPagoInicial);

        $mesesMsiInicial = (int) $request->query('meses_msi', session('meses_msi_seleccionado', 12));
        if (!in_array($mesesMsiInicial, [3, 6, 9, 12])) {
            $mesesMsiInicial = 12;
        }
        session()->put('meses_msi_seleccionado', $mesesMsiInicial);

        $subtotalContado = 0;
        $subtotalMsi = 0;
        foreach ($carrito as $id => &$item) {
            $base = (float)($item['precio_original'] ?? $item['precio']);
            $isEspecial = !empty($item['es_promo_especial']);
            $prodModel = Producto::find($item['producto_id'] ?? $id);
            if ($prodModel) {
                $isEspecial = $prodModel->isPromoEspecial();
            }

            if ($promoActiva) {
                $pContado = round($base * ($isEspecial ? 0.50 : 0.60), 2);
                $pMsi = round($base * ($isEspecial ? 0.65 : 0.70), 2);
            } else {
                $pContado = (float)($item['precio'] ?? $base);
                $pMsi = $pContado;
            }

            $item['precio_base_original'] = $base;
            $item['precio_contado'] = $pContado;
            $item['precio_msi'] = $pMsi;
            $item['es_promo_especial'] = $isEspecial;

            $subtotalContado += $pContado * $item['cantidad'];
            $subtotalMsi += $pMsi * $item['cantidad'];
        }
        unset($item);

        $subtotal = $subtotalContado;

        // Calcular cupón de descuento para Contado y MSI por separado
        $descuento = 0.00;
        $descuentoMsi = 0.00;
        $cuponAplicado = session()->get('cupon');
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
            if ($cupon) {
                $descuento = min($subtotalContado, $cupon->calcularDescuento($subtotalContado));
                $descuentoMsi = min($subtotalMsi, $cupon->calcularDescuento($subtotalMsi));
            } else {
                session()->forget('cupon');
                $cuponAplicado = null;
            }
        }

        $envio = ($subtotal >= 10000) ? 0 : 600.00;
        $total = max(0, $subtotal - $descuento) + $envio;

        $envioMsi = ($subtotalMsi >= 10000) ? 0 : 600.00;
        $totalMsi = max(0, $subtotalMsi - $descuentoMsi) + $envioMsi;
        $cuotaMsi12 = round($totalMsi / 12, 2);
        $cuotaMsi9 = round($totalMsi / 9, 2);
        $cuotaMsi6 = round($totalMsi / 6, 2);
        $cuotaMsi3 = round($totalMsi / 3, 2);

        return view('Principal.checkout', compact(
            'carrito', 'subtotal', 'envio', 'descuento', 'descuentoMsi', 'envioMsi', 'total', 'cuponAplicado',
            'subtotalContado', 'subtotalMsi', 'totalMsi', 'cuotaMsi12', 'cuotaMsi9', 'cuotaMsi6', 'cuotaMsi3',
            'tipoPagoInicial', 'mesesMsiInicial', 'promoActiva'
        ));
    }

    /**
     * Redirige cualquier intento de envío directo de formulario hacia la pasarela segura de Stripe.
     * Evita que se creen pedidos sin cobrar si el navegador envía el formulario por POST tradicional.
     */
    public function procesarCompra(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return $this->crearSesionPagoStripe($request);
        }

        return redirect()->route('checkout')
            ->with('info', 'Para completar tu compra de forma segura, haz clic en el botón de pago con Stripe.')
            ->withInput();
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
            'referencias'      => 'nullable|string|max:255',
        ]);

        if ($request->has('requiere_factura') && $request->requiere_factura) {
            $request->validate([
                'rfc_receptor'         => 'required|string|min:12|max:13',
                'razon_social'        => 'required|string|max:255',
                'regimen_fiscal'      => 'required|string',
                'uso_cfdi'            => 'required|string',
                'codigo_postal_fiscal' => 'required|string|max:10',
                'correo_facturacion'  => 'required|email|max:255',
            ]);
        }

        $cpLimpio = trim($request->codigo_postal);
        $cpValido = CatalogoCodigoPostal::where('codigo_postal', $cpLimpio)->where('activo', true)->exists();
        if (!$cpValido) {
            return response()->json([
                'success' => false,
                'message' => 'El Código Postal ' . $cpLimpio . ' no cuenta con cobertura de envío directo para pago en línea. Por favor contacta a un agente de ventas.'
            ], 422);
        }

        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito de compras está vacío.'
            ], 422);
        }

        $direccionCompleta = trim($request->direccion_envio);
        $referenciasEntrega = trim((string) $request->input('referencias', ''));
        if ($referenciasEntrega !== '' && !str_contains($direccionCompleta, $referenciasEntrega)) {
            $direccionCompleta .= " (Ref: {$referenciasEntrega})";
        }

        // Almacenar datos temporales de entrega y facturación en sesión
        session()->put('datos_envio_checkout', [
            'nombre_cliente'       => trim($request->nombre_cliente),
            'correo_cliente'       => trim($request->correo_cliente),
            'telefono_cliente'     => trim($request->telefono_cliente),
            'direccion_envio'      => $direccionCompleta,
            'ciudad'               => trim($request->ciudad),
            'codigo_postal'        => $cpLimpio,
            'referencias'          => $referenciasEntrega,
            'requiere_factura'     => (bool) $request->has('requiere_factura'),
            'rfc_receptor'         => $request->has('requiere_factura') ? strtoupper(trim($request->rfc_receptor)) : null,
            'razon_social'        => $request->has('requiere_factura') ? mb_strtoupper(trim($request->razon_social)) : null,
            'regimen_fiscal'      => $request->has('requiere_factura') ? $request->regimen_fiscal : null,
            'uso_cfdi'            => $request->has('requiere_factura') ? $request->uso_cfdi : null,
            'codigo_postal_fiscal' => $request->has('requiere_factura') ? trim($request->codigo_postal_fiscal) : null,
            'correo_facturacion'  => $request->has('requiere_factura') ? trim($request->correo_facturacion) : null,
        ]);

        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return response()->json([
                'success' => false,
                'message' => 'El pago en línea está temporalmente fuera de servicio.'
            ], 422);
        }

        $pedidoCreado = null;

        try {
            // Liberar automáticamente cualquier reserva de stock vencida (>36 min) de sesiones abandonadas
            Pedido::liberarReservasVencidas();

            $stripe = new \Stripe\StripeClient($stripeSecret);
            $fingerprint = hash('sha256', serialize([
                $carrito, session('cupon'), session('datos_envio_checkout'),
                $request->input('tipo_pago'), $request->input('meses_msi'),
            ]));

            // 1. Verificar pedido previo o reserva activa del usuario fuera del bloqueo de inventario
            $ultimo = Pedido::where('usuario_id', auth()->id())->find(session('ultimo_pedido_id'));
            if ($ultimo?->stripe_paid_at && $ultimo->checkout_fingerprint === $fingerprint) {
                return response()->json([
                    'success' => true,
                    'checkout_url' => route('checkout.stripe.exito', ['session_id' => $ultimo->stripe_session_id]),
                ]);
            }

            $anterior = Pedido::with('detalles')
                ->where('usuario_id', auth()->id())
                ->where('stock_reservado', true)
                ->first();

            if ($anterior && !empty($anterior->stripe_session_id)) {
                $sesionAnterior = $stripe->checkout->sessions->retrieve($anterior->stripe_session_id);
                if (($sesionAnterior->status ?? '') === 'complete' || ($sesionAnterior->payment_status ?? '') === 'paid') {
                    return response()->json([
                        'success' => true,
                        'checkout_url' => route('checkout.stripe.exito', ['session_id' => $sesionAnterior->id]),
                    ]);
                }
                if (($sesionAnterior->status ?? '') === 'open' && $anterior->checkout_fingerprint === $fingerprint) {
                    session()->put('ultimo_pedido_id', $anterior->id);
                    session()->put('carrito_checkout_hash', hash('sha256', serialize($carrito)));
                    return response()->json([
                        'success' => true,
                        'checkout_url' => $sesionAnterior->url,
                    ]);
                }
                if (($sesionAnterior->status ?? '') !== 'expired') {
                    try {
                        $stripe->checkout->sessions->expire($anterior->stripe_session_id);
                    } catch (\Exception $expErr) {
                        // Ignorar si ya había expirado en Stripe
                    }
                }
                $this->liberarReserva($anterior);
            } elseif ($anterior) {
                $this->liberarReserva($anterior);
            }

            $promoActiva = Producto::isCampanaPromocionalActiva();

            $tipoPago = $promoActiva ? $request->input('tipo_pago', 'contado') : 'contado';
            if (!in_array($tipoPago, ['contado', 'msi'])) {
                $tipoPago = 'contado';
            }
            $mesesMsi = (int) $request->input('meses_msi', 12);
            if (!in_array($mesesMsi, [3, 6, 9, 12])) {
                $mesesMsi = 12;
            }
            $tipoPagoGuardar = ($promoActiva && $tipoPago === 'msi') ? "msi_{$mesesMsi}" : 'contado';
            $currency = strtolower(config('services.stripe.currency', 'mxn'));
            $requiereFactura = (bool) $request->has('requiere_factura');

            // 2. Transacción corta en base de datos para bloquear stock, reservar inventario y crear el pedido
            DB::beginTransaction();

            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();

            $subtotal = 0;
            $itemsCalculados = [];
            foreach ($carrito as $id => $item) {
                $producto = Producto::with('detalles')->find($item['producto_id'] ?? $id);
                if (!$producto || !$producto->activo || $producto->isAgotado()) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'El mueble "' . ($item['nombre'] ?? 'seleccionado') . '" ya no se encuentra disponible. Por favor actualiza tu carrito.',
                    ], 422);
                }

                // Validar subartículo y stock disponible en tiempo real (con respaldo por SKU, nombre o primera variante activa si el ID cambió al editar el mueble)
                $subId = $item['subarticulo_id'] ?? null;
                $detalleSub = null;
                if (!empty($subId)) {
                    $detalleSub = $producto->detalles()
                        ->where('activo', true)
                        ->where('agotado', false)
                        ->whereKey($subId)
                        ->lockForUpdate()
                        ->first();
                }

                if (!$detalleSub) {
                    $skuItem = trim((string) ($item['sku'] ?? ''));
                    $nombreSubItem = trim(mb_strtolower((string) ($item['subarticulo_nombre'] ?? $item['color'] ?? '')));
                    $candidatos = $producto->detalles()
                        ->where('activo', true)
                        ->where('agotado', false)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    if ($skuItem !== '') {
                        $detalleSub = $candidatos->first(fn ($d) => trim((string) $d->sku) === $skuItem);
                    }
                    if (!$detalleSub && $nombreSubItem !== '') {
                        $detalleSub = $candidatos->first(fn ($d) => trim(mb_strtolower((string) $d->nombre)) === $nombreSubItem);
                    }
                    if (!$detalleSub) {
                        $detalleSub = $candidatos->first(fn ($d) => (int) $d->stock > 0) ?? $candidatos->first();
                    }

                    if ($detalleSub && isset($carrito[$id])) {
                        $carrito[$id]['subarticulo_id'] = $detalleSub->id;
                        session()->put('carrito', $carrito);
                    }
                }

                $stockReal = (int) ($detalleSub?->stock ?? 0);
                $cantidadItem = max(1, (int) ($item['cantidad'] ?? 1));
                if ($stockReal < $cantidadItem) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay stock suficiente para "' . $producto->nombre . '" (Disponible: ' . $stockReal . '). Ajusta la cantidad en tu carrito.',
                    ], 422);
                }

                $nuevoStock = max(0, $stockReal - $cantidadItem);
                $detalleSub->update([
                    'stock'   => $nuevoStock,
                    'agotado' => $nuevoStock <= 0,
                ]);

                $base = ($detalleSub && $detalleSub->precio !== null)
                    ? (float) $detalleSub->precio
                    : (float) ($item['precio_original'] ?? $producto->precio ?? $item['precio']);
                $isEspecial = $producto->isPromoEspecial();

                if ($promoActiva) {
                    if ($tipoPago === 'msi') {
                        // 35% de descuento en seleccionados, 30% en los demás
                        $itemPrecio = round($base * ($isEspecial ? 0.65 : 0.70), 2);
                        $descLabel = ' (' . ($isEspecial ? '35% desc. + MSI' : '30% desc. + MSI') . ')';
                    } else {
                        // 50% de descuento en seleccionados, 40% en los demás
                        $itemPrecio = round($base * ($isEspecial ? 0.50 : 0.60), 2);
                        $descLabel = ' (' . ($isEspecial ? '50% desc. Contado' : '40% desc. Contado') . ')';
                    }
                } else {
                    $pctNormal = (int) ($detalleSub?->porcentaje_descuento ?? $producto->porcentaje_descuento ?? 0);
                    $itemPrecio = $pctNormal > 0 ? round($base * (1 - $pctNormal / 100), 2) : (float) ($item['precio'] ?? $base);
                    $descLabel = '';
                }

                $subtotal += $itemPrecio * $cantidadItem;

                // Incluir acabado y nota especial si existen para que almacén/admin lo identifique con exactitud
                $acabadoNombre = trim((string) ($item['color'] ?? $item['subarticulo_nombre'] ?? ($detalleSub->nombre ?? '')));
                $acabadoTag = ($acabadoNombre !== '' && mb_strtolower($acabadoNombre) !== mb_strtolower($producto->nombre))
                    ? " [{$acabadoNombre}]"
                    : '';
                $notaEspecial = trim((string) ($item['solicitud_especial'] ?? ''));
                $notaTag = $notaEspecial !== '' ? " (Nota: {$notaEspecial})" : '';

                $itemsCalculados[] = [
                    'producto_detalle_id' => $detalleSub->id,
                    'producto_id'     => $producto->id,
                    'nombre'          => $producto->nombre . $acabadoTag . $descLabel . $notaTag,
                    'precio'          => $itemPrecio,
                    'cantidad'        => $cantidadItem,
                ];
            }

            $descuento = 0.00;
            $cuponCodigo = null;
            $cuponAplicado = session()->get('cupon');
            if ($cuponAplicado) {
                $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
                if ($cupon) {
                    $descuento = min($subtotal, $cupon->calcularDescuento($subtotal));
                    $cuponCodigo = $cupon->codigo;
                }
            }
            $envio = ($subtotal >= 10000) ? 0 : 600.00;
            $total = round(max(0, $subtotal - $descuento) + $envio, 2);

            if ($total < 10) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'El monto mínimo para procesar pagos en línea con Stripe es de $10.00 MXN.',
                ], 422);
            }

            $datosPedido = [
                'usuario_id'           => auth()->id(),
                'nombre_cliente'       => trim($request->nombre_cliente),
                'correo_cliente'       => trim($request->correo_cliente),
                'telefono_cliente'     => trim($request->telefono_cliente),
                'direccion_envio'      => $direccionCompleta,
                'ciudad'               => trim($request->ciudad),
                'codigo_postal'        => $cpLimpio,
                'total'                => $total,
                'tipo_pago'            => $tipoPagoGuardar,
                'cupon_codigo'         => $cuponCodigo,
                'descuento'            => $descuento,
                'estado'               => 'pendiente',
                'requiere_factura'     => $requiereFactura,
                'rfc_receptor'         => $requiereFactura ? strtoupper(trim($request->rfc_receptor)) : null,
                'razon_social'        => $requiereFactura ? mb_strtoupper(trim($request->razon_social)) : null,
                'regimen_fiscal'      => $requiereFactura ? $request->regimen_fiscal : null,
                'uso_cfdi'            => $requiereFactura ? $request->uso_cfdi : null,
                'codigo_postal_fiscal' => $requiereFactura ? trim($request->codigo_postal_fiscal) : null,
                'correo_facturacion'  => $requiereFactura ? trim($request->correo_facturacion) : null,
                'factura_estado'       => $requiereFactura ? 'pendiente' : 'no_solicitada',
                'stripe_currency'      => $currency,
                'checkout_fingerprint' => $fingerprint,
                'stock_reservado'      => true,
            ];

            $pedido = Pedido::create($datosPedido);

            foreach ($itemsCalculados as $calc) {
                if ($calc['producto_id']) {
                    DetallePedido::create([
                        'pedido_id'           => $pedido->id,
                        'producto_id'         => $calc['producto_id'],
                        'producto_detalle_id' => $calc['producto_detalle_id'],
                        'nombre_producto'     => $calc['nombre'],
                        'precio'              => $calc['precio'],
                        'cantidad'            => $calc['cantidad'],
                    ]);
                }
            }

            DB::commit();
            $pedidoCreado = $pedido;

            // 3. Crear sesión en Stripe fuera del bloqueo de base de datos
            $lineItems = [];
            foreach ($itemsCalculados as $calc) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $calc['nombre'],
                        ],
                        'unit_amount' => (int) round($calc['precio'] * 100),
                    ],
                    'quantity' => $calc['cantidad'],
                ];
            }

            if ($envio > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => 'Costo de Envío Especial',
                        ],
                        'unit_amount' => (int) round($envio * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            $folioFormat = str_pad($pedido->id, 5, '0', STR_PAD_LEFT);
            $metadataComun = [
                'pedido_id'  => (string) $pedido->id,
                'usuario_id' => (string) (auth()->id() ?? ''),
                'tipo_pago'  => $tipoPagoGuardar,
                'meses_msi'  => $tipoPago === 'msi' ? (string) $mesesMsi : '',
            ];

            $habilitarMsiStripe = ($promoActiva && $tipoPago === 'msi');

            $stripeParams = [
                'line_items'             => $lineItems,
                'adaptive_pricing'       => ['enabled' => false],
                'expires_at'             => now()->addMinutes(35)->timestamp,
                'mode'                   => 'payment',
                'client_reference_id'    => (string) $pedido->id,
                'customer_email'         => trim($request->correo_cliente),
                'integration_identifier' => 'sectormueble_checkout_kxmwpqzr',
                'metadata'               => $metadataComun,
                'payment_intent_data'    => [
                    'description' => "Pedido #{$folioFormat} - Sector Mueble ({$pedido->nombre_cliente})",
                    'metadata'    => $metadataComun,
                ],
                'payment_method_options' => [
                    'card' => [
                        'installments' => [
                            'enabled' => $habilitarMsiStripe,
                        ],
                    ],
                ],
                'custom_text'            => [
                    'submit' => [
                        'message' => $habilitarMsiStripe
                            ? "Modalidad elegida: Meses Sin Intereses ({$mesesMsi} MSI). Activa la casilla 'Pagar en cuotas (meses sin intereses)' y selecciona tu plazo."
                            : 'Modalidad elegida: Pago de Contado (1 sola exhibición). Si deseas pagar a Meses Sin Intereses, regresa al checkout para cambiar de modalidad.',
                    ],
                ],
                'success_url'            => route('checkout.stripe.exito') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'             => route('checkout.stripe.cancelado') . '?pedido_id=' . $pedido->id,
            ];

            if ($descuento > 0) {
                try {
                    $coupon = $stripe->coupons->create([
                        'amount_off' => (int) round($descuento * 100),
                        'currency'   => $currency,
                        'duration'   => 'once',
                        'name'       => 'Descuento ' . ($cuponCodigo ? ('(' . $cuponCodigo . ')') : ''),
                    ]);
                    $stripeParams['discounts'] = [
                        ['coupon' => $coupon->id],
                    ];
                } catch (\Exception $ce) {
                    \Illuminate\Support\Facades\Log::warning("Respaldo de descuento directo en line_items (Cupón Stripe): " . $ce->getMessage());
                    // Si la clave de API es restringida y no permite crear cupones, consolidar el monto exacto con descuento aplicado
                    $resumenNombres = collect($itemsCalculados)->map(fn($i) => "{$i['cantidad']}x {$i['nombre']}")->implode(', ');
                    $stripeParams['line_items'] = [
                        [
                            'price_data' => [
                                'currency' => $currency,
                                'product_data' => [
                                    'name'        => "Pedido #{$folioFormat} - Sector Mueble (Cupón {$cuponCodigo} aplicado)",
                                    'description' => \Illuminate\Support\Str::limit($resumenNombres, 450),
                                ],
                                'unit_amount' => (int) round($total * 100),
                            ],
                            'quantity' => 1,
                        ],
                    ];
                }
            }

            $session = $stripe->checkout->sessions->create($stripeParams, [
                'idempotency_key' => 'checkout-' . $pedido->id . '-' . $fingerprint,
            ]);
            $pedido->forceFill([
                'stripe_session_id' => $session->id,
            ])->save();

            session()->put('ultimo_pedido_id', $pedido->id);
            session()->put('carrito_checkout_hash', hash('sha256', serialize($carrito)));

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url,
            ]);

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            if ($pedidoCreado) {
                try {
                    $this->liberarReserva($pedidoCreado);
                } catch (\Exception $releaseErr) {
                    \Illuminate\Support\Facades\Log::error("Error liberando reserva tras fallo de Stripe: " . $releaseErr->getMessage());
                }
            }
            \Illuminate\Support\Facades\Log::error("Error al crear sesión de pago en Stripe: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'No se pudo iniciar el pago. Puedes volver a intentarlo.',
            ], 500);
        }
    }

    /**
     * Marca un pedido como pagado y realiza las tareas post-pago (100% idempotente y protegido contra condiciones de carrera).
     */
    protected function marcarPedidoComoPagado(Pedido $pedidoInput, $session)
    {
        $recienPagado = false;
        $pedido = $pedidoInput;

        DB::transaction(function () use ($pedidoInput, $session, &$pedido, &$recienPagado) {
            $locked = Pedido::with('detalles')->where('id', $pedidoInput->id)->lockForUpdate()->first();
            if (!$locked) {
                return;
            }

            $this->validarSesionPedido($locked, $session);
            if ($locked->stripe_paid_at || in_array($locked->estado, ['procesando', 'procesado', 'pagado', 'enviado', 'entregado', 'recibido', 'completado'])) {
                $pedido = $locked;
                return;
            }

            // Si el pedido no tenía reserva previa (ej. pedido en curso antes de la migración o liberado por expiración tardía), descontar inventario ahora
            if (!$locked->stock_reservado) {
                foreach ($locked->detalles as $detItem) {
                    $det = null;
                    if (!empty($detItem->producto_detalle_id)) {
                        $det = \App\Models\ProductoDetalle::whereKey($detItem->producto_detalle_id)->lockForUpdate()->first();
                    }
                    if (!$det && !empty($detItem->producto_id)) {
                        $producto = Producto::with('detalles')->find($detItem->producto_id);
                        if ($producto) {
                            $det = $producto->detalles->where('activo', true)->first(function ($d) use ($detItem) {
                                return !empty($d->nombre) && str_contains($detItem->nombre_producto, $d->nombre);
                            }) ?? $producto->detalles->where('activo', true)->first() ?? $producto->detalles->first();
                        }
                    }
                    if ($det) {
                        $nuevoStock = max(0, (int) $det->stock - (int) $detItem->cantidad);
                        $det->update([
                            'stock'   => $nuevoStock,
                            'agotado' => $nuevoStock <= 0,
                        ]);
                    }
                }
            }

            $locked->forceFill([
                'estado'            => 'procesando',
                'stripe_session_id' => $locked->stripe_session_id ?: ($session->id ?? null),
                'stripe_paid_at'    => now(),
                'stock_reservado'   => false,
            ])->save();

            $pedido = $locked;
            $recienPagado = true;
        });

        // Ejecutar notificaciones externas solo si esta petición fue la que transicionó el pedido a pagado
        if ($recienPagado) {
            // 4. Enviar correo electrónico de confirmación de compra al cliente
            try {
                $correoDestino = !empty($pedido->correo_cliente) ? $pedido->correo_cliente : optional($pedido->usuario)->email;
                if ($correoDestino) {
                    \Illuminate\Support\Facades\Notification::route('mail', $correoDestino)
                        ->notify(new \App\Notifications\EstadoPedidoNotificacion($pedido, 'procesando'));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al enviar correo de confirmación del pedido #{$pedido->id}: " . $e->getMessage());
            }

            // 5. Emitir Factura vía FastAPI si fue solicitada y no se ha timbrado
            if ($pedido->requiere_factura && $pedido->factura_estado !== 'facturado') {
                try {
                    app(\App\Services\FacturacionFastApiService::class)->generarFactura($pedido);
                } catch (\Exception $fe) {
                    \Illuminate\Support\Facades\Log::error("Error timbrando factura automática pedido #{$pedido->id}: " . $fe->getMessage());
                }
            }
        }

        return $pedido;
    }

    private function validarSesionPedido(Pedido $pedido, $session): void
    {
        if (($session->payment_status ?? null) !== 'paid'
            || ($session->mode ?? null) !== 'payment'
            || (string) $session->client_reference_id !== (string) $pedido->id
            || (string) $session->metadata->pedido_id !== (string) $pedido->id
            || ($pedido->stripe_session_id && $pedido->stripe_session_id !== $session->id)
            || $session->amount_total !== (int) round($pedido->total * 100)
            || $session->currency !== ($pedido->stripe_currency ?? 'mxn')) {
            throw new \RuntimeException("La sesión de Stripe no coincide con el pedido {$pedido->id}.");
        }
    }

    private function liberarReserva(Pedido $pedido): void
    {
        $pedido->liberarReservaStock('cancelado');
    }

    /**
     * Procesa la confirmación exitosa de pago al retornar de Stripe Checkout.
     */
    public function confirmarPagoStripe(Request $request)
    {
        $sessionId = $request->query('session_id');
        $pedidoId = null;

        $stripeSecret = config('services.stripe.secret');
        if (!is_string($sessionId) || empty($sessionId) || empty($stripeSecret)) {
            return redirect()->route('checkout')->with('error', 'Sesión de pago no válida o no encontrada.');
        }

        try {
            $stripe = new \Stripe\StripeClient($stripeSecret);
            $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId);

            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()->route('checkout')->with('error', 'El pago aún no figura como completado en Stripe. Por favor intenta nuevamente.');
            }

            if (!empty($checkoutSession->metadata->pedido_id)) {
                $pedidoId = $checkoutSession->metadata->pedido_id;
            } elseif (!empty($checkoutSession->client_reference_id)) {
                $pedidoId = $checkoutSession->client_reference_id;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error al consultar la sesión de Stripe {$sessionId}: " . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'No fue posible validar el pago con Stripe. Si tu banco realizó el cargo, contáctanos indicando tu correo.');
        }

        if (empty($pedidoId)) {
            return redirect()->route('checkout')->with('error', 'No se localizó la orden correspondiente.');
        }

        $pedido = Pedido::with('detalles')->find($pedidoId);

        if (!$pedido) {
            return redirect()->route('inicio')->with('info', 'Tu pedido ya ha sido registrado.');
        }

        // Si hay una sesión de usuario activa, verificar que no pertenezca a una cuenta distinta (permite retorno sin cookie tras 3D Secure)
        if (auth()->check() && $pedido->usuario_id && (int) auth()->id() !== (int) $pedido->usuario_id) {
            abort(403, 'No tienes autorización para confirmar este pedido.');
        }

        // Marcar como pagado de forma segura e idempotente
        $pedido = $this->marcarPedidoComoPagado($pedido, $checkoutSession);

        // Vaciar carrito en sesión y autorizar vista del recibo incluso si el navegador reinició cookies tras 3D Secure
        if ((int) session('ultimo_pedido_id') === (int) $pedido->id
            && session('carrito_checkout_hash') === hash('sha256', serialize(session('carrito')))) {
            $carritoGuardado = json_encode(session('carrito'));
            session()->forget(['carrito', 'cupon', 'datos_envio_checkout', 'ultimo_pedido_id', 'carrito_checkout_hash']);
            User::whereKey($pedido->usuario_id)->where('carrito_guardado', $carritoGuardado)->update(['carrito_guardado' => null]);
        }
        session()->put('pedido_confirmado_acceso_' . $pedido->id, true);

        return redirect()->route('pedido.confirmado', $pedido->id)->with('success', '¡Pago procesado con éxito en Stripe! Tu pedido ha sido confirmado.');
    }

    /**
     * Webhook de Stripe para confirmación asíncrona de pagos en segundo plano.
     * Soporta checkout.session.completed, checkout.session.async_payment_succeeded,
     * checkout.session.async_payment_failed y checkout.session.expired.
     */
    public function webhookStripe(Request $request)
    {
        $secret = config('services.stripe.webhook_secret');
        $stripeSecret = config('services.stripe.secret');
        $payload = $request->getContent();
        $sigHeader = (string) $request->header('Stripe-Signature', '');

        if ($sigHeader === '') {
            return response()->json(['error' => 'Firma ausente'], 400);
        }

        try {
            if (!empty($secret)) {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
            } else {
                if (empty($stripeSecret)) {
                    return response()->json(['error' => 'Webhook sin configurar'], 503);
                }
                $rawEvent = json_decode($payload);
                if (!$rawEvent || !isset($rawEvent->type, $rawEvent->data->object->id)) {
                    return response()->json(['error' => 'Evento inválido'], 400);
                }
                // Respaldo seguro: cuando STRIPE_WEBHOOK_SECRET no está definido aún en .env,
                // verificar la sesión directamente contra la API de Stripe con STRIPE_SECRET
                $stripe = new \Stripe\StripeClient($stripeSecret);
                $verifiedSession = $stripe->checkout->sessions->retrieve($rawEvent->data->object->id);
                $rawEvent->data->object = $verifiedSession;
                $event = $rawEvent;
            }
        } catch (\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Evento inválido'], 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error verificando Webhook de Stripe: " . $e->getMessage());
            return response()->json(['error' => 'Error verificando evento'], 400);
        }

        if ($event && isset($event->type)) {
            $session = $event->data->object ?? null;
            $pedidoId = $session->metadata->pedido_id ?? $session->client_reference_id ?? null;

            if ($pedidoId) {
                $pedido = Pedido::with('detalles')->find($pedidoId);
                if ($pedido) {
                    if (in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'])) {
                        if (($session->payment_status ?? '') === 'paid') {
                            $this->marcarPedidoComoPagado($pedido, $session);
                        }
                    } elseif (in_array($event->type, ['checkout.session.async_payment_failed', 'checkout.session.expired'])) {
                        DB::transaction(function () use ($pedido, $session) {
                            $locked = Pedido::with('detalles')->lockForUpdate()->findOrFail($pedido->id);
                            if ($locked->stripe_session_id === $session->id && $locked->stock_reservado && !$locked->stripe_paid_at) {
                                $this->liberarReserva($locked);
                            }
                        });
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Maneja el retorno cuando el cliente cancela o regresa desde Stripe Checkout.
     */
    public function cancelarPagoStripe(Request $request)
    {
        return redirect()->route('checkout')->with('info', 'El proceso de pago en Stripe fue cancelado. Tus productos siguen guardados en tu carrito por si deseas reintentar.');
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

        // Si no se proporcionó ID, buscar el último pedido del usuario autenticado
        if (!$pedido && auth()->check()) {
            $pedido = Pedido::with('detalles')
                ->where('usuario_id', auth()->id())
                ->latest()
                ->first();
        }

        // Si aún así no existe ningún pedido, redirigir al catálogo
        if (!$pedido) {
            return redirect()->route('catalogo')->with('info', 'No se localizó ningún pedido reciente para mostrar.');
        }

        // Control de Privacidad y Acceso:
        // Puede ver el recibo: el administrador, el dueño autenticado del pedido, o quien acaba de retornar de Stripe con sesión autorizada
        $esAdmin = auth()->check() && (bool) auth()->user()->is_admin;
        $accesoRecienteStripe = (bool) session()->get('pedido_confirmado_acceso_' . $pedido->id, false);

        if (!$esAdmin && !$accesoRecienteStripe) {
            if (!auth()->check()) {
                return redirect()->route('login')->with('info', 'Por favor inicia sesión para consultar los detalles de tu compra.');
            }

            if ($pedido->usuario_id && (int) $pedido->usuario_id !== (int) auth()->id()) {
                abort(403, 'No tienes autorización para consultar este recibo de compra.');
            }
        }

        // Verificar en tiempo real contra Stripe para que un pedido sin pago ($0.00 en Stripe) jamás aparezca como "Gracias por tu compra / Procesando"
        $diagnosticoStripe = $pedido->obtenerDiagnosticoStripe();

        return view('Principal.confirmado', compact('pedido', 'diagnosticoStripe'));
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
            'telefono' => 'required|string|min:10|max:20',
            'codigo_postal' => 'nullable|string|size:5',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'telefono.required' => 'El número de teléfono móvil es obligatorio para coordinar envíos y contacto.',
            'telefono.min' => 'Ingresa un número de teléfono válido de al menos 10 dígitos.',
        ]);

        // Preservar estado del carrito y cupón antes de registrar y regenerar sesión
        $carritoPrevio = session()->get('carrito', []);
        $cuponPrevio = session()->get('cupon');

        // Determinar código postal desde el formulario o sesión previa
        $cpRegistrado = $request->input('codigo_postal') ?: session('codigo_postal');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
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
     * Muestra la vista de perfil de usuario para consultar y editar sus datos, junto con su historial de compras/pedidos.
     */
    public function mostrarPerfil(Request $request)
    {
        $user = auth()->user();

        // Obtener historial de pedidos del usuario por usuario_id o por su correo electrónico registrado
        $pedidos = Pedido::with('detalles.producto')
            ->where(function ($query) use ($user) {
                $query->where('usuario_id', $user->id)
                      ->orWhere('correo_cliente', $user->email);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Principal.perfil', compact('user', 'pedidos'));
    }

    /**
     * Actualiza la información del perfil del usuario (excepto el correo).
     */
    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'codigo_postal' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $datosActualizar = [
            'name' => $request->name,
            'telefono' => $request->telefono,
            'codigo_postal' => $request->codigo_postal,
        ];

        if ($request->filled('password')) {
            $datosActualizar['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($datosActualizar);

        // Actualizar el código postal de la sesión si se editó
        if ($request->filled('codigo_postal')) {
            session(['codigo_postal' => $request->codigo_postal]);
        }

        return back()->with('exito', '¡Tu perfil ha sido actualizado correctamente!');
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
     * Sincroniza la lista de favoritos de la sesión con la base de datos del usuario autenticado.
     */
    private function sincronizarFavoritosUsuario()
    {
        if (auth()->check()) {
            try {
                $favoritos = session()->get('favoritos', []);
                auth()->user()->update([
                    'favoritos_guardados' => !empty($favoritos) ? json_encode($favoritos) : null,
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

        // Marcar ruleta como jugada en la sesión y cookie persistente (1 año)
        session()->put('ruleta_jugada', true);
        \Illuminate\Support\Facades\Cookie::queue('sm_ruleta_played', 'true', 525600);

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

    /**
     * Procesa y envía la solicitud del carrito al agente de ventas (2225722219)
     * por API en segundo plano con enlace directo al WhatsApp del cliente.
     */
    public function contactarAgenteVentasApi(Request $request)
    {
        $request->validate([
            'nombre_cliente'   => 'nullable|string|max:255',
            'telefono_cliente' => 'required|string|min:10|max:20',
            'codigo_postal'    => 'nullable|string|max:10',
        ], [
            'telefono_cliente.required' => 'Por favor ingresa tu número de teléfono móvil para que el agente pueda responderte por WhatsApp.',
            'telefono_cliente.min'      => 'Ingresa un número de teléfono válido de al menos 10 dígitos.',
        ]);

        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito de compras está vacío.'
            ], 422);
        }

        $nombre = trim($request->input('nombre_cliente')) ?: (auth()->user()->name ?? 'Cliente Web');
        $telefonoBruto = trim($request->input('telefono_cliente'));
        $cp = trim($request->input('codigo_postal')) ?: session('codigo_postal', 'Sin especificar');

        // Dirección completa de entrega
        $direccionEnvio = trim($request->input('direccion_envio')) ?: '';
        $ciudad = trim($request->input('ciudad')) ?: '';
        $referencias = trim($request->input('referencias')) ?: '';

        $partesDireccion = array_filter([$direccionEnvio, $ciudad, "C.P. {$cp}"]);
        if (!empty($referencias)) {
            $partesDireccion[] = "(Ref: {$referencias})";
        }
        $direccionCompleta = !empty($partesDireccion) ? implode(', ', $partesDireccion) : "C.P. {$cp}";

        // Limpiar número del cliente a dígitos
        $digitosTel = preg_replace('/[^0-9]/', '', $telefonoBruto);
        if (strlen($digitosTel) === 10) {
            $digitosTel = '52' . $digitosTel; // Formato México
        }

        // Calcular totales para Contado y MSI
        $subtotalContado = 0;
        $subtotalMsi = 0;
        $itemsTexto = "";
        foreach ($carrito as $id => $item) {
            $base = (float)($item['precio_original'] ?? $item['precio']);
            $isEspecial = !empty($item['es_promo_especial']);
            $producto = Producto::find($item['producto_id'] ?? $id);
            if ($producto) {
                $isEspecial = $producto->isPromoEspecial();
            }
            $pContado = round($base * ($isEspecial ? 0.50 : 0.60), 2);
            $pMsi = round($base * ($isEspecial ? 0.65 : 0.70), 2);

            $subtotalContado += $pContado * $item['cantidad'];
            $subtotalMsi += $pMsi * $item['cantidad'];

            $itemsTexto .= "• {$item['cantidad']}x {$item['nombre']} [Contado: $" . number_format($pContado * $item['cantidad'], 2, '.', ',') . " | MSI: $" . number_format($pMsi * $item['cantidad'], 2, '.', ',') . "]\n";
        }

        $descuento = 0.00;
        $cuponAplicado = session()->get('cupon');
        if ($cuponAplicado) {
            $cupon = Cupon::where('codigo', $cuponAplicado['codigo'])->where('activo', true)->first();
            if ($cupon) {
                $descuento = $cupon->calcularDescuento($subtotalContado);
            }
        }
        $envio = ($subtotalContado >= 10000) ? 0 : 600.00;
        $totalContado = max(0, $subtotalContado - $descuento) + $envio;
        $totalMsi = max(0, $subtotalMsi - $descuento) + $envio;
        $cuota12 = round($totalMsi / 12, 2);

        $totalContadoFmt = number_format($totalContado, 2, '.', ',');
        $totalMsiFmt = number_format($totalMsi, 2, '.', ',');
        $cuota12Fmt = number_format($cuota12, 2, '.', ',');

        // Enlace interactivo para que el agente responda directamente al WhatsApp del cliente
        $mensajeRespuestaAgente = rawurlencode("Hola {$nombre}, un gusto saludarte. Recibimos tu solicitud en Sector Mueble para tu pedido con dirección: {$direccionCompleta}. ¿En qué podemos ayudarte?");
        $linkRespuestaCliente = "https://wa.me/{$digitosTel}?text={$mensajeRespuestaAgente}";

        // Construir mensaje estructurado para la API de Ventas (Texto plano o CallMeBot)
        $mensajeVentas = "📥 *NUEVA SOLICITUD DE ATENCIÓN DE VENTAS*\n\n";
        $mensajeVentas .= "👤 *Cliente:* {$nombre}\n";
        $mensajeVentas .= "📞 *Teléfono:* +{$digitosTel}\n";
        $mensajeVentas .= "📍 *Dirección / CP:* {$direccionCompleta}\n\n";
        $mensajeVentas .= "🛋️ *ARTÍCULOS EN EL CARRITO:*\n{$itemsTexto}\n";
        $mensajeVentas .= "💰 *Total Contado:* \${$totalContadoFmt} MXN\n";
        $mensajeVentas .= "🗓️ *Total con MSI:* \${$totalMsiFmt} MXN (12 pagos de \${$cuota12Fmt} MXN)\n\n";
        $mensajeVentas .= "💬 *HAZ CLIC AQUÍ PARA RESPONDER AL CLIENTE POR WHATSAPP:*\n{$linkRespuestaCliente}";

        $numeroVentasRaw = config('services.whatsapp.ventas_number', '522226702641');
        $numeroVentas = preg_replace('/[^0-9]/', '', $numeroVentasRaw);
        if (strlen($numeroVentas) === 10) {
            $numeroVentas = '52' . $numeroVentas;
        }

        $apiUrl = config('services.whatsapp.api_url');
        $apiToken = config('services.whatsapp.api_token');

        // Registro de respaldo en logs
        \Illuminate\Support\Facades\Log::info("SOLICITUD DE ATENCIÓN A VENTAS POR WHATSAPP (A {$numeroVentas}):\n" . $mensajeVentas);

        // Despacho vía API (Meta WhatsApp Cloud API, CallMeBot, Green API o Webhook Universal)
        $enviadoPorApi = false;

        if (!empty($apiToken) && empty($apiUrl)) {
            // Pasarela Gratuita CallMeBot
            try {
                $urlCallMeBot = "https://api.callmebot.com/whatsapp.php?phone={$numeroVentas}&text=" . urlencode($mensajeVentas) . "&apikey={$apiToken}";
                $respCallMeBot = \Illuminate\Support\Facades\Http::timeout(10)->get($urlCallMeBot);
                if ($respCallMeBot->successful()) {
                    $enviadoPorApi = true;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al enviar WhatsApp por CallMeBot: " . $e->getMessage());
            }
        } elseif (!empty($apiUrl)) {
            // Pasarela Meta WhatsApp Cloud API / Green API / Webhook Universal
            try {
                if (str_contains($apiUrl, 'graph.facebook.com')) {
                    // Meta WhatsApp Cloud API oficial
                    $templateName = config('services.whatsapp.template', 'aviso_nuevo_contacto');

                    $payloadMeta = [
                        'messaging_product' => 'whatsapp',
                        'recipient_type'    => 'individual',
                        'to'                => $numeroVentas,
                    ];

                    if (!empty($templateName)) {
                        $templateLang = config('services.whatsapp.language', 'es');
                        $itemsTextoLimpio = str_replace(["\r\n", "\r", "\n"], ' | ', trim($itemsTexto));
                        $itemsTextoLimpio = preg_replace('/\s*\|\s*\|\s*/', ' | ', $itemsTextoLimpio);

                        $payloadMeta['type'] = 'template';
                        $payloadMeta['template'] = [
                            'name'     => $templateName,
                            'language' => [
                                'code' => $templateLang
                            ],
                            'components' => [
                                [
                                    'type'       => 'body',
                                    'parameters' => [
                                        ['type' => 'text', 'text' => (string) $nombre],
                                        ['type' => 'text', 'text' => '+' . (string) $digitosTel],
                                        ['type' => 'text', 'text' => (string) $direccionCompleta],
                                        ['type' => 'text', 'text' => (string) $itemsTextoLimpio],
                                        ['type' => 'text', 'text' => '$' . (string) $totalContadoFmt . ' MXN'],
                                    ]
                                ]
                            ]
                        ];
                    } else {
                        $payloadMeta['type'] = 'text';
                        $payloadMeta['text'] = [
                            'preview_url' => true,
                            'body'        => $mensajeVentas,
                        ];
                    }

                    $responseMeta = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(10)->withToken($apiToken)->post($apiUrl, $payloadMeta);

                    \Illuminate\Support\Facades\Log::info("Meta WhatsApp API Response status=" . $responseMeta->status() . " body=" . $responseMeta->body());

                    if ($responseMeta->successful()) {
                        $enviadoPorApi = true;
                    } else {
                        \Illuminate\Support\Facades\Log::error("Error al enviar WhatsApp por Meta API (Template): [" . $responseMeta->status() . "] " . $responseMeta->body() . ". Reintentando con texto plano...");
                        // Reintento de respaldo con texto plano por si la plantilla aún no está aprobada
                        $responseMetaText = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(10)->withToken($apiToken)->post($apiUrl, [
                            'messaging_product' => 'whatsapp',
                            'recipient_type'    => 'individual',
                            'to'                => $numeroVentas,
                            'type'              => 'text',
                            'text'              => [
                                'preview_url' => true,
                                'body'        => $mensajeVentas,
                            ],
                        ]);
                        if ($responseMetaText->successful()) {
                            $enviadoPorApi = true;
                        }
                    }
                } elseif (str_contains($apiUrl, 'green-api.com')) {
                    // Green API
                    $respGreen = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(10)->post($apiUrl, [
                        'chatId'  => $numeroVentas . '@c.us',
                        'message' => $mensajeVentas,
                    ]);
                    if ($respGreen->successful()) {
                        $enviadoPorApi = true;
                    }
                } else {
                    // UltraMsg / Evolution API / Custom Webhook Universal
                    $respWeb = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(10)->withHeaders([
                        'Authorization' => 'Bearer ' . $apiToken,
                        'Content-Type'  => 'application/json',
                    ])->post($apiUrl, [
                        'to'        => $numeroVentas,
                        'phone'     => $numeroVentas,
                        'body'      => $mensajeVentas,
                        'message'   => $mensajeVentas,
                        'token'     => $apiToken,
                        'token_key' => $apiToken,
                    ]);
                    if ($respWeb->successful()) {
                        $enviadoPorApi = true;
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al enviar WhatsApp por API: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "¡Solicitud enviada con éxito! Nuestro agente de ventas te contactará directamente a tu número (+{$digitosTel}) a la brevedad.",
            'telefono_cliente' => $digitosTel,
            'enviado_api' => $enviadoPorApi,
        ]);
    }

    /**
     * Actualiza el estatus del pedido a 'contacto_agente' (Contacto a Agente de Ventas)
     * cuando un cliente contacta al agente de ventas y notifica al correo verificado.
     */
    public function contactarAgentePedido(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $estadoAnterior = $pedido->estado;
        $yaPagado = in_array($estadoAnterior, ['procesando', 'procesado', 'pagado', 'completado', 'enviado', 'entregado', 'recibido']);

        // Solo cambiar a 'contacto_agente' si el pedido no estaba ya pagado/en curso
        if (!$yaPagado && $estadoAnterior !== 'contacto_agente') {
            $pedido->update(['estado' => 'contacto_agente']);

            // Notificar por correo al cliente verificado
            try {
                $correoDestino = !empty($pedido->correo_cliente) ? $pedido->correo_cliente : optional($pedido->usuario)->email;
                if ($correoDestino) {
                    \Illuminate\Support\Facades\Notification::route('mail', $correoDestino)
                        ->notify(new \App\Notifications\EstadoPedidoNotificacion($pedido, 'contacto_agente'));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al notificar contacto de agente para pedido #{$pedido->id}: " . $e->getMessage());
            }
        }

        $numPedido = str_pad($pedido->id, 5, '0', STR_PAD_LEFT);
        $mensajeWhatsapp = rawurlencode("Hola, quisiera solicitar atención de un agente de ventas sobre mi pedido #{$numPedido} a nombre de {$pedido->nombre_cliente}.");
        $whatsappUrl = "https://wa.me/522225722219?text={$mensajeWhatsapp}";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El estatus de tu pedido ha sido actualizado a "Contacto a Agente de Ventas".',
                'whatsapp_url' => $whatsappUrl,
            ]);
        }

        return redirect()->away($whatsappUrl);
    }
}



