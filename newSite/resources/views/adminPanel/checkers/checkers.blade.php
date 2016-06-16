@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/checkers.checkersTitle'))
@section('APtitle', trans('adminPanel/checkers.checkersHeader'))

@section('APcontent')

    @if ($checkers > 0)

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
                <a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/checkers.checkersCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table id="checkers" class="table table-hover" width="100%">
            <thead>
                <tr class="success">
                    <td>#</td>
                    <td>{{ trans('shared.checker') }}</td>
                    <td>{{ trans('shared.game') }}</td>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <script>
            var table = $('#checkers').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{!! route('admin.checkersTable') !!}',
                    data: function(d) {
                        d.gameId = $('#games').val() || '' ;
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
                    { data: 'checkerName', name: 'checkerName' },
                    { data: 'gameName', name: 'g.name' }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0}
                ],
                'initComplete': function () {
                    var column = this.api().column(2);

                    var select = $('<select id="games"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                $('#checkers').DataTable().draw();
                            });

                    column.data().unique().sort().each( function ( d, j ) {
                        var cell = $(d);
                        var hrefArray = cell.attr('href').split('/');
                        var id = hrefArray[hrefArray.length - 1];

                        select.append( '<option value="'+id+'">'+cell.text()+'</option>' )
                    } );
                }
            });
        </script>
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/checkers.checkersWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/checkers.checkersWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/checkers.checkersCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection