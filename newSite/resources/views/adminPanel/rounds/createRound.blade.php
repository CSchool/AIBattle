@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.createRoundTitle', ['tournament' => $tournament->name]))
@section('APtitle', trans('adminPanel/rounds.createRoundTitle', ['tournament' => $tournament->name]))

@section('APcontent')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div id="errorDiv" class="alert alert-danger hidden">
        <strong>{{ trans('shared.errorMessage') }}:</strong> <br><br>
        <ul id="errorList">

        </ul>
    </div>

    <div class="panel panel-success">

        <div class="panel-heading">{{ trans('adminPanel/rounds.createRoundOptions') }}</div>

        <div class="panel-body">
            <div class="form-group">
                <label for="name">{{ trans('adminPanel/checkers.checkerName') }}:</label>
                <input type="text" class="form-control" name="name" id="name"/>
            </div>

            <div class="form-group">
                <label for="checker">{{ trans('adminPanel/rounds.createRoundCheckers', ["game" => $tournament->game->name]) }}:</label>
                <select class="form-control" name="checker" id="checker">
                    @foreach($checkers as $checkerElement)
                        <option value="{{ $checkerElement->id }}">
                            {{ $checkerElement->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if ($tournament->checker->hasSeed == 1)
            <div class="form-group">
                <label for="seed">{{ trans('adminPanel/rounds.createRoundSeed') }}:</label>
                <input type="text" class="form-control" name="seed" id="seed"/>
            </div>
            @endif

            @if (count($prevRounds) > 0)
                <div class="form-group">
                    <label for="prevRound">{{ trans('adminPanel/rounds.roundsPrev') }}:</label>
                    <select class="form-control" name="prevRound" id="prevRound">
                        <option value="-1" selected>{{ trans('adminPanel/rounds.roundsPrevNA') }}</option>
                        @foreach($prevRounds as $prev)
                            <option value="{{ $prev->id }}">{{ $prev->name . ' (' . $prev->id . ')' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="filterBlock" style="display: none;">
                    <label for="scoreFilter">{{ trans('adminPanel/rounds.createRoundScoreFilter') }}:</label>
                    <input type="text" class="form-control" name="scoreFilter" id="scoreFilter"/>
                </div>
            @endif
        </div>

    </div>

    <div class="panel panel-info">

        <div class="panel-heading">{{ trans('adminPanel/rounds.createRoundPossibleUsersHeader') }}</div>

        <div class="panel-body">
            <table id="possiblePlayers" class="table table-hover" width="100%"></table>
        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-md-2">
                    <button id="acceptAll" class="btn btn-success btn-block">{{ trans('adminPanel/rounds.createRoundAcceptAllPlayers') }}</button>
                </div>
                <div class="col-md-2 col-md-offset-8">
                    <button id="filterByScore" class="btn btn-warning btn-block" >{{ trans('adminPanel/rounds.createRoundScoreFilterButton') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-warning">

        <div class="panel-heading">{{ trans('adminPanel/rounds.createRoundAcceptedUsersHeader') }}</div>

        <div class="panel-body">
            <table id="acceptedPlayers" class="table table-hover" width="100%">
                <thead>
                <tr>
                    <th>{{ trans('shared.user') }}</th>
                    <th>{{ trans('shared.strategy') }}</th>
                    <th>{{ trans('adminPanel/rounds.roundResultScore') }}</th>
                    <th>{{ trans('adminPanel/rounds.createRoundAction') }}</th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-md-2">
                    <button id="declineAll" class="btn btn-danger btn-block" disabled>{{ trans('adminPanel/rounds.createRoundDeclineAllPlayers') }}</button>
                </div>
            </div>
        </div>

    </div>

    <div class="panel panel-default">
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-2">
                    <button id="startRound" class="btn btn-success btn-block">{{ trans('adminPanel/rounds.createRoundStartRound') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var possiblePlayers;
        var acceptedPlayers;
        var possiblePlayersTable;
        var acceptedPlayersTable;
        var acceptAllButton;
        var declineAllButton;

        var buttonMap = [
            {
                from: null,
                to: null,
                color: 'danger',
                glyphicon: 'remove',
                text: '{{ trans('adminPanel/rounds.createRoundRemovePlayer') }}',
                buttonFrom: null,
                buttonTo: null
            },
            {
                from: null,
                to: null,
                color: 'success',
                glyphicon: 'ok',
                text: '{{ trans('adminPanel/rounds.createRoundAddPlayer') }}',
                buttonFrom: null,
                buttonTo: null
            }
        ];

        function transferPlayer(strategyId, info) {
            info.from.column(3).data().each(function (data, index) {
                var element = $(data);
                var id = element.data('id');

                if (id == strategyId) {
                    var player = element.data('player');
                    var score = element.data('score');

                    info.from.rows('[id=' + id +']').remove().draw();

                    if (!info.from.data().count()) {
                        info.buttonFrom.prop('disabled', true);
                    }

                    var button =    '<button data-id="' + id + '" data-player="' + player + '" data-score="' + score + '" ' +
                                    'class="btn-xs btn-' + info.color + '"> ' +
                                    '<i class="glyphicon glyphicon-' + info.glyphicon + '"></i> ' +
                                    info.text + '</button>';

                    info.to.row.add([player, id, score, button]).draw(false);

                    if (info.buttonTo.prop('disabled')) {
                        info.buttonTo.prop('disabled', false);
                    }

                    return false;
                }
            });
        }

        // get every strategy id from given table
        function getStrategyIdTable(players) {
            var strategies = [];

            players.rows().every(function() {
                strategies.push(this.data()[1]);
            });

            return strategies;
        }

        // get id of strategies which are higher or equal than limit for given table
        function filterStrategiesByScore(players, limit) {
            var strategies = [];

            players.rows().every(function() {
                var score =  this.data()[2];
                var id = this.data()[1];

                if (score >= limit) {
                    strategies.push(id);
                }
            });

            return strategies;
        }

        // view errors
        function showErrors(errors) {
            var error = $('#errorDiv');
            error.removeClass("hidden");

            var errorList = $('#errorList');
            errorList.empty();

            $.each(errors, function (key, value) {
                errorList.append('<li>' + value + '</li>');
            });

            $("html, body").animate({ scrollTop: 0 }, "slow");
        }

        $(document).ready(function () {

            possiblePlayers = $('#possiblePlayers');
            acceptedPlayers = $('#acceptedPlayers');

            acceptAllButton = $('#acceptAll');
            declineAllButton = $('#declineAll');

            $.get('{!! route('admin.getPossibleUsers', [$tournament->id, -1]) !!}', function(data) {

                // getting data of possible users

                data = JSON.parse(data);

                acceptedPlayersTable = acceptedPlayers.DataTable({
                    'responsive': true,
                    @if (App::getLocale() == 'ru')
                    "language": {
                        url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                    },
                    @endif
                    "columnDefs": [
                        { "width": "15%", className: "text-center", "targets": 3, orderable: false, searchable: false },
                        { "targets": 2, visible: false }
                    ],
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                        var id = $(aData[3]).data('id');
                        $(nRow).attr('id', id);
                    }
                });

                possiblePlayersTable = possiblePlayers.DataTable({
                    data: data,
                    'responsive': true,
                    @if (App::getLocale() == 'ru')
                    "language": {
                        url: '{{ URL::asset('datatablesLanguage/russianDatatables.json') }}'
                    },
                    @endif
                    columns:
                    [
                        {title: '{{ trans('shared.user') }}'},
                        {title: '{{ trans('shared.strategy') }}'},
                        {title: '{{ trans('adminPanel/rounds.roundResultScore') }}'},
                        {title: '{{ trans('adminPanel/rounds.createRoundAction') }}'}
                    ],
                    "columnDefs": [
                        { "width": "15%", className: "text-center", "targets": 3, orderable: false, searchable: false },
                        { "targets": 2, visible: false }
                    ],
                    fnCreatedRow: function( nRow, aData, iDataIndex ) {
                        var id = $(aData[3]).data('id');
                        $(nRow).attr('id', id);
                    }
                });

                buttonMap[0].from = possiblePlayersTable;
                buttonMap[0].to = acceptedPlayersTable;
                buttonMap[0].buttonFrom = acceptAllButton;
                buttonMap[0].buttonTo = declineAllButton;

                buttonMap[1].from = acceptedPlayersTable;
                buttonMap[1].to = possiblePlayersTable;
                buttonMap[1].buttonFrom = declineAllButton;
                buttonMap[1].buttonTo = acceptAllButton;


                // Manual transfer of players by clicking on buttons in rows
                acceptedPlayers.find('tbody').on('click', 'button', function () {
                    transferPlayer($(this).data('id'), buttonMap[1]);
                });

                possiblePlayers.find('tbody').on('click', 'button', function () {
                    transferPlayer($(this).data('id'), buttonMap[0]);
                });

                // Accept all players (even if they are invisible)
                acceptAllButton.click(function () {
                    var idTable = getStrategyIdTable(possiblePlayersTable);

                    idTable.forEach(function (item) {
                       transferPlayer(item, buttonMap[0]);
                    });

                    $(this).prop('disabled', true);
                    declineAllButton.prop('disabled', false);
                });

                // Decline all players (even if they are invisible)
                declineAllButton.click(function () {
                    var idTable = getStrategyIdTable(acceptedPlayersTable);

                    idTable.forEach(function (item) {
                        transferPlayer(item, buttonMap[1]);
                    });

                    $(this).prop('disabled', true);
                    acceptAllButton.prop('disabled', false);
                });

                // filter strategies by score
                $('#filterByScore').click(function () {

                    var score = parseInt($('#scoreFilter').val());
                    var prevRound = $('#prevRound').val();

                    if (!isNaN(score) && prevRound != -1) {
                        var idTable = filterStrategiesByScore(possiblePlayersTable, score);

                        idTable.forEach(function (item) {
                            transferPlayer(item, buttonMap[0]);
                        });
                    } else {
                        // something wrong - show message about this
                        var errors = [];

                        if (isNaN(score)) {
                            errors.push('{{ trans('adminPanel/rounds.createRoundScoreFilterInvalid') }}');
                        }

                        if (prevRound == -1) {
                            errors.push('{{ trans('adminPanel/rounds.createRoundScoreFilterNegativePrevRound') }}');
                        }

                        showErrors(errors);
                    }
                });

                // start of round
                $('#startRound').click(function () {
                    var data = {
                        players: [],
                        name: $('#name').val(),
                        checker: $('#checker').val(),
                        previousRound: $('#prevRound').val()
                    };

                    var seed = $('#seed');

                    if (seed.length) {
                        data.seed = seed.val();
                    }

                    // get players data
                    acceptedPlayersTable.rows().data().each(function (element, index) {
                        data.players.push({
                            player: element[0],
                            strategyId: element[1]
                        });
                    });

                    // redirect to previous menu if round start
                    $.post('{{ url('adminPanel/tournaments', [$tournament->id, 'rounds', 'create']) }}',
                        {
                            data: data,
                            _token: $('input[name=_token]').val()
                        })
                        .done(function(data) {
                            if (data.status == 'ok') {
                                window.location.replace('/adminPanel/tournaments/' + data.tournamentId + '/rounds');
                            } else {
                                showErrors(data.errors);
                            }

                        })
                        .fail(function(xhr, textStatus, errorThrown) {
                            console.log(xhr.responseText);
                        });

                });


                // reload all tables by taking previous round
                $('#prevRound').change(function() {

                    var prevRound = $(this).val();

                    // replace prevRound to id from prev round selector
                    var possiblePlayersUrl = '{!! route('admin.getPossibleUsers', [$tournament->id, 'prevRound']) !!}'
                            .replace('prevRound', prevRound);

                    $.get(possiblePlayersUrl, function(data) {

                        data = JSON.parse(data);

                        // add new data to the possible users table
                        possiblePlayersTable.clear();
                        possiblePlayersTable.rows.add(data);

                        // clear accepted users table
                        acceptedPlayersTable.clear();

                        // make score table visible if needed
                        possiblePlayersTable.column(2).visible(prevRound != -1);
                        acceptedPlayersTable.column(2).visible(prevRound != -1);

                        // draw changes of tables
                        possiblePlayersTable.draw();
                        acceptedPlayersTable.draw();

                        // working with buttons accessibility
                        acceptAllButton.prop('disabled', false);
                        declineAllButton.prop('disabled', true);
                    });

                    // change score filter state
                    if (prevRound == -1) {
                        $('#filterByScore').hide();
                        $('#filterBlock').hide();
                    } else {
                        $('#filterByScore').show();
                        $('#filterBlock').show();
                    }
                });

            });
        });
    </script>
@endsection