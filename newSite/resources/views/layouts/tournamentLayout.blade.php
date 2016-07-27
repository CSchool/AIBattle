@extends('layouts.mainLayout')

@section('content')

    <style>
        .groupMargin {
            margin-bottom: 15pt;
        }
    </style>

    <div class="container">

        <div class="page-header text-center">
            <h1>@yield('tournamentTitle')</h1>
        </div>

        <div class="text-center groupMargin">
            <div class="btn-group">
                <a href="{{ url('/tournaments', [$tournament->id]) }}" id="informationLink" class="btn btn-primary">
                    {{ trans('layouts/tournamentLayout.information') }}
                </a>
                <a href="{{ url('/tournaments', [$tournament->id, 'rounds']) }}" id="roundsLink" class="btn btn-primary">
                    {{ trans('layouts/tournamentLayout.rounds') }}
                </a>

                @if (Auth::user())
                    <a href="{{ url('/tournaments', [$tournament->id, 'strategies']) }}" id="strategiesLink" class="btn btn-primary">
                        {{ trans('layouts/tournamentLayout.strategies') }}
                    </a>
                @endif
            </div>
        </div>

        @yield('tournamentContent')

    </div>
@endsection