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
        <script src="{{ URL::asset('js/plotly-latest.min.js') }}"></script>


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
                top: 55px;
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

        .list-group.list-group-root {
            padding: 0;
            /*overflow: hidden;*/
        }

        .list-group-item {
            padding: 5px 15px;
        }

        .list-group.list-group-root .list-group {
            margin-bottom: 0;
        }

        .list-group.list-group-root .list-group-item {
            border-radius: 0;
            border-width: 1px 0 0 0;
        }

        .list-group.list-group-root > .list-group-item:first-child {
            border-top-width: 0;
        }

        .list-group.list-group-root > .list-group > .list-group-item {
            padding-left: 30px;
        }

        .list-group.list-group-root > .list-group > .list-group > .list-group-item {
            padding-left: 45px;
        }

        .noBorder {
            border: none
        }

        .displayBlock {
            display: none;
        }
    </style>

    <body id="AIBattleLayout">
        <div class="container-fluid">
            <div class="text-center"><h2>AIBattle v2 -  CSchool, 2016</h2></div>

            <div class="col-sm-4 col-md-3 sidebar">
                <div role="navigation">

                    <ul class="list-group list-group-root well">

                        <li class="list-group-item">
                            <a href="{{ url('/') }}">
                                <h4>AIBattle</h4>
                            </a>
                        </li>

                        <!-- change user -->
                        <li class="list-group-item dropdown">
                            <a href="#" class="tree-toggle">{{ $globalCurrentUser->username or trans('layouts/mainLayout.anonymous') }} <span class="caret"></span></a>
                            <ul class="list-group tree displayBlock">
                                @if (!isset($globalCurrentUser))
                                    <li class="list-group-item noBorder"><a href="{{url('auth/login')}}">{{ trans('layouts/mainLayout.login') }}</a></li>
                                    <li class="list-group-item noBorder"><a href="{{url('auth/register')}}">{{ trans('layouts/mainLayout.register') }}</a></li>
                                @else
                                    <li class="list-group-item noBorder"><a href="{{url('/userProfile')}}">{{ trans('layouts/mainLayout.userProfile') }}</a></li>
                                    <li class="list-group-item noBorder"><a href="{{url('auth/logout')}}">{{ trans('layouts/mainLayout.logout') }}</a></li>
                                @endif
                            </ul>
                        </li>

                        <!-- tournaments -->

                        <li class="list-group-item">
                            <a href="#" class="tree-toggle">{{ trans('layouts/mainLayout.tournaments') }} <span class="caret"></span></a>
                            @section('tournamentSidebar')
                                <ul class="list-group tree">
                                        @foreach ($globalCurrentTournaments as $tournament)
                                            <li class="list-group-item noBorder">
                                                <a href="{{ url('/tournaments', [$tournament->id]) }}" >{{ $tournament->name }}</a>
                                            </li>
                                        @endforeach
                                </ul>
                            @show

                        </li>

                        @if (isset($globalCurrentUser) && $globalCurrentUser->isAdmin())
                        <li class="list-group-item">
                            @section('adminPanelSidebar')
                                <a href="{{ url('adminPanel') }}">
                                    <div class="text-danger"><span class="glyphicon glyphicon-wrench"></span> {{ trans('layouts/mainLayout.adminPanel') }}</div>
                                </a>
                            @show
                        </li>
                        @endif

                        <li class="list-group-item dropdown">
                            <a href="#" class="tree-toggle">{{ trans('layouts/mainLayout.language') }} <span class="caret"></span></a>
                            <ul class="list-group tree displayBlock">
                                <li class="list-group-item noBorder"><a href="{{ url('/locale/en') }}">{{ trans('layouts/mainLayout.enLanguage') }}</a></li>
                                <li class="list-group-item noBorder"><a href="{{ url('/locale/ru') }}">{{ trans('layouts/mainLayout.rusLanguage') }}</a></li>
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

            });

        </script>
    </body>
</html>