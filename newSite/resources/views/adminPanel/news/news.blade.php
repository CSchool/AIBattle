@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/news.newsTitle'))
@section('APtitle', trans('adminPanel/news.newsHeader'))

@section('APcontent')

    @if (isset($news) && count($news) > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/news/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/news.newsCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
                <tr class="success">
                    <td>#</td>
                    <td>{{ trans('shared.news') }}</td>
                </tr>
            </thead>
            <tbody>
            @foreach($news as $element)
                <tr>
                    <td>
                        {{ $element->id }}
                    </td>
                    <td>
                        <a href="{{ url('/adminPanel/news', [$element->id]) }}" role="button">{{ $element->header }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $news->render() !!}
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>{{ trans('adminPanel/news.newsWarning') }}</h3></div>
            <div class="row"><p>{{ trans('adminPanel/news.newsWarningMessage') }}</p></div>
            <div class="row">
                <a href="{{ url('/adminPanel/news/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/news.newsCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection