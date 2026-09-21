<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    /**
     * Genera el catálogo dinámico de productos en formato XML RSS 2.0 / Google Shopping / Meta Commerce Manager.
     */
    public function xml(Request $request): Response
    {
        $productos = Producto::activo()
            ->with(['detalles' => function ($query) {
                $query->where('activo', true);
            }])
            ->get();

        $content = view('feeds.productos-xml', compact('productos'))->render();

        return response($content, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
