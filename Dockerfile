FROM php:8.2-cli as builder

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && docker-php-ext-install \
    curl \
    intl \
    mysqli \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

FROM php:8.2-cli

WORKDIR /app

COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

COPY . /app

EXPOSE 8000

CMD ["php", "-c", "php.ini", "-S", "0.0.0.0:8000", "src/App.php"]
