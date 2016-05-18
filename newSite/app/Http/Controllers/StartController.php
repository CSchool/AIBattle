<?php

namespace AIBattle\Http\Controllers;

use AIBattle\News;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StartController extends Controller
{
    //
    public function start() {
        $user = Auth::user();
        return view('start', ['news' => News::orderBy('id', 'desc')->simplePaginate(5)]);
    }

    public function switchLanguage($locale) {
        Session::put('userLocale', $locale);
        Session::save();

        return redirect(url()->previous());
    }
}
