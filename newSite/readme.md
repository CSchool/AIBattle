# AI Battle v.2

Попытка написать сайт при помощи framework'а [Laravel v5.2](https://laravel.com/).

## Что реализовано на данный момент?

* Регистрация и авторизация пользователей;
* Через database seed предустановлен пользователь admin (с паролем admin);
* Макет стартовой страницы, макет административной панели;
* Добавление/редактирование/удаленией новостей через административную панель;
* Отображение новостей на главной странице и возможность pagination (разбиение на несколько страниц).
* Профиль пользователя (изменение имени/фамилии/отчества/описания) + просмотр пользователей через администраторскую панель (с возможным изменением всех атрибутов, в том числе и группы пользователя)
* Добавление, редактирование и удаление игр в администраторской панели.
* Добавление, редактирование и удаление чекеров (тестировщиков) в администраторской панели.
* Компиляция чекеров

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

## Основные моменты и положения в коде

### Migrations (Миграции)

Находятся в папке `database/migrations`. Задают структуру таблиц, которые будут пользоваться в приложении.
Создаются при помощи команды `php artisan migrate:make {migrationName} --create={tableName}`, где `{migrationName}` - название миграции, `{tableName}` - название таблицы, которая будет создаваться в миграции.
После выполнения команды в указанной папке появляется файл, в имене которого указывается время создания + `{migrationName}`, таким образом, разрешаются зависимости таблиц.

Пример миграции:

    class CreateUsersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 30);
                $table->string('password');
                $table->string('group', 12)->defalut('user');

                $table->string('name', 32)->nullable();
                $table->string('surname', 32)->nullable();
                $table->string('patronymic', 32)->nullable();
                $table->text('description')->nullable();


                $table->rememberToken();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::drop('users');
        }
    }

Синтаксис позволяет не писать руками запрос, чей синтаксис разнится от различных версий СУБД (однако если хочется, то фреймворк предоставляет такую возможность).

Пример foreign keys:

    public function up()
        {
            Schema::create('rounds', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->integer('game')->index()->unsigned();
                $table->integer('tournament')->unsigned();
                $table->integer('checkers')->unsigned();

                $table->integer('previousRound')->default(-1);
                $table->string('name', 64);
                $table->dateTime('date');

                $table->boolean('visible')->default(false);
                $table->integer('seed')->default(false);

                $table->foreign('game')->references('id')->on('games')->onDelete('cascade');
                $table->foreign('tournament')->references('id')->on('tournaments')->onDelete('cascade');
                $table->foreign('checkers')->references('id')->on('checkers')->onDelete('cascade');
            });
        }

### Seeds

Находятся в папке `database/seeds`. Позволяют после миграции вставять некоторые значения в таблицы.

Пример:

    class UsersTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            //
            DB::table('users')->insert([
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'group' => 'admin',
            ]);
        }
    }

### Models (модели)

Находятся в папке `app` (возможно, стоит перенсти в папку Models?). Олицетворяют собой объект из таблицы.

Пример использования (взят с сайта):

    $flights = Flight::where('active', 1)
                   ->orderBy('name', 'desc')
                   ->take(10)
                   ->get();

Пример собственной статической функции:

    private static function isInGroup($group)
    {
         $currentUser = Auth::user();

         if (!isset($currentUser))
             return false;

         $users = User::where('group', '=', $group)->get();

         if (!isset($users))
             return false;

         return $users->contains($currentUser);
     }

    public static function isAdmin()
    {
        $currentUser = Auth::user();

        if (!isset($currentUser))
            return false;

        return User::isInGroup('admin');
    }

Внесение новой записи в таблицу:

    $news = new News();

    $news->header = $request->input('title');
    $news->text = $request->input('newsText');
    $news->date = Carbon::createFromFormat('d/m/Y', $request->input('datetimepicker'));

    $news->save();

(`Carbon` - класс для работы с датами)

Обновление определенной записи:

    $news = News::find($request->input('newsId'));

    if (isset($news)) {
        $news->header = $request->input('title');
        $news->text = $request->input('newsText');
        $news->date = Carbon::createFromFormat('d/m/Y', $request->input('datetimepicker'));

        $news->save();

        return redirect('adminPanel/news');
    }
    else
        abort(404);

Удаление записи:

    $news = News::find($request->input('newsId'));
    if (isset($news))
        $news->delete();



### Routes (они же маршруты)

Находятся в файле `app/Http/routes.php`. Сопоставляют тип запроса (GET, POST и свои кастомные PUT/DELETE), путь, по которому обращается пользователь
и метод контроллера, который вызывается в данном случае.

Пример маршрутов:

    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');

Первый маршрут обрабатывает GET запрос по пути `auth/register` и вызывает `Auth\AuthController@getRegister`.
Второй маршрут обрабатывает POST запрос по этому же пути и вызывает другой контроллер (в данном случае обрабатывается данные с формы).

Так же существует возможность обработки групп запросов:

    // Admin panel routes
    Route::group(['prefix' => 'adminPanel', 'middleware' => 'auth.admin'], function() {
        Route::get('/', 'AdminPanel\MainController@showView');

        Route::group(['prefix' => 'news'], function() {
            Route::get('/', 'AdminPanel\NewsController@showNews');
            Route::get('/create', 'AdminPanel\NewsController@showCreateNewsForm');
            Route::post('/create', 'AdminPanel\NewsController@createNews');
            Route::get('/edit/{id}', 'AdminPanel\NewsController@getEditNews');
            Route::post('/edit/{id}', 'AdminPanel\NewsController@editNews');
        });

    });

 Если путь начинается с `adminPanel`, то необходимо запустить маршруты, которые находятся в анонимной функции.
 В параметре `middleware` можно указать реакцию, если пользователь перешел по пути, которые начинается с `adminPanel`: в данном случае будет происходить проверка пользователя на принадлежность к администраторам.

 В маршруты можно передавать параметры и обрабатывать их в контроллерах: `Route::get('/edit/{id}', 'AdminPanel\NewsController@getEditNews');`.

