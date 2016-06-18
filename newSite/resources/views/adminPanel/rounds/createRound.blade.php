@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.createRoundTitle', ['tournament' => $tournament->name]))
@section('APtitle', trans('adminPanel/rounds.createRoundTitle', ['tournament' => $tournament->name]))

@section('APcontent')
    <div class="panel panel-info">
        <div class="panel-heading">{{ trans('adminPanel/rounds.createRoundPossibleUsersHeader') }}</div>
        <div class="panel-body">
            <table id="possiblePlayers" class="table table-hover" width="100%"></table>
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

    <script>

        /* TODO:
            Save chosen players into hidden input
         */

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
            });
        });
    </script>
@endsection