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
                <a href="#" id="informationLink" class="btn btn-primary">Information</a>
                <a href="#" id="roundsLink" class="btn btn-primary">Rounds</a>
                <a href="#" id="strategiesLink" class="btn btn-primary">Strategies</a>
            </div>
        </div>

        @yield('tournamentContent')

    </div>
@endsection