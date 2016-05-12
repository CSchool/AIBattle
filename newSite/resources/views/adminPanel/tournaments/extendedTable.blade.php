@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Tournaments')
@section('APtitle', 'Administration Panel - Tournaments')

@section('APcontent')
    @if (count($tournaments) > 0)
        <div class="panel panel-primary">
            <div class="panel-heading">{{ $title }}</div>
            <div class="panel-body">
                @include('adminPanel.tournaments.tableTemplate', ['tournaments' => $tournaments, 'mode' => "all"])
            </div>
            <div class="panel-footer clearfix">
                <a href="{{ url('adminPanel/tournaments') }}" class="btn btn-lg btn-primary">Back</a>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>Warning!</h3></div>
            <div class="row"><p>There are no {{mb_strtolower($title)}} tournaments at all! </p></div>
            <div class="row"><a href="{{ url('/adminPanel/tournaments') }}" class="btn btn-success btn-lg" role="button">Back</a></div>
        </div>
    @endif
@endsection