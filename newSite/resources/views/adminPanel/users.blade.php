@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/users.usersTitle'))
@section('APtitle', trans('adminPanel/users.usersHeader'))

@section('APcontent')

    @if ($users > 0)

        <style>
            .dataTables_filter {
                display: none;
            }

            tfoot {
                display: table-header-group;
            }
        </style>

        <table id="users" class="table table-hover" width="100%">
            <thead>
                <tr class="success">
                    <td>#</td>
                    <td>{{ trans('adminPanel/users.usersLogin') }}</td>
                    <td>{{ trans('shared.group') }}</td>
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
            var table = $('#users').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{!! route('admin.usersTable') !!}'
                },
                'responsive': true,
                @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
                @endif
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'username', name: 'username' },
                    { data: 'group', name: 'group' }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0}
                ],
                'initComplete': function () {
                    var column = this.api().column(2);

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

    @else
        <div class="alert alert-warning">
            {{ trans('adminPanel/users.usersNoUsers') }}
        </div>
    @endif

@endsection