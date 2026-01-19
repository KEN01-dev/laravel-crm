FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpq-dev libonig-dev \
 && docker-php-ext-install pdo_mysql mbstring zip intl opcache \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["bash", "-lc", "php artisan serve --host=0.0.0.0 --port=8000"]
