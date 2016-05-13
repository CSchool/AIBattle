@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', 'Administration Panel - Create Tournament')
    @section('APtitle', 'Administration Panel - Create Tournament')
@elseif ($mode == "edit")
    @section('title', 'Administration Panel - Edit Tournament')
    @section('APtitle', 'Administration Panel - Edit Tournament')
@endif

@section('APcontent')
    @include('assets.error')

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if ($mode == "create")
                Create tournament # {{ $tournamentCount }}
            @elseif ($mode == "edit")
                Edit tournament # {{ $tournament->id }}
            @endif
        </div>
        <div class="panel-body">
            {{ Form::open() }}

            @if ($mode == "edit")
                {{ Form::hidden('defaultCheckerId', $tournament->defaultChecker) }}
            @endif

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name"
                       @if ($mode == "edit") value="{{ $tournament->name }}" @endif />
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                    <textarea class="form-control" name="description" id="description">
                        @if ($mode == "edit") {{ $tournament->description }} @endif
                    </textarea>
            </div>

            <div class="form-group">
                <label for="game">Game:</label>
                <select class="form-control" name="game" id="game">
                    @foreach($games as $gameElement)
                        <option value="{{ $gameElement->id }}"
                            @if ($mode == "edit" && $tournament->game_id == $gameElement->id) selected @endif>
                            {{ $gameElement->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="checker">Checker by default:</label>
                <select class="form-control" name="checker" id="checker">
                    @foreach($checkers as $checker)
                        <option value="{{ $checker->id }}"
                            @if ($mode == "edit" && $tournament->defaultChecker == $checker->id) selected @endif>
                            {{ $checker->name }} ({{ $checker->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="state">State:</label>
                <select class="form-control" name="state" id="state">
                    <option value="preparing" @if ($mode == "edit" && $tournament->state == "preparing") selected @endif>Preparing</option>
                    <option value="running" @if ($mode == "edit" && $tournament->state == "running") selected @endif>Running</option>
                    <option value="closed" @if ($mode == "edit" && $tournament->state == "closed") selected @endif>Closed</option>
                </select>
            </div>
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editFormFooter', array('link' => url('adminPanel/tournaments'), 'name' => 'tournament'))
        </div>

        {{ Form::close() }}
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready( function () {

            tinymce.init({
                selector: '#description',
                menubar: false
            });

            var game = $('#game');

            game.change(function () {
                $.get('/adminPanel/tournaments/ajax/getCheckersByGameId/' + $(this).val(), function (data) {
                    var list = $('#checker');
                    data = $.parseJSON(data);

                    list.empty();

                    $.each(data, function (key, value) {
                        list.append($("<option></option>")
                                .attr("value", value.id)
                                .text(value.name + " (" + value.id + ")"));
                    });
                });
            });
        });
    </script>
@endsection