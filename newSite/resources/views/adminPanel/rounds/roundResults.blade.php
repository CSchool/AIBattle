@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.roundResultTitle', ['round' => $round->name, 'date' => $round->date]))
@section('APtitle', trans('adminPanel/rounds.roundResultTitle', ['round' => $round->name, 'date' => $round->date]))

@include('assets.adminPanel.tournamentsSidebar', ['tournament' => $tournament])

@section('APcontent')
    @include('assets.roundResultContent', ['round' => $round])
@endsection