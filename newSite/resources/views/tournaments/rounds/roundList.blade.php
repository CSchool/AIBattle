@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/main.title', ['id' => $tournament->id]))
@section('tournamentTitle', $tournament->name)

@section('tournamentContent')
    <div class="panel panel-info">
        <div class="panel-heading">{{ trans('tournaments/rounds.roundListPanelHeader', ['tournament' => $tournament->name]) }}</div>
        <div class="panel-body">
            <div class="list-group text-center">
                @foreach($rounds as $round)
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <a href="{{url('tournaments', [$tournament->id, 'rounds', $round->id, 'results'])}}" class="list-group-item">{{ $round->name . ' (' . $round->date . ')' }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#roundsLink').addClass('active');
        });
    </script>
@endsection