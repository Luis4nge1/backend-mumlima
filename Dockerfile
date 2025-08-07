# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones y dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip curl git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita módulos necesarios de Apache
RUN a2enmod rewrite headers deflate

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia archivos del proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Configura Laravel para Cloud Run
RUN cp .env.example .env

# Instala dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Genera la clave de aplicación
RUN php artisan key:generate

# Optimizaciones de Laravel para producción
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Permisos correctos para Cloud Run
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Configura Apache para Cloud Run
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Configura Apache para Cloud Run puerto 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf
RUN echo "Listen 8080" > /etc/apache2/ports.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expone el puerto que Cloud Run espera
EXPOSE 8080

# Comando para iniciar Apache
CMD ["apache2-foreground"]