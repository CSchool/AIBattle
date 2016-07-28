@extends('layouts.mainLayout')

@section('content')
    <div class="page-header text-center">
        <h1>@yield('tournamentTitle')</h1>
    </div>
    @yield('tournamentContent')
@endsection