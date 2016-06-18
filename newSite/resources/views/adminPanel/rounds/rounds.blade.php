@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.roundsTitle', ['tournament' => $tournament->name]))
@section('APtitle', trans('adminPanel/rounds.roundsHeading', ['tournament' => $tournament->name]))

@section('APcontent')
    <style>
        tfoot {
            display: table-header-group;
        }

        .dataTables_filter {
            display: none;
        }
    </style>

    @if ($rounds > 0)
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/rounds.roundsWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/rounds.roundsWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/tournaments/' . $tournament->id . '/rounds/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/rounds.roundsCreate') }}
                </a>
            </div>
        </div>
    @endif

@endsection