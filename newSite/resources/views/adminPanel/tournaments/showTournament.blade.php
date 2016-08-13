@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/tournaments.showTournamentTitle'))
@section('APtitle', trans('adminPanel/tournaments.showTournamentHeading'))

@include('assets.adminPanel.tournamentsSidebar', ['tournament' => $tournament])

@section('APcontent')

    <style>
        tfoot {
            display: table-header-group;
        }

        .dataTables_filter {
            display: none;
        }

        td.last {
            white-space: nowrap;
        }
    </style>


    <div class="panel panel-primary">
        <div class="panel-heading"> {{ trans('adminPanel/tournaments.showTournamentPanelHeading', ['id' => $tournament->id]) }}</div>
        <div class="panel-body">
            <p><strong>{{ trans('adminPanel/tournaments.tournamentName') }}: </strong> {{ $tournament->name }} </p>

            <div class="form-group">
                <label for="description">{{ trans('adminPanel/tournaments.tournamentDescription') }}:</label>
                <textarea class="form-control" name="description" id="description" disabled>{{ $tournament->description }}</textarea>
            </div>

            <p><strong>{{ trans('shared.game') }}: </strong> <a href="{{ url('adminPanel/games', [$tournament->getGame()->id]) }}">{{ $tournament->getGame()->name }}</a> </p>

            <p><strong>{{ trans('adminPanel/tournaments.tournamentDefaultChecker') }}: </strong> <a href="{{ url('adminPanel/checkers', [$tournament->getChecker()->id]) }}">{{ $tournament->getChecker()->name }}</a> </p>

            <p><strong>{{ trans('adminPanel/tournaments.tournamentState') }}: </strong> {{ trans('adminPanel/tournaments.tournamentState' . ucfirst($tournament->state))  }} </p>

            <div class="text-center"><strong>Passed and failed strategies over 10 days</strong></div>
            <div id="strategyStats"></div>

            <div class="text-center"><strong>Last strategies of tournament</strong></div>
            <div class="table-responsive">
                <table id="strategies" class="table table-hover nowrap" width="100%">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>{{ trans('shared.strategy') }}</td>
                            <td>{{ trans('tournaments/strategies.trainingPlayer') }}</td>
                            <td>{{ trans('tournaments/strategies.showStrategiesStrategyStatus') }}</td>
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
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/tournaments'),
                'editLink' => url('adminPanel/tournaments/edit', [$tournament->id]),
                'editName' => trans('adminPanel/tournaments.showTournamentEditRedirectFooter'),
                'specialMode' => 'tournament',
                'roundsLink' => url('adminPanel/tournaments', [$tournament->id, 'rounds']),
                'strategiesLink' => url('adminPanel/tournaments/' . $tournament->id . '/strategies'),
                ]
            )
        </div>
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#description',
            menubar: false,
            readonly: true
        });

        var trace1 = {
            x: [],
            y: [],
            name: 'Passed',
            type: 'bar'
        };

        var trace2 = {
            x: [],
            y: [],
            name: 'Failed',
            type: 'bar'
        };

        $.get("{{ route('ajax.passedStrategies', $tournament->id) }}", function (data) {
            var acceptedData = JSON.parse(data);
            acceptedData.forEach(function(item) {
                console.log(item.date + ' ' + item.count);
                trace1.x.push(item.date);
                trace1.y.push(item.count);
            });

            $.get("{{ route('ajax.failedStrategies', $tournament->id) }}", function (data) {
                var failedData = JSON.parse(data);
                failedData.forEach(function(item) {
                    console.log(item.date + ' ' + item.count);
                    trace2.x.push(item.date);
                    trace2.y.push(item.count);
                });

                var p = [trace1, trace2];

                var layout = {
                    barmode: 'group',
                    xaxis: {
                        type: 'date',
                        tickformat: '%d %b'
                    }
                };

                Plotly.newPlot('strategyStats', p, layout);
            });
        });

        var table = $('#strategies').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{!! route('admin.strategiesTable', $tournament->id) !!}',
            'responsive': true,
            @if (App::getLocale() == 'ru')
            "language": {
                url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
            },
            @endif
            "columns": [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'username', name: 'users.username'},
                { data: 'status', name: 'status' }
            ],
            "columnDefs": [
                { width: "5%",  targets: 2 },
                { width: "10%", targets: 0}
            ],
            order: [0, 'desc'],
            'fnDrawCallback': function (settings) {
                $('[data-toggle="popover"]').popover({
                    container: 'body',
                    html: true
                });
            }
        });

        $(document).ready(function () {
            var columnData = [
                {
                    index: 3,
                    data: ['OK', 'ERR', 'ACT']
                },
                {
                    index: 2,
                    data: []
                }
            ];

            $.get("{{ route('ajax.usersStrategies', $tournament->id) }}", function (data) {
                var users = JSON.parse(data);
                users.forEach(function (item) {
                    columnData[1].data.push(item.username);
                });

                columnData.forEach(function (item) {
                    var column = table.columns(item.index);

                    var select = $('<select class="input-large"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? val : '', true, false).draw();
                            } );

                    item.data.forEach( function (d) {
                        select.append('<option value="'+d+'">'+d+'</option>')
                    });
                });
            });
        });


    </script>
@endsection