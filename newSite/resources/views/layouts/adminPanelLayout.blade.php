@extends('layouts.mainLayout')

@section('tournamentSidebar')
    <ul class="list-group tree displayBlock">
        @foreach ($globalCurrentTournaments as $tournament)
            <li class="list-group-item noBorder">
                <a href="{{ url('/tournaments', [$tournament->id]) }}" >{{ $tournament->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection

@section('adminPanelSidebar')
    <a href="#" class="tree-toggle">
        <div class="text-danger">
            <span class="glyphicon glyphicon-wrench"></span> {{ trans('layouts/mainLayout.adminPanel') }} <span class="caret"></span>
        </div>
    </a>
    <ul class="list-group tree">

        <!-- main page -->
        <li class="list-group-item noBorder">
            <a href="{{ url('adminPanel') }}">{{ trans('adminPanel/main.generalLink') }}</a>
        </li>

        <!--games -->
        <li class="list-group-item noBorder">
            <a href="#" class="tree-toggle">{{ trans('adminPanel/main.gamesLink') }} <span class="caret"></span></a>
            @section('adminPanelGames')
                <ul class="list-group tree">
                    <li class="list-group-item noBorder">
                        <a href="{{ url('adminPanel/games') }}">{{ trans('adminPanel/main.generalLink') }}</a>
                    </li>
                    @foreach($globalGamesAvailable as $game)
                        <li class="list-group-item noBorder">
                            <a href="{{ url('adminPanel/games', [$game->id]) }}">{{ $game->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @show
        </li>

        <!-- tournaments -->
        <li class="list-group-item noBorder">
            <a href="#" class="tree-toggle">{{ trans('adminPanel/main.tournamentsLink') }} <span class="caret"></span></a>

            @section('adminPanelTournaments')
                <ul class="list-group tree">
                    <li class="list-group-item noBorder">
                        <a href="{{ url('adminPanel/tournaments') }}" class="tree-toggle">{{ trans('adminPanel/main.generalLink') }}</a>
                    </li>
                    @if (count($globalCurrentTournaments) > 0)
                        <li class="list-group-item noBorder"><i>{{ trans('adminPanel/tournaments.sidebarRunning') }}</i></li>
                        @foreach($globalCurrentTournaments as $tournament)
                            <li class="list-group-item noBorder">
                                <a class="btn btn-success btn-block" style="white-space: normal;" href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ $tournament->name }} </a>
                            </li>
                        @endforeach
                    @endif

                    @if (count($globalPreparingTournaments) > 0)
                        <li class="list-group-item noBorder"><i>{{ trans('adminPanel/tournaments.sidebarPreparing') }}</i></li>
                        @foreach($globalPreparingTournaments as $tournament)
                            <li class="list-group-item noBorder">
                                <a class="btn btn-info btn-block" style="white-space: normal;" href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ $tournament->name }} </a>
                            </li>
                        @endforeach
                    @endif

                    @if (count($globalClosedTournaments) > 0)
                        <li class="list-group-item noBorder"><i>{{ trans('adminPanel/tournaments.sidebarClosed') }}</i></li>
                        @foreach($globalClosedTournaments as $tournament)
                            <li class="list-group-item noBorder">
                                <a class="btn btn-info btn-block" style="white-space: normal;" href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ $tournament->name }} </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            @show
        </li>

        <li class="list-group-item noBorder">
            <a href="{{ url('adminPanel/users') }}" class="">{{ trans('adminPanel/main.usersLink') }}</a>
        </li>

        <li class="list-group-item noBorder">
            <a href="{{ url('adminPanel/news') }}" class="">{{ trans('adminPanel/main.newsLink') }}</a>
        </li>
    </ul>
@endsection

@section('content')

    <div class="page-header text-center">
        <h1>@yield('APtitle')</h1>
    </div>


    @yield('APcontent')
@endsection