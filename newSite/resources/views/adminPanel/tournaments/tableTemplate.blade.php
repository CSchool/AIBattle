<table class="table table-bordered table-hover">
    <thead>
    <tr class="success">
        <td>#</td>
        <td>Tournament</td>
        <td>Game</td>
        <td>Default Checker</td>
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
                <a href="{{ url('/adminPanel/games', [$tournament->game]) }}"> {{ $tournament->getGame()->name }} </a>
            </td>
            <td>
                <a href="{{ url('/adminPanel/checkers', [$tournament->defaultChecker]) }}"> {{ $tournament->getChecker()->name }} </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if ($mode == "all")
{!! $tournaments->render() !!}
@endif