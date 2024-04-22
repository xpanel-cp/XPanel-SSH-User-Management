<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Users;
use App\Models\Xguard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use DateTime;

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
            Process::run("sudo xp_user_limit add {$request->username} {$request->multiuser}");

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
        $status_user = Users::where('username',$request->username)->get();
        $multiuser=$status_user[0]->multiuser;
        if ($check_user > 0) {
            if ($status_user[0]->status == 'active') {
                Process::run("sudo killall -u {$request->username}");
                Process::run("sudo pkill -u {$request->username}");
                Process::run("sudo timeout 10 pkill -u {$request->username}");
                Process::run("sudo timeout 10 killall -u {$request->username}");
                Process::run("sudo userdel -r {$request->username}");
                Process::run("sudo xp_user_limit del {$request->username} {$multiuser}");
                Users::where('username', $request->username)->delete();
                Traffic::where('username', $request->username)->delete();
                return response()->json(['message' => 'User Deleted']);
            } else {
                Users::where('username', $request->username)->delete();
                Traffic::where('username', $request->username)->delete();
                Process::run("sudo xp_user_limit del {$request->username} {$multiuser}");
            }
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
        $settings = Settings::all();
        $tls_port=$settings[0]->tls_port;
        $check_user = Users::where('username', $username)->count();
        $xguard = Xguard::all();
        if(env('XGUARD')=='active' AND !empty($xguard[0]->domain))
        {
            $port_ssh=$xguard[0]->port;
        }
        else {
            $port_ssh=env('PORT_SSH');
        }
        if ($check_user > 0) {
            $user=Users::where('username', $username)->with('traffics')->get();
            $user[] = [
                "port_direct" => $port_ssh,
                "port_tls" => $tls_port,
                "port_dropbear" => env('PORT_DROPBEAR'),
                "message" => 'success'
            ];
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
                Process::run("sudo xp_user_limit del {$request->username} {$request->multiuser}");
            } else {
                Process::run("sudo killall -u {$request->username}");
                Process::run("sudo pkill -u {$request->username}");
                Process::run("sudo timeout 10 pkill -u {$request->username}");
                Process::run("sudo timeout 10 killall -u {$request->username}");
                Process::run("sudo userdel -r {$request->username}");
                Process::run("sudo xp_user_limit del {$request->username} {$request->multiuser}");
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
        $user = Users::where('username',$request->username)->get();
        $multiuser=$user[0]->multiuser;
        if ($check_user > 0) {
            Users::where('username', $request->username)->update(['status' => 'active']);
            $user = Users::where('username', $request->username)->get();
            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user[0]->username}");
            Process::input($user[0]->password."\n".$user[0]->password."\n")->timeout(120)->run("sudo passwd {$request->username}");
            Process::run("sudo xp_user_limit add {$request->username} {$multiuser}");

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
        $user = Users::where('username',$request->username)->get();
        $multiuser=$user[0]->multiuser;
        if ($check_user > 0) {
            Users::where('username', $request->username)->update(['status' => 'deactive']);
            Process::run("sudo killall -u {$request->username}");
            Process::run("sudo pkill -u {$request->username}");
            Process::run("sudo timeout 10 pkill -u {$request->username}");
            Process::run("sudo timeout 10 killall -u {$request->username}");
            Process::run("sudo userdel -r {$request->username}");
            Process::run("sudo xp_user_limit del {$request->username} {$multiuser}");
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
        $user = Users::where('username',$request->username)->get();
        $multiuser=$user[0]->multiuser;
        if ($check_user > 0) {
            Users::where('username', $request->username)
                ->update(['status' => 'active', 'end_date' => $newdate]);
            $user = Users::where('username', $request->username)->get();
            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user[0]->username}");
            Process::input($user[0]->password."\n".$user[0]->password."\n")->timeout(120)->run("sudo passwd {$request->username}");
            Process::run("sudo xp_user_limit add {$request->username} {$multiuser}");

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
    public function traffic_user(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username' => 'required|string',
            'traffic' => 'required|numeric',
            'type_traffic' => 'required|string',
        ]);
        $this->checktoken($request->token);
        $check_user = Users::where('username', $request->username)->count();
        $user = Users::where('username',$request->username)->get();
        $multiuser=$user[0]->multiuser;
        if ($check_user > 0) {
            if ($request->type_traffic == "gb") {
                $traffic = $request->traffic * 1024;
            } else {
                $traffic = $request->traffic;
            }
            Users::where('username', $request->username)->increment('traffic', $traffic);
            Users::where('username', $request->username)->update(['status' => 'active']);
            $user = Users::where('username', $request->username)->get();
            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user[0]->username}");
            Process::input($user[0]->password."\n".$user[0]->password."\n")->timeout(120)->run("sudo passwd {$request->username}");
            Process::run("sudo xp_user_limit add {$request->username} {$multiuser}");

            return response()->json(['message' => 'User Add Traffic']);
        }
        else
        {
            return response()->json(['message' => 'Not Exist User']);
        }
    }
    public function online_user(Request $request,$token)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Token');
        }
        $this->checktoken($token);
        $duplicate = [];
        $data = [];

        $list = Process::run("sudo lsof -i :" . env('PORT_SSH') . " -n | grep -v root | grep ESTABLISHED");
        $output = $list->output();
        $onlineuserlist = preg_split("/\r\n|\n|\r/", $output);

        foreach ($onlineuserlist as $user) {
            $user = preg_replace("/\\s+/", " ", $user);
            if (strpos($user, ":AAAA") !== false) {
                $userarray = explode(":", $user);
            } else {
                $userarray = explode(" ", $user);
            }
            if (!isset($userarray[8])) {
                $userarray[8] = null;
            }
            if (isset($userarray[8])) {
                $ip = explode('->', $userarray[8]);
                $ip = explode(':', $ip[1]);
                $userip = $ip[0];
            }

            if (!isset($userarray[2])) {
                $userarray[2] = null;
            }
            $connection = "sub connection";
            if (!in_array($userarray[2], $duplicate)) {
                $connection = "one connection";
                array_push($duplicate, $userarray[2]);
            }
            if (!empty($userarray[1]) && !empty($userarray[2]) && $userarray[2] !== "sshd" && $userarray[2] !== "root") {
                $data[] = [
                    "username" => $userarray[2],
                    "connection" => $connection,
                    "ip" => $userip,
                    "pid" => $userarray[1]
                ];
            }
        }
        $data = json_decode(json_encode($data));
        return response()->json($data);
    }
    public function kill(Request $request, $token,$method,$param)
    {
        if (!is_string($method) and !is_string($param) and !is_string($token)) {
            abort(400, 'Not Valid Method and Param');
        }
        $this->checktoken($token);
        if($method=='user')
        {
            Process::run("sudo killall -u {$param}");
            Process::run("sudo pkill -u {$param}");
            Process::run("sudo timeout 10 pkill -u {$param}");
            Process::run("sudo timeout 10 killall -u {$param}");
        }
        elseif($method=='id')
        {
            Process::run("sudo kill -9 {$param}");
        }

        return response()->json(['message' => 'User Killed']);
    }
    public function backup(Request $request, $token)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Method and Param');
        }
        $this->checktoken($token);
        $date = date("Y-m-d---h-i-s");
        $ip_bk = str_replace(".", "-", $_SERVER["SERVER_ADDR"]);
        Process::run("mysqldump -u '" .env('DB_USERNAME'). "' --password='" .env('DB_PASSWORD'). "' XPanel_plus > /var/www/html/app/storage/backup/".$ip_bk."-XPanel-".$date.".sql");
        $download=$_SERVER["SERVER_ADDR"].':'.env('PORT_PANEL')."/api/$token/backup/dl/".$ip_bk."-XPanel-".$date.".sql";

        return response()->json(['message' => 'Backup Maked','link' => $download]);
    }
    public function download_backup(Request $request,$token,$name)
    {

        if (!is_string($name) and !is_string($token)) {
            abort(400, 'Not Valid Username');
        }
        $this->checktoken($token);
        $fileName = $name;
        $filePath = storage_path('backup/'.$fileName);

        if (file_exists('/var/www/html/app/storage/backup/'.$fileName)) {
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment',
            ])->deleteFileAfterSend(true);
        }
    }
    public function filtering(Request $request,$token)
    {
        if (!is_string($token)) {
            abort(400, 'Not Valid Token');
        }
        $this->checktoken($token);
        $data = [];
        $serverip = $_SERVER["SERVER_ADDR"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://check-host.net/check-tcp?host=" . $serverip.":".env('PORT_SSH')."&max_nodes=50");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = ["Accept: application/json", "Cache-Control: no-cache"];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $array = json_decode($response, true);
        $resultlink = "https://check-host.net/check-result/" . $array["request_id"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $resultlink);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = ["Accept: application/json", "Cache-Control: no-cache"];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        sleep(3);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $array2 = json_decode($server_output, true);
        foreach ($array2 as $key => $value) {
            $flag = str_replace(".node.check-host.net", "", $key);
            if (is_numeric($value[0]["time"])) {
                $status = "Online";
            } else {
                $status = "Filter";
            }
            $data[] = [
                "location" => $flag,
                "status" => $status
            ];

        }
        $data = json_decode(json_encode($data));
        return response()->json($data);
    }

    public function sync_check(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $check_user = Users::where('username', $request->username)->where('password', $request->password)->where('status', 'active')->count();
        if($check_user>0)
        {
            return response()->json(['message' => 'success']);
        }
        else
        {
            return response()->json(['message' => 'error']);
        }
    }

    public function sync_user(Request $request,$user,$pass)
    {
        if (!is_string($user) and !is_string($pass)) {
            abort(400, 'Not Validate');
        }
        $settings = Settings::all();
        $tls_port=$settings[0]->tls_port;
        $check_user = Users::where('username', $user)->where('password', $pass)->where('status', 'active')->count();
        if ($check_user > 0) {
            $user=Users::where('username', $user)->with('traffics')->get();
            if($user[0]->traffic>0) {
                if (1024 <= $user[0]->traffic) {
                    $trafficValue = floatval($user[0]->traffic);
                    $total = round($trafficValue / 1024, 3) . ' GB';
                } else {
                    $total = $user[0]->traffic . ' MB';
                }
            }
            else
            {
                $total='Unlimit';
            }

            if (1024 <= $user[0]['traffics'][0]['total']) {
                $trafficValue = floatval($user[0]['traffics'][0]['total']);
                $total_usage = round($trafficValue / 1024, 3) . ' GB';
            } else {
                $total_usage = $user[0]['traffics'][0]['total'] . ' MB';
            }
            $end_date = $user[0]->end_date;
            if (!empty($end_date)) {
                $start_inp = date("Y-m-d");
                $today = new DateTime($start_inp); // تاریخ امروز
                $futureDate = new DateTime($end_date);
                if ($today > $futureDate) {
                    $interval = $futureDate->diff($today);
                    $daysDifference = -1 * $interval->days; // تعداد روزهای منفی برای تاریخ‌های گذشته
                } else {
                    $interval = $today->diff($futureDate);
                    $daysDifference = $interval->days;
                }
            } else {
                $daysDifference = 'Unlimit';
            }
            $user_detail = [
                "message" => 'success',
                "username" => $user[0]->username,
                "traffic" => $total,
                "traffic_usage" => $total_usage,
                "validity_day" => $daysDifference
            ];
            return response()->json($user_detail);
        }
        else
        {
            return response()->json(['message' => 'NotValidate']);
        }
    }

}
