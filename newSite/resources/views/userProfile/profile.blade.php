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
        </div>

        @if (!isset($fromAdminPanel))
            <a href="{{ url('userProfile/update') }}" class="btn btn-primary btn-lg">Update profile</a>
        @else
            <a href="{{ url('userProfile/update', [$profileUser->id]) }}" class="btn btn-primary btn-lg">Update profile</a>
        @endif

    </div>
@endsection