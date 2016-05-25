@extends('layouts.tournamentLayout')

@if ($mode == "create")
    @section('title', trans('tournaments/strategies.strategyFormCreateTitle'))
    @section('tournamentTitle', trans('tournaments/strategies.strategyFormCreateHeader'))
@elseif (str_contains($mode, "edit"))
    @section('title', trans('tournaments/strategies.strategyFormEditTitle'))
    @section('tournamentTitle', trans('tournaments/strategies.strategyFormEditHeader', ['id' => $strategy->id]))
@endif

@section('tournamentContent')
    <div class="panel panel-primary">

        <div class="panel-heading">
            @if ($mode == "create")
                {{ trans('tournaments/strategies.strategyFormCreatePanelHeader', ['tournament' => $tournament->name]) }}
            @elseif ( str_contains($mode, "edit"))
                {{ trans('tournaments/strategies.strategyFormEditPanelHeader', [
                    'tournament' => $tournament->name,
                    'id' => $strategy->id,
                    ])
                }}
            @endif
        </div>

        <div class="panel-body">
            {{ Form::open(array('files' => true, 'method' => 'post'))}}

            <div class="form-group">
                <label for="name">{{ trans('tournaments/strategies.strategyFormName') }}:</label>
                <a data-toggle="popover" data-trigger="hover" data-content="{{ trans('tournaments/strategies.strategyFormNamePopover') }}">
                    <span class="glyphicon glyphicon-info-sign"></span>
                </a>
                <input type="text" class="form-control" name="name" id="name"
                       @if (str_contains($mode, "edit")) value="{{ $strategy->name }}" @endif />
            </div>

            <div class="form-group">
                <label for="description">{{ trans('tournaments/strategies.strategyFormDescription') }}:</label>
                <a data-toggle="popover" data-trigger="hover" data-content="{{ trans('tournaments/strategies.strategyFormDescriptionPopover') }}">
                    <span class="glyphicon glyphicon-info-sign"></span>
                </a>
                <textarea class="form-control" name="description" id="description">
                    @if (str_contains($mode, "edit")) {{ $strategy->description }} @endif
                </textarea>
            </div>

            @if ($mode == "create")
                <div class="form-group">
                    <label for="compiler">{{ trans('tournaments/strategies.strategyFormCompilerLabel') }}:</label>
                    <select class="form-control" name="compiler" id="compiler">
                        <option value="gcc">C++</option>
                        <option value="fpc">Pascal</option>
                    </select>
                </div>

                <div>
                    <label>{{ trans('tournaments/strategies.strategyFormWayOfLoadingFile') }}:</label>
                    <div>
                        <label class="radio-inline"><input type="radio" name="loadChoose" value="fileLoad" checked>
                            {{ trans('tournaments/strategies.strategyFormLoadFromFile') }}
                        </label>
                        <label class="radio-inline"><input type="radio" name="loadChoose" value="textLoad">
                            {{ trans('tournaments/strategies.strategyFormLoadFromFile') }}
                        </label>
                    </div>
                </div>

                <hr>

                <div id="fileLoad" class="form-group">
                    {!! Form::label( trans('tournaments/strategies.strategyFormSourceLabel') . ':' ) !!}
                    {!! Form::file('strategySource', null) !!}
                </div>

                <div id="textLoad" class="form-group hidden">
                    <label for="strategyText">{{ trans('tournaments/strategies.strategyFormStrategyCodeLabel') }}:</label>
                    <textarea class="form-control" name="strategyText" id="strategyText" rows="5" style="resize:vertical"></textarea>
                </div>
            @endif

        </div>

        <div class="panel-footer clearfix">
            @include('assets.editFormFooter', array(
                    'link' => $backLink,
                    'name' => trans('tournaments/strategies.strategyFormStrategy')
                    )
                )
        </div>
            {{ Form::close() }}
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        $('[data-toggle="popover"]').popover();

        $(document).ready( function () {
            tinymce.init({
                selector: '#description',
                menubar: false
            });

            $('input[name=loadChoose]').on('change', function() {
                var divs = ['fileLoad', 'textLoad'];

                var filtered = divs.filter(function(val) {
                    return val != $(this).val();
                }, $(this));


                $('#' + $(this).val()).removeClass('hidden');
                $('#' + filtered[0]).addClass('hidden');

                if ($(this).val() == "textLoad")
                    $('input[name=strategySource]').val(''); // clear the input file
            });
        });
    </script>
@endsection