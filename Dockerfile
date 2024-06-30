FROM ubuntu/nginx:1.18-20.04_beta

RUN apt-get update && apt-get upgrade -y

RUN apt-get install \
  php7.4 \
  php7.4-common \
  php7.4-curl \
  php7.4-fpm \
  php7.4-gd \
  php7.4-mysql \
  php7.4-xml \
  php7.4-zip -y

# Enable environment variables for PHP-FPM
RUN sed -i 's/;clear_env = no/clear_env = no/' /etc/php/7.4/fpm/pool.d/www.conf

# Enable verbose error messages
RUN sed -i 's/display_errors = Off/display_errors = On/' /etc/php/7.4/fpm/php.ini

# Copy nginx site configuration
COPY docker/nginx/default /etc/nginx/sites-available/default

# Restart nginx
RUN service nginx restart

# Switch to /var/www/html
WORKDIR /var/www/html

# Start PHP-FPM
CMD mkdir -p uploads; chown -R www-data:www-data uploads; chmod -R 755 uploads; /etc/init.d/php7.4-fpm start -F && nginx -g "daemon off;"
