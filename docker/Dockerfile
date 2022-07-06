FROM php:8.1-apache as runtime

COPY --from=composer:2.1.5 /usr/bin/composer /usr/local/bin/composer

# Set common env variables
ENV TZ="UTC"
ENV APACHE_DOCUMENT_ROOT="/app/public"
ENV COMPOSER_HOME="/tmp/composer"

RUN apt-get update -y && apt-get install -y \
      git \
      libzip-dev \
      unzip \
    && a2enmod rewrite \
    # Create non-root app user
    && adduser \
        --disabled-password \
        --uid 10001 \
        "appuser" \
    # Create app dir \
    && mkdir /app \
    && chown -R appuser:appuser /app

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Change default document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

USER appuser:appuser

WORKDIR /app
