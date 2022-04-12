Installation And Configuration

1.	Install prerequisites software
* GIT - https://git-scm.com/downloads
* XAMP (MYSQL and PHP) - https://www.apachefriends.org/download.html
* Composer - https://getcomposer.org
* Laravel - https://laravel.com/docs/master/installation

2.	Download source code from Github by executing command from console
```console
git clone https://github.com/Mr-Kranarong/osml.git
```
3.	Enter source code directory by executing command from console
```console
cd osml
```
4.	Download dependency libraries by executing command from console
```console
composer install
```
5.  Start MYSQL Database. Go to phpMyAdmin and create a database
![image](https://user-images.githubusercontent.com/55760976/162913186-02787ed1-801f-4c2b-b140-c42e2b822ce3.png)
6.	Go to project source code directory and rename “.env.example” to “.env” and edit config.
![image](https://user-images.githubusercontent.com/55760976/162914437-971a3dce-3c38-4d7f-b209-825498d56f51.png)
7.	Generate unique key for Laravel project by executing command
```console
php artisan key:generate
```
8.	Edit database name in “database.sql” then import data via phpMyAdmin
9.	Run the project by executing command
```console
php artisan serve
```
