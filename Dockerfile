# ==========================================
# Etapa 1: Node (Construcción de Assets de Vite)
# ==========================================
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ==========================================
# Etapa 2: Composer (Dependencias de PHP)
# ==========================================
FROM composer:2.7 AS composer-builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --ignore-platform-reqs --no-scripts --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

# ==========================================
# Etapa 3: Producción (PHP-FPM + Nginx)
# ==========================================
FROM php:8.2-fpm-alpine

# Instalar Nginx y dependencias de sistema
RUN apk add --no-cache \
    nginx \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Configurar y compilar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl gd

# Copiar configuración de Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar el código fuente y las dependencias de las etapas anteriores
COPY . .
COPY --from=composer-builder /app/vendor ./vendor
COPY --from=node-builder /app/public/build ./public/build

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copiar y preparar el script de inicio
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer el puerto 80
EXPOSE 80

# Definir el punto de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
