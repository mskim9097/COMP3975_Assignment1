FROM php:8.2-cli

RUN apt-get update && apt-get install -y git libsqlite3-dev pkg-config \
  && docker-php-ext-install pdo_sqlite

WORKDIR /var/www/html

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public", "public/router.php"]