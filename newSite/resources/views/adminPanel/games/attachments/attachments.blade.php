@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/attachments.attachmentsTitle'))
@section('APtitle', trans('adminPanel/attachments.attachmentsHeading'))

@section('APcontent')
    @if (isset($attachments) && count($attachments) > 0)

        <div class="text-center">
            <div class="row">
                <a href="{{ url('/adminPanel/games/' . $game->id . '/attachments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/attachments.attachmentsCreate') }}
                </a>
            </div>
            <br>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success">
                <td>#</td>
                <td>{{ trans('adminPanel/attachments.attachmentOriginalName') }}</td>
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
            <div class="row"><h3>{{ trans('adminPanel/attachments.attachmentsWarning') }}</h3></div>

            <div class="row"><p>{{ trans('adminPanel/attachments.attachmentsWarningMessage') }}</p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/games/' . $game->id . '/attachments/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/attachments.attachmentsCreate') }}
                </a>
            </div>
        </div>
    @endif
@endsection