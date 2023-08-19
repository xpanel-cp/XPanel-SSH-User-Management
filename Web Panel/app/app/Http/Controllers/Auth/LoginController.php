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
        $pssword=Hash::make($pssword);
        $check_user = Admins::where('username',env('DB_USERNAME'))->count();
        if ($check_user > 0) {
            Admins::where('username', env('DB_USERNAME'))->update(['password' => $pssword]);
        }
        else
        {
            Admins::create([
                'username' => env('DB_USERNAME'),
                'password' => $pssword,
                'permission' => 'admin',
                'credit' => '0',
                'status' => 'active'
            ]);
        }
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
