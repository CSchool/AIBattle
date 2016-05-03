@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Create News')
@section('APtitle', 'Administration Panel - Create News')

@section('APcontent')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-primary">
        <div class="panel-heading">Create news # {{ $newsCount }}</div>
        <div class="panel-body">
            {{ Form::open() }}

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" id="title"  />
            </div>

            <div class="form-group">
                <label for="newsText">Text:</label>
                <textarea class="form-control" name="newsText" id="newsText"></textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">Date:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker" />
                </div>
            </div>

            <button type="submit" class="btn btn-lg btn-success btn-block">Create news</button>

            {{ Form::close() }}
        </div>
    </div>

    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('newsText');


        $(document).ready(function() {
            $('#datetimepicker').datepicker({
                yearRange: "1990:2016",
                dateFormat: "dd/mm/yy"
            });


        });


    </script>
@endsection