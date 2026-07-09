<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $consulta->where('nombre', 'like', '%' . $request->buscar . '%')
                ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
        }

        // Filtro por categoría
        if ($request->filled('categoria') && $request->categoria != 'todas') {
            $consulta->where('categoria', $request->categoria);
        }

        // Filtro por precio mínimo
        if ($request->filled('precio_min')) {
            $consulta->where('precio', '>=', $request->precio_min);
        }

        // Filtro por precio máximo
        if ($request->filled('precio_max')) {
            $consulta->where('precio', '<=', $request->precio_max);
        }

        // Ordenación
        if ($request->filled('ordenar')) {
            switch ($request->ordenar) {
                case 'precio_asc':
                    $consulta->orderBy('precio', 'asc');
                    break;
                case 'precio_desc':
                    $consulta->orderBy('precio', 'desc');
                    break;
                case 'calificacion_desc':
                    $consulta->orderBy('calificacion', 'desc');
                    break;
                default:
                    $consulta->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $consulta->orderBy('created_at', 'desc');
        }

        $productos = $consulta->paginate(9)->withQueryString();
        
        $categorias = Producto::select('categoria')
            ->distinct()
            ->pluck('categoria');

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

        // Envío gratis si supera los 400, si no, 29 de coste de envío
        $envio = ($subtotal > 400 || $subtotal == 0) ? 0 : 29.00;
        $total = $subtotal + $envio;

        return view('Principal.carrito', compact('carrito', 'subtotal', 'envio', 'total'));
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
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen_url' => $producto->imagen_url,
                'cantidad' => $cantidad,
                'categoria' => $producto->categoria,
                'stock_disponible' => $producto->stock
            ];
        }

        session()->put('carrito', $carrito);

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
     * Mostrar la vista para finalizar compra.
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

        $envio = ($subtotal > 400) ? 0 : 29.00;
        $total = $subtotal + $envio;

        return view('Principal.checkout', compact('carrito', 'subtotal', 'envio', 'total'));
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

        $envio = ($subtotal > 400) ? 0 : 29.00;
        $total = $subtotal + $envio;

        try {
            DB::beginTransaction();

            // 1. Crear Pedido
            $pedido = Pedido::create([
                'nombre_cliente' => $request->nombre_cliente,
                'correo_cliente' => $request->correo_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'direccion_envio' => $request->direccion_envio,
                'ciudad' => $request->ciudad,
                'codigo_postal' => $request->codigo_postal,
                'total' => $total,
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

            // Vaciar carrito
            session()->forget('carrito');

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
}
