@extends('layouts.mainLayout')

@section('title', 'Registration page')

@section('content')
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <div class="page-header text-center">
                <h2>Registration in system</h2>
            </div>

            @include('assets.error')

            <form class="form-signin" role="form" method="POST" action="/auth/register">
                {!! csrf_field() !!}

                <div class="form-group">
                    <input type="login" class="form-control" name="username" placeholder="Nickname" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Password conformation" />
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block">Create account</button>
            </form>
        </div>

    </div>
@endsection