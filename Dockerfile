FROM php:8.2-rc-fpm-bullseye

#definindo argumentos para docker composer
ARG user
ARG uid

# Instalando dependencias
RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip \
	poppler-utils \
	pdftk \
	libmagickwand-dev \
	libmagickcore-dev \
	libzip-dev \
	libssl-dev \
	libpcre3-dev \
	default-mysql-client \
	minify \
	net-tools \
	vim

#clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#install php extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

#instalando dependencias do pecl
RUN pecl install imagick \
	&& docker-php-ext-enable imagick

#RUN pecl install rar \
#	&& docker-php-ext-enable rar

RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug

RUN pecl install redis \
	&& docker-php-ext-enable redis

RUN pecl install swoole \
    && docker-php-ext-enable swoole

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# movendo php.ini para o certo
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
	&& echo "post_max_size = 100M" >>  "$PHP_INI_DIR/php.ini" \
	&& echo "upload_max_filesize = 100M" >> "$PHP_INI_DIR/php.ini" \
	&& echo "memory_limit = 100M" >> "$PHP_INI_DIR/php.ini"

RUN touch "$PHP_INI_DIR/conf.d/90-xdebug.ini" \
	&& echo "xdebug.mode=debug" >> "$PHP_INI_DIR/conf.d/90-xdebug.ini" \
	&& echo "xdebug.discover_client_host=0" >> "$PHP_INI_DIR/conf.d/90-xdebug.ini" \
	&& echo "xdebug.client_host=host.docker.internal" >> "$PHP_INI_DIR/conf.d/90-xdebug.ini" \
	&& echo "xdebug.client_port=9003" >> "$PHP_INI_DIR/conf.d/90-xdebug.ini" \
	&& echo "xdebug.start_with_request=yes" >> "$PHP_INI_DIR/conf.d/90-xdebug.ini" 

RUN sed -i 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read | write" pattern="PDF" \/>/g' /etc/ImageMagick-6/policy.xml

#RUN echo "nameserver 8.8.8.8" >> /etc/resolv.conf
# Create system user to run Composer and Artisan Commands
#se estiver usando docker descomentar as linha abaixo
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
	chown -R $user:$user /home/$user 
#echo $uid $user


# Set working directory
WORKDIR /var/www
RUN chown -R $user:$user /var/www

#CMD ["php", "artisan", "octane:start", "--host=0.0.0.0", "--port=8000"]

#USER $user
