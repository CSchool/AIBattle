@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/tournaments.extendedTableTitle'))
@section('APtitle', trans('adminPanel/tournaments.extendedTableHeading'))

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
            <div class="row"><h3>{{ trans('adminPanel/tournaments.extendedTableWarning') }}</h3></div>
            <div class="row">
                <p>
                    {{ trans('adminPanel/tournaments.extendedTableWarningMessage', ['state' => mb_strtolower( trans('adminPanel/tournaments.tournamentState1' . ucfirst($title)))]) }}
                </p>
            </div>
            <div class="row">
                <a href="{{ url('/adminPanel/tournaments') }}" class="btn btn-success btn-lg" role="button">
                    Back
                </a>
            </div>
        </div>
    @endif
@endsection