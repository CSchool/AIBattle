@extends('layouts.tournamentLayout')

@section('title', 'Tournament #' . $tournament->id)
@section('tournamentTitle', $tournament->name)

@section('tournamentContent')

    <style>
        .downloadHref li {
            display: block;
        }

        .downloadHref li:before {
            /*Using a Bootstrap glyphicon as the bullet point*/
            content: "\e025";
            font-family: 'Glyphicons Halflings';
            font-size: 10px;
            float: left;
            margin-top: 3px;
            margin-left: -17px;
            color: #000000;
        }
    </style>

    <div class="panel panel-info">
        <div class="panel-heading">
            Game: <strong>{{ $tournament->game->name }}</strong>
        </div>
        <div class="panel-body">
            <p><strong>Tournament description:</strong></p>
            {!! $tournament->description !!}

            <p><strong>Game description:</strong></p>
            {!! $tournament->game->description !!}

            <p><strong>Attachments:</strong></p>
            <div class="downloadHref">
                <ul>
                    @foreach($tournament->game->attachments as $attachment)
                        <li>
                            <a href="{{ url('download/tournament/' . $tournament->id . '/attachment/' . $attachment->id) }}">
                                {{ $attachment->originalName }}
                            </a>
                            ({{ $attachment->description }})
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#informationLink').addClass('active');
        });
    </script>
@endsection

