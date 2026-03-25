# Folosim o imagine oficială care are PHP și serverul web Apache incluse
FROM php:8.2-apache

# Instalăm extensiile necesare pentru ca PHP să poată vorbi cu baza de date
# Instalam "postasul" msmtp si extensiile de care aveam deja nevoie
RUN apt-get update && apt-get install -y msmtp \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis

# Ii spunem poștașului să ducă scrisorile la containerul mailhog
RUN echo "defaults\nport 1025\ntls off\naccount mailhog\nhost mailhog\nfrom admin@perfectworld.local\naccount default : mailhog" > /etc/msmtprc

# Activăm funcția mail() in PHP
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

# Copiem toate fișierele site-ului tău din folder în interiorul containerului
COPY . /var/www/html/

# Oferim permisiunile corecte
RUN chown -R www-data:www-data /var/www/html/
