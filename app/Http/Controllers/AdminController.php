<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Cupon;
use App\Models\User;
use App\Models\RuletaOpcion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Dashboard general con métricas de ventas y pedidos.
     */
    public function dashboard()
    {
        // Métricas básicas
        $ventasTotales = Pedido::where('estado', '!=', 'cancelado')->sum('total');
        $pedidosTotales = Pedido::count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $mueblesTotales = Producto::count();
        $clientesTotales = User::where('is_admin', false)->count();
        $mueblesStockBajo = Producto::where('stock', '<=', 5)->get();

        // Pedidos recientes
        $pedidosRecientes = Pedido::orderBy('created_at', 'desc')->take(5)->get();

        return view('Admin.dashboard', compact(
            'ventasTotales',
            'pedidosTotales',
            'pedidosPendientes',
            'mueblesTotales',
            'clientesTotales',
            'mueblesStockBajo',
            'pedidosRecientes'
        ));
    }

    // --- CRUD DE MUEBLES (ARTÍCULOS) ---

    public function productosIndex()
    {
        $productos = Producto::orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.productos.index', compact('productos'));
    }

    public function productosCrear()
    {
        return view('Admin.productos.crear');
    }

    public function productosGuardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen_url' => 'required|url',
            'categoria' => 'required|string',
            'stock' => 'required|integer|min:0',
            'calificacion' => 'required|numeric|between:1,5',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen_url' => $request->imagen_url,
            'categoria' => $request->categoria,
            'stock' => $request->stock,
            'calificacion' => $request->calificacion,
            'destacado' => $request->has('destacado'),
        ]);

        return redirect()->route('admin.productos')->with('success', 'Mueble agregado con éxito al catálogo.');
    }

    public function productosEditar($id)
    {
        $producto = Producto::findOrFail($id);
        return view('Admin.productos.editar', compact('producto'));
    }

    public function productosActualizar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen_url' => 'required|url',
            'categoria' => 'required|string',
            'stock' => 'required|integer|min:0',
            'calificacion' => 'required|numeric|between:1,5',
        ]);

        $producto->update([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen_url' => $request->imagen_url,
            'categoria' => $request->categoria,
            'stock' => $request->stock,
            'calificacion' => $request->calificacion,
            'destacado' => $request->has('destacado'),
        ]);

        return redirect()->route('admin.productos')->with('success', 'Mueble actualizado correctamente.');
    }

    public function productosEliminar($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('admin.productos')->with('success', 'Mueble eliminado del catálogo.');
    }

    /**
     * Muestra el formulario para aplicar un descuento directo a un producto.
     */
    public function productosDescuento($id)
    {
        $producto = Producto::findOrFail($id);
        return view('Admin.productos.descuento', compact('producto'));
    }

    /**
     * Aplica o quita el descuento directo de un producto.
     */
    public function productosAplicarDescuento(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        // Si se quiere quitar el descuento
        if ($request->has('quitar_descuento')) {
            $producto->update([
                'porcentaje_descuento' => null,
                'precio_descuento'     => null,
            ]);
            return redirect()->route('admin.productos')
                ->with('success', "Descuento quitado del mueble \"{$producto->nombre}\".");
        }

        $request->validate([
            'porcentaje_descuento' => 'required|integer|min:1|max:99',
        ]);

        $porcentaje = (int) $request->porcentaje_descuento;
        $precioConDescuento = round($producto->precio * (1 - $porcentaje / 100), 2);

        $producto->update([
            'porcentaje_descuento' => $porcentaje,
            'precio_descuento'     => $precioConDescuento,
        ]);

        return redirect()->route('admin.productos')
            ->with('success', "Descuento del {$porcentaje}% aplicado a \"{$producto->nombre}\". Precio final: \${$precioConDescuento}.");
    }

    // --- CRUD DE CUPONES (DESCUENTOS) ---

    public function cuponesIndex()
    {
        $cupones = Cupon::orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.cupones.index', compact('cupones'));
    }

    public function cuponesCrear()
    {
        return view('Admin.cupones.crear');
    }

    public function cuponesGuardar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:cupones,codigo|max:50',
            'tipo' => 'required|in:fijo,porcentaje',
            'valor' => 'required|numeric|min:0',
        ]);

        Cupon::create([
            'codigo' => strtoupper($request->codigo),
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('admin.cupones')->with('success', 'Cupón de descuento creado con éxito.');
    }

    public function cuponesEditar($id)
    {
        $cupon = Cupon::findOrFail($id);
        return view('Admin.cupones.editar', compact('cupon'));
    }

    public function cuponesActualizar(Request $request, $id)
    {
        $cupon = Cupon::findOrFail($id);

        $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo,' . $cupon->id,
            'tipo' => 'required|in:fijo,porcentaje',
            'valor' => 'required|numeric|min:0',
        ]);

        $cupon->update([
            'codigo' => strtoupper($request->codigo),
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('admin.cupones')->with('success', 'Cupón actualizado correctamente.');
    }

    public function cuponesEliminar($id)
    {
        $cupon = Cupon::findOrFail($id);
        $cupon->delete();

        return redirect()->route('admin.cupones')->with('success', 'Cupón de descuento eliminado.');
    }

    // --- GESTIÓN DE PEDIDOS ---

    public function pedidosIndex()
    {
        $pedidos = Pedido::orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.pedidos.index', compact('pedidos'));
    }

    public function pedidosDetalle($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);
        return view('Admin.pedidos.detalle', compact('pedido'));
    }

    public function pedidosActualizarEstado(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:pendiente,procesado,enviado,entregado,cancelado',
        ]);

        $pedido->update([
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.pedidos.detalle', $pedido->id)->with('success', 'Estado del pedido actualizado correctamente.');
    }

    // --- GESTIÓN DE RULETA DE PREMIOS ---

    public function ruletaIndex()
    {
        $opciones = RuletaOpcion::orderBy('posicion', 'asc')->get();
        
        // Si no existen 3 opciones, asegurar que existan posiciones 1, 2 y 3
        for ($i = 1; $i <= 3; $i++) {
            if (!$opciones->contains('posicion', $i)) {
                RuletaOpcion::create([
                    'posicion' => $i,
                    'titulo' => 'Premio Opción ' . $i,
                    'codigo_cupon' => 'PREMIO' . $i,
                    'tipo_descuento' => 'porcentaje',
                    'descuento_valor' => 10,
                    'tiempo_minutos' => 15,
                    'color_bg' => $i == 1 ? '#B45309' : ($i == 2 ? '#15803D' : '#1E3A8A'),
                    'activo' => true,
                ]);
            }
        }

        $opciones = RuletaOpcion::orderBy('posicion', 'asc')->get();
        return view('Admin.ruleta.index', compact('opciones'));
    }

    public function ruletaActualizar(Request $request)
    {
        $request->validate([
            'opciones' => 'required|array',
            'opciones.*.titulo' => 'required|string|max:255',
            'opciones.*.codigo_cupon' => 'nullable|string|max:50',
            'opciones.*.tipo_descuento' => 'required|in:porcentaje,fijo,envio_gratis',
            'opciones.*.descuento_valor' => 'required|numeric|min:0',
            'opciones.*.tiempo_minutos' => 'required|integer|min:1|max:1440',
            'opciones.*.color_bg' => 'required|string|max:20',
        ]);

        foreach ($request->opciones as $posicion => $datos) {
            $opcion = RuletaOpcion::where('posicion', $posicion)->first();
            if ($opcion) {
                $opcion->update([
                    'titulo' => $datos['titulo'],
                    'codigo_cupon' => strtoupper($datos['codigo_cupon'] ?? ''),
                    'tipo_descuento' => $datos['tipo_descuento'],
                    'descuento_valor' => $datos['descuento_valor'],
                    'tiempo_minutos' => $datos['tiempo_minutos'],
                    'color_bg' => $datos['color_bg'],
                    'activo' => isset($datos['activo']) ? true : false,
                ]);

                // También asegurar o actualizar el cupón correspondiente en la tabla cupones si tiene código
                if (!empty($datos['codigo_cupon'])) {
                    $codigo = strtoupper($datos['codigo_cupon']);
                    $tipoCupon = $datos['tipo_descuento'] == 'envio_gratis' ? 'fijo' : $datos['tipo_descuento'];
                    $valorCupon = $datos['tipo_descuento'] == 'envio_gratis' ? 0 : $datos['descuento_valor'];

                    Cupon::updateOrCreate(
                        ['codigo' => $codigo],
                        [
                            'tipo' => $tipoCupon,
                            'valor' => $valorCupon,
                            'activo' => true,
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.ruleta')->with('success', 'Las 3 opciones de la Ruleta de Premios han sido actualizadas con éxito.');
    }
}
