#!/bin/bash

# Script de inicio para Apache en Cloud Run
set -e

# Obtener el puerto de Cloud Run (por defecto 8080)
PORT=${PORT:-8080}

echo "🚀 Iniciando Apache en puerto $PORT"

# Configurar Apache para escuchar en el puerto correcto
echo "Listen $PORT" > /etc/apache2/ports.conf

# Configurar el VirtualHost con el puerto correcto
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Verificar que Laravel esté configurado correctamente
if [ ! -f /var/www/html/.env ]; then
    echo "⚠️ Archivo .env no encontrado, copiando desde .env.example"
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generar clave de aplicación si no existe
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generando clave de aplicación..."
    cd /var/www/html && php artisan key:generate --force
fi

# Limpiar cache si es necesario
echo "🧹 Limpiando cache..."
cd /var/www/html
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Verificar permisos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Configuración completada, iniciando Apache..."

# Iniciar Apache en primer plano
exec apache2-foreground