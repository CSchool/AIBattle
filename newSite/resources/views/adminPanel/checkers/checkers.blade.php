@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/checkers.checkersTitle'))
@section('APtitle', trans('adminPanel/checkers.checkersHeader'))

@section('APcontent')

    @if (isset($checkers) && count($checkers) > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/checkers.checkersCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>{{ trans('shared.checker') }}</td>
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
            <div class="row"><h3>{{ trans('adminPanel/checkers.checkersWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/checkers.checkersWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/checkers/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/checkers.checkersCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection