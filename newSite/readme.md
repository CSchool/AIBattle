# AI Battle v.2

Попытка написать сайт при помощи framework'а [Laravel v5.2](https://laravel.com/).

## Что реализовано на данный момент?

* Регистрация и авторизация пользователей;
* Через database seed предустановлен пользователь admin (с паролем admin);
* Макет стартовой страницы, макет административной панели;
* Добавление/редактирование/удаленией новостей через административную панель;
* Отображение новостей на главной странице и возможность pagination (разбиение на несколько страниц).

Список будет обновляться по мере разработки!

## Как запустить и посмотреть?

На данный момент решение разрабатывается в виртуальном образе [Homestead](https://laravel.com/docs/5.2/homestead).
В данном образе представлено:

* Ubuntu 14.04
* Git
* PHP 7.0
* HHVM
* Nginx
* MySQL
* MariaDB
* Sqlite3
* Postgres
* Composer
* Node (With PM2, Bower, Grunt, and Gulp)
* Redis
* Memcached
* Beanstalkd

Текущий недостаток - относительно медленная работа сайта из браузера (отклик простых страниц ~500ms),
если виртуализироваться под Windows, если делать виртуализацию под Linux (или сделать настройку сразу под нативную систему), то такой проблемы не замечается.

Порядок установки:

1. Скачать [VirtualBox](https://www.virtualbox.org/) и [Vagrant](https://www.vagrantup.com/);
2. Установить сам виртуальный образ: в консоли ввести `vagrant box add laravel/homestead`;
3. Сделать инициализацию образа:
    + `git clone https://github.com/laravel/homestead.git Homestead` (необходимо сделать в директории, в которой будет производиться разработка)
    + `bash init.sh` (Linux) или выполнить `init.bat` (Windows);
    + Удостовериться, что в `~/.homestead` (Linux) / `C:\Users\{User}\.homestead` находится файл `Homestead.yaml` (!!!)
4. Сгенерировать ключи для ssh-доступа к образу: `ssh-keygen -t rsa -C "your@email.com"`
(для Winodws это можно выполнить при помощи `git-bash`) и указать их в файле конфигурации `Homestead.yaml`:

        authorize: {PATH_TO_KEYS}\id_rsa.pub

        keys:
            - {PATH_TO_KEYS}\id_rsa

5. Указать расшаренные папки (`map` - путь на хостовой машине, `to` - путь в образе) в файле конфигурации `Homestead.yaml`:

        folders:
            - map: {Path1}
              to: {Path2}

6. Настроить адрес, по которому будет слушать сайт (`map` - домен, `to` - корневая папка сервера):

        sites:
            - map: {Name} // homestead.app
              to: {Path3} // example - /home/vagrant/Code/Laravel/public

7. Добавить в файл с хостами упоминание о домене (`/etc/host` - Linux, `C:\Windows\System32\drivers\etc\hosts` - Windows)

        192.168.10.10  homestead.app

    Если вбить в браузер homestead.app, то можно проверить будет ли откликаться сайт

    (NB: К сайту можно так же подключиться через 8000 порт: 127.0.0.1:8000)

8. Скопировать проект из папки `newSite` и положить его в расшаренную папку

8. Запустить образ: `vagrant up` в папке Homestead из пункта 3

10. В папке с проектом произвести миграцию таблиц (т.е. занесение таблиц в БД) и seeding (заполнение таблиц значениями):

    `php artisan migrate`

    `php artisan db:seed`

11. Обратиться к сайту!