@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', trans('adminPanel/attachments.attachmentFormTitleCreate'))
    @section('APtitle', trans('adminPanel/attachments.attachmentFormHeadingCreate'))
@elseif ($mode == "edit")
    @section('title', trans('adminPanel/attachments.attachmentFormTitleEdit'))
    @section('APtitle', trans('adminPanel/attachments.attachmentFormHeadingEdit'))
@endif

@section('APcontent')
    @include('assets.error')

    <div class="panel panel-primary">

        <div class="panel-heading">
            @if ($mode == "create")
                 {{ trans('adminPanel/attachments.attachmentFormTitleCreate', ['count' => $attachmentsCount, 'name' => $gameName]) }}
            @elseif ($mode == "edit")
                {{ trans('adminPanel/attachments.attachmentFormPanelHeadingEdit', ['id' => $attachment->id, 'name' => $gameName]) }}
            @endif

        </div>

        <div class="panel-body">
            {{ Form::open(array('files' => true, 'method' => 'post'))}}

            <div class="form-group">
                <label for="description">{{ trans('adminPanel/attachments.attachmentDescription') }}:</label>
                <input type="text" class="form-control" name="description" id="description"
                   @if ($mode == "edit") value="{{ $attachment->description }}" @endif />
            </div>

            <div class="form-group">
                {!! Form::label(trans('adminPanel/attachments.attachmentFormSourceLabel')) !!}
                {!! Form::file('attachmentSource', null) !!}
            </div>
        </div>

        <div class="panel-footer clearfix">
            @include('assets.editFormFooter', array(
                'link' => url('adminPanel/games/' . $attachment->game_id . '/attachments'),
                'name' => trans('adminPanel/attachments.editFormFooterName')
                )
            )
        </div>

        {{ Form::close() }}
    </div>

@endsection