@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', trans('adminPanel/games.gameFormTitleCreate'))
    @section('APtitle', trans('adminPanel/games.gameFormPageHeadingCreate'))
@elseif ($mode == "edit")
    @section('title', trans('adminPanel/games.gameFormTitleEdit'))
    @section('APtitle', trans('adminPanel/games.gameFormPageHeadingEdit'))
@endif

@section('APcontent')

    @include('assets.error')

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if ($mode == "create")
                {{ trans('adminPanel/games.gameFormPanelHeadingCreate') . ' ' . $gameCount }}
            @elseif ($mode == "edit")
                {{ trans('adminPanel/games.gameFormPanelHeadingEdit') . ' ' . $game->id }}
            @endif
        </div>
        <div class="panel-body">
            {{ Form::open( array('files' => true, 'method' => 'post')) }}

            @if ($mode == "edit")
                {{ Form::hidden('gameId', $game->id ) }}
            @endif

            @if (class_exists('ZipArchive'))
                @if ($mode == "create")
                    <div class="alert alert-info">
                        <p>{{ trans('adminPanel/games.archiveMessage') }}</p>
                        <br>
                        <div class="form-group">
                            {!! Form::label(trans('adminPanel/games.archiveLabel')) !!}
                            {!! Form::file('gameArchive') !!}
                        </div>
                    </div>
                @endif
            @endif

            <hr>

            <div class="form-group">
                <label for="name">{{ trans('adminPanel/games.gameName') }}:</label>

                @if (empty(old('name')) === true)
                <input type="text" class="form-control" name="name" id="name"
                       @if ($mode == "edit") value="{{ $game->name }}" @endif />
                @else
                    <input type="text" class="form-control" name="name" id="name"
                       value="{{ old('name') }}" />
               @endif
            </div>

            <div class="form-group">
                <label for="description">{{ trans('adminPanel/games.gameDescription') }}:</label>

                @if (empty(old('description')) === true)
                    <textarea class="form-control" name="description" id="description">
                        @if ($mode == "edit") {{ $game->description }} @endif
                    </textarea>
                @else
                    <textarea class="form-control" name="description" id="description">
                        {{ old('description') }}
                    </textarea>
                @endif
            </div>

            <div class="form-group">
                <label for="timeLimit">{{ trans('adminPanel/games.gameTimeLimit') }}:</label>

                @if (empty(old('timeLimit')) === true)
                    <input type="text" class="form-control" name="timeLimit" id="timeLimit"
                        @if ($mode == "edit")  value="{{ $game->timeLimit }}" @elseif ($mode == "create") value="1000" @endif  />
                @else
                    <input type="text" class="form-control" name="timeLimit" id="timeLimit"
                      value="{{ old('timeLimit') }}"  />
                @endif
            </div>

            <div class="form-group">
                <label for="memoryLimit">{{ trans('adminPanel/games.gameMemoryLimit') }}:</label>

                @if (empty(old('memoryLimit')) === true)
                    <input type="text" class="form-control" name="memoryLimit" id="memoryLimit"
                        @if ($mode == "edit")  value="{{ $game->memoryLimit }}" @elseif ($mode == "create") value="64000" @endif/>
                @else
                    <input type="text" class="form-control" name="memoryLimit" id="memoryLimit"
                       value="{{ old('memoryLimit') }}"/>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label(trans('adminPanel/games.gameVisualizer')) !!}
                {!! Form::file('visualizer', null) !!}
                @if ($mode == "edit" && $game->hasVisualizer)
                    <div class="checkbox">
                        <label><input type="checkbox" name="deleteVisualizer" value="">{{ trans('adminPanel/games.gameDeleteVisualizer') }}</label>
                    </div>
                @endif
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array('link' => url('adminPanel/games'), 'name' => trans('adminPanel/games.editFormFooterName')))
            </div>

            {{ Form::close() }}
        </div>
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#description',
            menubar: false
        });


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

            $(':input[name=gameArchive]').change(function () {
                $(":input").not("[name=gameArchive], [name=create], [name=update], [name=delete], [name=_token]").prop('disabled', $(this).val() != "");
            });

        });
    </script>
@endsection