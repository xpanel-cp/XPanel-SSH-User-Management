<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');

    }
    public function check()
    {
        $user = Auth::user();
        if($user->permission=='reseller')
        {
            exit(view('access'));
        }
    }
    public function index()
    {
        $this->check();
        $admins = Admins::where('permission', 'reseller')->orderBy('id', 'desc')->get();

        return view('admins.index', compact('admins'));
    }

    public function insert(Request $request)
    {
        $this->check();
        $request->validate([
            'username'=>'required|string',
            'password'=>'required|string'
        ]);
        $hashedPassword = Hash::make($request->password);
        $check_user = Admins::where('username',$request->username)->count();
        if ($check_user < 1) {
            Admins::create([
                'username' => $request->username,
                'password' => $hashedPassword,
                'permission' => 'reseller',
                'credit' => '0',
                'status' => 'active'
            ]);
        }

        return redirect()->intended(route('admins'));
    }

    public function activeadmin(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            Admins::where('username', $username)
                ->update(['status' => 'active']);
        }
        return redirect()->back()->with('success', 'Activated');
    }

    public function deactiveadmin(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            Admins::where('username', $username)
                ->update(['status' => 'deactive']);
        }
        return redirect()->back()->with('success', 'Deactivated');
    }

    public function deleteadmin(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            Admins::where('username', $username)->delete();
        }
        return redirect()->back()->with('success', 'Deleted');
    }

    public function edit(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            $user = Admins::where('username', $username)
                ->get();
            $user = $user[0];
            return view('admins.edit')->with('show', $user);
        }
        else
        {
            return redirect()->back()->with('success', 'Not User');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        $hashedPassword = Hash::make($request->password);
        Admins::where('username', $request->username)
            ->where('permission', 'reseller')
            ->update(['password' => $hashedPassword]);
        return redirect()->back()->with('success', 'Update Success');
    }


}
