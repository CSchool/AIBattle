<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <title>AIBattle - @yield('title')</title>
    </head>

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

        <script src="{{ URL::asset('js/jquery-1.12.3.min.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>

    </body>
</html>