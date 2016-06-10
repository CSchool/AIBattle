@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/strategies.trainingTitle', ['tournament' => $tournament->name]))
@section('tournamentTitle', trans('tournaments/strategies.trainingHeader', ['tournament' => $tournament->name]))

@section('tournamentContent')

    <style>
        tfoot {
            display: table-header-group;
        }

        .dataTables_filter {
            display: none;
        }

        td.last {
            white-space: nowrap;
        }
    </style>

    <div class="panel panel-info">

        <div class="panel-heading">
            {{ trans('tournaments/strategies.trainingResultHeader', ['tournament' => $tournament->name]) }}
        </div>

        <div class="panel-body">
            @include('assets.duelTable', ['tableId' => 'duels', 'hasVisualizer' => $tournament->game->hasVisualizer])
        </div>
    </div>

    <div class="panel panel-warning">

        <div class="panel-heading">
            {{ trans('tournaments/strategies.trainingCompetitionHeader') }}
        </div>

        <div class="panel-body">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="competition" class="table  table-hover nowrap" width="100%">
                        <thead>
                            <tr class="default">
                                <td>#</td>
                                <td>{{ trans('tournaments/strategies.trainingPlayer') }}</td>
                                <td>{{ trans('shared.strategy') }}</td>
                                <td class="last">{{ trans('tournaments/strategies.trainingAction') }}</td>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>

        var duelsTable = $('#duels').DataTable({
            "processing": true,
            "serverSide": true,

            "ajax": {
                url: '{!! route('tournament.trainingDuels', $tournament->id) !!}',
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
            "columns": [
                { data: 'id', name: 'id'},
                { data: 'user1', name: 'user1' },
                { data: 'user2', name: 'user2' },
                { data: 'status', name: 'status' },
                { data: 'hasVisualizer', name: 'Games.hasVisualizer', orderable: false, searchable: false }
            ],
            "order": [[ 0, "desc" ]],
            @if ($tournament->game->hasVisualizer)
            "columnDefs": [
                { "width": "5%", className: "text-center", "targets": [3,4]}
            ],
            @else
            "columnDefs": [
                { "width": "5%", className: "text-center", "targets": 3 }
            ],
            @endif
            'initComplete': function() {

                var columns = [1, 2];

                for (var i = 0; i < columns.length; ++i) {
                    var column = this.api().column(columns[i]);

                    var select = $('<select id=user' + (i + 1) +' name=user' + (i + 1) + '><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())

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

        var competitionTable = $('#competition').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{!! route('tournament.trainingCompetition', $tournament->id) !!}',
            'responsive': true,
            @if (App::getLocale() == 'ru')
            "language": {
                url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
            },
            @endif
            "columns": [
                { data: 'id', name: 'strategies.id' },
                { data: 'user.username', name: 'User.username' },
                { data: 'name', name: 'strategies.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            "columnDefs": [
                { "width": "5%", className: "text-center", "targets": 3 }
            ],
            'initComplete': function () {
                var column = this.api().column(1);

                var select = $('<select><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            }
        });
    </script>

@endsection