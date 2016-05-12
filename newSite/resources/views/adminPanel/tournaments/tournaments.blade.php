@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Tournaments')
@section('APtitle', 'Administration Panel - Tournaments')

<style>
    .bottomHref {
        margin-bottom: 30px;
    }
</style>

@section('APcontent')

    @if (count($runningTournaments) > 0 || count($preparingTournaments) > 0 || count($closedTournaments) > 0)

        <div class="text-center">
            <div class="row"><a href="{{ url('/adminPanel/tournaments/create') }}" class="btn btn-success btn-lg" role="button">Create tournament</a></div>
            <br>
        </div>

        @if (count($runningTournaments) > 0)
            <div class="panel panel-success">
                <div class="panel-heading">Running ({{ $runningCount }})</div>
                <div class="panel-body">
                    @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $runningTournaments, 'mode' => "part"])
                </div>
                <div class="panel-footer">
                    <a href="{{ url('adminPanel/tournaments/show/running') }}" class="btn btn-success btn-lg">View other</a>
                </div>
            </div>
        @endif

        @if (count($preparingTournaments) > 0)
            <div class="panel panel-warning">
                <div class="panel-heading">Preparing ({{ $preparingCount }})</div>
                <div class="panel-body">
                    @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $preparingTournaments, 'mode' => "part"])
                </div>
                <div class="panel-footer">
                    <a href="{{ url('adminPanel/tournaments/show/preparing') }}" class="btn btn-success btn-lg">View other</a>
                </div>
            </div>
        @endif

        @if (count($closedTournaments) > 0)
            <div class="panel panel-info">
                <div class="panel-heading">Closed ({{ $closedCount }})</div>
                <div class="panel-body">
                    @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $closedTournaments, 'mode' => "part"])
                </div>
                <div class="panel-footer">
                    <a href="{{ url('adminPanel/tournaments/show/closed') }}" class="btn btn-success btn-lg">View other</a>
                </div>
            </div>
        @endif

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>Warning!</h3></div>
            <div class="row"><p>There are no tournaments at all! Do you want to create tournament?</p></div>
            <div class="row"><a href="{{ url('/adminPanel/tournaments/create') }}" class="btn btn-success btn-lg" role="button">Create tournament</a></div>
        </div>
    @endif

    <div class="text-center">
        <div class="row bottomHref">
            <a href="{{ url('/adminPanel') }}" class="btn btn-primary btn-lg " role="button">Back to main menu</a>
        </div>
    </div>
@endsection