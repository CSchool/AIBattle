@extends('layouts.mainLayout')

@section('tournamentSidebar')
    <ul class="list-group tree">
        @foreach ($globalCurrentTournaments as $tournamentElement)
            @if ($tournament->id != $tournamentElement->id)
                <li class="list-group-item noBorder">
                    <a href="{{ url('/tournaments', [$tournamentElement->id]) }}" >{{ $tournamentElement->name }}</a>
                </li>
            @else
                <li class="list-group-item noBorder">
                    <a href="#" class="tree-toggle">{{ $tournamentElement->name }}  <span class="caret"></span></a>
                    <ul class="list-group tree">
                        @if (Auth::user())
                            <li class="list-group-item noBorder">
                                @if ($globalVisibleRounds->where('tournament_id', $tournament->id)->count() > 0)
                                    <a href="#" class="tree-toggle">
                                        {{ trans('layouts/tournamentLayout.rounds') }} <span class="caret"></span>
                                    </a>

                                    <ul class="list-group tree">
                                        @foreach($globalVisibleRounds->where('tournament_id', $tournament->id)->select(['id', 'name'])->get() as $round)
                                            <li class="list-group-item noBorder">
                                                <a href="{{ url('/tournaments', [$tournament->id, 'rounds', $round->id, 'results']) }}">
                                                    {{ $round->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <a href="{{ url('/tournaments', [$tournament->id, 'rounds']) }}">
                                        {{ trans('layouts/tournamentLayout.rounds') }}
                                    </a>
                                @endif
                            </li>
                        @endif
                        <li class="list-group-item noBorder">
                            <a href="{{ url('/tournaments', [$tournament->id, 'strategies']) }}" id="strategiesLink">
                                {{ trans('layouts/tournamentLayout.strategies') }}
                            </a>
                        </li>
                        <li class="list-group-item noBorder">
                            <a href="{{ url('/tournaments', [$tournament->id, 'training']) }}">
                                {{ trans('tournaments/strategies.showStrategiesTrainingLink') }}
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
@endsection

@section('content')

    <div class="page-header text-center">
        <h1>@yield('tournamentTitle')</h1>
    </div>

    @yield('tournamentContent')
@endsection