@extends('layouts.mainLayout')

@section('title', 'Authorization page')

@section('content')
<div class="container">
    <div class="col-md-4 col-md-offset-4">
        <div class="page-header text-center">
            <h2>Authorization in system</h2>
        </div>

        @include('assets.error')

        <form class="form-signin" role="form" method="POST" action="/auth/login">
            {!! csrf_field() !!}

            <div class="form-group">
                <input type="login" class="form-control" name="username" placeholder="Nickname" />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" />
            </div>
            <button type="submit" class="btn btn-lg btn-primary btn-block">Enter the AIBattle</button>
        </form>
    </div>

</div>
@endsection