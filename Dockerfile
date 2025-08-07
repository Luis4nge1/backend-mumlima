# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip curl git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia archivos del proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Instala dependencias
RUN composer install --no-dev --optimize-autoloader

# Configura Laravel para Cloud Run (sin .env file)
RUN echo 'APP_NAME="Sistema Fiscalizacion"' > .env && \
    echo 'APP_ENV=production' >> .env && \
    echo 'APP_KEY=' >> .env && \
    echo 'APP_DEBUG=false' >> .env && \
    echo 'APP_URL=https://localhost' >> .env && \
    echo 'LOG_CHANNEL=stderr' >> .env && \
    echo 'LOG_LEVEL=info' >> .env && \
    echo 'DB_CONNECTION=mysql' >> .env && \
    echo 'DB_HOST=127.0.0.1' >> .env && \
    echo 'DB_PORT=3306' >> .env && \
    echo 'DB_DATABASE=laravel' >> .env && \
    echo 'DB_USERNAME=root' >> .env && \
    echo 'DB_PASSWORD=' >> .env && \
    echo 'SESSION_DRIVER=cookie' >> .env && \
    echo 'CACHE_DRIVER=file' >> .env && \
    echo 'QUEUE_CONNECTION=sync' >> .env

RUN php artisan key:generate --force

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configura Apache para puerto 8080
RUN echo "Listen 8080" > /etc/apache2/ports.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configuración simple de VirtualHost
RUN echo '<VirtualHost *:8080>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog /dev/stderr\n\
    CustomLog /dev/stdout combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expone puerto
EXPOSE 8080

# Script de inicio con debugging
RUN echo '#!/bin/bash\n\
set -e\n\
echo "🚀 Iniciando aplicación en puerto 8080"\n\
echo "📁 Verificando directorio public:"\n\
ls -la /var/www/html/public/\n\
echo "🔧 Configuración Apache:"\n\
apache2ctl -S\n\
echo "✅ Iniciando Apache..."\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]