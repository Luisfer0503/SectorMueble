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
        Schema::create('terminos_condiciones', function (Blueprint $table) {
            $table->id();
            $table->longText('contenido');
            $table->timestamps();
        });

        // Contenido inicial por defecto proporcionado por la empresa
        $textoInicial = <<<'TEXT'
Términos y Condiciones

¡Bienvenido a SECTOR MUEBLE, nos encanta que visites nuestra página web!

Te queremos compartir que los presentes términos y condiciones fueron elaborados de conformidad a lo establecido por los artículos 76 bis y 76 bis 1 de la Ley Federal de Protección al Cliente y observando lo recomendado por la Norma Mexicana NMX-COE-001-SCFI-2018 publicada en el Diario Oficial de la Federación el día 30 de abril del año 2019, mismos que establecen las disposiciones a las que se sujetarán todas aquellas personas definidas por dicha norma mexicana como Usuarios o Clientes al ingresar al sitio www.sectormueble.com buscando en todo momento, garantizar, transparentar, informar y orientar adecuadamente sobre la navegación y realización de cualquier tipo de transacción a través del sitio web. Por lo anterior, y en concordancia a lo antes señalado, le informamos: Aceptación de Términos Legales: Bienvenido al sitio web www.sectormueble.com, el cual es propiedad de CASA TAPICERÍA Y ATELIER S.A. de C.V. (en adelante “SECTOR MUEBLE”), con domicilio en Calle Lago de Chapala, 105, 4, Colonia Manantiales, C.P.72760, San Pedro Cholula con RFC CTA2209268QA. Te rogamos leas detalladamente los presentes términos y condiciones y consultar nuestro AVISO DE PRIVACIDAD en la sección PROTECCIÓN DE DATOS PERSONALES, para conocer el tratamiento que le daremos a sus datos y los fines para los cuales serán utilizados, antes de utilizar el sitio web.

Te informamos que al ingresar y utilizar este Sitio web, identificado con el nombre de dominio www.sectormueble.com, propiedad de SECTOR MUEBLE el Usuario está aceptando los Términos y Condiciones de uso contenidos en este contrato y declara expresamente su aceptación utilizando para tal efecto medios electrónicos, en términos de lo dispuesto por el artículo 1803 y demás relativos del Código Civil Federal, en caso contrario, te pedimos abstenerte de utilizarlo y realizar alguna transacción en virtud de que la aceptación de los mismos es de carácter obligatorio.

Identificación del Proveedor
SECTOR MUEBLE te ofrece una gran variedad de productos y servicios a través del presente portal por lo cual te proporcionamos nuestros datos de identificación para cualquier requerimiento, comentario o pregunta sobre éstos términos: CASA TAPICERÍA Y ATELIER S.A. de C.V., RFC CTA2209268QA. DOMICILIO: domicilio en Calle Lago de Chapala, 105, 4, Colonia Manantiales, C.P.72760, San Pedro Cholula

Condiciones, características y especificaciones de los bienes, productos o servicios

Condiciones
A través de www.sectormueble.com ofrecemos diversos productos entre los cuales podrás encontrar artículos y secciones tales como: mesas, sillas, sillones, recámaras y accesorios de interiorismo en general.
Te recomendamos leer los presentes términos y condiciones para conocer las reglas y restricciones que rigen el uso de nuestro sitio web, productos y aplicaciones, así como el proceso de compra mediante el cual te proporcionaremos información de tu producto sobre las carácteristicas, promociones vigentes, disponibilidad, fechas estimadas de entrega, costos de envio, opiniones del artículo y previo a concluir tu proceso de compra te indicaremos el monto total a pagar. Te informamos que tus datos están completamente a salvo debido a la confidencialidad de nuestro sitio web, te recomendamos registrar una cuenta en en nuestro sitio para hacer más sencillo tu proceso de compra y para compras futuras que desees realizar, para más información revisa la sección “CUENTA Y REGISTRO” en los presentes términos.

Características y especificaciones de los bienes, productos y servicios
Previo a procesar la compra podrás conocer las características del o los productos que deseas adquirir en el apartado ver detalles, dicha información es proporcionada para fines informativos; sin embargo, es importante que previo a utilizar el o los productos, leas las etiquetas, advertencias e instrucciones de uso, para un adecuado funcionamiento y conservación.
En caso de tener alguna duda sobre las características te invitamos a contactar a alguno de nuestros asesores a través de nuestro Centro de Atención Telefónica o vía whatsapp desde nuestro sitio.
Te informamos que los precios, promociones, disponibilidad e inventario de los productos que aparecen en www.sectormueble.com.mx, son exclusivos para ventas por internet, cualquier duda ponemos a tu disposición el Centro de Atención vía whatsapp o correo electrónico, para más información “CONTACTO Y NOTIFICACIONES”.

Pagos y registros
Una vez que se te indique el monto total a pagar en caso de no estar registrado deberás registrar tu datos personales así como los datos del medio de pago de tu elección.
Si ya te encuentras registrado dentro del portal deberás seleccionar el método de pago con el cual realizarás la compra. Los cuales podrán ser dirección, tarjeta de débito y/o crédito, transferencia bancaria o Paypal mismos que te serán informados durante el proceso de compra.

Cuenta y registro
Perfil del Cliente
Para realizar tus compras a través de nuestro portal es posible que se te solicite realizar un registro y crear una cuenta proporcionando cierta información personal, la cual será resguardada de manera confidencial, te rogamos revisar nuestro Aviso de Privacidad. En dicha cuenta podrás asociar tu método de pago preferido para realizar tus compras, te pedimos que antes de guardar tu información personal verifiques que ésta sea correcta, es responsabilidad del Cliente proporcionar datos claros y verídicos, así como de mantenerlos actualizados. Durante el registro deberás generar una contraseña de acceso conformada por 8 carácteres alfanuméricos, te pedimos resguardar la contraseña y datos de tu cuenta de manera confidencial, ya que con ello podrás acceder desde cualquier dispositivo electrónico, por lo anterior, el titular eres responsable de todas las actividades que ocurran bajo dicha cuenta y contraseña. Te sugerimos restringir el acceso a tu dispositivo electrónico con los mecanismos de seguridad con los que cuenta el mismo. Cualquier uso no autorizado de tu contraseña o cuenta debe ser notificado a SECTOR MUEBLE ya que de ninguna manera nosotros seremos responsables directa o indirectamente por cualquier pérdida o daño de cualquier tipo incurrido que resulte de la falta de cumplimiento a la presente condición.
El Cliente solo podrá contar con una Cuenta del Usuario. En caso de que SECTOR MUEBLE detecte distintas Cuentas que contengan datos coincidentes o relacionados, podrá cancelarlas, suspenderlas o inhabilitarlas, a su sola discreción y en cualquier momento. El Usuario será responsable por todas las operaciones efectuadas desde su Cuenta del Usuario. Está prohibida la venta, cesión, transferencia o transmisión de la Cuenta del Usuario bajo cualquier título, ya sea oneroso o gratuito.
SECTOR MUEBLE se reserva el derecho rechazar el registro de una Cuenta del Usuario en caso de considerar que el Usuario no ha dado cumplimiento a los presentes Términos y Condiciones, sin que la Empresa esté obligada a comunicar o exponer las razones de su decisión y sin que se genere derecho a indemnización o resarcimiento alguno a favor del Usuario.
El Usuario será responsable por todas las operaciones efectuadas desde su Cuenta del Usuario.
SECTOR MUEBLE concede una licencia no transferible y revocable para utilizar el sitio web, en virtud de los Términos y Condiciones de uso descritos, con el propósito de la compra de artículos personales vendidos en la misma Página. El Usuario sólo podrá imprimir y/o copiar cualquier información y/o imagen contenida o publicada en el sitio web www.sectormueble.com.mx exclusivamente para uso personal, por lo que queda expresa y terminantemente prohibido el uso comercial de dicha información. En caso de ser persona moral se sujetará a lo dispuesto por el artículo 148, fracción IV de la Ley Federal del Derecho de Autor. La reimpresión, publicación, distribución, asignación, sublicencia, venta, reproducción electrónica o por otro medio, parcial o total, de cualquier información, imagen, documento o gráfico que aparezca en el sitio web www.sectormueble.com.mx, para cualquier uso distinto al personal no comercial le está expresamente prohibido al Usuario, a menos de que cuente con la autorización previa y por escrito de SECTOR MUEBLE. Cualquier infracción de estos Términos y Condiciones de Uso dará lugar a la revocación inmediata.
Ciertos servicios y las características relacionadas que pueden estar disponibles en www.sectormueble.com.mx pueden requerir el registro o suscripción. El Usuario reconoce que, al proporcionar la información de carácter personal, otorga a SECTOR MUEBLE la autorización señalada en el artículo 109 de la Ley Federal del Derecho de Autor. Si el Usuario decide registrarse o suscribirse a cualquiera de estos servicios o funciones relacionadas, el mismo se compromete a proporcionar información precisa y actualizada acerca de si mismo, y a actualizar rápidamente esa información si hay algún cambio.
Durante el proceso de registro, el Usuario acepta recibir correos electrónicos promocionales de www.sectormueble.com.mx. No obstante, posteriormente, puede optar por no recibir tales correos promocionales haciendo clic en el enlace en la parte inferior de cualquier correo electrónico promocional.
SECTOR MUEBLE se reserva el derecho de bloquear el acceso o remover en forma parcial o total toda información, comunicación o material que a su exclusivo juicio pueda resultar: i) abusivo, difamatorio u obsceno; ii) fraudulento, artificioso o engañoso; iii) violatorio de derechos de autor, marcas, confidencialidad, secretos industriales o cualquier derecho de propiedad intelectual de un tercero; iv) ofensivo o; v) que de cualquier forma contravenga lo establecido en este contrato.
El Cliente asume la responsabilidad de todos los costes, tasas, impuestos y demandas que se derivarán del uso de este sitio web. Los datos de acceso comunicados al Cliente para su perfil han sido concebidos exclusivamente para uso personal, y deberán tratarse con confidencialidad. El Usuario deberá modificar sus contraseñas con regularidad. Todas las transacciones realizadas mediante la cuenta de perfil serán imputadas al titular de la cuenta de perfil pertinente, y tendrán carácter vinculante.
El Usuario se responsabiliza sin limitaciones de los daños directos e indirectos, así como los daños consecuentes, que pudiera ocasionar por negligencia grave o intención ilegal.
SECTOR MUEBLE comercializa diversos productos para menores de edad; sin embargo, la(s) compra(s) y transacciones se deberán realizar por personas mayores de edad, o en su caso, estar accediendo bajo supervisión y consentimiento de tus padres o tutores. Se deberá contar con alguno de los medios de pago asociados a su cuenta, en caso de estar registrado; de lo contrario, se solicitará un método de pago válido. Para más información te sugerimos revisar dentro de nuestro sitio la sección denominada PAGOS Y REGISTROS.

