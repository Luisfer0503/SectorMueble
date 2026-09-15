<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Pedido - Sector Mueble</title>
    <style>
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background-color: #FAF8F5;
            margin: 0;
            padding: 0;
            color: #18181B;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #FAF8F5;
            padding: 40px 12px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(136, 103, 75, 0.10);
            border: 1px solid #EAE5DC;
        }
        .header {
            background-color: #FAF8F5;
            border-bottom: 1px solid #EAE5DC;
            padding: 32px 24px;
            text-align: center;
        }
        .header-logo {
            height: 38px;
            width: auto;
            vertical-align: middle;
            display: inline-block;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #88674B;
            text-transform: uppercase;
            display: inline-block;
            vertical-align: middle;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #74563C;
            font-weight: 500;
        }
        .content {
            padding: 40px 32px;
            line-height: 1.6;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-enviado {
            background-color: #EEF2FF;
            color: #3730A3;
            border: 1px solid #C7D2FE;
        }
        .badge-entregado {
            background-color: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }
        .badge-contacto {
            background-color: #F3E8FF;
            color: #6B21A8;
            border: 1px solid #E9D5FF;
        }
        .badge-default {
            background-color: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #5C4033;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .text {
            font-size: 15px;
            color: #52525B;
            margin-bottom: 20px;
            line-height: 1.65;
        }
        .verified-email-box {
            background-color: #FAF8F5;
            border: 1px solid #EAE5DC;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 13px;
            color: #5C4033;
            margin-bottom: 24px;
        }
        .verified-email-box strong {
            color: #88674B;
            font-family: monospace;
        }
        .order-summary {
            background-color: #FAF8F5;
            border: 1px solid #EAE5DC;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .order-summary h3 {
            margin-top: 0;
            margin-bottom: 14px;
            font-size: 14px;
            color: #5C4033;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #EAE5DC;
            padding-bottom: 8px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px dashed #EAE5DC;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding-top: 14px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #18181B;
            border-top: 2px solid #88674B;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0 16px;
        }
        .btn-action {
            display: inline-block;
            background-color: #88674B;
            color: #FFFFFF !important;
            font-weight: 700;
            font-size: 15px;
            padding: 16px 36px;
            text-decoration: none;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(136, 103, 75, 0.3);
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            background-color: #74563C;
        }
        .footer {
            background-color: #FAF8F5;
            padding: 24px 30px;
            text-align: center;
            font-size: 12px;
            color: #71717A;
            border-top: 1px solid #EAE5DC;
            line-height: 1.5;
        }
        .footer-logo {
            height: 24px;
            width: auto;
            margin-bottom: 8px;
            opacity: 0.8;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                    <tr>
                        <td style="vertical-align: middle; padding-right: 10px;">
                            <img src="{{ asset('logo2.png') }}" alt="Sector Mueble Logo" class="header-logo">
                        </td>
                        <td style="vertical-align: middle;">
                            <h1 style="color: #88674B; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">SECTOR MUEBLE</h1>
                        </td>
                    </tr>
                </table>
                <p>E-commerce de Muebles de Diseño & Decoración</p>
            </div>

            <!-- Content -->
            <div class="content">
                
                <!-- Status Badge -->
                <div style="text-align: center;">
                    @if($nuevoEstado === 'enviado')
                        <div class="status-badge badge-enviado">
                            🚚 ¡Tu Pedido ha sido Enviado!
                        </div>
                    @elseif($nuevoEstado === 'entregado' || $nuevoEstado === 'recibido')
                        <div class="status-badge badge-entregado">
                            🎉 ¡Tu Pedido ha sido Entregado!
                        </div>
                    @elseif($nuevoEstado === 'contacto_agente')
                        <div class="status-badge badge-contacto">
                            💬 En Contacto con Agente de Ventas
                        </div>
                    @else
                        <div class="status-badge badge-default">
                            📦 Actualización de Pedido #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                    @endif
                </div>

                <h2 class="greeting">¡Hola, {{ $pedido->nombre_cliente }}! 👋</h2>

                @if($nuevoEstado === 'enviado')
                    <p class="text">
                        Nos complace informarte que tu pedido <strong>#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</strong> ha sido empacado con cuidado y ya se encuentra <strong>en camino a tu domicilio</strong>.
                    </p>
                @elseif($nuevoEstado === 'entregado' || $nuevoEstado === 'recibido')
                    <p class="text">
                        ¡Tu pedido <strong>#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</strong> ha sido marcado como <strong>entregado con éxito</strong>! Esperamos que disfrutes tus nuevas piezas de diseño.
                    </p>
                @elseif($nuevoEstado === 'contacto_agente')
                    <p class="text">
                        Tu pedido <strong>#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</strong> ha pasado al estatus de <strong>Contacto a Agente de Ventas</strong>. Un asesor especializado se encuentra dando seguimiento personalizado a tu compra.
                    </p>
                @else
                    <p class="text">
                        El estatus de tu pedido <strong>#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</strong> ha sido actualizado en nuestro sistema.
                    </p>
                @endif

                <!-- Verified Email Indicator -->
                <div class="verified-email-box">
                    ✉️ Notificación enviada a tu correo verificado: <strong>{{ $pedido->correo_cliente }}</strong>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Detalles del Pedido #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    
                    @foreach($pedido->detalles as $det)
                        <div class="item-row">
                            <span>{{ $det->cantidad }}x {{ $det->nombre_producto }}</span>
                            <span style="font-weight: 600;">$ {{ number_format($det->precio * $det->cantidad, 2, '.', ',') }} MXN</span>
                        </div>
                    @endforeach

                    <div class="total-row">
                        <span>Total del Pedido</span>
                        <span style="color: #88674B;">$ {{ number_format($pedido->total, 2, '.', ',') }} MXN</span>
                    </div>
                </div>

                <!-- Address Info -->
                <div style="background-color: #FAF8F5; border: 1px solid #EAE5DC; border-radius: 12px; padding: 16px; font-size: 13px; color: #52525B; margin-bottom: 28px;">
                    <strong style="color: #5C4033; display: block; margin-bottom: 4px;">📍 Dirección de Entrega:</strong>
                    {{ $pedido->direccion_envio }}, CP {{ $pedido->codigo_postal }}, {{ $pedido->ciudad }}.<br>
                    <strong>Contacto:</strong> {{ $pedido->telefono_cliente }}
                </div>

                <!-- Button -->
                <div class="button-wrapper">
                    <a href="https://wa.me/522225722219?text={{ urlencode('Hola, quisiera consultar el estatus de mi pedido #' . str_pad($pedido->id, 5, '0', STR_PAD_LEFT)) }}" class="btn-action" target="_blank">
                        Contactar Soporte por WhatsApp
                    </a>
                </div>

            </div>

            <!-- Footer -->
            <div class="footer">
                <img src="{{ asset('logo.png') }}" alt="Sector Mueble Logo" class="footer-logo"><br>
                &copy; {{ date('Y') }} Sector Mueble. Todos los derechos reservados.<br>
                Este es un correo automático para tu cuenta verificada en Sector Mueble.
            </div>
        </div>
    </div>
</body>
</html>
