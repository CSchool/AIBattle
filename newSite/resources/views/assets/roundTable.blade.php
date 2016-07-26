<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th class="text-center"></th>

            @foreach($players as $player)
                <th class="text-center">{{ $player["username"] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($players as $player)
            <tr>
                <th class="text-center">{{ $player["username"] }}</th>

                @foreach($roundTable[$player["strategy"]] as $row)
                    <td class="text-center">
                        @if (count($row) > 0)
                            <a title="{{ $row[0]["status"] }}" href="{{ url('tournaments/' . $round->tournament_id . '/training/' . $row[0]["id"]) }}">{{ $row[0]["score"] }}</a>
                            \
                            <a title="{{ $row[1]["status"] }}" href="{{ url('tournaments/' . $round->tournament_id . '/training/' . $row[1]["id"]) }}">{{ $row[1]["score"] }}</a>
                        @endif
                    </td>
                @endforeach
            </tr>

        @endforeach
    </tbody>
</table>
