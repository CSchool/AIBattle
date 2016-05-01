@extends('layouts.mainLayout')

@section('content')
    <div class="container">
        <div class="page-header text-center">
            <h1>@yield('APtitle')</h1>
        </div>

        @yield('APcontent')
    </div>
@endsection