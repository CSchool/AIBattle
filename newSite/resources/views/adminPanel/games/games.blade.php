@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/games.gamesTitle'))
@section('APtitle', trans('adminPanel/games.gamesHeading'))

@section('APcontent')

    @if (isset($games) && count($games) > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/games.gamesCreateGame') }}
                </a>
            </div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>{{ trans('shared.game') }}</td>
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
            <div class="row"><h3>{{ trans('adminPanel/games.gamesWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/games.gamesWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/games.gamesCreateGame') }}
                </a>
            </div>
        </div>
    @endif
@endsection