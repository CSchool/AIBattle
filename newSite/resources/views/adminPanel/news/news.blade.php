@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - News')
@section('APtitle', 'Administration Panel - News')

<style>
    .bottomHref {
        margin-bottom: 30px;
    }
</style>

@section('APcontent')

    @if (isset($news) && count($news) > 0)

        <div class="text-center">
            <div class="row"><a href="{{ url('/adminPanel/news/create') }}" class="btn btn-success btn-lg" role="button">Create news</a></div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>News</td>
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
            <div class="row"><h3>Warning!</h3></div>
            <div class="row"><p>There are no news at all! Do you want to create news?</p></div>
            <div class="row"><a href="{{ url('/adminPanel/news/create') }}" class="btn btn-success btn-lg" role="button">Create news</a></div>
        </div>
    @endif

    <div class="text-center">
        <div class="row bottomHref">
            <a href="{{ url('/adminPanel') }}" class="btn btn-primary btn-lg " role="button">Back to main menu</a>
        </div>
    </div>
@endsection