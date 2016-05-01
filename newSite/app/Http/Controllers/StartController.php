<?php

namespace AIBattle\Http\Controllers;

use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use Illuminate\Support\Facades\Auth;

class StartController extends Controller
{
    //
    public function start() {
        $user = Auth::user();
        return view('start', ['user' => $user]);
    }
}
