<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <title>AIBattle -  404 Error</title>

        <style>
            .vertical-center {
                min-height: 100%;
                min-height: 100vh;

                display: flex;
                align-items: center;

                margin-bottom: 0;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class=" vertical-center">
                <div class="alert alert-danger text-center center-block">
                    <h1>404</h1>
                    <h2>{{ trans('shared.404Message' )}}</h2>
                </div>
            </div>
        </div>
    </body>
</html>