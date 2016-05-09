@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', 'Administration Panel - Create Game')
    @section('APtitle', 'Administration Panel - Create Game')
@elseif ($mode == "edit")
    @section('title', 'Administration Panel - Edit Game')
    @section('APtitle', 'Administration Panel - Edit Game')
@endif

@section('APcontent')

    @include('assets.error')

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if ($mode == "create")
                Create game # {{ $gameCount }}
            @elseif ($mode == "edit")
                Edit game # {{ $game->id }}
            @endif
        </div>
        <div class="panel-body">
            {{ Form::open( array('files' => true, 'method' => 'post')) }}

            @if ($mode == "edit")
                {{ Form::hidden('gameId', $game->id ) }}
            @endif

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name"
                       @if ($mode == "edit") value="{{ $game->name }}" @endif />
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" id="description">
                    @if ($mode == "edit") {{ $game->description }} @endif
                </textarea>
            </div>

            <div class="form-group">
                <label for="timeLimit">Time (ms):</label>
                <input type="text" class="form-control" name="timeLimit" id="timeLimit"
                    @if ($mode == "edit")  value="{{ $game->timeLimit }}" @elseif ($mode == "create") value="1000" @endif  />
            </div>

            <div class="form-group">
                <label for="memoryLimit">Memory limit (kb):</label>
                <input type="text" class="form-control" name="memoryLimit" id="memoryLimit"
                   @if ($mode == "edit")  value="{{ $game->memoryLimit }}" @elseif ($mode == "create") value="64000" @endif/>
            </div>

            <div class="form-group">
                {!! Form::label('Visualizer') !!}
                {!! Form::file('visualizer', null) !!}
                @if ($mode == "edit" && $game->hasVisualizer)
                    <div class="checkbox">
                        <label><input type="checkbox" name="deleteVisualizer" value="">Delete visualizer</label>
                    </div>
                @endif
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array('link' => url('adminPanel/games'), 'name' => 'Game'))
            </div>

            {{ Form::close() }}
        </div>
    </div>

    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description');

        $(document).ready(function () {
            var checkbox = $('input[name=deleteVisualizer]');
            var file = $('input[name=visualizer]');

            checkbox.change(function () {
                if ($(this).is(':checked')) {
                    file.prop('disabled', true);
                    file.val(''); // clear the input file
                } else {
                    file.prop('disabled', false);
                }
            });

            checkbox.val('0');
        });
    </script>
@endsection