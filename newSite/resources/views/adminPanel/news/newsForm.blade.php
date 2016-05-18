@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', trans('adminPanel/news.newsFormTitleCreate'))
    @section('APtitle', trans('adminPanel/news.newsFormHeaderCreate'))
@elseif ($mode == "edit")
    @section('title', trans('adminPanel/news.newsFormTitleEdit'))
    @section('APtitle', trans('adminPanel/news.newsFormHeaderEdit'))
@endif

@section('APcontent')
    @include('assets.error')

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if ($mode == "create")
                {{ trans('adminPanel/news.newsFormPanelHeaderCreate', ['count' => $newsCount]) }}
            @elseif ($mode == "edit")
                {{ trans('adminPanel/news.newsFormPanelHeaderEdit', ['id' => $news->id]) }}
            @endif
        </div>
        <div class="panel-body">
            {{ Form::open() }}

            <div class="form-group">
                <label for="title">{{ trans('adminPanel/news.newsFormTitle') }}:</label>
                <input type="text" class="form-control" name="title" id="title"
                   @if ($mode == "edit") value="{{ $news->header }}" @endif />
            </div>

            <div class="form-group">
                <label for="newsText">{{ trans('adminPanel/news.newsFormText') }}:</label>
                <textarea class="form-control" name="newsText" id="newsText">
                    @if ($mode == "edit") {{ $news->text }} @endif
                </textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">{{ trans('adminPanel/news.newsFormDate') }}:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker"
                       @if ($mode == "edit") value="{{ $news->date->format('d/m/Y') }}" @endif />
                </div>
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array('link' => url('adminPanel/news'), 'name' => trans('adminPanel/news.newsFormEditFormFooter')))
            </div>

            {{ Form::close() }}
        </div>
    </div>

    <script src="{{ url('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#newsText',
            menubar: false
        });

        $(document).ready(function() {
            $('#datetimepicker').datepicker({
                yearRange: "1990:2016",
                dateFormat: "dd/mm/yy"
            });
        });

    </script>
@endsection