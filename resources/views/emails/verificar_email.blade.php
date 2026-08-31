<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu dirección de correo electrónico - Sector Mueble</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #1F2937;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid #E5E7EB;
        }
        .header {
            background: linear-gradient(135deg, #451A03 0%, #78350F 50%, #92400E 100%);
            color: #FEF3C7;
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #FDE68A;
            opacity: 0.9;
        }
        .content {
            padding: 36px 30px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #78350F;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .text {
            font-size: 15px;
            color: #4B5563;
            margin-bottom: 24px;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn-confirm {
            display: inline-block;
            background-color: #92400E;
            color: #FFFFFF !important;
            font-weight: 600;
            font-size: 16px;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.3);
            transition: background-color 0.2s ease;
        }
        .btn-confirm:hover {
            background-color: #78350F;
        }
        .info-box {
            background-color: #FEF3C7;
            border-left: 4px solid #D97706;
            padding: 16px;
            border-radius: 4px;
            font-size: 13px;
            color: #92400E;
            margin-bottom: 24px;
        }
        .url-fallback {
            font-size: 12px;
            color: #6B7280;
            word-break: break-all;
            background-color: #F9FAFB;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #F3F4F6;
        }
        .url-fallback a {
            color: #92400E;
            text-decoration: underline;
        }
        .footer {
            background-color: #F9FAFB;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado con Identidad Sector Mueble -->
        <div class="header">
            <h1>🪑 SECTOR MUEBLE</h1>
            <p>E-commerce de Muebles de Diseño & Decoración</p>
        </div>

        <!-- Contenido Principal -->
        <div class="content">
            <h2 class="greeting">¡Hola, {{ $user->name }}!</h2>
            <p class="text">
                Gracias por registrarte en <strong>Sector Mueble</strong>. Para asegurar que la cuenta te pertenece y comenzar a disfrutar de nuestras colecciones exclusivas de muebles y beneficios, por favor confirma tu correo electrónico haciendo clic en el siguiente botón:
            </p>

            <div class="button-wrapper">
                <a href="{{ $url }}" class="btn-confirm" target="_blank">
                    Confirmar mi Correo Electrónico
                </a>
            </div>

            <div class="info-box">
                ⏱️ Este enlace de verificación expira en <strong>60 minutos</strong> por razones de seguridad.
            </div>

            <p class="text" style="font-size: 13px; color: #6B7280;">
                Si el botón no funciona, copia y pega la siguiente dirección URL en la barra de tu navegador web:
            </p>

            <div class="url-fallback">
                <a href="{{ $url }}" target="_blank">{{ $url }}</a>
            </div>

            <p class="text" style="font-size: 13px; color: #9CA3AF; margin-top: 24px;">
                Si no creaste una cuenta en Sector Mueble, no se requiere ninguna acción adicional.
            </p>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            &copy; {{ date('Y') }} Sector Mueble. Todos los derechos reservados.<br>
            Este es un correo automático, por favor no respondas a este mensaje.
        </div>
    </div>
</body>
</html>
