@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/games.showGameTitle'))
@section('APtitle', trans('adminPanel/games.showGameHeading'))

@section('APcontent')

    {{ Form::hidden('visualizerTextShow', trans('adminPanel/games.showGameShowVisualizer')) }}
    {{ Form::hidden('visualizerTextHide', trans('adminPanel/games.showGameHideVisualizer')) }}

    <div class="panel panel-primary">
        <div class="panel-heading">
            {{ trans('adminPanel/games.showGamePanelHeading') . ' ' . $game->id }}
        </div>
        <div class="panel-body">
            <p><strong>{{ trans('adminPanel/games.gameName') }}:</strong> {{ $game->name }}</p>

            <div class="form-group">
                <label for="description">{{ trans('adminPanel/games.gameDescription') }}:</label>
                <textarea class="form-control" name="description" id="description" disabled>{{ $game->description }}</textarea>
            </div>

            <p><strong>{{ trans('adminPanel/games.gameTimeLimit') }}: </strong> {{ $game->timeLimit }} </p>

            <p><strong>{{ trans('adminPanel/games.gameMemoryLimit') }}: </strong> {{ $game->memoryLimit }} </p>

            @if ($game->hasVisualizer)
                <div class="row">
                    <div class="col-md-12">
                        <a href="#code" id="codeHref" class="btn btn-info" data-toggle="collapse">
                            {{ trans('adminPanel/games.showGameShowVisualizer') }} <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                        <div id="code" class="collapse">
                            <pre class="line-numbers"><code class="language-javascript">{{ $visualizerData }}</code></pre>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    {{ trans('adminPanel/games.showGameVisualizerNotExist') }}
                </div>
            @endif
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/games'),
                'editLink' => url('adminPanel/games/edit', [$game->id]),
                'editName' => trans('adminPanel/games.showGameEditRedirectFooterName'),
                'specialMode' => 'attachment',
                'archiveLink' => '/download/game/' . $game->id . '/archive',
                'attachmentRoute' => url('/adminPanel/games', [$game->id, 'attachments'])
                ])
        </div>
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#description',
            menubar: false,
            readonly: true
        });

        $(document).ready(function() {

            var code = $('#code');

            code.on('shown.bs.collapse', function () {
                $('#codeHref').html($('input[name=visualizerTextHide]').val() + " <span class='glyphicon glyphicon-chevron-up'></span>");
            });

            code.on('hidden.bs.collapse', function () {
                $('#codeHref').html($('input[name=visualizerTextShow]').val() + " <span class='glyphicon glyphicon-chevron-down'></span>");
            });
        });
    </script>
@endsection