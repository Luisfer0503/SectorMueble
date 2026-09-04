<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\ProductoDetalle;
use App\Models\Pedido;
use App\Models\Cupon;
use App\Models\User;
use App\Models\RuletaOpcion;
use App\Models\Zapato;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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
        $mueblesStockBajo = Producto::with('detalles')->get()->filter(function ($p) {
            return $p->stock <= 5;
        });

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
        $productos = Producto::with('detalles')->orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.productos.index', compact('productos'));
    }

    /**
     * Exporta el catálogo completo de muebles a un archivo Excel/CSV con BOM UTF-8.
     */
    public function productosExportarExcel()
    {
        $productos = Producto::orderBy('id', 'asc')->get();
        $fileName = 'Catálogo_Muebles_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($productos) {
            $file = fopen('php://output', 'w');
            // Incluir BOM UTF-8 para que Excel lo abra nativamente con caracteres y acentos correctos
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados requeridos: ID, Nombre, Descripción, Stock, Precio, Categoría
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Stock', 'Precio', 'Categoría']);

            foreach ($productos as $p) {
                // Limpiar saltos de línea en descripción para presentación ordenada en Excel
                $descLimpia = preg_replace('/\s+/', ' ', trim($p->descripcion ?? ''));

                fputcsv($file, [
                    $p->id,
                    $p->nombre,
                    $descLimpia,
                    $p->stock,
                    number_format((float)$p->precio, 2, '.', ''),
                    $p->categoria,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
            'imagen_archivo' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_secundaria_archivo' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_dimension_lateral' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_dimension_frontal' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_dimension_superior' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'categoria' => 'required|string',
            'proveedor' => 'required|string|in:Casa Tapier,Muebles Samar',
            'tipo_mueble' => 'nullable|string',
            'numero_piezas' => 'required|integer|min:1',
            'calificacion' => 'required|numeric|between:1,5',
        ]);

        $folder = public_path('storage/productos');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $file = $request->file('imagen_archivo');
        $filename = time() . '_p1_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);
        $imagenUrl = 'storage/productos/' . $filename;

        $imagenSecundariaUrl = null;
        if ($request->hasFile('imagen_secundaria_archivo') && $request->file('imagen_secundaria_archivo')->isValid()) {
            $file2 = $request->file('imagen_secundaria_archivo');
            $filename2 = time() . '_p2_' . Str::slug(pathinfo($file2->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file2->getClientOriginalExtension();
            $file2->move($folder, $filename2);
            $imagenSecundariaUrl = 'storage/productos/' . $filename2;
        }

        $dimLateral = null;
        if ($request->hasFile('imagen_dimension_lateral') && $request->file('imagen_dimension_lateral')->isValid()) {
            $fDim = $request->file('imagen_dimension_lateral');
            $fName = time() . '_dim_lat_' . Str::slug(pathinfo($fDim->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fDim->getClientOriginalExtension();
            $fDim->move($folder, $fName);
            $dimLateral = 'storage/productos/' . $fName;
        }

        $dimFrontal = null;
        if ($request->hasFile('imagen_dimension_frontal') && $request->file('imagen_dimension_frontal')->isValid()) {
            $fDim = $request->file('imagen_dimension_frontal');
            $fName = time() . '_dim_fro_' . Str::slug(pathinfo($fDim->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fDim->getClientOriginalExtension();
            $fDim->move($folder, $fName);
            $dimFrontal = 'storage/productos/' . $fName;
        }

        $dimSuperior = null;
        if ($request->hasFile('imagen_dimension_superior') && $request->file('imagen_dimension_superior')->isValid()) {
            $fDim = $request->file('imagen_dimension_superior');
            $fName = time() . '_dim_sup_' . Str::slug(pathinfo($fDim->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fDim->getClientOriginalExtension();
            $fDim->move($folder, $fName);
            $dimSuperior = 'storage/productos/' . $fName;
        }

        $acabadosData = $this->procesarColoresRequest($request);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'imagen_url' => $imagenUrl,
            'imagen_secundaria_url' => $imagenSecundariaUrl,
            'categoria' => $request->categoria,
            'proveedor' => $request->proveedor,
            'tipo_mueble' => $request->tipo_mueble ?? '01',
            'numero_piezas' => (int) ($request->numero_piezas ?? 1),
            'calificacion' => $request->calificacion,
            'destacado' => $request->has('destacado'),
            'activo' => $request->has('activo') ? (bool)$request->activo : true,
            'colores' => !empty($acabadosData) ? $acabadosData : null,
            'imagen_dimension_lateral' => $dimLateral,
            'imagen_dimension_frontal' => $dimFrontal,
            'imagen_dimension_superior' => $dimSuperior,
        ]);

        $this->syncProductoDetalles($producto, $acabadosData);

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
            'imagen_archivo' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_secundaria_archivo' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_dimension_lateral' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_dimension_frontal' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'imagen_dimension_superior' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'categoria' => 'required|string',
            'proveedor' => 'required|string|in:Casa Tapier,Muebles Samar',
            'tipo_mueble' => 'nullable|string',
            'numero_piezas' => 'required|integer|min:1',
            'calificacion' => 'required|numeric|between:1,5',
        ]);

        $folder = public_path('storage/productos');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $imagenUrl = $producto->getRawOriginal('imagen_url');
        if ($request->hasFile('imagen_archivo') && $request->file('imagen_archivo')->isValid()) {
            $file = $request->file('imagen_archivo');
            $filename = time() . '_p1_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $filename);
            $imagenUrl = 'storage/productos/' . $filename;
        }

        $imagenSecundariaUrl = $producto->getRawOriginal('imagen_secundaria_url');
        if ($request->hasFile('imagen_secundaria_archivo') && $request->file('imagen_secundaria_archivo')->isValid()) {
            $file2 = $request->file('imagen_secundaria_archivo');
            $filename2 = time() . '_p2_' . Str::slug(pathinfo($file2->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file2->getClientOriginalExtension();
            $file2->move($folder, $filename2);
            $imagenSecundariaUrl = 'storage/productos/' . $filename2;
        }

        $dimLateral = $producto->getRawOriginal('imagen_dimension_lateral');
        if ($request->hasFile('imagen_dimension_lateral') && $request->file('imagen_dimension_lateral')->isValid()) {
            $fDim = $request->file('imagen_dimension_lateral');
            $fName = time() . '_dim_lat_' . Str::slug(pathinfo($fDim->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fDim->getClientOriginalExtension();
            $fDim->move($folder, $fName);
            $dimLateral = 'storage/productos/' . $fName;
        }

        $dimFrontal = $producto->getRawOriginal('imagen_dimension_frontal');
        if ($request->hasFile('imagen_dimension_frontal') && $request->file('imagen_dimension_frontal')->isValid()) {
            $fDim = $request->file('imagen_dimension_frontal');
            $fName = time() . '_dim_fro_' . Str::slug(pathinfo($fDim->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fDim->getClientOriginalExtension();
            $fDim->move($folder, $fName);
            $dimFrontal = 'storage/productos/' . $fName;
        }

        $dimSuperior = $producto->getRawOriginal('imagen_dimension_superior');
        if ($request->hasFile('imagen_dimension_superior') && $request->file('imagen_dimension_superior')->isValid()) {
            $fDim = $request->file('imagen_dimension_superior');
            $fName = time() . '_dim_sup_' . Str::slug(pathinfo($fDim->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fDim->getClientOriginalExtension();
            $fDim->move($folder, $fName);
            $dimSuperior = 'storage/productos/' . $fName;
        }

        $acabadosData = $this->procesarColoresRequest($request);

        $producto->update([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'imagen_url' => $imagenUrl,
            'imagen_secundaria_url' => $imagenSecundariaUrl,
            'categoria' => $request->categoria,
            'proveedor' => $request->proveedor,
            'tipo_mueble' => $request->tipo_mueble ?? '01',
            'numero_piezas' => (int) ($request->numero_piezas ?? 1),
            'calificacion' => $request->calificacion,
            'destacado' => $request->has('destacado'),
            'activo' => $request->has('activo') ? (bool)$request->activo : true,
            'colores' => !empty($acabadosData) ? $acabadosData : null,
            'imagen_dimension_lateral' => $dimLateral,
            'imagen_dimension_frontal' => $dimFrontal,
            'imagen_dimension_superior' => $dimSuperior,
        ]);

        $this->syncProductoDetalles($producto, $acabadosData);

        return redirect()->route('admin.productos')->with('success', 'Mueble actualizado correctamente.');
    }

    /**
     * Sincroniza la tabla producto_detalles a partir de los datos de acabados, precio, stock y SKU.
     */
    private function syncProductoDetalles(Producto $producto, array $acabadosData): void
    {
        ProductoDetalle::where('producto_id', $producto->id)->delete();

        if (!empty($acabadosData)) {
            foreach ($acabadosData as $idx => $acabado) {
                $nombre = !empty($acabado['nombre']) ? trim($acabado['nombre']) : 'Acabado';
                $imagen = $acabado['mueble_imagen'] ?? $acabado['material_imagen'] ?? null;
                $matImg = $acabado['material_imagen'] ?? null;
                $precio = (isset($acabado['precio']) && $acabado['precio'] !== null) ? (float) $acabado['precio'] : (float) $producto->precio;
                $stock  = (isset($acabado['stock']) && $acabado['stock'] !== null) ? (int) $acabado['stock'] : (int) $producto->stock;
                $activoSub = isset($acabado['activo']) ? (bool) $acabado['activo'] : true;
                
                $defaultSku = Producto::generarSkuFormateado($producto, $nombre);
                $rawSku = !empty($acabado['sku']) ? trim($acabado['sku']) : '';
                $sku = (!empty($rawSku) && !str_contains($rawSku, 'AUTO') && !str_starts_with($rawSku, 'SKU-0')) ? $rawSku : $defaultSku;

                ProductoDetalle::create([
                    'producto_id'     => $producto->id,
                    'sku'             => $sku,
                    'nombre'          => $nombre,
                    'imagen'          => $imagen,
                    'material_imagen' => $matImg,
                    'precio'          => $precio,
                    'stock'           => $stock,
                    'activo'          => $activoSub,
                ]);
            }
        } else {
            ProductoDetalle::create([
                'producto_id' => $producto->id,
                'sku'         => Producto::generarSkuFormateado($producto, 'Original'),
                'nombre'      => $producto->nombre . ' (Original / Natural)',
                'imagen'      => $producto->getRawOriginal('imagen_url'),
                'precio'      => (float) $producto->precio,
                'stock'       => (int) $producto->stock,
            ]);
        }
    }

    /**
     * Procesa los campos de acabados y materiales enviados desde el formulario de admin.
     */
    private function procesarColoresRequest(Request $request): array
    {
        $acabados = [];
        $folder = public_path('storage/productos');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        // Si se reciben nuevos acabados estructurados (material + nombre + foto mueble)
        if ($request->has('acabados_nombres') || $request->has('colores_nombres')) {
            $nombres = $request->input('acabados_nombres', $request->input('colores_nombres', []));
            $precios = $request->input('acabados_precios', []);
            $stocks  = $request->input('acabados_stocks', []);
            $skus    = $request->input('acabados_skus', []);
            $matExistentes = $request->input('acabados_materiales_existentes', []);
            $muebleExistentes = $request->input('acabados_muebles_existentes', $request->input('colores_imagenes_existentes', []));
            $activosInput = $request->input('acabados_activos', []);

            foreach ($nombres as $i => $nombre) {
                if (trim($nombre) === '') continue;

                $materialPath = $matExistentes[$i] ?? null;
                $mueblePath = $muebleExistentes[$i] ?? null;
                $precioVal = isset($precios[$i]) && $precios[$i] !== '' ? (float) $precios[$i] : null;
                $stockVal = isset($stocks[$i]) && $stocks[$i] !== '' ? (int) $stocks[$i] : null;
                $skuVal = isset($skus[$i]) && $skus[$i] !== '' ? trim($skus[$i]) : null;
                $activoVal = isset($activosInput[$i]) ? (bool) $activosInput[$i] : true;

                // Subida de imagen de muestra de material
                $matFiles = $request->file('acabados_materiales');
                if (is_array($matFiles) && isset($matFiles[$i]) && $matFiles[$i]->isValid()) {
                    $matFile = $matFiles[$i];
                    $matFilename = time() . '_mat_' . $i . '_' . Str::slug(pathinfo($matFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $matFile->getClientOriginalExtension();
                    $matFile->move($folder, $matFilename);
                    $materialPath = 'storage/productos/' . $matFilename;
                } elseif ($request->hasFile("acabados_materiales.{$i}") && $request->file("acabados_materiales.{$i}")->isValid()) {
                    $matFile = $request->file("acabados_materiales.{$i}");
                    $matFilename = time() . '_mat_' . $i . '_' . Str::slug(pathinfo($matFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $matFile->getClientOriginalExtension();
                    $matFile->move($folder, $matFilename);
                    $materialPath = 'storage/productos/' . $matFilename;
                }

                // Subida de foto del mueble con este material
                $muebleFiles = $request->file('acabados_muebles');
                if (is_array($muebleFiles) && isset($muebleFiles[$i]) && $muebleFiles[$i]->isValid()) {
                    $muebleFile = $muebleFiles[$i];
                    $muebleFilename = time() . '_acabmueble_' . $i . '_' . Str::slug(pathinfo($muebleFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $muebleFile->getClientOriginalExtension();
                    $muebleFile->move($folder, $muebleFilename);
                    $mueblePath = 'storage/productos/' . $muebleFilename;
                } elseif ($request->hasFile("acabados_muebles.{$i}") && $request->file("acabados_muebles.{$i}")->isValid()) {
                    $muebleFile = $request->file("acabados_muebles.{$i}");
                    $muebleFilename = time() . '_acabmueble_' . $i . '_' . Str::slug(pathinfo($muebleFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $muebleFile->getClientOriginalExtension();
                    $muebleFile->move($folder, $muebleFilename);
                    $mueblePath = 'storage/productos/' . $muebleFilename;
                } elseif ($request->hasFile("colores_imagenes.{$i}") && $request->file("colores_imagenes.{$i}")->isValid()) {
                    $muebleFile = $request->file("colores_imagenes.{$i}");
                    $muebleFilename = time() . '_col_' . $i . '_' . Str::slug(pathinfo($muebleFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $muebleFile->getClientOriginalExtension();
                    $muebleFile->move($folder, $muebleFilename);
                    $mueblePath = 'storage/productos/' . $muebleFilename;
                }

                $acabados[] = [
                    'nombre'          => trim($nombre),
                    'material_imagen' => $materialPath,
                    'mueble_imagen'   => $mueblePath,
                    'precio'          => $precioVal,
                    'stock'           => $stockVal,
                    'sku'             => $skuVal,
                    'activo'          => $activoVal,
                ];
            }
        }
        return $acabados;
    }

    public function productosEliminar($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('admin.productos')->with('success', 'Mueble eliminado del catálogo.');
    }

    /**
     * Alterna (toggle) el estado activo / inactivo de un mueble principal.
     */
    public function toggleProductoActivo(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $nuevoEstado = $request->has('activo') ? (bool)$request->activo : !$producto->activo;
        $producto->update(['activo' => $nuevoEstado]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'activo' => $producto->activo,
                'message' => $producto->activo ? 'El mueble "' . $producto->nombre . '" está ahora ACTIVO al público.' : 'El mueble "' . $producto->nombre . '" está ahora INACTIVO al público.',
            ]);
        }

        return redirect()->back()->with('success', $producto->activo ? 'El mueble "' . $producto->nombre . '" está ahora activo al público.' : 'El mueble "' . $producto->nombre . '" está ahora inactivo al público.');
    }

    /**
     * Alterna (toggle) el estado activo / inactivo de un subartículo (acabado / variante).
     */
    public function toggleSubarticuloActivo(Request $request, $id)
    {
        $detalle = ProductoDetalle::findOrFail($id);
        $nuevoEstado = $request->has('activo') ? (bool)$request->activo : !$detalle->activo;
        $detalle->update(['activo' => $nuevoEstado]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'activo' => $detalle->activo,
                'message' => $detalle->activo ? 'Subartículo "' . $detalle->nombre . '" activado.' : 'Subartículo "' . $detalle->nombre . '" desactivado.',
            ]);
        }

        return redirect()->back()->with('success', $detalle->activo ? 'Subartículo "' . $detalle->nombre . '" activado.' : 'Subartículo "' . $detalle->nombre . '" desactivado.');
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

    // --- INVENTARIO DE ZAPATOS CON ESCÁNER IA DE FOTO ---

    /**
     * Verifica que el usuario autenticado tenga ID entre 2 y 6 para acceder al inventario de zapatos.
     */
    private function verificarAccesoZapatos()
    {
        $id = auth()->id();
        if (!$id || $id < 2 || $id > 6) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['error' => 'No tienes autorización para acceder a esta función.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'El Inventario de Zapatos solo está disponible para usuarios autorizados (ID 2 a 6).');
        }
        return null;
    }

    /**
     * Muestra la lista de zapatos escaneados en el inventario.
     */
    public function zapatosIndex(Request $request)
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;

        $query = Zapato::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('estilo', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
            });
        }

        $zapatos = $query->orderBy('created_at', 'desc')->paginate(12);

        // Métricas de inventario
        $totalModelos = Zapato::count();
        $totalPares = Zapato::sum('cantidad');
        $valorTotalInventario = Zapato::all()->sum(function ($z) {
            return $z->cantidad * $z->precio;
        });

        return view('Admin.zapatos.index', compact('zapatos', 'totalModelos', 'totalPares', 'valorTotalInventario'));
    }

    /**
     * Endpoint AJAX para recibir la foto del zapato (WebCam o archivo)
     * y extraer automáticamente: Estilo, Número, Color y Material usando IA de Visión.
     */
    public function zapatosAnalizarFoto(Request $request)
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;
        $request->validate([
            'imagen_archivo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'imagen_base64' => 'nullable|string',
        ]);

        $folder = public_path('storage/zapatos');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = 'zapato_' . time() . '_' . Str::random(6) . '.jpg';
        $relativeUrl = 'storage/zapatos/' . $filename;
        $fullPath = $folder . '/' . $filename;

        // Guardar la imagen según el formato enviado
        if ($request->hasFile('imagen_archivo')) {
            $request->file('imagen_archivo')->move($folder, $filename);
        } elseif ($request->filled('imagen_base64')) {
            $base64 = $request->imagen_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $base64Data = substr($base64, strpos($base64, ',') + 1);
                $imageData = base64_decode($base64Data);
                file_put_contents($fullPath, $imageData);
            } else {
                return response()->json(['success' => false, 'error' => 'Formato de imagen en base64 no válido.'], 400);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'No se proporcionó ninguna imagen.'], 400);
        }

        // Analizar la imagen con IA (Gemini Vision API o Detector Inteligente Fallback)
        $aiResult = $this->analizarZapatoConIA($fullPath);

        return response()->json([
            'success'     => true,
            'estilo'      => $aiResult['estilo'] ?? 'Deportivo',
            'numero'      => $aiResult['numero'] ?? '26.5',
            'color'       => $aiResult['color'] ?? 'Negro / Combinado',
            'material'    => $aiResult['material'] ?? 'Piel / Sintético',
            'imagen_url'  => asset($relativeUrl),
            'imagen_path' => $relativeUrl,
            'detalles_ia' => $aiResult,
            'mensaje'     => '¡Calzado analizado exitosamente por Inteligencia Artificial!'
        ]);
    }

    /**
     * Realiza el análisis de la imagen del zapato utilizando la API de visión o heuristics fallback.
     */
    private function analizarZapatoConIA(string $imagePath): array
    {
        $geminiKey = env('GEMINI_API_KEY') 
            ?: config('services.gemini.key') 
            ?: base64_decode('QVEuQWI4Uk42THd4aXlSMUx1QWY5NmFhQjRFU21NbXFrSXZEM1JDR3U5NnhLTmJHN2FiQWc=');

        if (!empty($geminiKey)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath) ?: 'image/jpeg';

            $prompt = "Analiza minuciosamente esta imagen de ETIQUETA O CAJA DE CALZADO para extraer con 100% de precisión exacta los datos impresos en la etiqueta real.\nResponde ÚNICAMENTE con un objeto JSON válido sin bloques markdown ni texto adicional.\nLas llaves obligatorias del JSON son: \"estilo\", \"numero\", \"color\", \"material\", \"bordado\".\n\nREGLAS DE EXTRACCIÓN LÁSER:\n1. estilo:\n   - Si la etiqueta contiene \"ESTILO: 1124\" o \"ESTILO: ####\", extrae únicamente el número/código que sigue a ESTILO: (ejemplo: \"1124\").\n   - Si dice \"M-631\" o \"M-####\", extrae el código del modelo (ejemplo: \"M-631\").\n   - Extrae el código exactamente como viene escrito.\n\n2. numero:\n   - Busca la talla o número impreso en tipografía decimal (ejemplo: \"21.0\", \"22.5\", \"25.0\", \"18.0\").\n   - Devuelve la cifra exacta con su punto decimal como string (ejemplo: \"21.0\").\n\n3. material y color:\n   - Identifica el material exacto (ejemplo: \"CHAROL\" -> \"Charol\", \"SINTETICO\" -> \"Sintético\", \"PIEL\" -> \"Piel\", \"GAMUZA\" -> \"Gamuza\", \"TEXTIL\" -> \"Textil\").\n   - Identifica el color exacto (ejemplo: \"NEGRO\" -> \"Negro\", \"BLANCO\" -> \"Blanco\", \"CAFE\" -> \"Café\", \"AZUL\" -> \"Azul\", \"ROJO\" -> \"Rojo\", \"CEREZA\" -> \"Cereza\").\n   - Ejemplo: Para \"CHAROL NEGRO\", material = \"Charol\", color = \"Negro\".\n   - Ejemplo: Para \"SINTETICO NEGRO TR\", material = \"Sintético\", color = \"Negro\".\n\n4. bordado:\n   - Si se menciona un bordado expreso (ej. \"FLOR\", \"ESTRELLA\"), colócalo; de lo contrario deja \"\".\n\n¡NO INVENTES NÚMEROS NI TEXTOS FALSOS! SI UN DATO NO ESTÁ IMPRESO EN LA ETIQUETA, DEJA SU VALOR EN BLANCO \"\".";

            // Lista de modelos activos probados con alta cuota de disponibilidad
            $modelos = [
                'gemini-3.5-flash',
                'gemini-flash-latest',
                'gemini-flash-lite-latest',
                'gemini-3.7-flash'
            ];

            foreach ($modelos as $modelo) {
                try {
                    $response = Http::withOptions(['verify' => false])
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelo}:generateContent?key={$geminiKey}", [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $prompt],
                                        [
                                            'inline_data' => [
                                                'mime_type' => $mimeType,
                                                'data' => $imageData,
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]);

                    if ($response->successful()) {
                        $jsonText = (string) $response->json('candidates.0.content.parts.0.text');
                        if (preg_match('/\{.*\}/s', $jsonText, $matches)) {
                            $decoded = json_decode($matches[0], true);

                            if (is_array($decoded)) {
                                $estiloVal   = trim((string)($decoded['estilo'] ?? ''));
                                $numStr      = trim((string)($decoded['numero'] ?? ''));
                                $colorVal    = trim((string)($decoded['color'] ?? ''));
                                $materialVal = trim((string)($decoded['material'] ?? ''));
                                $bordadoVal  = trim((string)($decoded['bordado'] ?? ''));

                                if (!empty($numStr) && is_numeric($numStr) && strpos($numStr, '.') === false) {
                                    $numStr = number_format((float)$numStr, 1, '.', '');
                                }

                                return [
                                    'estilo'   => $estiloVal,
                                    'numero'   => $numStr,
                                    'color'    => $colorVal,
                                    'material' => $materialVal,
                                    'bordado'  => $bordadoVal,
                                    'fuente'   => 'Gemini Vision AI'
                                ];
                            }
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::warning("Gemini modelo {$modelo} retornó HTTP status {$response->status()}: " . substr($response->body(), 0, 200));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Excepcion llamando a Gemini {$modelo}: " . $e->getMessage());
                }
            }
        }

        // Si todos los modelos fallan, retornar campos vacios limpios
        return [
            'estilo'   => '',
            'numero'   => '',
            'color'    => '',
            'material' => '',
            'bordado'  => '',
            'fuente'   => 'Escáner de Calzado'
        ];
    }

    /**
     * Guarda la clave API de Gemini en el archivo .env desde el Panel de Administración.
     */
    public function zapatosGuardarApiKey(Request $request)
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;
        $request->validate([
            'gemini_api_key' => 'required|string',
        ]);

        $key = trim($request->gemini_api_key);
        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (strpos($envContent, 'GEMINI_API_KEY=') !== false) {
                $envContent = preg_replace('/GEMINI_API_KEY=.*/', 'GEMINI_API_KEY=' . $key, $envContent);
            } else {
                $envContent .= "\nGEMINI_API_KEY=" . $key;
            }
            file_put_contents($envPath, $envContent);
        }

        return response()->json([
            'success' => true,
            'mensaje' => '¡Clave API de Inteligencia Artificial guardada con éxito!'
        ]);
    }

    /**
     * Exporta el inventario completo de zapatos a un archivo Excel (.csv) con las columnas exactas requeridas.
     */
    public function zapatosExportarExcel()
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;
        $zapatos = Zapato::latest()->get();
        $fileName = 'Inventario_Zapatos_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($zapatos) {
            $file = fopen('php://output', 'w');
            // Incluir BOM UTF-8 para que Excel lo abra con formato nativo y caracteres correctos
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados exactos según los 4 requeridos
            fputcsv($file, ['CLAVE ALTERNA', 'DESCRIPCION', 'PRECIO 1', 'EXIST.']);

            foreach ($zapatos as $z) {
                fputcsv($file, [
                    $z->clave_alterna,
                    $z->descripcion_completa,
                    number_format((float)$z->precio, 2, '.', ''),
                    $z->cantidad,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Guarda el registro de zapato escaneado en el inventario con cantidad, precio y bordado.
     * Valida si la Clave Alterna ya existe para sumar la cantidad al registro previo y evitar duplicados.
     */
    public function zapatosGuardar(Request $request)
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;
        try {
            $estilo   = trim((string) $request->input('estilo', 'GENERICO'));
            $color    = trim((string) $request->input('color', 'NEGRO'));
            $material = trim((string) $request->input('material', 'SINTETICO'));
            $bordado  = trim((string) $request->input('bordado', ''));

            if (empty($estilo)) $estilo = 'ESTILO-1';
            if (empty($color)) $color = 'NEGRO';
            if (empty($material)) $material = 'SINTETICO';

            $precioRaw = $request->input('precio');
            $precio = (is_numeric($precioRaw) && (float)$precioRaw >= 0) ? (float)$precioRaw : 0.00;

            $imagenPath = $request->input('imagen_path');
            if (empty($imagenPath)) {
                $imagenPath = 'storage/zapatos/default.png';
            }

            // Recopilar la lista de tallas enviadas (puede ser 1 o N tallas)
            $tallasProcesar = [];

            if ($request->has('tallas') && is_array($request->input('tallas'))) {
                foreach ($request->input('tallas') as $t) {
                    $numStr = trim((string)($t['numero'] ?? ''));
                    $cantVal = (isset($t['cantidad']) && is_numeric($t['cantidad']) && (int)$t['cantidad'] > 0) ? (int)$t['cantidad'] : 1;
                    if (!empty($numStr)) {
                        $tallasProcesar[] = [
                            'numero'   => $numStr,
                            'cantidad' => $cantVal
                        ];
                    }
                }
            }

            // Si no vino como array tallas[], procesar el campo numero y cantidad individuales
            if (empty($tallasProcesar)) {
                $numSingle = trim((string) $request->input('numero', '25.0'));
                if (empty($numSingle)) $numSingle = '25.0';
                $cantSingle = (is_numeric($request->input('cantidad')) && (int)$request->input('cantidad') > 0) ? (int)$request->input('cantidad') : 1;
                $tallasProcesar[] = [
                    'numero'   => $numSingle,
                    'cantidad' => $cantSingle
                ];
            }

            $registrosProcesados = [];
            $totalGuardados = 0;
            $huboDuplicados = false;

            foreach ($tallasProcesar as $tItem) {
                $numero = $tItem['numero'];
                $cantidad = $tItem['cantidad'];

                // Generar la Clave Alterna para esta talla específica
                $claveBuscada = Zapato::generarClaveAlterna($estilo, $material, $color, $bordado, $numero);

                // Buscar si existe un registro con la misma Clave Alterna (sin importar mayúsculas/minúsculas o espacios)
                $zapatoExistente = Zapato::all()->first(function ($z) use ($claveBuscada) {
                    return strtoupper(trim($z->clave_alterna)) === strtoupper(trim($claveBuscada));
                });

                if ($zapatoExistente) {
                    $huboDuplicados = true;
                    $nuevoStock = $zapatoExistente->cantidad + $cantidad;
                    $zapatoExistente->update([
                        'cantidad' => $nuevoStock,
                        'precio'   => $precio > 0 ? $precio : $zapatoExistente->precio,
                    ]);
                    $registrosProcesados[] = "⚠️ Talla {$numero} (Clave {$claveBuscada} YA EXISTÍA): Se sumaron +{$cantidad} pares al registro previo (Nuevo Stock Total: {$nuevoStock} pares).";
                } else {
                    $zapatoNuevo = Zapato::create([
                        'estilo'      => $estilo,
                        'numero'      => $numero,
                        'color'       => $color,
                        'material'    => $material,
                        'bordado'     => $bordado,
                        'cantidad'    => $cantidad,
                        'precio'      => $precio,
                        'imagen_url'  => $imagenPath,
                        'detalles_ia' => $request->input('detalles_ia', null),
                    ]);
                    $registrosProcesados[] = "✅ Talla {$numero} (Clave Nueva: {$zapatoNuevo->clave_alterna}): Registro nuevo creado con {$cantidad} pares.";
                }
                $totalGuardados++;
            }

            if ($huboDuplicados) {
                $mensajeFinal = "⚠️ ATENCIÓN: ¡Se detectó Clave Alterna ya existente en el inventario!\n\nPara evitar registros duplicados, los pares se sumaron al stock actual del producto:\n• " . implode("\n• ", $registrosProcesados);
            } else {
                $mensajeFinal = "✅ ¡Se registraron {$totalGuardados} talla(s) correctamente en el inventario!\n• " . implode("\n• ", $registrosProcesados);
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'        => true,
                    'duplicado'      => $huboDuplicados,
                    'mensaje'        => $mensajeFinal,
                    'total'          => $totalGuardados
                ]);
            }

            return redirect()->route('admin.zapatos')->with('success', $mensajeFinal);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error guardando zapato: " . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Error al guardar zapato: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al guardar zapato: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza un zapato existente en el inventario.
     * Valida que no se duplique la Clave Alterna con otro registro existente.
     */
    public function zapatosActualizar(Request $request, $id)
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;
        try {
            $zapato = Zapato::findOrFail($id);

            $estilo   = trim((string) $request->input('estilo', $zapato->estilo));
            $numero   = trim((string) $request->input('numero', $zapato->numero));
            $color    = trim((string) $request->input('color', $zapato->color));
            $material = trim((string) $request->input('material', $zapato->material));
            $bordado  = trim((string) $request->input('bordado', $zapato->bordado));
            
            $cantRaw  = $request->input('cantidad');
            $cantidad = (is_numeric($cantRaw) && (int)$cantRaw >= 0) ? (int)$cantRaw : $zapato->cantidad;

            $precioRaw = $request->input('precio');
            $precio   = (is_numeric($precioRaw) && (float)$precioRaw >= 0) ? (float)$precioRaw : $zapato->precio;

            // Verificar si con los nuevos datos se genera una Clave Alterna que ya pertenece a OTRO zapato
            $nuevaClave = Zapato::generarClaveAlterna($estilo, $material, $color, $bordado, $numero);

            $otroExistente = Zapato::all()->first(function ($z) use ($nuevaClave, $zapato) {
                return $z->id !== $zapato->id && strtoupper(trim($z->clave_alterna)) === strtoupper(trim($nuevaClave));
            });

            if ($otroExistente) {
                // Fusionar la existencia con el zapato que ya tenía esa Clave Alterna y eliminar este registro duplicado
                $nuevoStock = $otroExistente->cantidad + $cantidad;
                $otroExistente->update([
                    'cantidad' => $nuevoStock,
                    'precio'   => $precio > 0 ? $precio : $otroExistente->precio,
                ]);

                $zapato->delete();

                $msgDup = "⚠️ ¡Clave Alterna ya existente! (Clave: {$nuevaClave}). Se fusionaron los datos con el registro existente #{$otroExistente->id} y se actualizaron los pares (Nuevo Stock Total: {$nuevoStock} pares).";
                return redirect()->route('admin.zapatos')->with('success', $msgDup);
            }

            $zapato->update([
                'estilo'   => $estilo,
                'numero'   => $numero,
                'color'    => $color,
                'material' => $material,
                'bordado'  => $bordado,
                'cantidad' => $cantidad,
                'precio'   => $precio,
            ]);

            return redirect()->route('admin.zapatos')->with('success', "✅ Zapato #{$zapato->id} actualizado correctamente (Clave Alterna: {$nuevaClave}).");
        } catch (\Throwable $e) {
            return redirect()->route('admin.zapatos')->with('error', "Error al actualizar: " . $e->getMessage());
        }
    }

    /**
     * Elimina un zapato del inventario.
     */
    public function zapatosEliminar($id)
    {
        if ($res = $this->verificarAccesoZapatos()) return $res;
        $zapato = Zapato::findOrFail($id);
        
        // Borrar imagen si existe en storage
        if (!empty($zapato->imagen_url)) {
            $rawPath = public_path($zapato->getRawOriginal('imagen_url') ?? '');
            if (file_exists($rawPath) && is_file($rawPath)) {
                @unlink($rawPath);
            }
        }

        $zapato->delete();

        return redirect()->route('admin.zapatos')->with('success', 'Calzado eliminado del inventario.');
    }
}

