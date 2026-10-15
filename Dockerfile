FROM composer:2 AS dependencies
WORKDIR /app
COPY composer.json ./
COPY src ./src
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

FROM php:8.3-cli-alpine
WORKDIR /app
COPY --from=dependencies /app/vendor /app/vendor
COPY composer.json ./
COPY src ./src
COPY public ./public
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public", "public/router.php"]
