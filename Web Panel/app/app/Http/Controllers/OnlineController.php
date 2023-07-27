<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Process\ProcessResult;
use Auth;
use Illuminate\Support\Facades\DB;


class OnlineController extends Controller
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
    public function kill_pid(Request $request,$pid)
    {
        if (!is_numeric($pid)) {
            abort(400, 'Not Valid Username');
        }
        Process::run("sudo kill -9 {$pid}");
        return redirect()->back()->with('success', 'Killed');
    }

    public function kill_user(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        Process::run("sudo killall -u {$username}");
        return redirect()->back()->with('success', 'Killed');
    }
    public function index()
    {
        $this->check();
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
            $color = "#dc2626";
            if (!in_array($userarray[2], $duplicate)) {
                $color = "#269393";
                array_push($duplicate, $userarray[2]);
            }
            if (!empty($userarray[1]) && !empty($userarray[2]) && $userarray[2] !== "sshd" && $userarray[2] !== "root") {
                $data[] = [
                    "username" => $userarray[2],
                    "color" => $color,
                    "ip" => $userip,
                    "pid" => $userarray[1]
                ];
            }
        }
        $data = json_decode(json_encode($data));
        return view('users.online', compact('data'));
    }
    public function filtering()
    {
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
            $flag = preg_replace("/[0-9]+/", "", $flag);
            if ($flag == "ir" || $flag == "us" || $flag == "fr" || $flag == "de") {
                if (is_numeric($value[0]["time"])) {
                    $status = "Online";
                } else {
                    $status = "Filter";
                }
                $data[] = [
                    "flag" => $flag,
                    "status" => $status
                ];
            }
        }
        $data = json_decode(json_encode($data));
        return view('dashboard.filtering', compact('data'));
    }
}
