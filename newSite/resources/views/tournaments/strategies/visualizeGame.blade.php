<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ trans('tournaments/strategies.visualizeGameTitle', ['game' => $game]) }}</title>

        <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <script src="{{ URL::asset('js/jquery-1.12.3.min.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    </head>
    <body>

        <div class="container">

            <div id="headerDiv" class="page-header text-center">
                <h2>
                    {{ trans('tournaments/strategies.visualizeGameHeader', ['game' => $game, 'user1' => $user1, 'user2' => $user2]) }}
                    <div id = "hiddenScore">{{ $status }}</div>
                </h2>
                <h3 id = "scoreDiv"></h3>
            </div>

            <div id="canvasDiv" class="text-center">
                <canvas id="cv"></canvas>
            </div>

            <br>

            <div id="buttonsDiv" class="text-center">
                <div class="row">
                    <a href="javascript:play_start();" class="btn btn-md btn-success"><span class="glyphicon glyphicon-play"></span>
                        {{ trans('tournaments/strategies.visualizeGameStart') }}
                    </a>
                    <a href="javascript:play_stop();" class="btn btn-md btn-info"><span class="glyphicon glyphicon-pause"></span>
                        {{ trans('tournaments/strategies.visualizeGameStop') }}
                    </a>
                    <a href="javascript:play_reset();" class="btn btn-md btn-danger"><span class="glyphicon glyphicon-repeat"></span>
                        {{ trans('tournaments/strategies.visualizeGameReset') }}
                    </a>
                </div>
                <br>
                <div class="row">
                    <a href="javascript:stepPrev();" class="btn btn-md btn-warning"><span class="glyphicon glyphicon-chevron-left"></span>
                        {{ trans('tournaments/strategies.visualizeGamePrevTurn') }}
                    </a>
                    <a href="javascript:stepNext();" class="btn btn-md btn-warning"><span class="glyphicon glyphicon-chevron-right"></span>
                        {{ trans('tournaments/strategies.visualizeGameNextTurn') }}
                    </a>
                </div>
            </div>
        </div>

        {!! Form::hidden('log', $log) !!}

        <script>

            var headerDiv = $('#headerDiv');
            var buttonsDiv = $('#buttonsDiv');

            var minimumHeight = 300;
            var minimumWidth = 300;

            window.onload = function()
            {
                var newHeight = $( window ).height() - (headerDiv.height() + buttonsDiv.height());
                startup(
                    Math.max($( window ).width(), minimumWidth),
                    Math.max(newHeight, minimumHeight)
                );
            };
            window.onresize = function()
            {
                var newHeight = $( window ).height() - (headerDiv.height() + buttonsDiv.height());
                resize(
                        Math.max($( window ).width(), minimumWidth),
                        Math.max(newHeight, minimumHeight)
                );
            };

            {!! $visualizer !!}
        </script>

    </body>
</html>