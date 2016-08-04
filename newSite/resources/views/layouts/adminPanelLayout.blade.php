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
            <ul class="list-group tree displayBlock">
                <li class="list-group-item noBorder">
                    <a href="{{ url('adminPanel/games') }}">{{ trans('adminPanel/main.generalLink') }}</a>
                </li>
                @foreach($globalGamesAvailable as $game)
                    <li class="list-group-item noBorder">
                        <a href="#" class="tree-toggle">{{ $game->name }} <span class="caret"></span></a>
                        <ul class="list-group tree displayBlock">
                            <li class="list-group-item noBorder">
                                <a href="{{ url('adminPanel/games', [$game->id]) }}">{{ trans('shared.description') }}</a>
                            </li>
                            <li class="list-group-item noBorder">
                                <a href="{{ url('adminPanel/games', [$game->id, 'attachments']) }}">{{ trans('adminPanel/main.attachmentsLink') }}</a>
                            </li>
                            <li class="list-group-item noBorder">
                                <a href="{{ url('adminPanel/games', [$game->id, 'checkers']) }}">{{ trans('adminPanel/main.checkersLink')  }}</a>
                            </li>
                        </ul>
                    </li>
                @endforeach
            </ul>
        </li>

        <!-- tournaments -->
        <li class="list-group-item noBorder">
            <a href="#" class="tree-toggle">{{ trans('adminPanel/main.tournamentsLink') }} <span class="caret"></span></a>
            <ul class="list-group tree displayBlock">
                <li class="list-group-item noBorder">
                    <a href="{{ url('adminPanel/tournaments') }}">{{ trans('adminPanel/main.generalLink') }}</a>
                </li>
                @if (count($globalCurrentTournaments) > 0)
                    @include('assets.sidebarAdminTournaments', [
                        'text' => trans('adminPanel/tournaments.sidebarRunning'),
                        'tournaments' => $globalCurrentTournaments
                    ])
                @endif

                @if (count($globalPreparingTournaments) > 0)
                    @include('assets.sidebarAdminTournaments', [
                        'text' => trans('adminPanel/tournaments.sidebarPreparing'),
                        'tournaments' => $globalPreparingTournaments
                    ])
                @endif

                @if (count($globalClosedTournaments) > 0)
                    @include('assets.sidebarAdminTournaments', [
                        'text' => trans('adminPanel/tournaments.sidebarClosed'),
                        'tournaments' => $globalClosedTournaments
                    ])
                @endif
            </ul>
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