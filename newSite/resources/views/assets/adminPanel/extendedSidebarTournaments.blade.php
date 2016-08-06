<ul class="list-group tree">
    <li class="list-group-item noBorder"><i>{{ $text }}</i></li>

    @foreach($tournaments as $currentTournament)
        @if ($tournament->id != $currentTournament->id)
            <li class="list-group-item noBorder">
                <a class="{{"btn " . $style . " btn-block"}}" style="white-space: normal;" href="{{ url('adminPanel/tournaments', [$currentTournament->id]) }}">{{ $currentTournament->name }}</a>
            </li>
        @else
            <li class="list-group-item noBorder">
                <a class="{{"btn " . $style . " btn-block tree-toggle"}}" style="white-space: normal;" href="#">{{ $currentTournament->name }} <span class="caret"></span></a>
                <ul class="list-group tree">
                    <li class="list-group-item noBorder">
                        <a href="#" class="tree-toggle">{{ trans('adminPanel/main.roundsLink') }} <span class="caret"></span></a>
                        <ul class="list-group tree">
                            <li class="list-group-item noBorder">
                                <a href="{{ url('adminPanel/tournaments', [$currentTournament->id, 'rounds']) }}">{{ trans('adminPanel/main.generalLink') }}</a>
                            </li>

                            @foreach($currentTournament->rounds as $round)
                                <li class="list-group-item noBorder">
                                    <a href="{{ url('adminPanel/tournaments', [$currentTournament->id, 'rounds', $round->id]) }}">
                                        {{ $round->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
    @endforeach
</ul>