Entregas

Envío a Domicilio
Actualmente nuestro servicio a domicilio es totalmente gratuito en Puebla, San Andrés Cholula y San Pedro Cholula (sujeto a verificación de cobertura de C.P.).
Debido a la distancia de algunas direcciones de entrega, antes de realizar el pago del producto solicitaremos tu autorización para recibir atención personalizada a través de un agente de ventas, que podrá darte la información necesaria para tu compra y envío.
Para poder realizar la compra de los productos el Cliente deberá realizar el pago de los productos seleccionados, impuestos y gastos de envío correspondientes (costo de flete) a través de los proveedores de servicios de pagos que SECTOR MUEBLE ponga a disposición del Usuario en el Sitio web.
El Usuario solamente podrá comprar productos en SECTOR MUEBLE con entrega en un domicilio que esté dentro de las áreas de cobertura de entrega SECTOR MUEBLE vigentes al momento de la compra. SECTOR MUEBLE podrá tener cobertura de entrega distinta para cada uno de sus productos.
SECTOR MUEBLE no ofrece servicio de volado para los muebles, por lo tanto, recomendamos medir los espacios del ambiente y del elevador, y prestar atención a las medidas de los productos antes de efectuar la compra. Las Empresas Transportadoras, mediante evaluación de riesgo hecha en el momento de la entrega, podrá no ejecutar la entrega, salvo que el cliente indique lo contrario quedando bajo su propia responsabilidad el daño al producto o a la estructura (escaleras y elevadores, por ejemplo).
Regularmente nuestros servicios logísticos realizan el envío de tus compras, en ocasiones, nos apoyamos de algunos partners para entregarte en un horario de 8:00 am a 6:00 pm de lunes a viernes.
Para SECTOR MUEBLE, es importante proporcionar un servicio eficiente para nuestros Clientes, por lo cual al realizar tu compra es indispensable que se proporcione la dirección completa del destino y facturación (en caso de requerirse), lo anterior con la finalidad de evitar cualquier retraso en la entrega de tus compras, ya que para nosotros no es posible entregar los productos con direcciones incompletas o únicamente Códigos Postales.

