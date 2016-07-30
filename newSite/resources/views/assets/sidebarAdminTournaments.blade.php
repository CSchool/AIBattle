<li class="list-group-item noBorder">
    <a href="#" class="tree-toggle">{{ $text }} <span class="caret"></span></a>
    <ul class="list-group tree displayBlock">
        @foreach($tournaments as $tournament)
            <li class="list-group-item noBorder">
                <a href="#" class="tree-toggle">{{ $tournament->name }} <span class="caret"></span></a>
                <ul class="list-group tree displayBlock">
                    <li class="list-group-item noBorder">
                        <a href="{{ url('adminPanel/tournaments', [$tournament->id]) }}">{{ trans('shared.description') }}</a>
                    </li>
                    <li class="list-group-item noBorder">
                        <a href="{{ url('adminPanel/tournaments', [$tournament->id, 'rounds']) }}">{{ trans('adminPanel/main.roundsLink') }}</a>
                    </li>
                </ul>
            </li>
        @endforeach
    </ul>
</li>