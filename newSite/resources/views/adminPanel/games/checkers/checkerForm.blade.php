@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', trans('adminPanel/checkers.checkerFormTitleCreate'))
    @section('APtitle', trans('adminPanel/checkers.checkerFormHeadingCreate'))
@elseif ($mode == "edit")
    @section('title', trans('adminPanel/checkers.checkerFormTitleEdit'))
    @section('APtitle', trans('adminPanel/checkers.checkerFormHeadingEdit'))
@endif

@include('assets.adminPanel.gamesSidebar', ["game" => $game])

@section('APcontent')
    @include('assets.error')

    @if (count($games) == 0)
        @include('assets.warningBlock', [
            'warningMessage' => trans('adminPanel/games.NoGamesMessage'),
            'url' => url('/adminPanel/games/create'),
            'buttonText' => trans('adminPanel/games.NoGamesCreateGame'),
        ])
    @else
        <div class="panel panel-primary">

            <div class="panel-heading">
                @if ($mode == "create")
                    {{ trans('adminPanel/checkers.checkerFormCreate', ['count' => $checkersCount]) }}
                @elseif ($mode == "edit")
                    {{ trans('adminPanel/checkers.checkerFormEdit', ['id' => $checker->id]) }}
                @endif
            </div>

            <div class="panel-body">
                {{ Form::open(array('files' => true, 'method' => 'post'))}}

                <div class="form-group">
                    <label for="name">{{ trans('adminPanel/checkers.checkerName') }}:</label>

                    @if (empty(old('name')) === true)
                        <input type="text" class="form-control" name="name" id="name"
                            @if ($mode == "edit") value="{{ $checker->name }}" @endif />
                    @else
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ old('name') }}" />
                    @endif
                </div>

                <div class="form-group">
                    <label for="hasSeed">{{ trans('adminPanel/checkers.checkerFormSeedLabel') }}:</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="hasSeed" id="hasSeed" value="0"
                            @if ($mode == "edit" && $checker->hasSeed) checked @endif>
                            {{ trans('adminPanel/checkers.checkerFormSeedMessage') }}
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label( trans('adminPanel/checkers.checkerFormSourceLabel') ) !!}
                    {!! Form::file('checkerSource', null) !!}
                </div>


            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array(
                    'link' => url('adminPanel/checkers'),
                    'name' => trans('adminPanel/checkers.checkerFormEditFormFooter')
                    )
                )
            </div>


        </div>
    @endif

@endsection