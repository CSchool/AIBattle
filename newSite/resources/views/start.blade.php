@extends('layouts.mainLayout')

@section('title', 'Main page')

@section('content')
    <div class="container">
        <div class="page-header text-center">
            <h1>Hello, world!</h1>
        </div>

        @include('assets.error')

        <div>
            @foreach($news as $element)
                <div class="panel panel-info">
                    <div class="panel-heading">{{ $element->header }} ({{ $element->date->format('d/m/Y') }})</div>
                    <div class="panel-body">
                        {!! $element->text !!}
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">Comments (0)</div>
                    </div>
                </div>
            @endforeach

            {!! $news->render() !!}
        </div>

    </div>
@endsection