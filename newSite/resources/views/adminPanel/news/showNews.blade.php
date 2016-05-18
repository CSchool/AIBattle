@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/news.showNewsTitle'))
@section('APtitle', trans('adminPanel/news.showNewsHeader'))

@section('APcontent')
    <div class="panel panel-primary">
        <div class="panel-heading">{{ trans('adminPanel/news.showNewsPanelHeader', ['id' => $news->id]) }}</div>
        <div class="panel-body">
            <div class="form-group">
                <label for="title">{{ trans('adminPanel/news.newsFormTitle') }}:</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $news->header }}" disabled/>
            </div>

            <div class="form-group">
                <label for="newsText">{{ trans('adminPanel/news.newsFormText') }}:</label>
                <textarea class="form-control" name="newsText" id="newsText" disabled>{{ $news->text }}</textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">{{ trans('adminPanel/news.newsFormDate') }}:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker" value="{{ $news->date->format('d/m/Y') }}" disabled />
                </div>
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editRedirectFooter', [
                    'backLink' => url('adminPanel/news'),
                    'editLink' => url('adminPanel/news/edit', [$news->id]),
                    'editName' => trans('adminPanel/news.showNewsEditRedirectFooter')])
            </div>
        </div>

        <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
        <script>
            tinymce.init({
                selector: '#newsText',
                menubar: false,
                readonly : true
            });


            $(document).ready(function() {
                $('#datetimepicker').datepicker({
                    yearRange: "1990:2016",
                    dateFormat: "dd/mm/yy"
                });
            });

        </script>
    </div>
@endsection