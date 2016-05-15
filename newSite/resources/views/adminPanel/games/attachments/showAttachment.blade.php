@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Show Attachment')
@section('APtitle', 'Administration Panel - Attachment')

@section('APcontent')
    <div class="panel panel-primary">


        <div class="panel-heading">
            Attachment # {{ $attachment->id }} for game "{{ $game->name }}"
        </div>

        <div class="panel-body">
            <p><strong>Original name:</strong> {{ $attachment->originalName }}</p>
            <p><strong>Description:</strong> {{ $attachment->description }}</p>

            <a href="{{ url('download/game/' . $game->id . '/attachment/' . $attachment->id) }}" class="btn btn-info">
                Download <span class="glyphicon glyphicon-download-alt"></span>
            </a>
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editRedirectFooter', [
                'backLink' => url('adminPanel/games', [$game->id, 'attachments']),
                'editLink' => url('adminPanel/games', [$game->id, 'attachments', 'edit', $attachment->id]),
                'editName' => 'attachment'
                ])
        </div>

    </div>
@endsection