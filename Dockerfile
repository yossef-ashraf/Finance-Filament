# استخدام صورة PHP رسمية
FROM php:8.1-fpm

# تثبيت التبعيات الأساسية
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# إعداد الدليل الأساسي
WORKDIR /var/www

# نسخ جميع الملفات إلى الحاوية
COPY . .

# تثبيت Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# تثبيت الحزم باستخدام Composer
RUN composer install --optimize-autoloader --no-dev

# إعداد الصلاحيات
RUN chown -R www-data:www-data /var/www

# تشغيل تطبيق Laravel باستخدام PHP-FPM
CMD ["php-fpm"]
