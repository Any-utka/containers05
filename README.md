# Лаборатораня работа №5 "Запуск сайта в контейнере"
## Цель работы:  
Сделать образ контейнера для запуска веб-сайта на базе Apache HTTP Server + PHP (mod_php) + MariaDB.
## Задание:  
Создать Dockerfile для сборки образа контейнера, который будет содержать веб-сайт на базе Apache HTTP Server + PHP (mod_php) + MariaDB. База данных MariaDB должна храниться в монтируемом томе. Сервер должен быть доступен по порту 8000.
Установить сайт WordPress. Проверить работоспособность сайта.
### Описание выполнения работы:
Создаем репозиторий ```containers05``` и клонируем его на компьютер. Для этого используем команду ```git clone https://github.com/Any-utka/containers05.git``` и переходим в нашу склонированную папку.
#### Делаем извлечение конфигурационных файлов apache2, php, mariadb из контейнера, для этого:  
1. Создаем в папке ```containers05``` папку ```files```, а также
- папку files/apache2 - для файлов конфигурации apache2;
- папку files/php - для файлов конфигурации php;
- папку files/mariadb - для файлов конфигурации mariadb.
2. Создаем в папке ``containers05`` файл ``Dockerfile`` со следующим содержимым:
  ```shell
  # create from debian image
  FROM debian:latest
  # install apache2, php, mod_php for apache2, php-mysql and mariadb
  RUN apt-get update && \
  apt-get install -y apache2 php libapache2-mod-php php-mysql mariadb-server && \
  apt-get clean
  ```
