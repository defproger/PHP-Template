FROM php:8.3-apache

# Установка пакетов и утилит
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    zsh \
    libzip-dev \
    zip \
    inotify-tools \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Включение модулей Apache
RUN a2enmod rewrite

# Настройка Apache для использования public как корневой директории
RUN sed -i -e 's!/var/www/html!/var/www/html/src/public!g' /etc/apache2/sites-available/000-default.conf

# Настройка прав доступа для папки public
RUN echo "<Directory /var/www/html/src/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Установка рабочей директории
WORKDIR /var/www/html

EXPOSE 80