@extends('layouts.mainLayout')

@section('title', 'Main page')

@section('content')
    <div class="container">
        <div class="page-header text-center">
            <h1>Hello, world!</h1>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems ...<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <?php var_dump($user); ?>
        </div>

    </div>
@endsection