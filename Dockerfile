# Gunakan image PHP 8.3 FPM
FROM php:8.3-fpm

# Install dependensi sistem yang dibutuhkan
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip unzip curl git nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Salin composer dari image resmi agar tidak download lagi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja aplikasi
WORKDIR /var/www

# Salin semua file project ke dalam container
COPY . .

# Install dependency backend Laravel
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# Install dependency frontend (Vite) dan build asset production
RUN npm install && npm run build

# Laravel commands yang penting sebelum dijalankan
RUN php artisan config:clear \
 && php artisan view:clear \
 && php artisan optimize:clear \
 && php artisan permission:cache-reset \
 && php artisan storage:link || true

# Jalankan seeder jika dibutuhkan (opsional, bisa dihilangkan kalau tidak perlu)
RUN php artisan db:seed --class=Database\\Seeders\\SuperAdminSeeder || true

# Buka port default Laravel
EXPOSE 8000

# Jalankan Laravel via built-in dev server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
