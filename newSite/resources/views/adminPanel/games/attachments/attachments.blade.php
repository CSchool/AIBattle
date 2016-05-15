@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Attachments')
@section('APtitle', 'Administration Panel - Attachments')

@section('APcontent')
    @if (isset($attachments) && count($attachments) > 0)

        <div class="text-center">
            <div class="row"><a href="{{ url('/adminPanel/games/' . $game->id . '/attachments/create') }}" class="btn btn-success btn-lg" role="button">Create attachment</a></div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>Original Name</td>
            </tr>
            </thead>
            <tbody>
            @foreach($attachments as $attachment)
                <tr>
                    <td>
                        {{ $attachment->id }}
                    </td>
                    <td>
                        <a href="{{ url('/adminPanel/games', [$game->id, 'attachments', $attachment->id]) }}" role="button">{{ $attachment->originalName }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $attachments->render() !!}

    @else
        <div class="alert alert-warning text-center">
            <div class="row"><h3>Warning!</h3></div>

            <div class="row"><p>There are no attachments at all! Do you want to create attachment?</p></div>

            <div class="row"><a href="{{ url('/adminPanel/games/' . $game->id . '/attachments/create') }}" class="btn btn-success btn-lg" role="button">Create attachment</a></div>
        </div>
    @endif
@endsection