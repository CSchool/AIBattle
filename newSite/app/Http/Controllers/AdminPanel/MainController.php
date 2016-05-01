<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    //
    public function showView()
    {
        return view('adminPanel/main', ['user' => Auth::user()]);
    }
}