Fecha de Entrega
Al momento de la compra se estima una fecha de entrega del o los productos(s) adquirido(s), la cual se determina en función al tipo y naturaleza de estos, por lo que durante el proceso de compra y antes de concluir la misma se te informará detalladamente.
La fecha de entrega la podrás verificar en el resumen de tu transacción o a través de la información que te proporcione el agente de ventas, si tienes alguna duda te invitamos a revisar el Módulo de Ayuda/Entrega/Mis Pedidos o bien contactar a alguno de nuestros asesores a través del Centro de Atención Telefónica o vía whatsapp donde amablemente te atenderemos.

Cobertura
Actualmente puedes hacer todas tus compras en línea desde nuestro sitio web en cualquier parte del mundo: sin embargo, la entrega de la mercancía únicamente se encuentra disponible dentro de la República Mexicana, por lo que no podemos enviar productos a direcciones internacionales. Los artículos son empaquetados para su cuidado y protección, por lo que no contamos con envolturas especiales.

Seguimiento a Mis Pedidos / compras
¿Cómo puedo darle seguimiento a Mis Compras?
Compra en www.sectormueble.com
Una vez que realizaste tu compra, te llegará un correo de “Confirmación de Compra” a la cuenta de correo que tengas vinculada, en este podrás encontrar el link Seguimiento a entrega que te llevará directamente al módulo de “Mis Pedidos”, en donde podrás visualizar todas las compras que hayas realizado en www.sectormueble.com. En este módulo podrás visualizar el historial de los pedidos de los últimos 6 meses.
Así mismo encontrarás información como:
- Número de pedido
- Fecha de compra
- Dirección de envío
- Fecha estimada de entrega o Fecha de entrega
- Detalle de los artículos
- Talla
- Color
- Material
- Cantidad de artículos comprados
Si deseas conocer el estatus de un artículo, deberás dar click en Seguimiento a entrega. En caso de que compres más de un artículo, es necesario realizar este proceso para cada uno.
Así mismo, podrás observar el artículo seleccionado con la siguiente información:
- Fecha de compra
- Dirección de envío
- Fecha estimada de entrega o Fecha de entrega
- Estados de envío:
  * Pedido confirmado: Validamos tu compra y nuestro inventario
  * Preparando tu pedido: Estamos preparando el artículo para ser enviado
  * Pedido en camino: Tu artículo está en ruta y puede ser entregado en los próximos días
  * Pedido entregado: El artículo fue entregado por nosotros o alguno de nuestros partners
- Partner que te entregará tu artículo y número de guía en caso de que aplique.
En ocasiones, nos apoyamos en algunos partners para poderte entregar antes, por lo que verás un link con la guía de nuestro partner en donde podrás darle seguimiento a tu paquete a través de su portal.

Precio, métodos de pago y facturación

