<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('politicas_envio', function (Blueprint $table) {
            $table->id();
            $table->longText('contenido');
            $table->timestamps();
        });

        $seccionesIniciales = [
            [
                'titulo' => '1. Cobertura de Entrega y Envíos Nacionales',
                'contenido' => "En SECTOR MUEBLE realizamos envíos de muebles y decoración a toda la República Mexicana.\n\n• Cobertura Local Directa: Contamos con logística especializada en Puebla Capital, San Andrés Cholula y San Pedro Cholula.\n• Cobertura Nacional Extendida: Para compras fuera de la zona metropolitana de Puebla, coordinamos la entrega a través de transportistas logísticos aliados especializados en carga pesada y delicada de mobiliario.\n• Entregas Internacionales: Actualmente solo realizamos envíos dentro del territorio nacional mexicano."
            ],
            [
                'titulo' => '2. Costos de Envío y Promoción de Envío Gratuito',
                'contenido' => "Los costos de flete se calculan en función de la ubicación de destino y el volumen total de la compra:\n\n• Envío Gratuito Premium: Aplica en compras superiores a $10,000 MXN en zonas con cobertura directa verificada mediante el Código Postal.\n• Cotización en Zonas Extendidas: En áreas sin cobertura directa automática, al momento de realizar la compra o cotizar se te asignará la tarifa preferencial de flete o te atenderá un agente de ventas para ajustar la logística."
            ],
            [
                'titulo' => '3. Tiempos Estimados y Horarios de Entrega',
                'contenido' => "Nuestros tiempos de entrega varían según el tipo de mueble (si se encuentra en inventario disponible o si es de fabricación a la medida):\n\n• Muebles en Stock Disponible: El tiempo estimado de entrega oscila entre 3 y 8 días hábiles.\n• Muebles Sobre Pedido / Personalizados: El tiempo de fabricación y despacho se informará durante el proceso de compra (generalmente de 2 a 4 semanas).\n• Horarios de Reparto: Las entregas se efectúan de Lunes a Viernes en un horario abierto de 8:00 am a 6:00 pm. Se enviará una notificación previa por WhatsApp o correo antes de la visita de la unidad de transporte."
            ],
            [
                'titulo' => '4. Condiciones de Recepción y Maniobras de Entrega',
                'contenido' => "Es fundamental tomar en cuenta las condiciones del lugar de entrega para garantizar la recepción adecuada de sus muebles:\n\n• Medición de Accesos: El cliente es responsable de verificar minuciosamente que las dimensiones de las piezas permitan el paso por puertas, pasillos, elevadores y escaleras de su inmueble.\n• Maniobras al Interior: Las cuadrillas de entrega dejarán el mueble en planta baja o primer piso donde exista libre acceso.\n• Sin Servicio de Volado de Muebles: SECTOR MUEBLE no realiza volado de muebles por fachadas, ventanas o balcones. En caso de requerirse volado exterior, deberá contratarse de forma independiente por cuenta y riesgo del cliente."
            ],
            [
                'titulo' => '5. Inspección al Recibir el Pedido y Reporte de Daños',
                'contenido' => "Al momento de recibir su mercancía, solicitamos realizar la revisión del estado físico del producto:\n\n• Firmar de Conformidad: Revise que los empaques no presenten abolladuras ni roturas antes de firmar el remito o guía de entrega.\n• Reporte Inmediato de Daños de Transporte: En el eventual caso de detectar algún daño estético o golpe derivado del traslado, es indispensable anotarlo en la guía del chofer y tomar fotografías/videos al instante.\n• Plazo de Notificación: Deberá notificar a nuestro Centro de Atención (222 670 2641 o hola@sectormueble.com.mx) dentro de un plazo máximo de 24 horas naturales tras la recepción para procesar el seguro de transporte."
            ]
        ];

        DB::table('politicas_envio')->insert([
            'id' => 1,
            'contenido' => json_encode($seccionesIniciales, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('politicas_envio');
    }
};
