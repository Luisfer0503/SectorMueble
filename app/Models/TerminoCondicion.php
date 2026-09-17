<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TerminoCondicion extends Model
{
    use HasFactory;

    protected $table = 'terminos_condiciones';

    protected $fillable = [
        'contenido',
    ];

    /**
     * Obtener el contenido activo de Términos y Condiciones como texto
     */
    public static function obtenerContenido(): string
    {
        $registro = self::find(1);
        if ($registro && !empty($registro->contenido)) {
            return $registro->contenido;
        }

        return "Términos y Condiciones de Sector Mueble.";
    }

    /**
     * Obtener lista estructurada de secciones de Términos y Condiciones.
     * Retorna array de secciones: [ ['titulo' => '...', 'contenido' => '...'], ... ]
     */
    public static function obtenerSecciones(): array
    {
        $registro = self::find(1);
        if ($registro && !empty($registro->contenido)) {
            $decoded = json_decode($registro->contenido, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }

        return self::seccionesPorDefecto();
    }

    /**
     * Secciones iniciales estructuradas por defecto para Sector Mueble
     */
    public static function seccionesPorDefecto(): array
    {
        return [
            [
                'titulo' => '1. Aceptación de Términos Legales',
                'contenido' => "Bienvenido al sitio web www.sectormueble.com, propiedad de CASA TAPICERÍA Y ATELIER S.A. de C.V. (en adelante \"SECTOR MUEBLE\"), con domicilio en Calle Lago de Chapala 105, Int. 4, Col. Manantiales, C.P. 72760, San Pedro Cholula, Puebla, con RFC CTA2209268QA.\n\nTe rogamos leas detalladamente los presentes Términos y Condiciones de Uso. Al ingresar y navegar en este sitio web, el Usuario acepta las condiciones contenidas en este contrato y declara su conformidad expresa mediante medios electrónicos. En caso de no estar de acuerdo con estos términos, solicitamos abstenerse de utilizar el sitio."
            ],
            [
                'titulo' => '2. Identificación del Proveedor y Datos de Contacto',
                'contenido' => "SECTOR MUEBLE pone a disposición de sus clientes y usuarios sus datos oficiales de identificación y contacto para cualquier consulta, sugerencia o seguimiento de pedidos:\n\n• Razón Social: CASA TAPICERÍA Y ATELIER S.A. de C.V.\n• RFC: CTA2209268QA\n• Domicilio Fiscal y Comercial: Calle Lago de Chapala 105, Int. 4, Col. Manantiales, C.P. 72760, San Pedro Cholula, Puebla.\n• Teléfono y Atención por WhatsApp: 222 670 2641\n• Correo Electrónico Oficial: hola@sectormueble.com.mx"
            ],
            [
                'titulo' => '3. Condiciones de Productos y Especificaciones de Muebles',
                'contenido' => "A través de www.sectormueble.com ofrecemos piezas de mobiliario y artículos de interiorismo de alta gama entre los que destacan mesas, salas, recámaras, comedores, sillas y accesorios decorativos.\n\n• Previo a procesar tu compra puedes consultar las especificaciones técnicas, materiales y acabados en el botón 'Ver Detalles' de cada producto.\n• Los precios, inventarios y promociones mostradas son exclusivos para compras por internet a través de nuestro sitio web oficial.\n• Es responsabilidad del cliente verificar las dimensiones de los accesos, puertas y elevadores de su domicilio antes de realizar la compra."
            ],
            [
                'titulo' => '4. Registro de Cuenta y Perfil del Cliente',
                'contenido' => "Para realizar compras en nuestro portal es posible registrarse creando una cuenta personal confidencial.\n\n• El cliente debe proporcionar datos verídicos y mantener actualizada su información de contacto y entrega.\n• El usuario es responsable de resguardar su contraseña de acceso confidencial. Todas las operaciones realizadas desde su cuenta serán atribuibles al titular.\n• SECTOR MUEBLE se reserva el derecho de cancelar o suspender cuentas que contravengan las políticas del sitio o registren actividades sospechosas."
            ],
            [
                'titulo' => '5. Envíos, Cobertura y Tiempos de Entrega',
                'contenido' => "Brindamos servicio de envío a domicilio con cobertura en la República Mexicana.\n\n• Envío Gratuito Premium: Aplica en compras desde $10,000 MXN en zona de cobertura directa (Puebla Capital, San Andrés Cholula y San Pedro Cholula).\n• Atención Personalizada en Zonas Extendidas: Si tu Código Postal requiere logística especial, nuestro equipo te asignará un agente de ventas para coordinar tu envío.\n• Horarios de Reparto: Las entregas se realizan de Lunes a Viernes de 8:00 am a 6:00 pm. No se incluye servicio de volado de muebles por fachada."
            ],
            [
                'titulo' => '6. Precios, Formas de Pago y Promociones',
                'contenido' => "Todos los precios publicados están expresados en Pesos Mexicanos (MXN) e incluyen el Impuesto al Valor Agregado (IVA).\n\n• Métodos de Pago Aceptados: Aceptamos Tarjetas de Crédito y Débito (Visa, MasterCard, American Express) procesadas de forma segura a través de Stripe, así como Transferencias Bancarias.\n• Meses Sin Intereses: Disponibles en tarjetas de crédito participantes al cumplir el monto mínimo de compra señalado en nuestras promociones vigentes.\n• Errores de Precio: En caso de errores manifiestos de sistema ($0.00), la empresa notificará al cliente la cancelación o actualización de la orden."
            ],
            [
                'titulo' => '7. Política de Ventas Finales y Garantía de 60 Días',
                'contenido' => "Todas las compras realizadas en nuestro sitio web son definitivas. No se realizan cancelaciones ni devoluciones por cambio de opinión.\n\n• Garantía de Fábrica (60 Días): Ofrecemos una garantía de 60 días naturales a partir de la entrega del producto para cubrir exclusivamente defectos comprobables de fabricación en estructura, materiales o tapicería.\n• Proceso de Garantía: Se requiere análisis técnico y evidencia en foto/video notificando al 222 670 2641 o a hola@sectormueble.com.mx.\n• Exclusiones: La garantía no aplica en daños por mal uso, golpes, negligencia, humedad, accidentes o intervención de personal no autorizado."
            ],
            [
                'titulo' => '8. Seguridad del Sitio, Notificaciones y Derechos de Autor',
                'contenido' => "En SECTOR MUEBLE protegemos tu información mediante protocolos de encriptación y privacidad SSL.\n\n• Al realizar compras autorizas recibir notificaciones sobre el estatus de tu pedido por correo electrónico y WhatsApp.\n• Todos los logotipos, marcas, fotografías, modelos 3D y contenidos publicados en www.sectormueble.com son propiedad exclusiva de CASA TAPICERÍA Y ATELIER S.A. de C.V. Queda prohibida su reproducción parcial o total sin autorización previa por escrito."
            ],
        ];
    }
}
