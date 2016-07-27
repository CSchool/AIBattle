@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/main.title', ['id' => $tournament->id]))
@section('tournamentTitle', $tournament->name)

@section('tournamentContent')
    @include('assets.roundResultContent', ['round' => $round])

    <script>
        $(document).ready(function () {
            $('#roundsLink').addClass('active');
        });
    </script>
@endsection