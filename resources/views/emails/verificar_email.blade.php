<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu dirección de correo electrónico - Sector Mueble</title>
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
            max-height: 42px;
            vertical-align: middle;
            display: inline-block;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #88674B; /* Nogal Cálido */
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
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #5C4033;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .text {
            font-size: 15px;
            color: #52525B;
            margin-bottom: 24px;
            line-height: 1.65;
        }
        .button-wrapper {
            text-align: center;
            margin: 36px 0;
        }
        .btn-confirm {
            display: inline-block;
            background-color: #4c6f4f;
            color: #FFFFFF !important;
            font-weight: 700;
            font-size: 15px;
            padding: 16px 36px;
            text-decoration: none;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(76, 111, 79, 0.3);
            transition: all 0.2s ease;
        }
        .btn-confirm:hover {
            background-color: #3c583e;
        }
        .info-box {
            background-color: #F5EBE0;
            border: 1px solid #D9C5B2;
            padding: 14px 24px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #5C4033;
            text-align: center;
            margin-bottom: 28px;
            box-shadow: 0 2px 8px rgba(92, 64, 51, 0.05);
        }
        .url-fallback {
            font-size: 12px;
            color: #71717A;
            word-break: break-all;
            background-color: #FAF8F5;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #EAE5DC;
            margin-top: 8px;
        }
        .url-fallback a {
            color: #4c6f4f;
            font-weight: 600;
            text-decoration: underline;
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
            <!-- Encabezado con Identidad Sector Mueble -->
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

            <!-- Contenido Principal -->
            <div class="content">
                <h2 class="greeting">¡Hola, {{ $user->name }}! 👋</h2>
                <p class="text">
                    Gracias por registrarte en <strong>Sector Mueble</strong>. Para verificar tu dirección de correo electrónico y comenzar a disfrutar de nuestras colecciones exclusivas de muebles de diseño, haz clic en el siguiente botón:
                </p>

                <div class="button-wrapper">
                    <a href="{{ $url }}" class="btn-confirm" target="_blank">
                        Confirmar Mi Correo Electrónico
                    </a>
                </div>

                <div class="info-box">
                    ⏱️ Este enlace de verificación expira en <strong>60 minutos</strong> por razones de seguridad.
                </div>

                <p class="text" style="font-size: 13px; color: #71717A; margin-bottom: 4px;">
                    Si el botón no funciona, copia y pega la siguiente dirección URL en la barra de tu navegador web:
                </p>

                <div class="url-fallback">
                    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                </div>

                <p class="text" style="font-size: 12px; color: #A1A1AA; margin-top: 28px; margin-bottom: 0;">
                    Si no creaste una cuenta en Sector Mueble, no se requiere ninguna acción adicional.
                </p>
            </div>

            <!-- Pie de página -->
            <div class="footer">
                <img src="{{ asset('logo.png') }}" alt="Sector Mueble Logo" class="footer-logo"><br>
                &copy; {{ date('Y') }} Sector Mueble. Todos los derechos reservados.<br>
                Este es un correo automático, por favor no respondas a este mensaje.
            </div>
        </div>
    </div>
</body>
</html>
