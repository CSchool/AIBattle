@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/attachments.attachmentsTitle'))
@section('APtitle', trans('adminPanel/attachments.attachmentsHeading'))

@include('assets.adminPanel.gamesSidebar', ["game" => $game])


@section('APcontent')

    <style>
        tfoot {
            display: table-header-group;
        }

        .dataTables_filter {
            display: none;
        }
    </style>

    @if ($attachments > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/games/' . $game->id . '/attachments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/attachments.attachmentsCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table id="attachments" class="table table-hover" width="100%">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>{{ trans('adminPanel/attachments.attachmentOriginalName') }}</td>
            </tr>
            </thead>
        </table>

        <script>
            var table = $('#attachments').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": '{!! route('admin.attachmentsTable', $game->id) !!}',
                'responsive': true,
                @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
                @endif
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'originalName', name: 'originalName' }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0}
                ]
            });
        </script>

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/attachments.attachmentsWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/attachments.attachmentsWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/games/' . $game->id . '/attachments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/attachments.attachmentsCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection