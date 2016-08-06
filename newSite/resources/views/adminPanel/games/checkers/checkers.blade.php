@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/checkers.checkersTitle'))
@section('APtitle', trans('adminPanel/checkers.checkersHeader'))

@include('assets.adminPanel.gamesSidebar', ["game" => $game])

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
                <a href="{{ url('/adminPanel/games/' . $game->id.'/checkers/create') }}" class="btn btn-success btn-lg" role="button">
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
                </tr>
            </thead>
        </table>

        <script>
            var table = $('#checkers').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{!! route('admin.checkersTable', [$game->id]) !!}'
                },
                'responsive': true,
                @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
                @endif
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0}
                ]
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