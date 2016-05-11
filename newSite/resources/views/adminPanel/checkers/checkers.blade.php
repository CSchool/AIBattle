@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Checkers')
@section('APtitle', 'Administration Panel - Checkers')

@section('APcontent')

    @if (isset($checkers) && count($checkers) > 0)

        <div class="text-center">
            <div class="row"><a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">Create checker</a></div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>Checker</td>
            </tr>
            </thead>
            <tbody>
            @foreach($checkers as $checker)
                <tr>
                    <td>
                        {{ $checker->id }}
                    </td>
                    <td>
                        <a href="{{ url('/adminPanel/checkers', [$checker->id]) }}" role="button">{{ $checker->name }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $checkers->render() !!}
    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>Warning!</h3></div>

            <div class="row"><p>There are no checkers at all! Do you want to create checker?</p></div>

            <div class="row"><a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">Create checker</a></div>
        </div>
    @endif
@endsection