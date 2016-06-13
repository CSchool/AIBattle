@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/games.gamesTitle'))
@section('APtitle', trans('adminPanel/games.gamesHeading'))

@section('APcontent')

    <style>
        .dataTables_filter {
            display: none;
        }
    </style>

    @if ($games > 0)
        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/games.gamesCreateGame') }}
                </a>
            </div>
            <br>
        </div>

        <div class="table-responsive">
            <table id="games" class="table table-hover nowrap" width="100%">
                <thead>
                    <tr class="success">
                        <td>#</td>
                        <td>{{ trans('shared.game') }}</td>
                        <td>{{ trans('shared.attachments') }}</td>
                    </tr>
                </thead>
            </table>
        </div>

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/games.gamesWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/games.gamesWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/games.gamesCreateGame') }}
                </a>
            </div>
        </div>
    @endif

    <script>
        var table = $('#games').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{!! route('admin.gamesTable') !!}',
            'responsive': true,
            @if (App::getLocale() == 'ru')
            "language": {
                url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
            },
            @endif
            "columns": [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'attachments', name: 'attachments', orderable: false, searchable: false }
            ],
            "columnDefs": [
                { "width": "5%", className: "text-center", "targets": [0,2]}
            ]
        });
    </script>
@endsection