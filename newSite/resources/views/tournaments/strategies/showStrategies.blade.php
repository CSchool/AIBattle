@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/strategies.showStrategiesTitle'))
@section('tournamentTitle', trans('tournaments/strategies.showStrategiesHeader', ['user' => ucfirst(Auth::user()->username)]))

@section('tournamentContent')

    <style>
        .blank_row
        {
            height: 10px !important;
            background-color: #FFFFFF;
        }
    </style>

    @if (count($strategies) == 0)
        @include('assets.warningBlock', [
            'warningMessage' => trans('tournaments/strategies.showStrategiesWarningMessage'),
            'url' => url('/tournaments/' . $tournament->id . '/strategies/create'),
            'buttonText' => trans('tournaments/strategies.showStrategiesWarningButtonText'),
        ])
    @else
        <div id="strategyPanel" class="panel panel-info">

            <div class="panel-heading">
                {{ trans('tournaments/strategies.showStrategiesPanelHeader', ['tournaments' => $tournament->name]) }}
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>{{ trans('shared.strategy') }}</td>
                        <td>{{ trans('tournaments/strategies.showStrategiesStrategyStatus') }}</td>
                    </tr>
                    </thead>
                    <tbody>

                    @if (isset($activeStrategy))
                        <tr class = "warning">
                            <td>
                                {{ $activeStrategy->id }}
                            </td>
                            <td>
                                <a href="{{ url('tournaments/' . $tournament->id . '/strategies', [$activeStrategy->id]) }}" role="button">{{ $activeStrategy->name }}</a>

                                @if (!empty($activeStrategy->description))
                                    <a
                                            data-toggle="popover"
                                            data-trigger="hover"
                                            data-content="{{ $activeStrategy->description }}"
                                    >
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $activeStrategy->status }}
                            </td>
                        </tr>

                        <tr class="blank_row">
                            <td colspan="3"></td>
                        </tr>
                    @endif

                    @foreach($strategies as $strategy)
                        <tr
                            @if ($strategy->status == 'ERR')
                                class = "danger"
                            @elseif ($strategy->status == 'OK')
                                class = "success"
                            @elseif ($strategy->status == 'ACT')
                                class = "warning"
                            @endif
                        >
                            <td>
                                {{ $strategy->id }}
                            </td>
                            <td>
                                <a href="{{ url('tournaments/' . $tournament->id . '/strategies', [$strategy->id]) }}" role="button">{{ $strategy->name }}</a>

                                @if ($strategy->status == 'ERR')

                                    <a
                                        data-toggle="popover"
                                        data-trigger="hover"
                                        data-content="{{ trans('tournaments/strategies.showStrategiesFailedCompilation') }}"
                                    >
                                        <span class="glyphicon glyphicon-fire"></span>
                                    </a>
                                @elseif (!empty($strategy->description))
                                    <a
                                            data-toggle="popover"
                                            data-trigger="hover"
                                            data-content="{{ $strategy->description }}"
                                    >
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $strategy->status }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {!! $strategies->render() !!}
            </div>

            <div class="panel-footer clearfix">
                <div class="row">
                    <div class="col-md-3">
                        <a href="#" class="btn btn-lg btn-warning btn-block">
                            {{ trans('tournaments/strategies.showStrategiesTrainingLink') }}
                        </a>
                    </div>
                    <div class="col-md-3 col-md-offset-6">
                        <a href="{{ url('tournaments', [$tournament->id, 'strategies', 'create']) }}" class="btn btn-lg btn-success btn-block">
                            {{ trans('shared.create') . ' ' . trans('tournaments/strategies.strategyFormStrategy') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        $('[data-toggle="popover"]').popover({
            container: 'body',
            html: true
        });
    </script>
@endsection