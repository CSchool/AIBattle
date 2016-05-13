@extends('layouts.tournamentLayout')

@section('title', 'Tournament #' . $tournament->id)
@section('tournamentTitle', $tournament->name)

@section('tournamentContent')

    <div class="panel panel-info">
        <div class="panel-heading">
            Game: <strong>{{ $tournament->game->name }}</strong>
        </div>
        <div class="panel-body">
            <p><strong>Tournament description:</strong></p>
            {!! $tournament->description !!}

            <p><strong>Game description:</strong></p>
            {!! $tournament->game->description !!}

            <p><strong>Attachments:</strong></p>
            Coming soon!
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#informationLink').addClass('active');
        });
    </script>
@endsection

