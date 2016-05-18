<table class="table table-bordered table-hover">
    <thead>
        <tr class="success">
            <td>ID</td>
            <td>{{ trans('shared.tournament') }}</td>
            <td>{{ trans('shared.game') }}</td>
            <td>{{ trans('adminPanel/tournaments.tournamentDefaultChecker') }}</td>
        </tr>
    </thead>
    <tbody>
    @foreach($tournaments as $tournament)
        <tr>
            <td>
                {{ $tournament->id }}
            </td>
            <td>
                <a href="{{ url('/adminPanel/tournaments', [$tournament->id]) }}" role="button">{{ $tournament->name }}</a>
            </td>
            <td>
                <a href="{{ url('/adminPanel/games', [$tournament->game]) }}"> {{ $tournament->game->name }} </a>
            </td>
            <td>
                <a href="{{ url('/adminPanel/checkers', [$tournament->defaultChecker]) }}"> {{ $tournament->checker->name }} </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if ($mode == "all")
{!! $tournaments->render() !!}
@endif