Precio
Todos los precios publicados en nuestro sitio se encuentran en moneda nacional (pesos mexicanos) y de acuerdo al Sistema General de Unidades de Medida vigente, por tal motivo te recomendamos revisar la configuración del idioma de tu navegador.
Todos los precios incluyen impuestos correspondientes, en caso contrario dicha situación se te informará antes de concretar la compra. Para poder realizar la compra de los productos el Cliente deberá realizar el pago de los productos seleccionados, impuestos y gastos de envío correspondientes (costo de flete) a través de los proveedores de servicios de pagos que SECTOR MUEBLE ponga a disposición del Usuario en el sitio web.
Todos los precios de los productos que se indican a través del sitio web de SECTOR MUEBLE incluyen el IVA y los demás impuestos que pudiera corresponder a éstos. No obstante, estos precios no incluyen los gastos correspondientes al envío de los productos (costo del flete), los cuales se detallarán aparte en cada pedido y deberán ser aceptados y pagados por el Usuario como parte del monto total del pedido.
Precios, promociones y disponibilidad, sujetos a cambios sin previo aviso.
El Usuario debe de considerar que se dan casos en los cuales una orden no puede ser procesada por diversos motivos. En ese sentido, SECTOR MUEBLE se reserva el derecho a denegar o cancelar cualquier pedido por cualquier razón, en cualquier momento. Además, SECTOR MUEBLE se reserva el derecho a solicitar al Usuario información adicional, antes de aceptar el pedido o inclusive una vez aceptado en primera instancia.
SECTOR MUEBLE proporcionará la información de precios más precisa para los Usuarios, sin embargo, aún pueden producirse ciertos errores, como los casos en que el precio de un artículo no se muestra correctamente en la página web o no corresponda a la promoción vigente. Como tal, SECTOR MUEBLE se reserva el derecho a denegar o cancelar cualquier orden que contenga un artículo que haya sido comprado con un precio incorrecto. En el caso de que el precio de un artículo sea incorrecto, es posible que a discreción SECTOR MUEBLE se ponga en contacto con el Usuario para solicitar instrucciones o cancelar el pedido y le notificará de tal cancelación, o en caso de que la compra se haya efectuado, de cancelar tales pedidos, ya sea o no que el pedido haya sido confirmado y pago.
Si por alguna razón, el precio se encuentra en $0.00 o $0.01, favor de entrar en contacto con la central de servicio al cliente de SECTOR MUEBLE. Por ninguna razón, se entenderá que éstos no tengan precio o se regalen y los pedidos que se realicen bajo esta situación, serán cancelados sin previo aviso. En caso de haber discrepancias entre los precios mostrados en el sitio web de SECTOR MUEBLE del mismo producto, se deberá verificar que en efecto se trate del mismo producto (mismas dimensiones, color, materiales y SKU), y en su caso, SECTOR MUEBLE se reserva el derecho de actualizar en el momento el precio del producto conforme al precio de venta al público autorizado previamente por SECTOR MUEBLE.

Pago
El pago del pedido hecho en el sitio web de SECTOR MUEBLE podrá realizarse por medio de cualquier de los medios de pago ofrecidos por el sitio, siendo estos, entre otros: tarjeta de crédito, tarjeta de débito, transferencia bancaria o Paypal. La lista de los medios de pago ofrecidos puede estar sujeta a modificaciones en cualquier momento sin previo aviso para los Usuarios. El número de orden (pedido) que se asigna al realizar la transacción en SECTOR MUEBLE es únicamente de carácter informativo y no implica la aceptación de la transacción por parte de SECTOR MUEBLE. En caso de tener algún problema con su orden, el Usuario será comunicado por correo electrónico o vía telefónica. Los pagos realizados en el sitio web por medio de tarjeta de crédito, débito o Paypal están sujetos a análisis y aprobación por parte de SECTOR MUEBLE. SECTOR MUEBLE se reserva el derecho de solicitar documentos oficiales al Usuario, como medio de validación al proceso de adquisición de productos a través del sitio web de SECTOR MUEBLE, para validar la titularidad y correcto uso del medio de pago, y en dado caso, a cancelar el pago realizado según su exclusivo criterio.
Realizada la compra por el Cliente, mediante la aceptación implícita de los Términos y Condiciones de Uso, SECTOR MUEBLE enviará un e-mail al Cliente informando los detalles de la compra realizada.
SECTOR MUEBLE enviará la confirmación de compra vía correo electrónico. Solo después de la confirmación del pago se liberarán los productos para entrega en la dirección de entrega indicada por el Cliente.
En caso de desconocimiento por parte de la Institución Bancaria correspondiente a los cargos efectuados por el Cliente que corresponda a través de tarjeta de crédito y derivados de operaciones realizadas en SECTOR MUEBLE, SECTOR MUEBLE se reserva el derecho de iniciar las acciones legales que correspondan y fincar las responsabilidades penales o civiles según sea el caso o de cualquier otra naturaleza, así como de realizar todas aquellas acciones internas que podrán ir desde hacer el cargo nuevamente a la tarjeta de crédito de dicho Cliente hasta la baja definitiva del Usuario en el sitio web, para lo cual no se necesitará autorización previa del Usuario.

