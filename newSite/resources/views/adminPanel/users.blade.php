@extends('layouts.adminPanelLayout')

@section('title', 'Administration Panel - Users')
@section('APtitle', 'Administration Panel - Users')

@section('APcontent')

    @if (isset($users) && count($users) > 0)
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Login</td>
                    <td>Group</td>
                    <td>Surname</td>
                    <td>Name</td>
                    <td>Patronymic</td>
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
                        <td>{{ $element->group }}</td>
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
            There is no users in the system!
        </div>
    @endif

@endsection