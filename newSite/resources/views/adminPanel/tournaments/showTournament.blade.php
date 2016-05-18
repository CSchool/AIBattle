@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/tournaments.showTournamentTitle'))
@section('APtitle', trans('adminPanel/tournaments.showTournamentHeading'))

@section('APcontent')
    <div class="panel panel-primary">
        <div class="panel-heading"> {{ trans('adminPanel/tournaments.showTournamentPanelHeading', ['id' => $tournament->id]) }}</div>
        <div class="panel-body">
            <p><strong>{{ trans('adminPanel/tournaments.tournamentName') }}: </strong> {{ $tournament->name }} </p>

            <div class="form-group">
                <label for="description">{{ trans('adminPanel/tournaments.tournamentDescription') }}:</label>
                <textarea class="form-control" name="description" id="description" disabled>{{ $tournament->description }}</textarea>
            </div>

            <p><strong>{{ trans('shared.game') }}: </strong> <a href="{{ url('adminPanel/games', [$tournament->getGame()->id]) }}">{{ $tournament->getGame()->name }}</a> </p>

            <p><strong>{{ trans('adminPanel/tournaments.tournamentDefaultChecker') }}: </strong> <a href="{{ url('adminPanel/checkers', [$tournament->getChecker()->id]) }}">{{ $tournament->getChecker()->name }}</a> </p>

            <p><strong>{{ trans('adminPanel/tournaments.tournamentState') }}: </strong> {{ trans('adminPanel/tournaments.tournamentState' . ucfirst($tournament->state))  }} </p>
        </div>
        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/tournaments'),
                'editLink' => url('adminPanel/tournaments/edit', [$tournament->id]),
                'editName' => trans('adminPanel/tournaments.showTournamentEditRedirectFooter')]
            )
        </div>
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#description',
            menubar: false,
            readonly: true
        });
    </script>
@endsection