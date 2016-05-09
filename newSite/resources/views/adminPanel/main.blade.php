@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel')
@section('APtitle', 'Administration Panel - Main Page')

@section('APcontent')

<div class="panel panel-primary">
    <div class="panel-heading">Administration</div>
    <div class="panel-body">
        <ul class="list-inline text-center">
            <li><a href="{{ url('/adminPanel/games') }}" class="btn btn-success" role="button">Games</a></li>
            <li><a href="#" class="btn btn-success" role="button">Checkers</a></li>
            <li><a href="#" class="btn btn-success" role="button">Tournaments</a></li>
            <li><a href="#" class="btn btn-success" role="button">Rounds</a></li>
            <li><a href="{{ url('/adminPanel/users') }}" class="btn btn-success" role="button">Users</a></li>
            <li><a href="{{ url('/adminPanel/news') }}" class="btn btn-success" role="button">News</a></li>
        </ul>
    </div>
</div>

@endsection