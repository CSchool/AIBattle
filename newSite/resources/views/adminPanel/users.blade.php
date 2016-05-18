@extends('layouts.adminPanelLayout')

@section('title', trans('adminPanel/users.usersTitle'))
@section('APtitle', trans('adminPanel/users.usersHeader'))

@section('APcontent')

    @if (isset($users) && count($users) > 0)
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>{{ trans('adminPanel/users.usersLogin') }}</td>
                    <td>{{ trans('shared.group') }}</td>
                    <td>{{ trans('userProfile/shared.surname') }}</td>
                    <td>{{ trans('userProfile/shared.name') }}</td>
                    <td>{{ trans('userProfile/shared.patronymic') }}</td>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $element)
                    <tr class="
                    @if ($element->group == "admin")
                        info
                    @elseif ($element->group == "banned")
                        danger
                    @endif
                    ">
                        <td>{{ $element->id }}</td>
                        <td><a href="{{ url('/userProfile', [$element->id]) }}">{{ $element->username }}</a></td>
                        <td>{{ trans('shared.group' . ucfirst($element->group)) }}</td>
                        <td>{{ $element->surname }}</td>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->patronymic }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $users->render() !!}
    @else
        <div class="alert alert-warning">
            {{ trans('adminPanel/users.usersNoUsers') }}
        </div>
    @endif

@endsection