Meses Sin Intereses
Pago en efectivo o con tarjeta a MSI*, consulta el monto mínimo vigente para meses sin intereses en el apartado de promociones.
Puedes realizar el pago de tu compra de forma segura con cualquier Tarjeta de Crédito o Débito: Visa, MasterCard, American Express, Mercado Pago o PayPal; es importante mencionar que la autorización final de tu compra dependerá del Emisor de tu Tarjeta, por lo que en caso de cualquier duda o aclaración al respecto te sugerimos contactar al mismo.

Facturación
Si requieres facturar tu compra, agradecemos nos ayudes en tener a la mano la siguiente información:
- Número de Pedido
- Constancia de Situación Fiscal con antigüedad no mayor a 3 meses.
- Correo electrónico: En el ticket proporcionado en el mail de Confirmación de Compra encontrarás un código alfanumérico, los números los podrás identificar con un guión bajo.
Es responsabilidad del Cliente proporcionar los datos e información verídica y clara para emitir la factura, por lo cual te recomendamos revisar que éstos sean correctos. En caso de requerir ayuda para obtener la factura te invitamos a comunicarte a nuestro Centro de Ventas y Atención Telefónica. La factura se te enviará vía correo electrónico a más tardar dentro de los siguientes 06 (seis) días hábiles posteriores a la fecha en la que se realizó tu compra.

Disponibilidad de productos
La disponibilidad de nuestros productos ofertados a través del sitio web se detallará durante el proceso de compra y antes de concluir la misma, en caso de que por cualquier razón el producto no está disponible, uno de nuestros asesores se pondrá en contacto contigo para ofrecerte alguna de las siguientes soluciones:
- Seleccionar un producto sustituto del mismo precio y similares características.
- Seleccionar un producto similar, en caso de variación en el precio del bien no disponible con el bien sustituto, el Cliente es responsable de asumir el pago de la cantidad que corresponda a la citada diferencia.

Política de Ventas Finales
Todas las compras realizadas en este sitio web son definitivas. No se aceptan cambios, cancelaciones ni devoluciones, y no se realizarán reembolsos de ningún tipo una vez procesado el pedido.
Excepción por garantía: Únicamente se procesarán devoluciones, cambios o reembolsos en los casos contemplados por la Ley Federal de Protección al Consumidor, tales como defectos comprobables de fábrica, daños en el transporte o si el producto/servicio no corresponde a lo contratado. Para más información comunicate al 2226702641 o al correo hola@sectormueble.com.mx.

Garantía
SECTOR MUEBLE actúa en calidad de distribuidor de fabricantes o distribuidores mayoristas que garantizan que los productos que se comercializan en el Sitio web www.sectormueble.com.mx funcionan correctamente y no presentan defectos ni vicios ocultos que puedan hacerlos peligrosos o inadecuados para un uso normal. No obstante lo anterior, el uso que cada Cliente dé a los productos es de su exclusiva responsabilidad, sin responsabilidad alguna de SECTOR MUEBLE.
La garantía de los productos cubre defectos de fabricación y está vigente durante un período de 60 días naturales a partir de la fecha de recepción del producto. Para hacer efectiva la garantía, el cliente debe notificar a SECTOR MUEBLE dentro de este plazo. Una vez recibida la notificación, la empresa cubrirá los costos asociados con la reparación o reemplazo del producto, según corresponda. Para que la garantía sea válida, se realizará un análisis técnico de los defectos reportados. Tras este análisis, se indicará al cliente el proceso a seguir para la reparación o sustitución del producto.
- La garantía cubre defectos en los materiales y textiles utilizados en la fabricación, siempre que el uso del producto se realice bajo condiciones normales.
- La garantía no cubre sistemas mecánicos ni aparatos eléctricos incluidos en los productos. En este caso, la cobertura estará a cargo del proveedor del componente, quien realizará un análisis técnico. Si el proveedor no cubre la garantía, el cliente deberá asumir los costos de reparación.
- Quedan excluidos de la garantía cualquier daño causado por:
  * Mal uso, negligencia, golpes.
  * Uso indebido de productos químicos de limpieza u otros productos similares.
  * Daños causados durante el transporte, montaje o desmontaje del producto no realizados por SECTOR MUEBLE o empresas contratadas por SECTOR MUEBLE.
  * Limpieza o mantenimiento no conformes a las instrucciones de SECTOR MUEBLE.
  * Daños causados por accidentes, caídas, desastres naturales, plagas, etc.
  * Uso diferente al uso tradicional de un mueble o uso definido por SECTOR MUEBLE.
  * Desgaste por uso normal, cortes o rayones, o el daño causado por golpes, accidentes o desechos.
  * En colchones por higiene no aplica garantía si la pieza no se encuentra en su empaque original sellado.
  * Si el producto ha sido alterado o reparado por personas no autorizadas.
  * Desgaste por uso normal.
