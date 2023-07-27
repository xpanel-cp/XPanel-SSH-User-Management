<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

class ApiController extends Controller
{

    public function checktoken($token)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Token');
        }
        if (Api::where('token', $token)->exists()) {
            $check = Api::where('token', $token)->get();
            if ($check[0]->allow_ip != '0.0.0.0/0') {
                $ipremote = $_SERVER['REMOTE_ADDR'];
                if ($check[0]->allow_ip != $ipremote) {
                    exit(view('access'));
                }
            }
        }
        else
        {
            exit(view('access'));
        }
    }
    public function listuser(Request $request, $token)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Token');
        }
        $this->checktoken($token);
        $users = Users::with('traffics')->orderby('id', 'desc')->get();
        return response()->json($users);
    }

    public function sort_listuser(Request $request,$token,$sort)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Token');
        }
        $this->checktoken($token);
        if (!is_string($sort)) {
            abort(400, 'Not Valid Token');
        }
        $users=Users::where('status', $sort)->with('traffics')->orderby('id', 'desc')->get();
        return response()->json($users);
    }

    public function add_user(Request $request)
    {

        $traffic='0';
        $request->validate([
            'token'=>'required|string',
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
        $this->checktoken($request->token);
        if($request->traffic>0)
        {$traffic=$request->traffic; }
        if (!empty($request->connection_start)) {
            $st_date = '';
        } else {
            $st_date = date("Y-m-d");
        }
        if ($request->type_traffic == "gb") {
            $traffic = $traffic * 1024;
        } else {
            $traffic = $traffic;
        }

        if (Users::where('username', $request->username)->exists()) {
            return response()->json(['message' => 'User Exist']);
        } else {
            Users::create([
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'multiuser' => $request->multiuser,
                'start_date' => $st_date,
                'end_date' => $request->expdate,
                'date_one_connect' => $request->connection_start,
                'customer_user' => 'API',
                'status' => 'active',
                'traffic' => $traffic,
                'referral' => '',
                'desc' => $request->desc
            ]);
            Traffic::create([
                'username' => $request->username,
                'download' => '0',
                'upload' => '0',
                'total' => '0'
            ]);

            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$request->username}");
            Process::input($request->password."\n".$request->password."\n")->timeout(120)->run("sudo passwd {$request->username}");

            return response()->json(['message' => 'User Created']);
        }

    }

    public function delete_user(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username' => 'required|string'
        ]);
        $this->checktoken($request->token);
        $check_user = Users::where('username', $request->username)->count();
        if ($check_user > 0) {
            Process::run("sudo killall -u {$request->username}");
            Process::run("sudo pkill -u {$request->username}");
            Process::run("sudo timeout 10 pkill -u {$request->username}");
            Process::run("sudo timeout 10 killall -u {$request->username}");
            Process::run("sudo userdel -r {$request->username}");
            Users::where('username', $request->username)->delete();
            Traffic::where('username', $request->username)->delete();
            return response()->json(['message' => 'User Deleted']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }

    public function show_detail(Request $request,$token,$username)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Token');
        }
        $this->checktoken($token);

        if (!is_string($username)) {
            abort(400, 'Not Valid Token');
        }

        $check_user = Users::where('username', $username)->count();
        if ($check_user > 0) {
            $user=Users::where('username', $username)->with('traffics')->get();
            return response()->json($user);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }

    public function edit(Request $request)
    {

        $request->validate([
            'token' => 'required|string',
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
        $this->checktoken($request->token);
        if ($request->type_traffic == "gb") {
            $traffic = $request->traffic * 1024;
        } else {
            $traffic = $request->traffic;
        }

        $check_user = Users::where('username', $request->username)->count();
        if ($check_user > 0) {
            $user = Users::where('username', $request->username)->get();
            $user = $user[0];
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
            if($user->password!=$request->password)
            {
                Process::input($request->password."\n".$request->password."\n")->timeout(120)->run("sudo passwd {$request->username}");

            }
            return response()->json(['message' => 'User Updated']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }
    public function active_user(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username' => 'required|string'
        ]);
        $this->checktoken($request->token);
        $check_user = DB::table('users')->where('username', $request->username)->count();
        if ($check_user > 0) {
            Users::where('username', $request->username)->update(['status' => 'active']);
            $user = Users::where('username', $request->username)->get();
            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user[0]->username}");
            Process::input($user[0]->password."\n".$user[0]->password."\n")->timeout(120)->run("sudo passwd {$request->username}");

            return response()->json(['message' => 'User Activated']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }
    public function deactive_user(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username' => 'required|string'
        ]);
        $this->checktoken($request->token);
        $check_user = Users::where('username', $request->username)->count();
        if ($check_user > 0) {
            Users::where('username', $request->username)->update(['status' => 'deactive']);
            Process::run("sudo killall -u {$request->username}");
            Process::run("sudo pkill -u {$request->username}");
            Process::run("sudo timeout 10 pkill -u {$request->username}");
            Process::run("sudo timeout 10 killall -u {$request->username}");
            Process::run("sudo userdel -r {$request->username}");
            return response()->json(['message' => 'User Deactivated']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }

    public function retraffic_user(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username' => 'required|string'
        ]);
        $this->checktoken($request->token);
        $check_user = Users::where('username', $request->username)->count();
        if ($check_user > 0) {
            Traffic::where('username', $request->username)->update(['download' => '0', 'upload' => '0', 'total' => '0']);
            return response()->json(['message' => 'User Reset Traffic']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }
    public function renewal_user(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username' => 'required|string',
            'day_date' => 'required|numeric',
            're_date' => 'required|string',
            're_traffic' => 'required|string'
        ]);
        $this->checktoken($request->token);
        $newdate = date("Y-m-d");
        $newdate = date('Y-m-d', strtotime($newdate . " + $request->day_date days"));
        $check_user = Users::where('username', $request->username)->count();
        if ($check_user > 0) {
            Users::where('username', $request->username)
                ->update(['status' => 'active', 'end_date' => $newdate]);
            $user = Users::where('username', $request->username)->get();
            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user[0]->username}");
            Process::input($user[0]->password."\n".$user[0]->password."\n")->timeout(120)->run("sudo passwd {$request->username}");

            if ($request->re_date == 'yes') {
                Users::where('username', $request->username)
                    ->update(['start_date' => date("Y-m-d")]);
            }
            if ($request->re_traffic == 'yes') {
                Traffic::where('username', $request->username)
                    ->update(['download' => '0', 'upload' => '0', 'total' => '0']);
            }
            return response()->json(['message' => 'User Renewal']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }


}
