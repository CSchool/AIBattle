@extends('layouts.mainLayout')

@section('title', trans('userProfile/updateForm.title'))

@section('content')

    @include('assets.error')

    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-primary">

            <div class="panel-heading">{{ trans('userProfile/updateForm.updateHeading') }}</div>
            <div class="panel-body">
                {{ Form::open() }}

                @if (isset($isAdmin) && $isAdmin === true)
                    <div class="form-group">
                        <label for="group">{{ trans('shared.group') }}:</label>
                        <select class="form-control" name="group" id="group">
                            <option value="user"    @if ($profileUser->group == "user") selected @endif>
                                {{ trans('shared.groupUser') }}
                            </option>
                            <option value="moder"   @if ($profileUser->group == "moder") selected @endif>
                                {{ trans('shared.groupModerator') }}
                            </option>
                            <option value="news"    @if ($profileUser->group == "news") selected @endif>
                                {{ trans('shared.groupNews') }}
                            </option>
                            <option value="admin"   @if ($profileUser->group == "admin") selected @endif>
                                {{ trans('shared.groupAdmin') }}
                            </option>
                            <option value="banned"  @if ($profileUser->group == "banned") selected @endif>
                                {{ trans('shared.groupBanned') }}
                            </option>
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label for="surname">{{ trans('userProfile/shared.surname') }}:</label>
                    <input type="text" class="form-control" name="surname" id="surname" value="{{ $profileUser->surname }}" />
                </div>

                <div class="form-group">
                    <label for="name">{{ trans('userProfile/shared.name') }}:</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $profileUser->name }}" />
                </div>

                <div class="form-group">
                    <label for="patronymic">{{ trans('userProfile/shared.patronymic') }}:</label>
                    <input type="text" class="form-control" name="patronymic" id="patronymic" value="{{ $profileUser->patronymic }}" />
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('userProfile/shared.description') }}:</label>
                    <textarea class="form-control" style="resize:vertical" rows="3" name="description" id="description">{{ $profileUser->description }}</textarea>
                </div>

                <div class="checkbox">
                    <label>{!! Form::checkbox('passwordChangeCheckbox', '1', false) !!} {{ trans('userProfile/updateForm.changePassword') }}</label>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control hidden" name="password" id="password" placeholder="{{ trans('shared.password') }}" />
                </div>

                <div class="form-group">
                    <input type="password" class="form-control hidden" name="password_confirmation" id="password_confirmation" placeholder="{{ trans('shared.passwordConfirmation') }}" />
                </div>

                <button type="submit" name="update" class="btn btn-lg btn-success btn-block">
                    {{ trans('userProfile/updateForm.updateButton') }}
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-lg btn-primary btn-block">
                    {{ trans('userProfile/updateForm.backButton') }}
                </a>

                {{ Form::close() }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var checkbox = $('input[name=passwordChangeCheckbox]');

            checkbox.change(function () {
                if ($(this).is(':checked')) {
                    $('#password').removeClass('hidden');
                    $('#password_confirmation').removeClass('hidden');
                } else {
                    $('#password').addClass('hidden');
                    $('#password_confirmation').addClass('hidden');
                }
            });

            checkbox.val('0');
        });
    </script>

@endsection