Además, las alteraciones causadas por condiciones climáticas o medioambientales no están cubiertas. Para productos destinados al exterior o expuestos a la intemperie, el cliente debe documentar e informar los procesos de mantenimiento aplicados para obtener la validación de la empresa y mantener la garantía durante el período ofertado.
SECTOR MUEBLE tendrá el derecho a realizar una verificación y análisis, ya sea por parte del área correspondiente de SECTOR MUEBLE o de terceros asignados por SECTOR MUEBLE para dicho fin, incluyendo y no limitado a la inspección visual, física y/o técnica de el o los productos averiados para validar la vigencia y en dado caso su aplicación de acuerdo con los factores antes mencionados. La descripción de los acontecimientos por parte del cliente no tendrá carácter vinculante hacia SECTOR MUEBLE para validar la aplicación de la garantía o los términos de devolución.
Si requieres algún apoyo te recordamos que podrás contactar al Centro de Atención al cliente.

Dudas o Aclaraciones
Dudas o mayor información te recomendamos comunicarte a nuestro Centro de Atención Telefónica al 2226702641 o al correo hola@sectormueble.com.mx, en donde con mucho gusto te resolveremos todas tus dudas.

Mecanismos de seguridad: aceptación, prueba e identidad de la transacción

Seguridad del sitio
En SECTOR MUEBLE nos preocupamos por tu seguridad y por eso pensamos en todas las herramientas necesarias para que en cada operación y transacción que realices desde nuestro Sitio web te respaldan.

Contacto y notificaciones
Para cualquier duda y sugerencia te invitamos a ponerte en contacto con alguno de me nuestros asesores a través de: teléfono: 2226702641, correo electrónico: hola@sectormueble.com.mx. Al aceptar nuestros términos y condiciones autorizas recibir notificaciones de nosotros a través de diversos medios entre ellos correo electrónico, mensaje de texto o whatsapp, notificaciones automáticas a través de la app, avisos o mensajes en el sitio web, o cualquier otro medio electrónico o físico que determine SECTOR MUEBLE, en cualquier momento puedes revocar este consentimiento contactandonos a través de los teléfonos y correo electrónico proporcionados. Así mismo durante el proceso de registro aceptas recibir correos electrónicos promocionales de SECTOR MUEBLE, puedes elegir no recibir tales correos promocionales a través de la liga que para tal efecto se encuentra en el inferior de cualquier correo electrónico promocional.
Lo anterior de conformidad a la Ley Federal de Protección al Cliente y su reglamento.

Derechos de autor, marcas y patentes.
Los signos distintivos con denominación SECTOR MUEBLE, www.sectormueble.com.mx y sus logotipos, entre otros, son marcas, nombres de dominio y/o nombres comerciales propiedad de CTA2209268QA y/o sus respectivas filiales o subsidiarias, mismas que se encuentran protegidas en México y en el extranjero por los tratados internacionales y leyes aplicables en materia de Propiedad Intelectual. Los derechos de Propiedad Intelectual sobre el contenido, organización, recopilación, compilación, información, transferencias magnéticas o electrónicas, conversión digital, logotipos, fotografías, imágenes, programas, aplicaciones, o en general cualquier información contenida o publicada en www.sectormueble.com.mx, son propiedad de sus respectivos titulares y se encuentran debidamente protegidos a favor de sus respectivos propietarios, de conformidad con la legislación aplicable en materia de Propiedad Intelectual e Industrial y su uso en www.sectormueble.com.mx se encuentra debidamente licenciado, autorizado o permitido únicamente como referencia.
TEXT;

        DB::table('terminos_condiciones')->insert([
            'id' => 1,
            'contenido' => $textoInicial,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminos_condiciones');
    }
};
