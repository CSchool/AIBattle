@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/tournaments.tournamentsTitle'))
@section('APtitle', trans('adminPanel/tournaments.tournamentsHeading'))

@section('APcontent')

    @if ($runningCount > 0 || $preparingCount > 0 || $closedCount > 0)

        <style>
            tfoot {
                display: table-header-group;
            }

            .dataTables_filter {
                display: none;
            }
        </style>

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/tournaments.tournamentsCreate') }}
                </a>
            </div>
            <br>
        </div>



        <table id="tournaments" class="table table-hover" width="100%">
            <thead>
                <tr class="success">
                    <td>#</td>
                    <td>{{ trans('shared.tournament') }}</td>
                    <td>{{ trans('adminPanel/tournaments.tournamentsState') }}</td>
                    <td>{{ trans('shared.game') }}</td>
                    <td>{{ trans('adminPanel/tournaments.tournamentDefaultChecker') }}</td>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>


        <script>
            var table = $('#tournaments').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{!! route('admin.tournamentsTable') !!}',
                    data: function(d) {
                        d.gameId = $('#gameSelect').val() || '';
                        d.checkerId = $('#checkerSelect').val() || '';
                        d.state = $('#stateSelect').val() || '';
                    }
                },
                'responsive': true,
                @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
                @endif
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'state', name: 'state', orderable: false, searchable: false },
                    { data: 'gameName', name: 'games.name' },
                    { data: 'checkerName', name: 'checkers.name' }
                ],

                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0}
                ],
                'initComplete': function () {
                    var columns = [
                        {id: 2, name: 'stateSelect'},
                        {id: 3, name: 'gameSelect'},
                        {id: 4, name: 'checkerSelect'}
                    ];

                    for (var i = 0; i < columns.length; ++i) {
                        var column = this.api().column(columns[i].id);

                        var select = $('<select id=' + columns[i].name + '><option value=""></option></select>')
                                .appendTo($(column.footer()).empty());

                        column.data().unique().sort().each( function ( d, j ) {
                            if (columns[i].name != 'stateSelect') {
                                var cell = $(d);

                                var hrefArray = cell.attr('href').split('/');
                                var id = hrefArray[hrefArray.length - 1];

                                select.append( '<option value="'+id+'">'+cell.text()+'</option>' );
                            } else {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            }
                        } );

                        $('#' + columns[i].name).on('change', function () {
                            // redraw table was used, because we have non-standart configuration of query
                            // and search wrapper search users only in one column!
                            $('#tournaments').DataTable().draw();
                        })
                    }
                }
            });
        </script>

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/tournaments.tournamentsWarning') }}</h3></div>
            <div class="row"><p>{{ trans('adminPanel/tournaments.tournamentsWarningMessage') }}</p></div>
            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/tournaments.tournamentsCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection