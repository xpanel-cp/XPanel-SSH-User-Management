<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');
    }
    public function index()
    {
        $user = Auth::user();
        $password_auto = Str::random(8);
        if($user->permission=='admin')
        {
            $users = Users::orderBy('id', 'desc')->get();
        }
        else{
            $users = Users::where('customer_user', $user->username)->orderby('id', 'desc')->get();
        }
        $settings = Settings::all();

        return view('users.home', compact('users', 'settings','password_auto'));
    }
    public function create()
    {
        $password_auto = Str::random(8);
        return view('users.create', compact('password_auto'));
    }

    public function newuser(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'username'=>'required|string',
            'password'=>'required|string',
            'email'=>'nullable|string',
            'mobile'=>'nullable|string',
            'multiuser'=>'required|numeric',
            'connection_start'=>'nullable|numeric',
            'traffic'=>'required|numeric',
            'expdate'=>'nullable|date_format:Y-m-d|after:today',
            'type_traffic'=>'required|string',
            'desc'=>'nullable|string'
        ]);

        if (!empty($request->connection_start)) {
            $start_date = '';
        } else {
            $start_date = date("Y-m-d");
        }
        if ($request->type_traffic == "gb") {
            $traffic = $request->traffic * 1024;
        } else {
            $traffic = $request->traffic;
        }
        $check_user = Users::where('username',$request->username)->count();
        if ($check_user < 1) {
            DB::beginTransaction();
            $user = Users::create([
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'multiuser' => $request->multiuser,
                'start_date' => $start_date,
                'end_date' => $request->expdate,
                'date_one_connect' => $request->connection_start,
                'customer_user' => $user->username,
                'status' => 'active',
                'traffic' => $traffic,
                'referral' => '',
                'desc' => $request->desc
            ]);

            Traffic::create([
                'username' => $user->username,
                'download' => '0',
                'upload' => '0',
                'total' => '0'
            ]);

            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user->username}");
            Process::input($user->password."\n".$user->password."\n")->timeout(120)->run("sudo passwd {$user->username}");

            DB::commit();
        }

        return redirect()->intended(route('users'));
    }

    public function bulkuser(Request $request)
    {
        $user_s = Auth::user();
        $request->validate([
            'count_user' => 'required|numeric',
            'start_user' => 'required|string',
            'start_number' => 'required|numeric',
            'password' => 'nullable|string',
            'char_pass' => 'required|numeric',
            'multiuser' => 'required|numeric',
            'connection_start' => 'required|numeric',
            'traffic' => 'required|numeric',
            'type_traffic' => 'required|string',
            'pass_random' => 'required|string'
        ]);
        if ($request->type_traffic == "gb") {
            $traffic = $request->traffic * 1024;
        } else {
            $traffic = $request->traffic;
        }
        $start_number=$request->start_number;
        for ($i = 0; $i < $request->count_user; $i++) {
            if ($start_number < $start_number + $request->count_user) {
                $list_users[] = $request->start_user . $start_number;
                $start_number++;
            }
        }
        foreach ($list_users as $user) {
            if(empty($request->password))
            {
                if($request->pass_random=='number')
                {
                    $chars = "1234567890";
                }
                else
                {
                    $chars = "abcdefghijklmnopqrstuvwxyz1234567890";
                }
                $password = substr( str_shuffle( $chars ), 0, $request->char_pass );
            }
            else
            {
                $password=$request->password;
            }
            $check_user = Users::where('username',$request->username)->count();
            if ($check_user < 1) {
                DB::beginTransaction();
                $user = Users::create([
                    'username' => $user,
                    'password' => $password,
                    'email' => '',
                    'mobile' => '',
                    'multiuser' => $request->multiuser,
                    'start_date' => '',
                    'end_date' => '',
                    'date_one_connect' => $request->connection_start,
                    'customer_user' => $user_s->id,
                    'status' => 'active',
                    'traffic' => $traffic,
                    'referral' => '',
                    'desc' => ''
                ]);

                Traffic::create([
                    'username' => $user->username,
                    'download' => '0',
                    'upload' => '0',
                    'total' => '0'
                ]);

                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user->username}");
                Process::input($user->password."\n".$user->password."\n")->timeout(120)->run("sudo passwd {$user->username}");

                DB::commit();

            }
        }
        return redirect()->intended(route('users'));
    }
    public function activeuser(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $user = Auth::user();
        if($user->permission=='admin')
        {
            $check_user = Users::where('username',$username)->count();
            if ($check_user > 0) {
                Users::where('username', $username)->update(['status' => 'active']);

                $user = Users::where('username',$username)->get();
                $password=$user[0]->password;
                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                Process::input($password."\n".$password."\n")->timeout(120)->run("sudo passwd {$username}");
            }
        }
        else{
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Users::where('username', $username)->update(['status' => 'active']);

                $user = Users::where('username',$username)->get();
                $password=$user[0]->username;
                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                Process::input($password."\n".$password."\n")->timeout(120)->run("sudo passwd {$username}");
            }
        }

        return redirect()->back()->with('success', 'Activated');
    }
    public function deactiveuser(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $user = Auth::user();
        if($user->permission=='admin') {
            $check_user = Users::where('username',$username)->count();
            if ($check_user > 0) {
                Users::where('username', $username)->update(['status' => 'deactive']);
                Process::run("sudo killall -u {$username}");
                Process::run("sudo pkill -u {$username}");
                Process::run("sudo timeout 10 pkill -u {$username}");
                Process::run("sudo timeout 10 killall -u {$username}");
                Process::run("sudo userdel -r {$username}");
            }
        }
        else{
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Users::where('username', $username)->update(['status' => 'deactive']);
                Process::run("sudo killall -u {$username}");
                Process::run("sudo pkill -u {$username}");
                Process::run("sudo timeout 10 pkill -u {$username}");
                Process::run("sudo timeout 10 killall -u {$username}");
                Process::run("sudo userdel -r {$username}");
            }
        }
        return redirect()->back()->with('success', 'Deactivated');

    }
    public function reset_traffic(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $user = Auth::user();
        if($user->permission=='admin') {
            $check_user = Users::where('username',$username)->count();
            if ($check_user > 0) {
                Traffic::where('username', $username)->update(['download' => '0', 'upload' => '0', 'total' => '0']);
            }
        }
        else
        {
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Traffic::where('username', $username)->update(['download' => '0', 'upload' => '0', 'total' => '0']);
            }
        }
        return redirect()->back()->with('success', 'Reset Traffic');
    }

    public function delete(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $user = Auth::user();
        if($user->permission=='admin')
        {
            $check_user = Users::where('username',$username)->count();
            if ($check_user > 0) {

                Process::run("sudo killall -u {$username}");
                Process::run("sudo pkill -u {$username}");
                Process::run("sudo timeout 10 pkill -u {$username}");
                Process::run("sudo timeout 10 killall -u {$username}");
                $userdelProcess =Process::run("sudo userdel -r {$username}");
                if ($userdelProcess->successful()) {
                    Users::where('username', $username)->delete();
                    Traffic::where('username', $username)->delete();
                }
            }
        }
        else {
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Process::run("sudo killall -u {$username}");
                Process::run("sudo pkill -u {$username}");
                Process::run("sudo timeout 10 pkill -u {$username}");
                Process::run("sudo timeout 10 killall -u {$username}");
                $userdelProcess =Process::run("sudo userdel -r {$username}");
                if ($userdelProcess->successful()) {
                    Users::where('username', $username)->delete();
                    Traffic::where('username', $username)->delete();
                }
            }
        }
        return redirect()->back()->with('success', 'Deleted');
    }
    public function delete_bulk(Request $request)
    {

        $user = Auth::user();
        if ($user->permission == 'admin') {
            foreach ($request->usernamed as $username) {
                $check_user = Users::where('username',$username)->count();
                if ($check_user > 0) {
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess =Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $username)->delete();
                        Traffic::where('username', $username)->delete();
                    }
                }
            }
        } else {
            foreach ($request->usernamed as $username) {
                $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
                if ($check_user > 0) {
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess =Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $username)->delete();
                        Traffic::where('username', $username)->delete();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Deleted');
    }

    public function renewal(Request $request)
    {
        $request->validate([
            'username_re' => 'required|string',
            'day_date' => 'required|numeric',
            're_date' => 'required|string',
            're_traffic' => 'required|string'
        ]);
        $newdate = date("Y-m-d");
        $newdate = date('Y-m-d', strtotime($newdate . " + $request->day_date days"));
        $user = Auth::user();
        if($user->permission=='admin') {
            $check_user = Users::where('username', $request->username_re)->count();
            if ($check_user > 0) {
                Users::where('username', $request->username_re)->update(['status' => 'active', 'end_date' => $newdate]);

                $user = Users::where('username', $request->username_re)->get();
                $username=$user[0]->username;
                $password=$user[0]->password;
                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                Process::input($password."\n".$password."\n")->timeout(120)->run("sudo passwd {$username}");
                if ($request->re_date == 'yes') {
                    Users::where('username', $request->username_re)->update(['start_date' => date("Y-m-d")]);
                }
                if ($request->re_traffic == 'yes') {
                    Traffic::where('username', $request->username_re)->update(['download' => '0', 'upload' => '0', 'total' => '0']);

                }
            }
        }
        else
        {
            $check_user = Users::where('username', $request->username_re)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Users::where('username', $request->username_re)->update(['status' => 'active', 'end_date' => $newdate]);

                $user = Users::where('username', $request->username_re)->get();
                $username=$user[0]->username;
                $password=$user[0]->password;
                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                Process::input($password."\n".$password."\n")->timeout(120)->run("sudo passwd {$username}");
                if ($request->re_date == 'yes') {
                    Users::where('username', $request->username_re)->update(['start_date' => date("Y-m-d")]);

                }
                if ($request->re_traffic == 'yes') {
                    Traffic::where('username', $request->username_re)->update(['download' => '0', 'upload' => '0', 'total' => '0']);

                }
            }
        }

        return redirect()->back()->with('success', 'Renewal Success');
    }
    public function edit(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $user = Auth::user();
        if($user->permission=='admin') {
            $check_user = Users::where('username', $username)->count();
            if ($check_user > 0) {
                $user = Users::where('username', $username)->get();
                $show = $user[0];
                return view('users.edit', compact('show'));
            } else {
                return redirect()->back()->with('success', 'Not User');
            }
        }
        else{
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                $user = Users::where('username', $request->username_re)->get();
                $show = $user[0];
                return view('users.edit', compact('show'));
            } else {
                return redirect()->back()->with('success', 'Not User');
            }
        }

    }
    public function update(Request $request)
    {
        $request->validate([
            'username'=>'required|string',
            'password'=>'required|string',
            'email'=>'nullable|string',
            'mobile'=>'nullable|string',
            'multiuser'=>'required|numeric',
            'traffic'=>'required|numeric',
            'expdate'=>'nullable|date_format:Y-m-d|after:today',
            'type_traffic'=>'required|string',
            'activate'=>'required|string',
            'desc'=>'nullable|string'
        ]);
        if ($request->type_traffic == "gb") {
            $traffic = $request->traffic * 1024;
        } else {
            $traffic = $request->traffic;
        }
        $user = Auth::user();
        if($user->permission=='admin') {
            $check_user = Users::where('username', $request->username)->count();
            if ($check_user > 0) {
                Users::where('username', $request->username)
                    ->update([
                        'password' => $request->password,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'multiuser' => $request->multiuser,
                        'traffic' => $traffic,
                        'end_date' => $request->expdate,
                        'status' => $request->activate,
                        'desc' => $request->desc
                    ]);
                if ($request->activate == "active") {
                    Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$request->username}");
                    Process::input($request->password."\n".$request->password."\n")->timeout(120)->run("sudo passwd {$request->username}");
                } else {
                    Process::run("sudo killall -u {$request->username}");
                    Process::run("sudo pkill -u {$request->username}");
                    Process::run("sudo timeout 10 pkill -u {$request->username}");
                    Process::run("sudo timeout 10 killall -u {$request->username}");
                    Process::run("sudo userdel -r {$request->username}");
                }
                if ($user->password != $request->password) {
                    Process::input($request->password."\n".$request->password."\n")->timeout(120)->run("sudo passwd {$request->username}");

                }
            }
        }
        else
        {
            $check_user = Users::where('username', $request->username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Users::where('username', $request->username)
                    ->update([
                        'password' => $request->password,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'multiuser' => $request->multiuser,
                        'traffic' => $traffic,
                        'end_date' => $request->expdate,
                        'status' => $request->activate,
                        'desc' => $request->desc
                    ]);
                if ($request->activate == "active") {
                    Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$request->username}");
                    Process::input($request->password."\n".$request->password."\n")->timeout(120)->run("sudo passwd {$request->username}");
                } else {
                    Process::run("sudo killall -u {$request->username}");
                    Process::run("sudo pkill -u {$request->username}");
                    Process::run("sudo timeout 10 pkill -u {$request->username}");
                    Process::run("sudo timeout 10 killall -u {$request->username}");
                    Process::run("sudo userdel -r {$request->username}");
                }
                if ($user->password != $request->password) {
                    Process::input($request->password."\n".$request->password."\n")->timeout(120)->run("sudo passwd {$request->username}");
                }
            }
        }
        return redirect()->back()->with('success', 'Update Success');
    }

}
