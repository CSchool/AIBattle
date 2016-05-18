@extends('layouts.tournamentLayout')

@section('title', trans('tournaments/main.title', ['id' => $tournament->id]))
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
            {{ trans('shared.game') }}: <strong>{{ $tournament->game->name }}</strong>
        </div>
        <div class="panel-body">
            <p><strong>{{ trans('tournaments/main.tournamentDescription') }}:</strong></p>
            {!! $tournament->description !!}

            <p><strong>{{ trans('tournaments/main.gameDescription') }}:</strong></p>
            {!! $tournament->game->description !!}

            <p><strong>{{ trans('shared.attachments') }}:</strong></p>
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

