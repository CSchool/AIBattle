@extends('layouts.adminPanelLayout')

@if ($mode == "create")
    @section('title', 'Administration Panel - Create Checker')
    @section('APtitle', 'Administration Panel - Create Checker')
@elseif ($mode == "edit")
    @section('title', 'Administration Panel - Edit Checker')
    @section('APtitle', 'Administration Panel - Edit Checker')
@endif

@section('APcontent')
    @include('assets.error')

    @if (count($games) == 0)
        <div class="alert alert-danger text-center">
            <div class="row"><p><strong>There is no added games at DB!</strong></p></div>

            <div class="row">
                <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">Create checker</a>
                <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg" role="button">Back</a>
            </div>
        </div>
    @else
        <div class="panel panel-primary">

            <div class="panel-heading">
                @if ($mode == "create")
                    Create checker # {{ $checkersCount }}
                @elseif ($mode == "edit")
                    Edit checker # {{ $checker->id }}
                @endif
            </div>

            <div class="panel-body">
                {{ Form::open(array('files' => true, 'method' => 'post'))}}

                <div class="form-group">
                    <label for="game">Game:</label>
                    <select class="form-control" name="game" id="game">
                        @foreach($games as $gameElement)
                            <option value="{{ $gameElement->id }}"
                            @if ($mode == "edit" && $checker->game == $gameElement->id) selected @endif>
                                {{ $gameElement->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" id="name"
                       @if ($mode == "edit") value="{{ $checker->name }}" @endif />
                </div>

                <div class="form-group">
                    <label for="hasSeed">RNG:</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="hasSeed" id="hasSeed" value="0"
                            @if ($mode == "edit" && $checker->hasSeed) checked @endif>
                            Use a default value from round settings
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('checkerSource:') !!}
                    {!! Form::file('checkerSource', null) !!}
                </div>


            </div>

            <div class="panel-footer clearfix">
                @include('assets.editFormFooter', array('link' => url('adminPanel/checkers'), 'name' => 'checker'))
            </div>

            {{ Form::close() }}
        </div>
    @endif

@endsection