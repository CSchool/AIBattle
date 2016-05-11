@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', 'Administration Panel - Create News')
    @section('APtitle', 'Administration Panel - Create News')
@elseif ($mode == "edit")
    @section('title', 'Administration Panel - Edit News')
    @section('APtitle', 'Administration Panel - Edit News')
@endif

@section('APcontent')
    @include('assets.error')

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if ($mode == "create")
                Create news # {{ $newsCount }}
            @elseif ($mode == "edit")
                Edit news # {{ $news->id }}
            @endif
        </div>
        <div class="panel-body">
            {{ Form::open() }}

            <div class="form-group">
                <label for="title">Game:</label>
                <input type="text" class="form-control" name="title" id="title"
                   @if ($mode == "edit") value="{{ $news->header }}" @endif />
            </div>

            <div class="form-group">
                <label for="newsText">Text:</label>
                <textarea class="form-control" name="newsText" id="newsText">
                    @if ($mode == "edit") {{ $news->text }} @endif
                </textarea>
            </div>

            <div class="form-group">
                <label for="datetimepicker">Date:</label>
                <div class='input-group date'>
                    <input type='text' class="form-control" name="datetimepicker" id="datetimepicker"
                       @if ($mode == "edit") value="{{ $news->date->format('d/m/Y') }}" @endif />
                </div>
            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array('link' => url('adminPanel/news'), 'name' => 'news'))
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