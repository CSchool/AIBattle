@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Edit News')
@section('APtitle', 'Administration Panel - Edit News')

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
        <div class="panel-heading">Edit news # {{ $news->id }}</div>
        <div class="panel-body">
            {{ Form::open() }}

            {{ Form::hidden('newsId', $news->id ) }}

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $news->header }}" />
            </div>

            <div class="form-group">
                <label for="newsText">Text:</label>
                <textarea class="form-control" name="newsText" id="newsText">{{ $news->text }}</textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">Date:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker" value="{{ $news->date->format('d/m/Y') }}" />
                </div>
            </div>

            <div class="btn-group btn-group-lg">
                <button type="submit" name="update" class="btn btn-lg btn-success btn-block">Update news</button>
                <button type="submit" name="delete" class="btn btn-lg btn-success btn-block">Remove news</button>
            </div>

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