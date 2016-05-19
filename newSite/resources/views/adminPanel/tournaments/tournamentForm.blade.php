@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', trans('adminPanel/tournaments.tournamentFormTitleCreate'))
    @section('APtitle', trans('adminPanel/tournaments.tournamentFormHeaderCreate'))
@elseif ($mode == "edit")
    @section('title', trans('adminPanel/tournaments.tournamentFormTitleEdit'))
    @section('APtitle', trans('adminPanel/tournaments.tournamentFormHeaderEdit'))
@endif

@section('APcontent')
    @include('assets.error')

    @if (count($games) == 0)
        @include('assets.errorNoGame')
    @else
        <div class="panel panel-primary">
            <div class="panel-heading">
                @if ($mode == "create")
                    {{ trans('adminPanel/tournaments.tournamentFormPanelHeadingCreate', ['count' => $tournamentCount]) }}
                @elseif ($mode == "edit")
                    {{ trans('adminPanel/tournaments.tournamentFormPanelHeadingEdit', ['count' => $tournament->id]) }}
                @endif
            </div>
            <div class="panel-body">
                {{ Form::open() }}

                @if ($mode == "edit")
                    {{ Form::hidden('defaultCheckerId', $tournament->defaultChecker) }}
                @endif

                <div class="form-group">
                    <label for="name">{{ trans('adminPanel/tournaments.tournamentName') }}:</label>
                    <input type="text" class="form-control" name="name" id="name"
                           @if ($mode == "edit") value="{{ $tournament->name }}" @endif />
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('adminPanel/tournaments.tournamentDescription') }}:</label>
                    <textarea class="form-control" name="description" id="description">
                        @if ($mode == "edit") {{ $tournament->description }} @endif
                    </textarea>
                </div>

                <div class="form-group">
                    <label for="game">{{ trans('shared.game') }}:</label>
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
                    <label for="checker">{{ trans('adminPanel/tournaments.tournamentDefaultChecker') }}:</label>
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
                    <label for="state">{{ trans('adminPanel/tournaments.tournamentState') }}:</label>
                    <select class="form-control" name="state" id="state">
                        <option value="preparing" @if ($mode == "edit" && $tournament->state == "preparing") selected @endif>
                            {{ trans('adminPanel/tournaments.tournamentStatePreparing') }}
                        </option>
                        <option value="running" @if ($mode == "edit" && $tournament->state == "running") selected @endif>
                            {{ trans('adminPanel/tournaments.tournamentStateRunning') }}
                        </option>
                        <option value="closed" @if ($mode == "edit" && $tournament->state == "closed") selected @endif>
                            {{ trans('adminPanel/tournaments.tournamentStateClosed') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array(
                    'link' => url('adminPanel/tournaments'),
                    'name' => trans('adminPanel/tournaments.tournamentFormEditFormFooter')
                    )
                )
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
    @endif

@endsection