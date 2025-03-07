# Используем официальный образ Debian
FROM debian:latest
VOLUME /var/lib/mysql
VOLUME /var/log


# Устанавливаем необходимые пакеты: Apache2, PHP, модуль PHP для Apache, расширение PHP для MySQL и сервер MariaDB
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php php-mysql mariadb-server && \
    apt-get clean
RUN apt-get install -y supervisor
# add wordpress files to /var/www/html
ADD https://wordpress.org/latest.tar.gz /var/www/html/
RUN tar -xvf /var/www/html/latest.tar.gz -C /var/www/html/ --strip-components=1 && \
    rm /var/www/html/latest.tar.gz
# copy the configuration file for apache2 from files/ directory
COPY files/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY files/apache2/apache2.conf /etc/apache2/apache2.conf

# copy the configuration file for php from files/ directory
COPY files/php/php.ini /etc/php/8.2/apache2/php.ini

# copy the configuration file for mysql from files/ directory
COPY files/mariadb/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf

COPY files/wp-config.php /var/www/html/wordpress/wp-config.php

# copy the supervisor configuration file
COPY files/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
# create mysql socket directory
RUN mkdir /var/run/mysqld && chown mysql:mysql /var/run/mysqld && \
    chown -R www-data:www-data /var/www/html/
EXPOSE 80
# start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
