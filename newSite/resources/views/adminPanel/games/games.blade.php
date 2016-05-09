@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Games')
@section('APtitle', 'Administration Panel - Games')

@section('APcontent')

    @if (isset($games) && count($games) > 0)

        <div class="text-center">
            <div class="row"><a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">Create game</a></div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>Game</td>
            </tr>
            </thead>
            <tbody>
            @foreach($games as $game)
                <tr>
                    <td>
                        {{ $game->id }}
                    </td>
                    <td>
                        <a href="{{ url('/adminPanel/games', [$game->id]) }}" role="button">{{ $game->name }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $games->render() !!}
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>Warning!</h3></div>

            <div class="row"><p>There are no games at all! Do you want to create game?</p></div>

            <div class="row"><a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">Create game</a></div>
        </div>
    @endif
@endsection