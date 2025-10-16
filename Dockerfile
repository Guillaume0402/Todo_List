FROM php:8.2-apache

# 📌 Installe les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# 📌 Installe l'extension MongoDB pour PHP
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# 📌 Active le mod_rewrite pour les routes MVC
RUN a2enmod rewrite

# 📌 Copie le projet dans le conteneur
COPY . /var/www/html/

# 📌 Fixe les droits
RUN chown -R www-data:www-data /var/www/html

# 📌 Définit le dossier de travail
WORKDIR /var/www/html

# 📌 Expose le port Apache
EXPOSE 80
