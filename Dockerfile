FROM php:8.1-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Clear Data
RUN apt-get clean
# Update
RUN apt-get update

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libz-dev libmemcached-dev && \
    pecl install memcached && \
    docker-php-ext-enable memcached 

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install mbstring zip exif pcntl
#RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-configure gd 
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1001 ewallet
RUN useradd -u 1001 -ms /bin/bash -g ewallet ewallet

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=ewallet:ewallet . /var/www

# Change current user to www
USER ewallet

RUN composer install

RUN php artisan key:generate

RUN php artisan config:cache
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
