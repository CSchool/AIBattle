@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/tournaments.usersTournamentsTitle', ['tournament' => $tournament->name]))
@section('APtitle', trans('adminPanel/tournaments.usersTournamentsHeader', ['tournament' => $tournament->name]))

@include('assets.adminPanel.tournamentsSidebar', ['tournament' => $tournament])

@section('APcontent')
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <td>#</td>
                <td>{{ trans('shared.strategy') }}</td>
                <td>{{ trans('shared.groupUser') }}</td>
                <td>{{ trans('tournaments/strategies.showStrategiesStrategyStatus') }}</td>
            </tr>
        </thead>

        <tbody>

        @foreach($strategies as $strategy)
            <tr
                    @if ($strategy->status == 'ERR')
                    class = "danger"
                    @elseif ($strategy->status == 'OK')
                    class = "success"
                    @elseif ($strategy->status == 'ACT')
                    class = "warning"
                    @endif
            >
                <td>
                    {{ $strategy->id }}
                </td>
                <td>
                    <a href="{{ url('adminPanel/tournaments/' . $tournament->id . '/strategies', [$strategy->id]) }}" role="button">{{ $strategy->name }}</a>
                </td>
                <td>
                    {{ $strategy->user->username }}
                </td>
                <td>
                    {{ $strategy->status }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $strategies->render() !!}
@endsection