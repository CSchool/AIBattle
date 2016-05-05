@extends('layouts.mainLayout')

@section('title', 'Update user profile')

@section('content')

    <div class="container">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-primary">

                <div class="panel-heading">Update profile</div>
                <div class="panel-body">
                    {{ Form::open() }}

                    @if (isset($isAdmin) && $isAdmin === true)
                        <div class="form-group">
                            <label for="group">Group:</label>
                            <select class="form-control" name="group" id="group">
                                <option value="user"    @if ($profileUser->group == "user") selected @endif>User</option>
                                <option value="moder"   @if ($profileUser->group == "moder") selected @endif>Moderator</option>
                                <option value="news"    @if ($profileUser->group == "news") selected @endif>Newsmaker</option>
                                <option value="admin"   @if ($profileUser->group == "admin") selected @endif>Admin</option>
                                <option value="banned"  @if ($profileUser->group == "banned") selected @endif>Banned</option>
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="title">Surname:</label>
                        <input type="text" class="form-control" name="surname" id="surname" value="{{ $profileUser->surname }}" />
                    </div>

                    <div class="form-group">
                        <label for="title">Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $profileUser->name }}" />
                    </div>

                    <div class="form-group">
                        <label for="title">Patronymic:</label>
                        <input type="text" class="form-control" name="patronymic" id="patronymic" value="{{ $profileUser->patronymic }}" />
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" style="resize:vertical" rows="3" name="description" id="description">{{ $profileUser->description }}</textarea>
                    </div>

                    <div class="checkbox">
                        <label>{!! Form::checkbox('passwordChangeCheckbox', '1', false) !!} Change password?</label>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control hidden" name="password" id="password" placeholder="Password" />
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control hidden" name="password_confirmation" id="password_confirmation" placeholder="Password conformation" />
                    </div>

                    <button type="submit" name="update" class="btn btn-lg btn-success btn-block">Update profile</button>

                    {{ Form::close() }}
                </div>
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