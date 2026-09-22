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
        Schema::create('avisos_privacidad', function (Blueprint $table) {
            $table->id();
            $table->longText('contenido');
            $table->timestamps();
        });

        $seccionesIniciales = [
            [
                'titulo' => '1. Identidad y Domicilio del Responsable',
                'contenido' => "CASA TAPICERÍA Y ATELIER S.A. de C.V. (en adelante \"SECTOR MUEBLE\"), con domicilio en Calle Lago de Chapala 105, Int. 4, Col. Manantiales, C.P. 72760, San Pedro Cholula, Puebla, con RFC CTA2209268QA, es el responsable del tratamiento y protección de sus datos personales, en estricto cumplimiento con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP) y su Reglamento."
            ],
            [
                'titulo' => '2. Datos Personales Recabados',
                'contenido' => "Para llevar a cabo las finalidades descritas en el presente Aviso de Privacidad, recabaremos los siguientes datos personales:\n\n• Datos de Identificación: Nombre completo, RFC o Identificación oficial (para efectos de facturación o entrega).\n• Datos de Contacto: Correo electrónico, teléfono fijo o celular/WhatsApp, domicilio de entrega y domicilio fiscal.\n• Datos Financieros y de Pago: La información de tus tarjetas bancarias y medios de pago se procesa de forma directa y encriptada a través de pasarelas de pago seguras (tales como Stripe o PayPal), por lo que SECTOR MUEBLE no almacena números de tarjeta ni códigos de seguridad."
            ],
            [
                'titulo' => '3. Finalidades del Tratamiento de los Datos',
                'contenido' => "Sus datos personales serán utilizados para las siguientes finalidades primarias y secundarias:\n\nFinalidades Primarias (necesarias para el servicio):\n• Procesar, gestionar y entregar las órdenes de compra de muebles y accesorios realizadas en nuestro sitio web.\n• Emitir las comprobantes fiscales (facturas CFDI) correspondientes a sus compras.\n• Proporcionar atención al cliente, seguimiento de envíos, aclaraciones y soporte técnico vía telefónica, correo o WhatsApp.\n• Hacer efectiva la garantía de 60 días en piezas de mobiliario cuando aplique.\n\nFinalidades Secundarias (opcionales):\n• Enviar promociones exclusivas, ofertas de temporada, catálogo de productos y noticias sobre SECTOR MUEBLE a través de correo electrónico o mensajes promocionales."
            ],
            [
                'titulo' => '4. Mecanismos para Limitar el Uso o Divulgación y Derechos ARCO',
                'contenido' => "Usted tiene derecho a conocer qué datos personales tenemos de usted, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de su información personal en caso de que esté desactualizada, sea inexacta o incompleta (Rectificación); que la eliminemos de nuestros registros o bases de datos cuando considere que la misma no está siendo utilizada adecuadamente (Cancelación); así como oponerse al uso de sus datos personales para fines específicos (Oposición). Estos son conocidos como Derechos ARCO.\n\nPara el ejercicio de cualquiera de los derechos ARCO o para revocar su consentimiento al envío de publicidad, puede enviar una solicitud por escrito al correo electrónico oficial: hola@sectormueble.com.mx o comunicarse al teléfono/WhatsApp: 222 670 2641."
            ],
            [
                'titulo' => '5. Transferencia de Datos Personales',
                'contenido' => "SECTOR MUEBLE no compartirá, venderá ni transferirá sus datos personales a terceros sin su consentimiento previo, salvo las excepciones previstas en el artículo 37 de la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, tales como:\n\n• Empresas de mensajería y paquetería contratadas para realizar la entrega de los muebles en su domicilio.\n• Proveedores de procesamientos de pago para validar la transacción financiera.\n• Autoridades competentes cuando así lo exija la legislación aplicable."
            ],
            [
                'titulo' => '6. Uso de Cookies y Tecnologías de Rastreo',
                'contenido' => "Le informamos que en nuestro sitio web www.sectormueble.com utilizamos cookies, web beacons y otras tecnologías a través de las cuales es posible monitorear su comportamiento como usuario de internet, brindarle un mejor servicio y experiencia de navegación en nuestra plataforma.\n\nLos datos que obtenemos de estas tecnologías incluyen su dirección IP, tipo de navegador, sistema operativo y páginas visitadas dentro de nuestro portal. Puede deshabilitar estas cookies en las opciones de configuración de su navegador web."
            ],
            [
                'titulo' => '7. Modificaciones al Aviso de Privacidad',
                'contenido' => "El presente Aviso de Privacidad puede sufrir modificaciones, cambios o actualizaciones derivadas de nuevos requerimientos legales, de nuestras propias necesidades por los productos o servicios que ofrecemos o de nuestras prácticas de privacidad.\n\nNos comprometemos a mantenerlo informado sobre los cambios que pueda sufrir el presente Aviso de Privacidad a través de nuestra página web en la sección correspondiente."
            ]
        ];

        DB::table('avisos_privacidad')->insert([
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
        Schema::dropIfExists('avisos_privacidad');
    }
};
