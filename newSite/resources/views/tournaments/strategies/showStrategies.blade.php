@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/strategies.showStrategiesTitle'))
@section('tournamentTitle', trans('tournaments/strategies.showStrategiesHeader', ['user' => ucfirst(Auth::user()->username)]))

@section('tournamentContent')


    <style>
        tfoot {
            display: table-header-group;
        }

        .dataTables_filter {
            display: none;
        }
    </style>


    @if ($strategies == 0)
        @include('assets.warningBlock', [
            'warningMessage' => trans('tournaments/strategies.showStrategiesWarningMessage'),
            'url' => url('/tournaments/' . $tournament->id . '/strategies/create'),
            'buttonText' => trans('tournaments/strategies.showStrategiesWarningButtonText'),
        ])
    @else
        <div id="strategyPanel" class="panel panel-info">

            <div class="panel-heading">
                {{ trans('tournaments/strategies.showStrategiesPanelHeader', ['tournaments' => $tournament->name]) }}
            </div>

            <div class="panel-body">

                @if (isset($activeStrategy))

                   <div class="alert alert-success">
                        <p>{{ trans('tournaments/strategies.showStrategiesActiveStrategy') }}
                            <a href="{{ url('tournaments/' . $tournament->id . '/strategies', [$activeStrategy->id]) }}" role="button">{{ $activeStrategy->id}} - {{ $activeStrategy->name }}</a>

                            @if (!empty($activeStrategy->description))
                                <a
                                        data-toggle="popover"
                                        data-trigger="hover"
                                        data-content="{{ $activeStrategy->description }}"
                                >
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </a>
                            @endif
                        </p>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="strategies" class="table table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>{{ trans('shared.strategy') }}</td>
                                <td>{{ trans('tournaments/strategies.showStrategiesStrategyStatus') }}</td>
                                <td></td>
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

            <div class="panel-footer clearfix">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ url('tournaments', [$tournament->id, 'training']) }}" class="btn btn-lg btn-warning btn-block">
                            {{ trans('tournaments/strategies.showStrategiesTrainingLink') }}
                        </a>
                    </div>
                    <div class="col-md-3 col-md-offset-6">
                        <a href="{{ url('tournaments', [$tournament->id, 'strategies', 'create']) }}" class="btn btn-lg btn-success btn-block">
                            {{ trans('shared.create') . ' ' . trans('tournaments/strategies.strategyFormStrategy') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        var table = $('#strategies').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{!! route('tournament.table', $tournament->id) !!}',
            'responsive': true,
            @if (App::getLocale() == 'ru')
                "language": {
                    url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                },
            @endif
            "columns": [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'setActive', name: 'setActive', orderable: false, searchable: false}
            ],
            "columnDefs": [
                { width: "5%",  targets: [2, 3] },
                { width: "10%", targets: 0}
            ],
            'initComplete': function () {

                var column = this.api().column(2);

                var select = $('<select class="input-large"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? val : '', true, false).draw();
                        } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );

            },
            'fnDrawCallback': function (settings) {
                $('[data-toggle="popover"]').popover({
                    container: 'body',
                    html: true
                });
            }

        });

        $('#strategiesLink').addClass('active');

    </script>
@endsection