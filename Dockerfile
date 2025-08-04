FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libxml2 \
    unzip \
    netcat-traditional && \
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip soap

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN a2enmod rewrite

# Configurar virtual host
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Configurar git para evitar errores de permisos
RUN git config --global --add safe.directory /var/www/html

# Directorio de trabajo
WORKDIR /var/www/html

# 1. Copiar TODO el código primero
COPY . .

# 2. Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# 3. Instalar dependencias Node
RUN npm install

# 4. Construir assets
RUN npm run build

# 5. Ejecutar comandos de Artisan que requieren todos los archivos
RUN php artisan package:discover --ansi

# Configurar permisos
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage

# Configurar entrypoint
COPY .docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]