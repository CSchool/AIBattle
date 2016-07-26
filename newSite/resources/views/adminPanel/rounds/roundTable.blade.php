@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/rounds.roundResultTitle', ['round' => $round->name, 'date' => $round->date]))
@section('APtitle', trans('adminPanel/rounds.roundResultTitle', ['round' => $round->name, 'date' => $round->date]))

@section('APcontent')
    @include('assets.roundTable', ['round' => $round])
@endsection