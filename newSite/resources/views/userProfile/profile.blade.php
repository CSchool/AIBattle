@extends('layouts.mainLayout')

@section('title', 'User profile')

@section('content')
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>{{ $profileUser->username }}</h2>
            </div>
            <div class="panel-body">
                <p>Name: {{ $profileUser->name }}</p>
                <p>Surname: {{ $profileUser->surname }}</p>
                <p>Patronymic: {{ $profileUser->patronymic }}</p>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" style="resize:vertical" rows="5" id="description" disabled>{{ $profileUser->description }}</textarea>
                </div>
            </div>
            <div class="panel-footer clearfix">
                @if (!isset($fromAdminPanel))
                    @include('assets.editRedirectFooter', ['backLink' => url('/'), 'editLink' => url('userProfile/update'), 'editName' => 'profile'])
                @else
                    @include('assets.editRedirectFooter', ['backLink' => url('adminPanel/users'), 'editLink' => url('userProfile/update', [$profileUser->id]), 'editName' => 'profile'])
                @endif
            </div>
        </div>
    </div>
@endsection