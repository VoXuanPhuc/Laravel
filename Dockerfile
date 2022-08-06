FROM php:8.1.7-apache

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        git \
        zip \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql

#Xdebug - should be removed for prod
RUN pecl install xdebug

# Set working directory
WORKDIR /var/www/html/identity-service


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . /var/www/html/identity-service

# Composer install
RUN composer install
RUN composer update --no-scripts
RUN composer dump-autoload

#Apache2
ENV PORT=8081
RUN rm -f /etc/apache2/sites-available/*
RUN rm -f /etc/apache2/sites-enabled/*

#ADD port
RUN echo "Listen ${PORT}" >> /etc/apache2/ports.conf

COPY ./docker/apache2/identity-service.conf  /etc/apache2/sites-available/
RUN a2ensite identity-service
RUN a2enmod rewrite
RUN a2enmod headers

# Allow app to write files
RUN chmod -R 777 /var/www/html/identity-service/storage

# Expose port and start apache2 server
EXPOSE ${PORT}

CMD ["apache2-foreground"]
