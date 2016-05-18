@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/attachments.showAttachmentTitle'))
@section('APtitle', trans('adminPanel/attachments.showAttachmentHeading'))

@section('APcontent')
    <div class="panel panel-primary">

        <div class="panel-heading">
           {{ trans('adminPanel/attachments.showAttachmentPanelHeading', ['id' => $attachment->id, 'name' => $game->name]) }}
        </div>

        <div class="panel-body">
            <p><strong>{{ trans('adminPanel/attachments.attachmentOriginalName') }}:</strong> {{ $attachment->originalName }}</p>
            <p><strong>{{ trans('adminPanel/attachments.attachmentDescription') }}:</strong> {{ $attachment->description }}</p>

            <a href="{{ url('download/game/' . $game->id . '/attachment/' . $attachment->id) }}" class="btn btn-info">
                {{ trans('adminPanel/attachments.showAttachmentDownload') }} <span class="glyphicon glyphicon-download-alt"></span>
            </a>
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/games', [$game->id, 'attachments']),
                'editLink' => url('adminPanel/games', [$game->id, 'attachments', 'edit', $attachment->id]),
                'editName' => trans('adminPanel/attachments.showAttachmentEditRedirectFooter')
                ])
        </div>

    </div>
@endsection