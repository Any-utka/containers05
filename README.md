# Лаборатораня работа №5 "Запуск сайта в контейнере"
## Цель работы:  
Сделать образ контейнера для запуска веб-сайта на базе Apache HTTP Server + PHP (mod_php) + MariaDB.
## Задание:  
Создать Dockerfile для сборки образа контейнера, который будет содержать веб-сайт на базе Apache HTTP Server + PHP (mod_php) + MariaDB. База данных MariaDB должна храниться в монтируемом томе. Сервер должен быть доступен по порту 8000.
Установить сайт WordPress. Проверить работоспособность сайта.
### Описание выполнения работы:
1. Создаем репозиторий ```containers05``` и клонируем его на компьютер. Для этого используем команду ```git clone https://github.com/Any-utka/containers05.git``` и переходим в нашу склонированную папку.
2. Делаем извлечение конфигурационных файлов apache2, php, mariadb из контейнера, для этого:

   
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
 
