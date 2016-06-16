<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\User;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class UsersController extends Controller
{
    public function showUsers() {
        return view('adminPanel/users', ['users' => User::all()->count()]);
    }

    public function usersTable() {
        $users = User::select('id', 'username', 'group');

        return Datatables::of($users)
                ->editColumn('username', function($users) {
                    return '<a href="' . url('/userProfile', [$users->id]) . '" role="button">' . $users->username . '</a>';
                })
                ->setRowId('id')
                ->setRowClass(function ($users) {
                    if ($users->group == "admin")
                        return 'info';
                    else if ($users->group == "banned")
                        return 'danger';
                    else return '';
                })
                ->make(true);
    }
}
