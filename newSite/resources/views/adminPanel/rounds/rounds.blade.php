@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.roundsTitle', ['tournament' => $tournament->name]))
@section('APtitle', trans('adminPanel/rounds.roundsHeading', ['tournament' => $tournament->name]))

@section('APcontent')
    <style>
        tfoot {
            display: table-header-group;
        }

        .dataTables_filter {
            display: none;
        }

        .progress {
            margin-bottom: 0;
        }
    </style>

    @if ($rounds > 0)
        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/' . $tournament->id . '/rounds/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/rounds.roundsCreate') }}
                </a>
            </div>
        </div>

        <table id="rounds" class="table table-hover" width="100%">
            <thead>
                <tr class="success">
                    <th>#</th>
                    <th>{{ trans('adminPanel/rounds.roundsName') }}</th>
                    <th>{{ trans('adminPanel/rounds.roundsState') }}</th>
                    <th>{{ trans('adminPanel/rounds.roundsDate') }}</th>
                    <th>{{ trans('adminPanel/rounds.roundsResults') }}</th>
                </tr>
            </thead>
        </table>

        <script>
            var table = $('#rounds').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": '{!! route('admin.roundsTable', $tournament->id) !!}',
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
                    { data: 'date', name: 'date' },
                    { data: 'rounds', name: 'score', searchable: false, orderable: false  }
                ],
                "columnDefs": [
                    { "width": "5%", className: "text-center", "targets": 0},
                    { "width": "15%", "targets": 3},
                    { "width": "10%", className: "text-center", "targets": 4}
                ],
                "order": [[ 0, "desc" ]]
            });
        </script>

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/rounds.roundsWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/rounds.roundsWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/' . $tournament->id . '/rounds/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/rounds.roundsCreate') }}
                </a>
            </div>
        </div>
    @endif

@endsection