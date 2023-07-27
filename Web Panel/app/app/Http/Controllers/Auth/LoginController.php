<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Route;
use App\Models\Admins;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admins', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        $pssword= env('DB_PASSWORD');
        $data2 = Admins::find(1);
        $data2->password = Hash::make($pssword);
        $data2->update();
        return view('auth.login');
    }

    public function login(Request $request)
    {

        // Validate the form data
        $this->validate($request, [
            'username'   => 'required',
            'password' => 'required'
        ]);
        // Attempt to log the user in
        if (Auth::guard('admins')->attempt(['username' => $request->username, 'password' => $request->password,'status'=>'active'])) {
            // if successful, then redirect to their intended location
            return redirect()->intended(route('dashboard'));
        }
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('username', 'remember'));
    }

    public function logout()
    {
        Auth::guard('admins')->logout();
        return redirect('/login');
    }
}
