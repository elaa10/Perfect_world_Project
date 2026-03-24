# Folosim o imagine oficială care are PHP și serverul web Apache incluse
FROM php:8.2-apache

# Instalăm extensiile necesare pentru ca PHP să poată vorbi cu baza de date
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiem toate fișierele site-ului tău din folder în interiorul containerului
COPY . /var/www/html/

# Oferim permisiunile corecte
RUN chown -R www-data:www-data /var/www/html/
