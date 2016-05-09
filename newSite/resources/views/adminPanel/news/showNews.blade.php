@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Shows news')
@section('APtitle', 'Administration Panel - News')

@section('APcontent')
    <div class="panel panel-primary">
        <div class="panel-heading">News # {{ $news->id }}</div>
        <div class="panel-body">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $news->header }}" disabled/>
            </div>

            <div class="form-group">
                <label for="newsText">Text:</label>
                <textarea class="form-control" name="newsText" id="newsText" disabled>{{ $news->text }}</textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">Date:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker" value="{{ $news->date->format('d/m/Y') }}" disabled />
                </div>
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editRedirectFooter', ['backLink' => url('adminPanel/news'), 'editLink' => url('adminPanel/news/edit', [$news->id]), 'editName' => 'news'])
            </div>
        </div>

        <script src="{{ url('/vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('newsText');
            CKEDITOR.instances['newsText'].setReadOnly(true);


            $(document).ready(function() {
                $('#datetimepicker').datepicker({
                    yearRange: "1990:2016",
                    dateFormat: "dd/mm/yy"
                });
            });

        </script>
    </div>
@endsection