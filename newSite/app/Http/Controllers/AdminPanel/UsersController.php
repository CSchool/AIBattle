<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\User;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function showUsers() {
        return view('adminPanel/users', ['users' => User::orderBy('id')->simplePaginate(10)]);
    }
}
