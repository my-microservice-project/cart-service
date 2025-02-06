FROM phpdockerio/php:8.3-fpm
WORKDIR "/app"

# Gerekli bağımlılıkları yükle
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    libpq-dev \
    php8.3-amqp \
    php8.3-gd \
    php8.3-intl \
    php8.3-mcrypt \
    php8.3-mongodb \
    php8.3-pgsql \
    php8.3-pdo \
    php8.3-pdo-pgsql \
    php8.3-redis \
    php8.3-uuid \
    php8.3-xdebug \
    php8.3-bcmath \
    php8.3-yaml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# PHP uzantılarının yüklendiğini kontrol et
RUN php -m | grep -E "pdo_pgsql|pgsql"
