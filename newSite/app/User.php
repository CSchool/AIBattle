<?php

namespace AIBattle;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'username', 'password',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'remember_token',
    ];

    private static function isInGroup($group)
    {
        $currentUser = Auth::user();

        if (!isset($currentUser))
            return false;

        $users = User::where('group', '=', $group)->get();

        if (!isset($users))
            return false;

        return $users->contains($currentUser);
    }

    public static function isAdmin()
    {
        $currentUser = Auth::user();

        if (!isset($currentUser))
            return false;

        return User::isInGroup('admin');
    }
}