### Controllers (Контроллеры)

Находятся в папке `app/Http/Controlles`. Позволяют обрабатывать запросы и реализовывают логику приложения.

Пример:

    class MainController extends Controller
    {
        //
        public function showView()
        {
            return view('adminPanel/main', ['user' => Auth::user()]);
        }
    }

В данном примере контроллер возвращает представление - `view` с параметром `user`, в котором хранится текущий залогиненный пользователь.

В контроллерах можно проверять данные из форм:

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:32',
            'password' => 'required|min:6|confirmed',
        ]);
    }

Поле `username` необходимо для валиадции формы, и вмещает в себя максимум 32 символа.
Поле `password` также необходимо для валидации, должно содержать не менее 6 символов, и должно совпадать с полем `password_confirmed`.

Если что-то пошло не так, можно сделать редирект на страницу с ошибкой:

    public function getEditNews($id) {
        $news = News::find($id);

        if (isset($news))
            return view('adminPanel/editNews', ['news' => $news]);
        else
            abort(404);
    }

`$news = News::find($id)` - поиск новости с определенным `id`.


### Views (Представления)

Находятся в папке `resources/views`. Предназначены вывода html-странички пользователю.

Пример (дочерний шаблон):

    @extends('layouts.mainLayout')

    @section('title', 'Main page')

    @section('content')
        <div class="container">
            <div class="page-header text-center">
                <h1>Hello, world!</h1>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems ...<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                @foreach($news as $element)
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ $element->header }} ({{ $element->date->format('d/m/Y') }})</div>
                        <div class="panel-body">
                            {!! $element->text !!}
                        </div>
                        <div class="panel-footer">
                            <div class="text-right">Comments (0)</div>
                        </div>
                    </div>
                @endforeach

                {!! $news->render() !!}
            </div>

        </div>
    @endsection

* `@extends` - расширение файла-шаблона;
* `@include` - вставка файла-шаблона;
* `@section` - вставка контента в родительский файл-шаблон;
* ` @if (...) ... @endif`, ` @foreach(...) ... @endforeach` - макросы обработки;
* `{{ ... }}` - выполнение выражения, обычно используется для вывода значений переменных;
* `{! ... !}` - выполнение выражения без экранирования

Переменные передается в контроллерах. Переменная $error - установлена для страниц в любом случае.


Пример (родительский шаблон):

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
            <link href="{{ URL::asset('css/jquery-ui-1.10.4.min.css') }}" rel="stylesheet">

            <script src="{{ URL::asset('js/jquery-1.12.3.min.js') }}"></script>
            <script src="{{ URL::asset('js/jquery-ui.min.js') }}"></script>
            <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>


            <title>AIBattle - @yield('title')</title>
        </head>

        <!-- temp fix for footer -->
        <style>
            html {
                position: relative;
                min-height: 100%;
            }
            body {
                /* Margin bottom by footer height */
                margin-bottom: 60px;
            }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                /* Set the fixed height of the footer here */
                height: 60px;
                background-color: #f5f5f5;
            }
        </style>

        <body id="AIBattleLayout">

            <nav class="navbar navbar-default navbar-static-top navbar-inverse" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="{{ url('/') }}" class="navbar-brand">AIBattle</a>
                    </div>

                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            @if (isset($user) && $user->isAdmin())
                                <li>
                                    <a href="{{ url('adminPanel') }}">
                                        <div class="text-danger"><span class="glyphicon glyphicon-wrench"></span> Control panel</div>
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle profilePadding" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-user"></span> {{ $user->username or 'default' }}
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    @if (!isset($user))
                                        <li><a href="{{url('auth/login')}}">Enter</a></li>
                                        <li class="divider"></li>
                                        <li><a href="{{url('auth/register')}}">Register</a></li>
                                    @else
                                        <li><a href="{{url('auth/logout')}}">Logout</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            @yield('content')

            <div class="footer">
                <div class="text-center">
                    AIBattle - соревнования по созданию искусственного интеллекта<br>
                    Летняя Компютерная Школа "КЭШ", 2014 - 2016, Великий Новгород
                </div>
            </div>
        </body>
    </html>


`@yield('content')`, `@yield('title')`  - место в шаблоне, которые будут заполнять потомки.

Пример форм:

    <div class="panel panel-primary">
        <div class="panel-heading">Edit news # {{ $news->id }}</div>
        <div class="panel-body">
            {{ Form::open() }}

            {{ Form::hidden('newsId', $news->id ) }}

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $news->header }}" />
            </div>

            <div class="form-group">
                <label for="newsText">Text:</label>
                <textarea class="form-control" name="newsText" id="newsText">{{ $news->text }}</textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">Date:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker" value="{{ $news->date->format('d/m/Y') }}" />
                </div>
            </div>

            <div class="btn-group btn-group-lg">
                <button type="submit" name="update" class="btn btn-lg btn-success btn-block">Update news</button>
                <button type="submit" name="delete" class="btn btn-lg btn-success btn-block">Remove news</button>
            </div>

            {{ Form::close() }}
        </div>
    </div>

При `Form::open()` создается CSRF token (устанавливается рандомно при вхождении пользователя на сайт) для всех форм (тем самым затрудняется  Сross Site Request Forgery - Межсайтовая подделка запроса)