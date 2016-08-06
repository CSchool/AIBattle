@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.showRoundTitle', ['round' => $round->name]))
@section('APtitle', trans('adminPanel/rounds.showRoundTitle', ['round' => $round->name]))

@include('assets.adminPanel.tournamentsSidebar', ['tournament' => $tournament])

@section('APcontent')
    <div class="panel panel-primary">
        <div class="panel-heading">{{ trans('adminPanel/rounds.showRoundPanelHeading', ['round' => $round->name]) }}</div>
        <div class="panel-body">
            {{ Form::open() }}
            <div class="form-group">
                <p><strong>{{ trans('adminPanel/rounds.roundsName') }}:</strong> {{ $round->name }}</p>
            </div>

            <div class="form-group">
                <p><strong>{{ trans('shared.game') }}:</strong> <a href="{{ url('/adminPanel/games', [$tournament->game->id]) }}">{{ $tournament->game->name }}</a></p>
                <p><strong>{{ trans('shared.tournament') }}:</strong> <a href="{{ url('/adminPanel/tournaments', [$tournament->id]) }}">{{ $tournament->name }}</a></p>
                <p><strong>{{ trans('shared.checker') }}:</strong> <a href="{{ url('/adminPanel/checkers', [$checker->id]) }}">{{ $checker->name }}</a></p>
            </div>

            <div class="form-group">
                @if ($round->previousRound == -1)
                    <p><strong>{{ trans('adminPanel/rounds.roundsPrev') }}:</strong> {{ $prevRoundName }}</p>
                @else
                    <p><strong>{{ trans('adminPanel/rounds.roundsPrev') }}:</strong> <a href="{{ url('adminPanel/tournaments/' . $round->tournament_id . '/rounds/' . $round->previousRound) }}">{{ $prevRoundName }}</a></p>
                @endif

                <p><strong>{{ trans('shared.date') }}:</strong> {{ $round->date }}</p>
                <p><strong>{{ trans('adminPanel/rounds.createRoundSeed') }}:</strong> {{ $round->seed }}</p>
            </div>

            <div class="form-group">
                <p><strong>{{ trans('adminPanel/rounds.roundsState') }}: </strong>
                    @if ($round->visible == 1)
                        {{ trans('adminPanel/rounds.showRoundVisibleState') }}
                    @else
                        {{ trans('adminPanel/rounds.showRoundInvisibleState') }}
                    @endif
                </p>
            </div>

        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/tournaments', [$round->tournament->id, 'rounds']),
                'resultsLink' => url('adminPanel/tournaments', [$round->tournament->id, 'rounds', $round->id, 'results']),
                'specialMode' => 'rounds',
                'isVisible' => $round->visible,
            ])
        </div>

        {{ Form::close() }}
    </div>
@endsection