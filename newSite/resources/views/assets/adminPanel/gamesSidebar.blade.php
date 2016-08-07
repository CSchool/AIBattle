@section('adminPanelGames')
    <ul class="list-group tree">
        <li class="list-group-item noBorder">
            <a href="{{ url('adminPanel/games') }}">{{ trans('adminPanel/main.generalLink') }}</a>
        </li>
        @foreach($globalGamesAvailable as $currentGame)
            @if ($game->id != $currentGame->id)
                <li class="list-group-item noBorder">
                    <a href="{{ url('adminPanel/games', [$currentGame->id]) }}">{{ $currentGame->name }}</a>
                </li>
            @else
                <li class="list-group-item noBorder">
                    <a href="#" class="tree-toggle">{{ $currentGame->name }}  <span class="caret"></span></a>
                    <ul class="list-group tree">
                        <li class="list-group-item noBorder">
                            <a href="{{ url('adminPanel/games', [$currentGame->id, 'attachments']) }}">{{ trans('adminPanel/main.attachmentsLink') }}</a>
                        </li>
                        <li class="list-group-item noBorder">
                            <a href="{{ url('adminPanel/games', [$currentGame->id, 'checkers']) }}">{{ trans('adminPanel/main.checkersLink')  }}</a>
                        </li>
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
@endsection