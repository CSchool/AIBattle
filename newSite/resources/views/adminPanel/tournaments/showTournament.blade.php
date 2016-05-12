@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Shows tournament')
@section('APtitle', 'Administration Panel - Tournament')

@section('APcontent')
    <div class="panel panel-primary">
        <div class="panel-heading">Tournament # {{ $tournament->id }}</div>
        <div class="panel-body">
            <p><strong>Name: </strong> {{ $tournament->name }} </p>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" id="description" disabled>{{ $tournament->description }}</textarea>
            </div>

            <p><strong>Game: </strong> <a href="{{ url('adminPanel/games', [$tournament->getGame()->id]) }}">{{ $tournament->getGame()->name }}</a> </p>

            <p><strong>Default checker: </strong> <a href="{{ url('adminPanel/checkers', [$tournament->getChecker()->id]) }}">{{ $tournament->getChecker()->name }}</a> </p>

            <p><strong>State: </strong> {{ $tournament->state }} </p>
        </div>
        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', ['backLink' => url('adminPanel/tournaments'), 'editLink' => url('adminPanel/tournaments/edit', [$tournament->id]), 'editName' => 'tournament'])
        </div>
    </div>

    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
@endsection