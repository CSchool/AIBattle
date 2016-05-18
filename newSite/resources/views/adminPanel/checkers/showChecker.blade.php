@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/checkers.showCheckerTitle'))
@section('APtitle', trans('adminPanel/checkers.showCheckerHeader'))

@section('APcontent')

    {{ Form::hidden('codeTextShow', trans('adminPanel/checkers.showCheckerShowCode')) }}
    {{ Form::hidden('codeTextHide', trans('adminPanel/checkers.showCheckerHideCode')) }}

    <div class="panel panel-primary">
        <div class="panel-heading">
            {{ trans('adminPanel/checkers.showCheckerPanelHeader', ['id' => $checker->id]) }}
        </div>
        <div class="panel-body">
            <p><strong>{{ trans('adminPanel/checkers.checkerName') }}: </strong> {{ $checker->name }} </p>

            <p><strong>{{ trans('adminPanel/checkers.checkerGame') }}:</strong> <a href="{{ url('adminPanel/games', [$game->id]) }}">{{ $game->name }}</a></p>

            <p><strong>{{ trans('adminPanel/checkers.showCheckerHasSeed') }}: </strong>
                @if ($checker->hasSeed)
                    {{ trans('adminPanel/checkers.showCheckerHasSeedTrue') }}
                @else
                    {{ trans('adminPanel/checkers.showCheckerHasSeedFalse') }}
                @endif
            </p>

            <div class="row">
                <div class="col-md-12">
                    <a href="#code" id="codeHref" class="btn btn-info" data-toggle="collapse">{{ trans('adminPanel/checkers.showCheckerShowCode') }} <span class="glyphicon glyphicon-chevron-down"></span></a>
                    <div id="code" class="collapse">
                        <pre class="line-numbers"><code class="language-javascript">{{ $checkerData }}</code></pre>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', ['backLink' => url('adminPanel/checkers'), 'editLink' => url('adminPanel/checkers/edit', [$checker->id]), 'editName' => trans('adminPanel/checkers.showCheckerEditRedirectFooter')])
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var code = $('#code');

            code.on('shown.bs.collapse', function () {
                $('#codeHref').html($('input[name=codeTextHide]').val() + " <span class='glyphicon glyphicon-chevron-up'></span>");
            });

            code.on('hidden.bs.collapse', function () {
                $('#codeHref').html($('input[name=codeTextShow]').val() + " <span class='glyphicon glyphicon-chevron-down'></span>");
            });
        });
    </script>
@endsection