<?php

namespace AIBattle\Http\Controllers;

use AIBattle\News;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use Illuminate\Support\Facades\Auth;

class StartController extends Controller
{
    //
    public function start() {
        $user = Auth::user();
        return view('start', ['user' => $user, 'news' => News::orderBy('id', 'desc')->simplePaginate(5)]);
    }
}
