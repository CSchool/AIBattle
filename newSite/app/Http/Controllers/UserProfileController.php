<?php

namespace AIBattle\Http\Controllers;

use AIBattle\User;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class UserProfileController extends Controller
{
    public function showAuthUserView() {
        $user = Auth::user();

        if (isset($user))
            return view('userProfile/profile', ['profileUser' => $user, 'user' => Auth::user()]);
        else
            abort(403); // maybe redirect to users list?
    }

    public function showUpdateProfileView() {
        $user = Auth::user();

        if (isset($user))
            return view('userProfile/updateForm', ['profileUser' => $user, 'user' => Auth::user(), 'isAdmin' => User::isAdmin()]);
        else
            abort(403);
    }
    
    public function showUpdateProfileToAdminView($id) {

        if (!User::isAdmin())
            abort(403);

        $user = User::find($id);

        if (isset($user))
            return view('userProfile/updateForm', ['profileUser' => $user, 'user' => Auth::user(), 'isAdmin' => User::isAdmin(), "updateProfile" => true]);
        else
            abort(404);
    }

    public function showUserView($id) {
        if (!User::isAdmin())
            abort(403);
        else {
            $user = User::find($id);

            if (isset($user))
                return view('userProfile/profile', ['profileUser' => $user, 'user' => Auth::user(), "fromAdminPanel" => true]);
            else
                abort(404);
        }
    }

    public function updateUserProfileSelf(Request $request) {
        $user = Auth::user();

        if (isset($user))
            return $this->updateUserProfile($request, $user->id, false);
        else
            abort(404);
    }

    public function updateUserProfileAdmin(Request $request, $id) {
        if (!User::isAdmin())
            abort(403);
        else {
            return $this->updateUserProfile($request, $id, true);
        }
    }

    public function updateUserProfile(Request $request, $id, $admin) {
        $validateArray = $admin === false ? ['surname' => 'required', 'name' => 'required'] : [];

        if ($request->has('passwordChangeCheckbox')) {
            $validateArray = array_add($validateArray, 'password', 'required|min:6|confirmed');
        }

        $this->validate($request, $validateArray);

        $user = User::find($id);

        if (isset($user)) {
            $user->surname = $request->input('surname');
            $user->name = $request->input('name');
            $user->patronymic = $request->input('patronymic');
            $user->description = $request->input('description');

            if (User::isAdmin())
                $user->group = $request->input('group');

            if ($request->has('passwordChangeCheckbox'))
                $user->password = bcrypt($request->input('password'));

            $user->save();

            if (!$admin)
                return redirect()->action('UserProfileController@showAuthUserView');
            else
                return redirect()->action('AdminPanel\UsersController@showUsers');
                //return redirect('adminPanel/users', ['users' => User::orderBy('id')->simplePaginate(10), 'user' => Auth::user()]);
        }
        else
            abort(404);
    }

}
