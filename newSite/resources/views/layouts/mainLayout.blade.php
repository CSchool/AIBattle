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
        <link href="{{ URL::asset('css/prism-line-numbers.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/datatables.min.css') }}" rel="stylesheet">

        <script src="{{ URL::asset('js/jquery-1.12.3.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>

        <script src="{{ URL::asset('js/prism.js') }}"></script>
        <script src="{{ URL::asset('js/prism-line-numbers.min.js') }}"></script>

        <script src="{{ URL::asset('js/datatables.min.js') }}"></script>

        <title>AIBattle - @yield('title')</title>
    </head>

    <!-- temp fix for footer -->
    <style>
        .sidebar {
            display: none;
        }

        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 15px;
                bottom: 0;
                left: 0;
                z-index: 1000;
                display: block;
                padding: 20px;
                overflow-x: hidden;
                overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
                background-color: #f5f5f5;
                border-right: 1px solid #eee;
            }
        }

        .main {
            padding: 15px;
        }

        @media (min-width: 768px) {
            .main {
                padding-right: 30px;
            }
        }
        .main .page-header {
            margin-top: 0;
        }
    </style>

    <body id="AIBattleLayout">

        <div class="container-fluid">
            <div class="col-sm-4 col-md-3 sidebar">
                <div role="navigation">
                    <ul class="list-group">

                        <!-- header -->
                        <li class="list-group-item">
                            <a href="{{ url('/') }}">
                                <h4>AIBattle</h4>
                            </a>
                        </li>

                        <!-- strategies -->
                        <li class="list-group-item">
                            <a href="#" class="nav tree-toggle">{{ trans('layouts/mainLayout.tournaments') }} <span class="caret"></span></a>
                            <ul class="nav tree">
                                @foreach ($globalCurrentTournaments as $tournament)
                                    <li>
                                        <a href="#" class="tree-toggle">{{ $tournament->name }} <span class="caret"></span></a>
                                        <ul class="tree">
                                            <li class="">
                                                <a href="{{ url('/tournaments', [$tournament->id]) }}">
                                                    {{ trans('layouts/tournamentLayout.information') }}
                                                </a>
                                            </li>
                                            @if (Auth::user())
                                                <li class="">
                                                    <a href="{{ url('/tournaments', [$tournament->id, 'rounds']) }}">
                                                        {{ trans('layouts/tournamentLayout.rounds') }}
                                                    </a>
                                                </li>
                                            @endif
                                            <li class="">
                                                <a href="{{ url('/tournaments', [$tournament->id, 'strategies']) }}" id="strategiesLink">
                                                    {{ trans('layouts/tournamentLayout.strategies') }}
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="{{ url('/tournaments', [$tournament->id, 'training']) }}">
                                                    {{ trans('tournaments/strategies.showStrategiesTrainingLink') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <!-- adminPanel -->
                        @if (isset($globalCurrentUser) && $globalCurrentUser->isAdmin())
                            <li class="list-group-item">
                                <a href="#" class="nav tree-toggle">
                                    <div class="text-danger">
                                        <span class="glyphicon glyphicon-wrench"></span> {{ trans('layouts/mainLayout.adminPanel') }} <span class="caret"></span>
                                    </div>
                                </a>
                                <ul class="nav tree">

                                    <!-- general access -->
                                    <li>
                                        <a href="{{ url('adminPanel') }}" class="tree-toggle">{{ trans('adminPanel/main.generalLink') }}</a>
                                    </li>

                                    <!-- games -->
                                    <li>
                                        <a href="#" class="tree-toggle">{{ trans('adminPanel/main.gamesLink') }} <span class="caret"></span></a>
                                        <ul class="tree">
                                            @foreach($globalGamesAvailable as $game)
                                                <li>
                                                    <a href="#" class="tree-toggle">{{ $game->name }} <span class="caret"></span></a>
                                                    <ul class="tree">
                                                        <li>
                                                            <a href="{{ url('adminPanel/games', [$game->id]) }}">{{ trans('shared.description') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('adminPanel/games', [$game->id, 'attachments']) }}">{{ trans('adminPanel/main.attachmentsLink') }}</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('adminPanel/games', [$game->id, 'checkers']) }}">{{ trans('adminPanel/main.checkersLink') . '(NOT DONE)' }}</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <!-- tournaments -->
                                    <!-- TODO: make asset for equal chunk of code, which just different by variable - state of tournaments! -->
                                    <li>
                                        <a href="#" class="tree-toggle">{{ trans('adminPanel/main.tournamentsLink') }} <span class="caret"></span></a>
                                        <ul class="tree">
                                            @if (count($globalCurrentTournaments) > 0)
                                                <li>
                                                    <a href="#" class="tree-toggle">{{ trans('adminPanel/tournaments.sidebarRunning') }} <span class="caret"></span></a>
                                                    <ul class="tree">
                                                        @foreach($globalCurrentTournaments as $tournament)
                                                        <li>
                                                            <a href="#" class="tree-toggle">{{ $tournament->name }} <span class="caret"></span></a>
                                                            <ul class="tree">
                                                                <li>
                                                                    <a href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ trans('shared.description') }}</a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('adminPanel/tournaments', [$tournament->id, 'rounds']) }}">{{ trans('adminPanel/main.roundsLink') }}</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                            @if (count($globalPreparingTournaments) > 0)
                                                <li>
                                                    <a href="#" class="tree-toggle">{{ trans('adminPanel/tournaments.sidebarPreparing') }} <span class="caret"></span></a>
                                                    <ul class="tree">
                                                        @foreach($globalPreparingTournaments as $tournament)
                                                            <li>
                                                                <a href="#" class="tree-toggle">{{ $tournament->name }} <span class="caret"></span></a>
                                                                <ul class="tree">
                                                                    <li>
                                                                        <a href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ trans('shared.description') }}</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ url('adminPanel/tournaments', [$tournament->id, 'rounds']) }}">{{ trans('adminPanel/main.roundsLink') }}</a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                            @if (count($globalClosedTournaments) > 0)
                                                <li>
                                                    <a href="#" class="tree-toggle">{{ trans('adminPanel/tournaments.sidebarClosed') }} <span class="caret"></span></a>
                                                    <ul class="tree">
                                                        @foreach($globalClosedTournaments as $tournament)
                                                            <li>
                                                                <a href="#" class="tree-toggle">{{ $tournament->name }} <span class="caret"></span></a>
                                                                <ul class="tree">
                                                                    <li>
                                                                        <a href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ trans('shared.description') }}</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ url('adminPanel/tournaments', [$tournament->id, 'rounds']) }}">{{ trans('adminPanel/main.roundsLink') }}</a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>

                                    <!-- users -->
                                    <li>
                                        <a href="{{ url('adminPanel/users') }}" class="tree-toggle">{{ trans('adminPanel/main.usersLink') }}</a>
                                    </li>

                                    <!-- news -->
                                    <li>
                                        <a href="{{ url('adminPanel/news') }}" class="tree-toggle">{{ trans('adminPanel/main.newsLink') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- language -->
                        <li class="list-group-item dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-flag"></span> {{ trans('layouts/mainLayout.language') }}
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/locale/en') }}">{{ trans('layouts/mainLayout.enLanguage') }}</a></li>
                                <li><a href="{{ url('/locale/ru') }}">{{ trans('layouts/mainLayout.rusLanguage') }}</a></li>
                            </ul>
                        </li>

                        <!-- change user -->
                        <li class="list-group-item dropdown">
                            <a href="#" class="dropdown-toggle profilePadding" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-user"></span> {{ $globalCurrentUser->username or trans('layouts/mainLayout.anonymous') }}
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                @if (!isset($globalCurrentUser))
                                    <li><a href="{{url('auth/login')}}">{{ trans('layouts/mainLayout.login') }}</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{url('auth/register')}}">{{ trans('layouts/mainLayout.register') }}</a></li>
                                @else
                                    <li><a href="{{url('/userProfile')}}">{{ trans('layouts/mainLayout.userProfile') }}</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{url('auth/logout')}}">{{ trans('layouts/mainLayout.logout') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-8 col-sm-offset-3 col-md-9  main">
                <div class="col-lg-12 col-md-12">
                    @yield('content')
                </div>
            </div>
        </div>

        <script>
            $( document ).ready(function () {
                var treeToggle = $('.tree-toggle');

                treeToggle.click(function () {
                    $(this).parent().children('ul.tree').toggle(200);
                });

                treeToggle.each(function () {
                    $(this).parent().children('ul.tree').toggle(0);
                });
            });

        </script>
    </body>
</html>