<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/jquery-ui-1.10.4.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/prism.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/prism-line-numbers.css') }}" rel="stylesheet">

        <script src="{{ URL::asset('js/jquery-1.12.3.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('js/prism.js') }}"></script>
        <script src="{{ URL::asset('js/prism-line-numbers.min.js') }}"></script>


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
                                    <li><a href="{{url('/userProfile')}}">Profile</a></li>
                                    <li class="divider"></li>
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