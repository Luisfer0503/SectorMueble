#!/bin/bash

# Script de Despliegue Automatizado a Producción - Sector Mueble
echo "🚀 Iniciando despliegue de Sector Mueble a Producción..."

# 1. Poner aplicación en modo mantenimiento temporal
php artisan down --message="Actualizando sistema, volvemos en un momento..." || true

# 2. Descargar últimos cambios del repositorio
git pull origin main

# 3. Instalar dependencias de PHP de producción
composer install --no-dev --optimize-autoloader

# 4. Ejecutar migraciones en base de datos de producción
php artisan migrate --force

# 5. Re-crear cachés de producción
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 6. Salir del modo mantenimiento
php artisan up

echo "✅ ¡Despliegue a Producción completado exitosamente!"
