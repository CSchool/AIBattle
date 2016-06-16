@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/tournaments.tournamentsTitle'))
@section('APtitle', trans('adminPanel/tournaments.tournamentsHeading'))

@section('APcontent')

    @if (count($runningTournaments) > 0 || count($preparingTournaments) > 0 || count($closedTournaments) > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/tournaments.tournamentsCreate') }}
                </a>
            </div>
            <br>
        </div>

        @if (count($runningTournaments) > 0)
            <div class="panel panel-success">
                <div class="panel-heading">{{ trans('adminPanel/tournaments.tournamentsRunning', ['count' => $runningCount]) }}</div>
                <div class="panel-body">
                    @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $runningTournaments, 'mode' => "part"])
                </div>
                <div class="panel-footer">
                    <a href="{{ url('adminPanel/tournaments/show/running') }}" class="btn btn-success btn-lg">
                        {{ trans('adminPanel/tournaments.tournamentsViewOther') }}
                    </a>
                </div>
            </div>
        @endif

        @if (count($preparingTournaments) > 0)
            <div class="panel panel-warning">
                <div class="panel-heading">{{ trans('adminPanel/tournaments.tournamentsPreparing', ['count' => $preparingCount]) }}</div>
                <div class="panel-body">
                    @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $preparingTournaments, 'mode' => "part"])
                </div>
                <div class="panel-footer">
                    <a href="{{ url('adminPanel/tournaments/show/preparing') }}" class="btn btn-success btn-lg">
                        {{ trans('adminPanel/tournaments.tournamentsViewOther') }}
                    </a>
                </div>
            </div>
        @endif

        @if (count($closedTournaments) > 0)
            <div class="panel panel-info">
                <div class="panel-heading">{{ trans('adminPanel/tournaments.tournamentsClosed', ['count' => $closedCount]) }}</div>
                <div class="panel-body">
                    @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $closedTournaments, 'mode' => "part"])
                </div>
                <div class="panel-footer">
                    <a href="{{ url('adminPanel/tournaments/show/closed') }}" class="btn btn-success btn-lg">
                        {{ trans('adminPanel/tournaments.tournamentsViewOther') }}
                    </a>
                </div>
            </div>
        @endif

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/tournaments.tournamentsWarning') }}</h3></div>
            <div class="row"><p>{{ trans('adminPanel/tournaments.tournamentsWarningMessage') }}</p></div>
            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/tournaments.tournamentsCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection