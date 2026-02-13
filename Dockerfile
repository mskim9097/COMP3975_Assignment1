FROM php:8.2-cli

RUN apt-get update && apt-get install -y git \
  && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY . /var/www/html

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public", "public/router.php"]