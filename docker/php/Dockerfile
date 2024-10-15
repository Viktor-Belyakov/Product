FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    librdkafka-dev \
    libssl-dev \
    pkg-config \
    build-essential \
    gnupg \
    curl \
    wget \
    && docker-php-ext-install pdo pdo_pgsql

# Установка rdkafka
RUN pecl install rdkafka \
    && docker-php-ext-enable rdkafka

# Создание директории для сертификатов и загрузка CA сертификата
RUN mkdir -p /usr/local/share/ca-certificates/Yandex && \
    wget "https://storage.yandexcloud.net/cloud-certs/CA.pem" -O /usr/local/share/ca-certificates/Yandex/YandexCA.crt

# Копирование Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочего каталога и копирование приложения
WORKDIR /var/www/app
COPY ../.. /var/www/app

# Установка прав
RUN chown -R www-data:www-data /var/www/app

# Установка зависимостей Composer
RUN composer install

# Установка Elasticsearch
RUN composer require elasticsearch/elasticsearch

# Установка прав
RUN chown -R www-data:www-data /var/www/app

# Экспонирование порта
EXPOSE 9000

# Команда запуска
CMD ["php-fpm"]