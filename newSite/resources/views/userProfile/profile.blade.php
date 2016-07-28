@extends('layouts.mainLayout')

@section('title', trans('userProfile/shared.title'))

@section('content')
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2>{{ $profileUser->username }}</h2>
        </div>
        <div class="panel-body">
            <p>{{ trans('userProfile/shared.name') }}: {{ $profileUser->name }}</p>
            <p>{{ trans('userProfile/shared.surname') }}: {{ $profileUser->surname }}</p>
            <p>{{ trans('userProfile/shared.patronymic') }}: {{ $profileUser->patronymic }}</p>
            <div class="form-group">
                <label for="description">{{ trans('userProfile/shared.description') }}:</label>
                <textarea class="form-control" style="resize:vertical" rows="5" id="description" disabled>{{ $profileUser->description }}</textarea>
            </div>
        </div>
        <div class="panel-footer clearfix">
            @if (!isset($fromAdminPanel))
                @include('assets.editRedirectFooter', [
                    'backLink' => url('/'),
                    'editLink' => url('userProfile/update'),
                    'editName' => trans('userProfile/shared.profile')]
                )
            @else
                @include('assets.editRedirectFooter', [
                    'backLink' => url('adminPanel/users'),
                    'editLink' => url('userProfile/update', [$profileUser->id]),
                    'editName' => trans('userProfile/shared.profile')]
                )
            @endif
        </div>
    </div>
@endsection