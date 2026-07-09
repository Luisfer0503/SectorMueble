<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Sofá nórdico de 3 plazas',
                'descripcion' => 'Sofá tapizado en tela resistente de alta calidad con patas de madera maciza de roble. Diseño minimalista y ergonómico ideal para dar un toque escandinavo a tu salón.',
                'precio' => 599.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800',
                'categoria' => 'Salón',
                'stock' => 10,
                'calificacion' => 4.8,
                'destacado' => true,
            ],
            [
                'nombre' => 'Mesa de centro de madera industrial',
                'descripcion' => 'Mesa de centro con tablero de madera de pino recuperada y patas de metal en estilo industrial. Cuenta con una balda inferior para almacenamiento adicional de revistas o mandos.',
                'precio' => 149.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?q=80&w=800',
                'categoria' => 'Salón',
                'stock' => 15,
                'calificacion' => 4.5,
                'destacado' => false,
            ],
            [
                'nombre' => 'Cama de matrimonio de madera fresno',
                'descripcion' => 'Estructura de cama para colchón de 150x200cm fabricada íntegramente en madera de fresno barnizada en tono natural. Cabecero integrado con listones verticales de diseño minimalista.',
                'precio' => 449.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=800',
                'categoria' => 'Dormitorio',
                'stock' => 8,
                'calificacion' => 4.9,
                'destacado' => true,
            ],
            [
                'nombre' => 'Armario empotrado blanco mate',
                'descripcion' => 'Armario de dos puertas correderas en color blanco mate. Interior totalmente equipado con barra de aluminio para colgar ropa, cajonera de tres cajones y múltiples estantes espaciosos.',
                'precio' => 799.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?q=80&w=800',
                'categoria' => 'Dormitorio',
                'stock' => 5,
                'calificacion' => 4.7,
                'destacado' => false,
            ],
            [
                'nombre' => 'Mesa de comedor redonda extensible',
                'descripcion' => 'Mesa de comedor redonda extensible de madera de haya. Pasa de un diámetro compacto de 110cm a 150cm con total facilidad gracias a su mecanismo interno, ideal para recibir visitas de amigos y familia.',
                'precio' => 389.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?q=80&w=800',
                'categoria' => 'Comedor',
                'stock' => 12,
                'calificacion' => 4.6,
                'destacado' => true,
            ],
            [
                'nombre' => 'Silla de comedor de terciopelo',
                'descripcion' => 'Silla ergonómica tapizada en terciopelo suave y elegante con patas de acero pintadas en negro mate. Aporta gran comodidad y resistencia, encajando en decoraciones tanto clásicas como modernas.',
                'precio' => 79.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?q=80&w=800',
                'categoria' => 'Comedor',
                'stock' => 40,
                'calificacion' => 4.4,
                'destacado' => false,
            ],
            [
                'nombre' => 'Escritorio flotante de pared',
                'descripcion' => 'Escritorio compacto diseñado para colgar en pared. Incluye práctico pasacables superior y una bandeja oculta inferior para guardar el teclado o el portátil. Ideal para estancias pequeñas y teletrabajo.',
                'precio' => 189.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=800',
                'categoria' => 'Oficina',
                'stock' => 14,
                'calificacion' => 4.5,
                'destacado' => false,
            ],
            [
                'nombre' => 'Silla de oficina ergonómica 3D',
                'descripcion' => 'Silla de oficina profesional con soporte lumbar ajustable, reposacabezas de altura graduable y reposabrazos 3D regulables. Tapizada en malla transpirable de alta densidad para largas jornadas laborales.',
                'precio' => 229.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1580481072645-022f9a6dbf27?q=80&w=800',
                'categoria' => 'Oficina',
                'stock' => 20,
                'calificacion' => 4.8,
                'destacado' => true,
            ],
            [
                'nombre' => 'Sillón colgante de ratán',
                'descripcion' => 'Sillón colgante con soporte de acero reforzado incluido. Cesta de ratán sintético de alta resistencia a los rayos UV, equipada con cojines súper mullidos de poliéster impermeable.',
                'precio' => 269.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800',
                'categoria' => 'Exterior',
                'stock' => 7,
                'calificacion' => 4.7,
                'destacado' => true,
            ],
            [
                'nombre' => 'Mesa de jardín de aluminio gris',
                'descripcion' => 'Mesa para terraza o jardín fabricada en aluminio anodizado con tablero de listones de madera sintética gris. Totalmente resistente a la intemperie y muy ligera para mover de sitio.',
                'precio' => 319.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1604014237800-1c9102c219da?q=80&w=800',
                'categoria' => 'Exterior',
                'stock' => 10,
                'calificacion' => 4.3,
                'destacado' => false,
            ],
            [
                'nombre' => 'Lámpara de pie de arco minimalista',
                'descripcion' => 'Lámpara de pie con pantalla ajustable de metal en negro mate e interior dorado reflectante. Perfecta para colocar junto al sofá y crear un ambiente cálido de lectura.',
                'precio' => 89.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?q=80&w=800',
                'categoria' => 'Salón',
                'stock' => 25,
                'calificacion' => 4.6,
                'destacado' => false,
            ],
            [
                'nombre' => 'Cómoda de 4 cajones de roble',
                'descripcion' => 'Cómoda de madera de roble con tiradores integrados de metal negro. Cuenta con cuatro amplios cajones con guías metálicas reforzadas de deslizamiento silencioso.',
                'precio' => 259.99,
                'imagen_url' => 'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c?q=80&w=800',
                'categoria' => 'Dormitorio',
                'stock' => 11,
                'calificacion' => 4.7,
                'destacado' => false,
            ],
        ];

        foreach ($productos as $item) {
            $item['slug'] = Str::slug($item['nombre']);
            Producto::create($item);
        }
    }
}
