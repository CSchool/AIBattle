@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/news.newsTitle'))
@section('APtitle', trans('adminPanel/news.newsHeader'))

@section('APcontent')

    @if ($news > 0)

        <style>
            .dataTables_filter {
                display: none;
            }
        </style>

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/news/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/news.newsCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table id="news" class="table table-hover" width="100%">
            <thead>
                <tr class="success">
                    <td>#</td>
                    <td>{{ trans('shared.news') }}</td>
                    <td>{{ trans('shared.date') }}</td>
                </tr>
            </thead>
        </table>

        <script>
            var table = $('#news').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{!! route('admin.newsTable') !!}'
                },
                'responsive': true,
                @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
                @endif
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'header', name: 'header' },
                    { data: 'date', name: 'date' }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0}
                ]
            });
        </script>
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/news.newsWarning') }}</h3></div>
            <div class="row"><p>{{ trans('adminPanel/news.newsWarningMessage') }}</p></div>
            <div class="row">
                <a href="{{ url('/adminPanel/news/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/news.newsCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection