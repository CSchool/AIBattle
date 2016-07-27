@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/strategies.strategyProfileTitle', ['id' => $strategy->id]))

@section('tournamentTitle', trans('tournaments/strategies.strategyProfileHeader', ['id' => $strategy->id]))

@section('tournamentContent')

    {{ Form::hidden('codeTextShow', trans('tournaments/strategies.strategyProfileShowCode')) }}
    {{ Form::hidden('codeTextHide', trans('tournaments/strategies.strategyProfileHideCode')) }}
    {{ Form::hidden('errorTextShow', trans('tournaments/strategies.strategyProfileShowError')) }}
    {{ Form::hidden('errorTextHide', trans('tournaments/strategies.strategyProfileHideError')) }}

    <div class="panel panel-primary">

        <div class="panel-heading">
            {{ trans('tournaments/strategies.strategyProfilePanelHeader', ['id' => $strategy->id, 'tournament' => $tournament->name]) }}
        </div>

        <div class="panel-body">

            @if ($strategy->status == 'ERR')
                <div class="alert alert-danger">
                    <p>{{ trans('tournaments/strategies.strategyProfileErrorMessage') }}</p>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#error" id="errorHref" class="btn btn-info" data-toggle="collapse">{{ trans('tournaments/strategies.strategyProfileShowError') }} <span class="glyphicon glyphicon-chevron-down"></span></a>
                            <div id="error" class="collapse">
                                <pre class="line-numbers"><code class="{{ $strategyPrismStyle }}">{{ $strategyErrorLog }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($strategy->status == 'ACT')
                <div class="alert alert-success">
                    <p>{{ trans('tournaments/strategies.strategyProfileActualStrategy') }}</p>
                </div>
            @endif

            <p><strong>{{ trans('tournaments/strategies.strategyFormName') }}:</strong> {{ $strategy->name }}</p>

            <div class="form-group">
                <label for="description">{{ trans('tournaments/strategies.strategyFormDescription') }}:</label>
                <textarea class="form-control" name="description" id="description">{{ $strategy->description }}</textarea>
            </div>

            <p><strong>{{ trans('tournaments/strategies.strategyFormCompilerLabel') }}:</strong> {{ $strategyLanguage }}</p>

            <div class="row">
                <div class="col-md-12">
                    <a href="#code" id="codeHref" class="btn btn-info" data-toggle="collapse">{{ trans('tournaments/strategies.strategyProfileShowCode') }} <span class="glyphicon glyphicon-chevron-down"></span></a>
                    <div id="code" class="collapse">
                        <pre class="line-numbers"><code class="{{ $strategyPrismStyle }}">{{ $strategyCode }}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer clearfix">
            <div class="row">
                <div class="col-md-3">
                    <a href="{{ $strategyBackLink }}" class="btn btn-primary btn-lg">
                        {{ trans('shared.back') }}
                    </a>
                </div>

                @if ($strategy->status == 'OK' && $strategyOwner)
                    <div class="col-md-3 col-md-offset-3">
                        <a href="{{ $strategySetActiveLink  }}" class="btn btn-warning btn-block btn-lg">
                            {{ trans('tournaments/strategies.strategyProfileMakeActualStrategy') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ $strategyEditLink }}" class="btn btn-success btn-block btn-lg">
                            {{ trans('shared.edit') . ' ' . trans('tournaments/strategies.strategyProfilePanelFooterEdit') }}
                        </a>
                    </div>
                @else
                    <div class="col-md-3 col-md-offset-6">
                        <a href="{{ $strategyEditLink }}" class="btn btn-success btn-block btn-lg">
                            {{ trans('shared.edit') . ' ' . trans('tournaments/strategies.strategyProfilePanelFooterEdit') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            tinymce.init({
                selector: '#description',
                menubar: false,
                readonly: true
            });


            var code = $('#code');

            code.on('shown.bs.collapse', function () {
                $('#codeHref').html($('input[name=codeTextHide]').val() + " <span class='glyphicon glyphicon-chevron-up'></span>");
            });

            code.on('hidden.bs.collapse', function () {
                $('#codeHref').html($('input[name=codeTextShow]').val() + " <span class='glyphicon glyphicon-chevron-down'></span>");
            });

            code = $('#error');

            code.on('shown.bs.collapse', function () {
                $('#errorHref').html($('input[name=errorTextHide]').val() + " <span class='glyphicon glyphicon-chevron-up'></span>");
            });

            code.on('hidden.bs.collapse', function () {
                $('#errorHref').html($('input[name=errorTextShow]').val() + " <span class='glyphicon glyphicon-chevron-down'></span>");
            });

            $('#strategiesLink').addClass('active');
        });
    </script>
@endsection