# 🚀 Guía Completa para Despliegue a Producción (Laravel + FastAPI Facturación)

Esta guía describe paso a paso cómo llevar a producción la plataforma de **Sector Mueble (Laravel)** junto con el microservicio de **Facturación Electrónica en FastAPI (Python)**.

---

## 📋 Requisitos del Servidor de Producción

- **Servidor Linux** (Ubuntu 22.04 / 24.04 LTS recomendado) o servidor Windows Server / Laragon Producción.
- **PHP 8.2+** con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json`, `gd`, `bcmath`.
- **Python 3.10+** y `pip`.
- **Servidor Web**: Nginx o Apache.
- **Base de Datos**: MySQL 8.0+ / MariaDB.
- **SSL / HTTPS**: Certificado SSL activo (ej. Let's Encrypt / Certbot).

---

## 1. Despliegue y Optimización de Laravel

### Paso 1.1. Actualizar el archivo `.env` de Producción
En el servidor de producción, edita el archivo `.env`:

```ini
APP_NAME="Sector Mueble"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sectormueble.com.mx

# Base de datos MySQL de Producción
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sectormueble_prod
DB_USERNAME=usuario_prod
DB_PASSWORD=password_seguro_db

# Configuración de Pasarela Stripe (Claves LIVE de Producción)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_CURRENCY=mxn

# Configuración del Microservicio de Facturación FastAPI
FASTAPI_FACTURACION_URL=http://127.0.0.1:8000
FASTAPI_FACTURACION_API_KEY=clave_secreta_fastapi_prod
FASTAPI_FACTURACION_TIMEOUT=30
```

### Paso 1.2. Ejecutar Migraciones y Optimizar Cachés de Laravel
Ejecuta la siguiente secuencia de comandos en la terminal de tu servidor:

```bash
# 1. Instalar dependencias PHP de producción sin paquetes de desarrollo
composer install --no-dev --optimize-autoloader

# 2. Ejecutar migraciones en base de datos de producción (agrega campos fiscales)
php artisan migrate --force

# 3. Crear enlace simbólico de almacenamiento público
php artisan storage:link

# 4. Limpiar y generar cachés de producción para máxima velocidad
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

### Paso 1.3. Asignar Permisos de Carpetas (Linux)
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 2. Despliegue del Microservicio de Facturación en FastAPI

### Paso 2.1. Configuración del Entorno Python para FastAPI
En la carpeta donde tengas ubicado tu proyecto de FastAPI (ejemplo: `/var/www/fastapi-facturacion`):

```bash
# 1. Crear e iniciar entorno virtual Python
python3 -m venv venv
source venv/bin/activate

# 2. Instalar dependencias necesarias
pip install --upgrade pip
pip install fastapi uvicorn gunicorn httpx pydantic python-dotenv
```

### Paso 2.2. Crear Servicio de Sistema (Systemd) para FastAPI en Linux
Crea el archivo de servicio `/etc/systemd/system/fastapi-facturacion.service`:

```ini
[Unit]
Description=Microservicio FastAPI de Facturacion Electrónica SAT
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/fastapi-facturacion
ExecStart=/var/www/fastapi-facturacion/venv/bin/gunicorn -w 4 -k uvicorn.workers.UvicornWorker main:app --bind 127.0.0.1:8000
Restart=always
RestartSec=5
Environment=PORT=8000

[Install]
WantedBy=multi-user.target
```

Inicia y habilita el servicio para que arranque automáticamente si el servidor se reinicia:

```bash
sudo systemctl daemon-reload
sudo systemctl start fastapi-facturacion
sudo systemctl enable fastapi-facturacion

# Verificar que está activo sin errores:
sudo systemctl status fastapi-facturacion
```

---

## 3. Configuración del Servidor Web Nginx (Proxy Inverso)

Configuración recomendada de Nginx en `/etc/nginx/sites-available/sectormueble`:

```nginx
server {
    listen 80;
    server_name sectormueble.com.mx www.sectormueble.com.mx;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name sectormueble.com.mx www.sectormueble.com.mx;

    root /var/www/SectorMueble/public;
    index index.php index.html;

    # SSL Certbot
    ssl_certificate /etc/letsencrypt/live/sectormueble.com.mx/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sectormueble.com.mx/privkey.pem;

    # Laravel App
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Proxy a FastAPI (Si se desea acceder directamente por subruta o puerto local)
    location /api-fastapi/ {
        proxy_pass http://127.0.0.1:8000/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Seguridad y estáticos
    location ~ /\.ht {
        deny all;
    }

    client_max_body_size 64M;
}
```

Recarga la configuración de Nginx:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## 4. Script de Despliegue Rápido Automatizado

Se ha incluido el script `desplegar.sh` en la raíz del proyecto para desplegar futuras actualizaciones con un solo comando:

```bash
chmod +x desplegar.sh
./desplegar.sh
```

---

## 🔍 Comprobaciones Finales de Funcionamiento

1. **Prueba del Portal de Facturación**: Ingresa a `https://sectormueble.com.mx/facturacion` y realiza una búsqueda de prueba.
2. **Prueba de Checkout**: Realiza un pedido de prueba marcando *"Requieres Factura Electrónica"* para verificar que se conecta con FastAPI y genera el comprobante fiscal.
3. **Revisión de Logs**: Si ocurre algún detalle en producción, consulta los logs con:
   ```bash
   tail -f storage/logs/laravel.log
   sudo journalctl -u fastapi-facturacion -f
   ```
