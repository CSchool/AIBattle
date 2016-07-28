@extends('layouts.mainLayout')

@section('title', trans('auth/register.title'))

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="page-header text-center">
                <h2>{{ trans('auth/register.registrationHeader') }}</h2>
            </div>

            @include('assets.error')

            <form class="form-signin" role="form" method="POST" action="/auth/register">
                {!! csrf_field() !!}

                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="{{ trans('auth/register.nickname') }}" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="{{ trans('shared.password') }}" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="{{ trans('shared.passwordConfirmation') }}" />
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block">{{ trans('auth/register.createAccount') }}</button>
            </form>
        </div>
    </div>
@endsection