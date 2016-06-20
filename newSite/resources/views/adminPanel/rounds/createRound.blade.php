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

            <div class="form-group">
                <label for="seed">{{ trans('adminPanel/rounds.createRoundSeed') }}:</label>
                <input type="text" class="form-control" name="seed" id="seed"/>
            </div>
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
                    <th>{{ trans('adminPanel/rounds.createRoundAction') }}</th>
                </tr>
                </thead>
            </table>
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

        var buttonMap = [
            {
                from: null,
                to: null,
                color: 'danger',
                glyphicon: 'remove',
                text: '{{ trans('adminPanel/rounds.createRoundRemovePlayer') }}'
            },
            {
                from: null,
                to: null,
                color: 'success',
                glyphicon: 'ok',
                text: '{{ trans('adminPanel/rounds.createRoundAddPlayer') }}'
            }
        ];

        function transferPlayer(strategyId, info) {
            info.from.column(2).data().each(function (data, index) {
                var element = $(data);
                var id = element.data('id');

                if (id == strategyId) {
                    var player = element.data('player');

                    info.from.rows('[id=' + id +']').remove().draw();

                    var button =    '<button data-id="' + id + '" data-player="' + player + '" ' +
                                    'class="btn-xs btn-' + info.color + '"> ' +
                                    '<i class="glyphicon glyphicon-' + info.glyphicon + '"></i> ' +
                                    info.text + '</button>';

                    info.to.row.add([player, id, button]).draw(false);

                    return false;
                }
            });
        }

        $(document).ready(function () {

            possiblePlayers = $('#possiblePlayers');
            acceptedPlayers = $('#acceptedPlayers');

            $.get('{!! route('admin.getPossibleUsers', [$tournament->id]) !!}', function(data) {

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
                        { "width": "15%", className: "text-center", "targets": 2, orderable: false, searchable: false }
                    ],
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                        var id = $(aData[2]).data('id');
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
                        {title: '{{ trans('adminPanel/rounds.createRoundAction') }}'}
                    ],
                    "columnDefs": [
                        { "width": "15%", className: "text-center", "targets": 2, orderable: false, searchable: false }
                    ],
                    fnCreatedRow: function( nRow, aData, iDataIndex ) {
                        var id = $(aData[2]).data('id');
                        $(nRow).attr('id', id);
                    }
                });

                buttonMap[0].from = possiblePlayersTable;
                buttonMap[0].to = acceptedPlayersTable;

                buttonMap[1].from = acceptedPlayersTable;
                buttonMap[1].to = possiblePlayersTable;

                acceptedPlayers.find('tbody').on('click', 'button', function () {
                    transferPlayer($(this).data('id'), buttonMap[1]);
                });

                possiblePlayers.find('tbody').on('click', 'button', function () {
                    transferPlayer($(this).data('id'), buttonMap[0]);
                });

                $('#acceptAll').click(function () {
                    possiblePlayers.find('tbody :button').each(function () {
                        transferPlayer($(this).data('id'), buttonMap[0]);
                    });
                });

                $('#startRound').click(function () {
                    var data = {
                        players: [],
                        name: $('#name').val(),
                        checker: $('#checker').val(),
                        seed: $('#seed').val()
                    };


                    acceptedPlayersTable.rows().data().each(function (element, index) {
                        data.players.push({
                            player: element[0],
                            strategyId: element[1]
                        });
                    });

                    $.post('{{ url('adminPanel/tournaments', [$tournament->id, 'rounds', 'create']) }}',
                        {
                            data: data,
                            _token: $('input[name=_token]').val()
                        })
                    .done(function(data) {
                        console.log(data);

                        if (data.status == 'ok') {
                            window.location.replace('/adminPanel/tournaments/' + data.tournamentId + '/rounds');
                        } else {
                            var error = $('#errorDiv');
                            error.removeClass("hidden");

                            var errorList = $('#errorList');
                            errorList.empty();

                            $.each(data.errors, function (key, value) {
                                errorList.append('<li>' + value + '</li>');
                            });

                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        }

                    })
                    .fail(function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                    });

                });
            });
        });
    </script>
@endsection