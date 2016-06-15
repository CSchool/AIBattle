@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/checkers.checkersTitle'))
@section('APtitle', trans('adminPanel/checkers.checkersHeader'))

@section('APcontent')

    @if ($checkers > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/checkers.checkersCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table id="checkers" class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>{{ trans('shared.checker') }}</td>
                <td>{{ trans('shared.game') }}</td>
            </tr>
            </thead>

        </table>

        <script>
            var table = $('#checkers').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": '{!! route('admin.checkersTable') !!}',
                'responsive': true,
                @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
                @endif
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'checkerName', name: 'checkerName' },
                    { data: 'gameName', name: 'gameName' }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0},
                    { "width": "25%", className: "text-center", "targets": 2}
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