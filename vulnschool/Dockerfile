# =============================================================================
# VulnSchool - Dockerfile
# Aplikasi akademik kampus yang SENGAJA DIBUAT RENTAN
# untuk keperluan pendidikan keamanan siber
# =============================================================================

FROM php:8.2-apache

# Install dependensi sistem dan ekstensi PHP yang dibutuhkan CI4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    intl \
    mbstring \
    gd \
    mysqli \
    pdo_mysql \
    zip \
    && docker-php-ext-enable \
    intl \
    mysqli \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi PHP untuk development (menampilkan error)
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/vulnschool.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/vulnschool.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/vulnschool.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/vulnschool.ini \
    && echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/vulnschool.ini \
    && echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/vulnschool.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy file composer terlebih dahulu untuk caching layer
COPY composer.json composer.lock ./

# Install dependensi PHP
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copy seluruh source code aplikasi
COPY . .

# Konfigurasi Apache: DocumentRoot mengarah ke folder public CI4
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/vulnschool-entrypoint.sh

# Set permission untuk folder writable
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable \
    && mkdir -p /var/www/html/public/uploads/foto \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads \
    && php -r '$f="/usr/local/bin/vulnschool-entrypoint.sh"; $s=file_get_contents($f); $s=preg_replace("/^\xEF\xBB\xBF/", "", $s); $s=str_replace(["\r\n", "\r"], "\n", $s); file_put_contents($f, $s);' \
    && chmod +x /usr/local/bin/vulnschool-entrypoint.sh

# Expose port 80 (akan di-map ke 8080 di docker-compose)
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/vulnschool-entrypoint.sh"]
