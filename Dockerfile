# Gunakan image PHP resmi
FROM php:8.2-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    npm \
    nodejs \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev

# Install ekstensi PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set workdir ke dalam container
WORKDIR /var/www

# Copy semua file ke container
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Expose port yang dipakai
EXPOSE 8000

# Jalankan Laravel menggunakan built-in server
CMD php artisan serve --host=0.0.0.0 --port=8000
