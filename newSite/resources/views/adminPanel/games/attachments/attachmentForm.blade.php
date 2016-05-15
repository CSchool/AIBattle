@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', 'Administration Panel - Create Attachment')
    @section('APtitle', 'Administration Panel - Create Attachment')
@elseif ($mode == "edit")
    @section('title', 'Administration Panel - Edit Attachment')
    @section('APtitle', 'Administration Panel - Edit Attachment')
@endif

@section('APcontent')
    @include('assets.error')

    <div class="panel panel-primary">

        <div class="panel-heading">
            @if ($mode == "create")
                Create attachment # {{ $attachmentsCount }} for game "{{ $gameName }}"
            @elseif ($mode == "edit")
                Edit attachment # {{ $attachment->id }} for game "{{ $gameName }}"
            @endif

        </div>

        <div class="panel-body">
            {{ Form::open(array('files' => true, 'method' => 'post'))}}

            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" name="description" id="description"
                   @if ($mode == "edit") value="{{ $attachment->description }}" @endif />
            </div>

            <div class="form-group">
                {!! Form::label('attachmentSource:') !!}
                {!! Form::file('attachmentSource', null) !!}
            </div>
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editFormFooter', array('link' => url('adminPanel/attachments'), 'name' => 'attachment'))
        </div>

        {{ Form::close() }}
    </div>

@endsection