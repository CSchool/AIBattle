<style>
    tfoot {
        display: table-header-group;
    }

    .dataTables_filter {
        display: none;
    }
</style>

<div class="text-center">
    <a class="btn btn-info btn-lg" href="{{ $roundTableUrl }}">{{ trans('shared.roundTable') }}</a>

    <br>

    <h2>{{ trans('adminPanel/rounds.roundResultScoreHeading') }}</h2>
</div>

<br>

<table id="score" class="table table-hover" width="100%">
    <thead>
        <tr class="success">
            <th>{{ trans('adminPanel/rounds.roundResultPlayer') }}</th>
            <th>{{ trans('adminPanel/rounds.roundResultScore') }}</th>
        </tr>
    </thead>
</table>

<div class="text-center">
    <h2>{{ trans('adminPanel/rounds.roundResultDuelsHeading') }}</h2>
</div>

@include('assets.duelTable', ['tableId' => 'duels', 'hasVisualizer' => $round->tournament->game->hasVisualizer])

<script>
    var table = $('#score').DataTable({
        "processing": true,
        "serverSide": true,
        @if ($mode == "admin")
        "ajax": '{!! route('admin.roundResults', $round->id) !!}',
        @else
        "ajax": '{!! route('users.roundResults', [$round->tournament->id, $round->id]) !!}',
        @endif
        'responsive': true,
        @if (App::getLocale() == 'ru')
        "language": {
            url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
        },
        @endif
        "columns": [
            { data: 'strategy', name: 'strategy', orderable: false },
            { data: 'score', name: 'score', searchable: false }
        ],
        "columnDefs": [
            { "width": "30%", "targets": 0}
        ],
        "order": [[ 1, "desc" ]],
        "paging":   false,
        "info":     false
    });

    var duelsTable = $('#duels').DataTable({
        "processing": true,
        "serverSide": true,

        "ajax": {
            @if ($mode == 'admin')
            url: '{!! route('admin.roundDuels', $round->id) !!}',
            @else
            url: '{!! route('users.roundDuels', [$round->tournament->id, $round->id]) !!}',
            @endif
            data: function(d) {
                d.user1 = $('#user1').val() || '';
                d.user2 = $('#user2').val() || '';
            }
        },

        'responsive': true,
        @if (App::getLocale() == 'ru')
        "language": {
            url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
        },
        @endif
        @if ($round->tournament->game->hasVisualizer)
        "columns": [
            { data: 'id', name: 'id'},
            { data: 'user1', name: 'user1' },
            { data: 'user2', name: 'user2' },
            { data: 'status', name: 'status' },
            { data: 'hasVisualizer', name: 'Games.hasVisualizer', orderable: false, searchable: false }
        ],
        @else
        "columns": [
            { data: 'id', name: 'id'},
            { data: 'user1', name: 'user1' },
            { data: 'user2', name: 'user2' },
            { data: 'status', name: 'status' }
        ],
        @endif
        "order": [[ 0, "desc" ]],
        @if ($round->tournament->game->hasVisualizer)
        "columnDefs": [
            { "width": "5%", className: "text-center", "targets": [3,4]}
        ],
        @else
        "columnDefs": [
            { "width": "10%", className: "text-center", "targets": 3 }
        ],
        @endif
        'initComplete': function() {
            var columns = [1, 2];

            for (var i = 0; i < columns.length; ++i) {
                var column = this.api().column(columns[i]);

                var select = $('<select id=user' + (i + 1) +' name=user' + (i + 1) + '><option value=""></option></select>')
                        .appendTo($(column.footer()).empty());

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );

                $('#user' + (i + 1)).on('change', function () {
                    // redraw table was used, because we have non-standart configuration of query
                    // and search wrapper search users only in one column!
                    $('#duels').DataTable().draw();
                })
            }
        }
    });
</script>