@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Show Checker')
@section('APtitle', 'Administration Panel - Checker')

@section('APcontent')

    <div class="panel panel-primary">
        <div class="panel-heading">
            Checker # {{ $checker->id }}
        </div>
        <div class="panel-body">
            <p><strong>Name </strong> {{ $checker->name }} </p>

            <p><strong>Game:</strong> <a href="{{ url('adminPanel/games', [$game->id]) }}">{{ $game->name }}</a></p>

            <p><strong>Has seed: </strong>
                @if ($checker->hasSeed)
                    true
                @else
                    false
                @endif
            </p>

            <div class="row">
                <div class="col-md-12">
                    <a href="#code" id="codeHref" class="btn btn-info" data-toggle="collapse">Show code <span class="glyphicon glyphicon-chevron-down"></span></a>
                    <div id="code" class="collapse">
                        <pre class="line-numbers"><code class="language-javascript">{{ $checkerData }}</code></pre>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', ['backLink' => url('adminPanel/checkers'), 'editLink' => url('adminPanel/checkers/edit', [$checker->id]), 'editName' => 'checker'])
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var code = $('#code');

            code.on('shown.bs.collapse', function () {
                $('#codeHref').html("Hide code <span class='glyphicon glyphicon-chevron-up'></span>");
            });

            code.on('hidden.bs.collapse', function () {
                $('#codeHref').html("Show code <span class='glyphicon glyphicon-chevron-down'></span>");
            });
        });
    </script>
@endsection