3. Создаем контейнер ```apache2-php-mariadb``` из образа ```apache2-php-mariadb``` и запустите его в фоновом режиме с командой запуска ```bash```.
![Img-1](https://imgur.com/QUp2uUw.png)
4. Копируем из контейнера файлы конфигурации ```apache2, php, mariadb``` в папку ```files/``` на компьютере. Для этого используем команды:
```shell
docker cp apache2-php-mariadb:/etc/apache2/sites-available/000-default.conf files/apache2/
docker cp apache2-php-mariadb:/etc/apache2/apache2.conf files/apache2/
docker cp apache2-php-mariadb:/etc/php/8.2/apache2/php.ini files/php/
docker cp apache2-php-mariadb:/etc/mysql/mariadb.conf.d/50-server.cnf files/mariadb/
```
![Img-2](https://imgur.com/7pi5iuv.png)
#### Настраиваем конфигурационные файлы
1. Конфигурационный файл ```apache2```:
- Открываем файл ```files/apache2/000-default.conf```, найдите строку ```#ServerName www.example.com``` и замените её на ```ServerName localhost```.
- Ищем строку ```ServerAdmin webmaster@localhost``` и меняем в ней почтовый адрес на свой.
- После строки ```DocumentRoot /var/www/html``` добавляем следующие строки: ```DirectoryIndex index.php index.html```
- Сохраняем файл и закрываем.
- В конце файла ```files/apache2/apache2.conf``` добавьте следующую строку: ```ServerName localhost```
2. Конфигурационный файл ```php```:
- Открывем файл ```files/php/php.ini```, ищем строку ```;error_log = php_errors.log``` и меняем её на ```error_log = /var/log/php_errors.log```.
- Настраиваем параметры ```memory_limit, upload_max_filesize, post_max_size``` и ```max_execution_time``` следующим образом:
   ```shell
   memory_limit = 128M
   upload_max_filesize = 128M
   post_max_size = 128M
   max_execution_time = 120
   ```
- Сохраняем файл и закрываем его.
3. Конфигурационный файл ```mariadb```:
- Открывем файл ```files/mariadb/50-server.cnf```, ищем строку ```#log_error = /var/log/mysql/error.log``` и раскомментиуем её.
- Сохраняем файл и закрываем его.
4. Создаем скрипт запуска:
-Создаем в папке ```files``` папку ```supervisor``` и файл ```supervisord.conf``` со следующим содержимым:
```shell
[supervisord]
nodaemon=true
logfile=/dev/null
user=root

# apache2
[program:apache2]
command=/usr/sbin/apache2ctl -D FOREGROUND
autostart=true
autorestart=true
startretries=3
stderr_logfile=/proc/self/fd/2
user=root

# mariadb
[program:mariadb]
command=/usr/sbin/mariadbd --user=mysql
autostart=true
autorestart=true
startretries=3
stderr_logfile=/proc/self/fd/2
user=mysql
```
5. Создаем ```Dockerfile```:  
Открываем файл Dockerfile и добавляем в него следующие строки:
 - после инструкции FROM ... добавляем монтирование томов:
```shell
# mount volume for mysql data
VOLUME /var/lib/mysql

# mount volume for logs
VOLUME /var/log
```
  - в инструкции RUN ... добавляем установку пакета supervisor.
  - после инструкции RUN ... добавляем копирование и распаковку сайта WordPress:
```shell
# add wordpress files to /var/www/html
ADD https://wordpress.org/latest.tar.gz /var/www/html/
```
   - после копирования файлов WordPress добавляем копирование конфигурационных файлов apache2, php, mariadb, а также скрипта запуска:
```shell
# copy the configuration file for apache2 from files/ directory
COPY files/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY files/apache2/apache2.conf /etc/apache2/apache2.conf

# copy the configuration file for php from files/ directory
COPY files/php/php.ini /etc/php/8.2/apache2/php.ini

# copy the configuration file for mysql from files/ directory
COPY files/mariadb/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf

# copy the supervisor configuration file
COPY files/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
```
для функционирования mariadb создайте папку /var/run/mysqld и установите права на неё:
```shell
# create mysql socket directory
RUN mkdir /var/run/mysqld && chown mysql:mysql /var/run/mysqld
```
   - открываем порт 80.
   - добавляем команду запуска *supervisord*:
```shell
# start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
```
- Собираем образ контейнера с именем *apache2-php-mariadb* и запускаем контейнер *apache2-php-mariadb* из образа *apache2-php-mariadb*.
![Img-3](https://imgur.com/WDBJqlw.png)
![Img-4](https://imgur.com/jok0P9d.png)
#### Создаем базу данных и пользователя
1. Создаем базу данных *wordpress* и пользователя *wordpress* с паролем *wordpress* в контейнере *apache2-php-mariadb*. Для этого, в контейнере *apache2-php-mariadb*, выполняем команды:
```sql
CREATE DATABASE wordpress;
CREATE USER 'wordpress'@'localhost' IDENTIFIED BY 'wordpress';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wordpress'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
![Img-5](https://imgur.com/yY4WPSx.png)
#### Создаем файл конфигурации WordPress
1. Открываем в браузере сайт WordPress по адресу *http://localhost/8080*, указываем параметры для подключения, которые были применены при создании базы данных.  
![Img-6](https://imgur.com/AlRlzw6.png)
2. Копируем содержимое файла конфигурации в файл *files/wp-config.php* на компьютере.
![Img-7](https://imgur.com/6LYcudt.png)
![Img-8](https://imgur.com/BWtqUpJ.png)
![Img-9](https://imgur.com/oloqevz.png)
![Img-10](https://imgur.com/PdY1Ya2.png)
#### Добавим файл конфигурации WordPress в Dockerfile
Добавляем в файл Dockerfile следующие строки:
 ```shell
# copy the configuration file for wordpress from files/ directory
COPY files/wp-config.php /var/www/html/wordpress/wp-config.php
```
#### Запуск и тестирование
Пересобираем образ контейнера с именем *apache2-php-mariadb* и запускаем контейнер *apache2-php-mariadb* из образа *apache2-php-mariadb.* Проверяем работу сайта WordPress.
![Img-11](https://imgur.com/zMu5ijj.png)
![Img-12](https://imgur.com/AzpyZ4L.png)
![Img-13](https://imgur.com/IOEhhFr.png)
### Вывод
В ходе выполнения работы был создан контейнер с именем apache2-php-mariadb, который включает в себя установку и настройку Apache, PHP и MariaDB для работы с WordPress. Была сделана настройка монтируемого тома для хранения данных MariaDB, что позволит обеспечить сохранность данных при перезапуске контейнера, был настроен веб-сервер Apache с поддержкой PHP, установлен и настроен WordPress, а также запущен сайт WordPress.
### Ответы на вопросы
1. Были изменены следующие файлы конфигурации:
- ```files/apache2/000-default.conf ```– настройка виртуального хоста Apache.
- ```files/apache2/apache2.conf``` – общие настройки Apache.
- ```files/php/php.ini``` – конфигурация PHP.
- ```files/mariadb/50-server.cnf``` – конфигурация MariaDB.
- ```files/supervisor/supervisord.conf``` – настройки процесс-менеджера Supervisor.
- ```files/wp-config.php``` – конфигурационный файл WordPress.
2. Инструкция ```DirectoryIndex``` задаёт файл, который ```Apache``` будет загружать по умолчанию при обращении к каталогу.
3. Файл ```wp-config.php``` содержит основные настройки WordPress, без которых он не сможет работать:
- Подключение к базе данных, то есть имя БД, логин, пароль, хост.
- Префикс таблиц базы данных.
- Ключи безопасности для шифрования данных.
- Различные параметры конфигурации WordPress.
4. Параметр ```post_max_size``` определяет максимальный размер данных, которые можно отправить в одном *POST*-запросе.
5. Недостатки:
- Нет разделения сервисов – Apache, PHP и MariaDB работают в одном контейнере, лучше разнести их на отдельные контейнеры;
- Отсутствует логирование и мониторинг – MariaDB и Apache не настроены на запись логов в доступное место.
