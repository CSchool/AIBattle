@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', trans('adminPanel/checkers.checkerFormTitleCreate'))
    @section('APtitle', trans('adminPanel/checkers.checkerFormHeadingCreate'))
@elseif ($mode == "edit")
    @section('title', trans('adminPanel/checkers.checkerFormTitleEdit'))
    @section('APtitle', trans('adminPanel/checkers.checkerFormHeadingEdit'))
@endif

@section('APcontent')
    @include('assets.error')

    @if (count($games) == 0)
        <div class="alert alert-danger text-center">
            <div class="row"><p><strong>{{ trans('adminPanel/checkers.checkerFormNoGames') }}</strong></p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">
                    {{ trans('adminPanel/checkers.checkerFormCreateGame') }}
                </a>
            </div>
        </div>
    @else
        <div class="panel panel-primary">

            <div class="panel-heading">
                @if ($mode == "create")
                    {{ trans('adminPanel/checkers.checkerFormCreateGame', ['count' => $checkersCount]) }}
                @elseif ($mode == "edit")
                    {{ trans('adminPanel/checkers.checkerFormCreateGame', ['id' => $checker->id]) }}
                @endif
            </div>

            <div class="panel-body">
                {{ Form::open(array('files' => true, 'method' => 'post'))}}

                <div class="form-group">
                    <label for="game">{{ trans('adminPanel/checkers.checkerGame') }}:</label>
                    <select class="form-control" name="game" id="game">
                        @foreach($games as $gameElement)
                            <option value="{{ $gameElement->id }}"
                            @if ($mode == "edit" && $checker->game_id == $gameElement->id) selected @endif>
                                {{ $gameElement->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">{{ trans('adminPanel/checkers.checkerName') }}:</label>
                    <input type="text" class="form-control" name="name" id="name"
                       @if ($mode == "edit") value="{{ $checker->name }}" @endif />
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

            {{ Form::close() }}
        </div>
    @endif

@endsection