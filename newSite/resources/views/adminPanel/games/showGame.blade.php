@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Show Game')
@section('APtitle', 'Administration Panel - Game')

@section('APcontent')

    <div class="panel panel-primary">
        <div class="panel-heading">
            Game # {{ $game->id }}
        </div>
        <div class="panel-body">
            <p><strong>Name:</strong> {{ $game->name }}</p>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" id="description" disabled>{{ $game->description }}</textarea>
            </div>

            <p><strong>Time limit (s): </strong> {{ $game->timeLimit }} </p>

            <p><strong>Memory limit (kb): </strong> {{ $game->memoryLimit }} </p>

            @if ($game->hasVisualizer)
                <div class="row">
                    <div class="col-md-12">
                        <a href="#code" id="codeHref" class="btn btn-info" data-toggle="collapse">Show visualizer <span class="glyphicon glyphicon-chevron-down"></span></a>
                        <div id="code" class="collapse">
                            <pre class="line-numbers"><code class="language-javascript">{{ $visualizerData }}</code></pre>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    This game hasn't visualizer!
                </div>
            @endif
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/games'),
                'editLink' => url('adminPanel/games/edit', [$game->id]),
                'editName' => 'game',
                'specialMode' => 'attachment',
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
                $('#codeHref').html("Hide visualizer <span class='glyphicon glyphicon-chevron-up'></span>");
            });

            code.on('hidden.bs.collapse', function () {
                $('#codeHref').html("Show visualizer <span class='glyphicon glyphicon-chevron-down'></span>");
            });
        });
    </script>
@endsection