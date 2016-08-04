@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/main.title'))
@section('APtitle', trans('adminPanel/main.pageHeader'))

@section('APcontent')

<div class="panel panel-primary">
    <div class="panel-heading">{{ trans('adminPanel/main.panelHeading') }}</div>
    <div class="panel-body">
        <ul class="list-inline text-center">
            <li>
                <a href="{{ url('/adminPanel/games') }}" class="btn btn-success" role="button">
                    {{ trans('adminPanel/main.gamesLink') }}
                </a>
            </li>
            <li>
                <a href="{{ url('/adminPanel/tournaments') }}" class="btn btn-success" role="button">
                    {{ trans('adminPanel/main.tournamentsLink') }}
                </a>
            </li>
            <li>
                <a href="{{ url('/adminPanel/users') }}" class="btn btn-success" role="button">
                    {{ trans('adminPanel/main.usersLink') }}
                </a>
            </li>
            <li>
                <a href="{{ url('/adminPanel/news') }}" class="btn btn-success" role="button">
                    {{ trans('adminPanel/main.newsLink') }}
                </a>
            </li>
        </ul>
    </div>
</div>

@endsection