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
        $total = [];

        $list = Process::run("sudo lsof -i :" . env('PORT_SSH') . " -n | grep -v root | grep ESTABLISHED");
        $output = $list->output();
        $onlineuserlist = preg_split("/\r\n|\n|\r/", $output);

        $list_drop = Process::run("sudo lsof -i :" . env('PORT_DROPBEAR') . " -n | grep ESTABLISHED");
        $output_drop = $list_drop->output();
        $onlineuserlist_drop = preg_split("/\r\n|\n|\r/", $output_drop);

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
                    "pid" => $userarray[1],
                    "protocol" => "Direct | TLS | WEBSOCKET"
                ];
            }
        }
        //Dropbear
        if (file_exists("/var/www/html/app/storage/dropbear.json")) {
            $jsonFilePath = '/var/www/html/app/storage/dropbear.json';
            $jsonData = file_get_contents($jsonFilePath);
            $dataArray = json_decode($jsonData, true);

            $duplicate = [];
            if (!empty($dataArray)) {
                foreach ($onlineuserlist_drop as $user_drop) {

                    $user_drop = preg_replace("/\\s+/", " ", $user_drop);
                    $user_droparray = explode(" ", $user_drop);

                    if (isset($user_droparray[1]) && !empty($user_droparray[1]) && $user_droparray[1] !== null) {
                        $targetPID = $user_droparray[1];
                        if (isset($user_droparray[8])) {
                            $ip = explode('->', $user_droparray[8]);
                            $ip = explode(':', $ip[1]);
                            $user_dropip = $ip[0];
                        }
                        foreach ($dataArray as $item) {
                            if (trim($item['PID']) === $targetPID) {
                                $color = "#dc2626";
                                if (!in_array($user_droparray[2], $duplicate)) {
                                    $color = "#269393";
                                    array_push($duplicate, $user_droparray[2]);
                                }
                                $data[] = [
                                    "username" => $item['user'],
                                    "color" => $color,
                                    "ip" => $user_dropip,
                                    "pid" => $user_droparray[1],
                                    "protocol" => "Dropbear",
                                ];
                            }
                        }
                    }
                }
            }
        }
        $data = json_decode(json_encode($data), true);
        //dd($data);
        $uniqueUsernames = array();
        $uniquePids = array();
        $tot='';
        foreach ($data as $user) {
            $username=$user['username'];
            $currentPid = $user['pid'];

            if (in_array($username, $uniqueUsernames)) {
                $username = $username;
                $tot++;
            } else {
                $uniqueUsernames[] = $username;
                $uniquePids[$username] = $currentPid;
            }
            if ($currentPid == $uniquePids[$username]) {
                $color = "#269393";
            }
            else
            {
                $color="#dc2626";
            }
            $total[] = [
                "username" => $username,
                "color" => $color,
                "ip" => $user['ip'],
                "pid" => $user['pid'],
                "protocol" => $user['protocol']
            ];
        }
        $data = json_decode(json_encode($total));
        return view('users.online', compact('data'));
    }
    public function filtering()
    {
        $data = [];
        $serverip = $_SERVER["SERVER_ADDR"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://check-host.net/check-tcp?host=" . $serverip.":".env('PORT_SSH')."&max_nodes=40");
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
        $allowedFlags = ["ir", "us", "fr", "de"];

        $data = [];

        foreach ($array2 as $key => $value) {
            $flag = str_replace(".node.check-host.net", "", $key);
            $flag = preg_replace("/[0-9]+/", "", $flag);
            if (in_array($flag, $allowedFlags)) {
                $curl = curl_init();
                $url = "https://check-host.net/nodes/hosts";

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($curl);
                $ip_add = '';
                $location = '';
                if ($response !== false) {
                    $json_data = json_decode($response, true);

                    $searchKey = $key;
                    if (isset($json_data['nodes'][$searchKey])) {
                        $location = $json_data['nodes'][$searchKey]['location'];
                        $ip_add = $json_data['nodes'][$searchKey]['ip'];
                    }
                }
                curl_close($curl);

                if ($value === NULL) {
                    $status = "Filter";
                } else {
                    $status = "Online";
                }

                $data[] = [
                    "flag" => $flag,
                    "status" => $status,
                    "ip" => $ip_add,
                    "location" => $location
                ];
            }
        }
        $data = json_decode(json_encode($data));
        return view('dashboard.filtering', compact('data'));
    }
}
