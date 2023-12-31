<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admins;
use App\Models\Users;
use App\Models\Traffic;
use Illuminate\Support\Facades\Process;


class DahboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');
    }
    public function check()
    {
        $user = Auth::user();
        if($user->permission=='reseller')
        {
            exit(redirect()->intended(route('users')));
        }
    }
    public function reboot()
    {
        $this->check();
        Process::run("sudo reboot");
    }
    public function index()
    {
        $this->check();
        $high_usages = Traffic::activeUsers()->orderBy('total', 'desc')->limit(15)->get();
        $u_online=0;
        $u_online_drop=0;
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
            if (!isset($userarray[2])) {
                $userarray[2] = null;
            }
            if (!empty($userarray[2]) && $userarray[2] !== "sshd"&& $userarray[2] !== "root") {
                $u_online++;

            }
        }
        //Dropbear
        if (file_exists("/var/www/html/app/storage/dropbear.json")) {
            foreach ($onlineuserlist_drop as $user_drop) {
                $user_drop = preg_replace("/\\s+/", " ", $user_drop);

                $user_droparray = explode(" ", $user_drop);

                if (!isset($user_droparray[8])) {
                    $user_droparray[8] = null;
                }
                if (isset($user_droparray[8])) {
                    $ip = explode('->', $user_droparray[8]);
                    $ip = explode(':', $ip[1]);
                    $user_dropip = $ip[0];
                }
                if (isset($user_droparray[1])) {
                    $jsonFilePath = '/var/www/html/app/storage/dropbear.json';
                    $jsonData = file_get_contents($jsonFilePath);
                    $dataArray = json_decode($jsonData, true);
                    $targetPID = $user_droparray[1];
                    $foundUser = null;
                    foreach ($dataArray as $item) {
                        if (trim($item['PID']) === $targetPID) {
                            $u_online_drop++;
                        }
                    }
                }

            }
        }


        $free = shell_exec("free");
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem, function ($value) {
            return $value !== NULL && $value !== false && $value !== "";
        });
        $mem = array_merge($mem);
        $memtotal = round($mem[1] / 1000000, 2);
        $memused = round($mem[2] / 1000000, 2);
        $memfree = round($mem[3] / 1000000, 2);
        $memtotal = str_replace(" GB", "", $memtotal);
        $memused = str_replace(" GB", "", $memused);
        $memfree = str_replace(" GB", "", $memfree);
        $memtotal = str_replace(" MB", "", $memtotal);
        $memused = str_replace(" MB", "", $memused);
        $memfree = str_replace(" MB", "", $memfree);
        $usedperc = 100 / $memtotal * $memused;
        $exec_loads = sys_getloadavg();
        $exec_cores = Process::run("grep -P '^processor' /proc/cpuinfo|wc -l");
        $exec_cores = $exec_cores->output();
        $exec_cores = trim($exec_cores);
        $cpu = round($exec_loads[1] / ($exec_cores + 1) * 100, 0);
        $diskfree = round(disk_free_space(".") / 1000000000);
        $disktotal = round(disk_total_space(".") / 1000000000);
        $diskused = round($disktotal - $diskfree);
        $diskusage = round($diskused / $disktotal * 100);
        $traffic_rx = Process::run("netstat -e -n -i |  grep \"RX packets\" | grep -v \"RX packets 0\" | grep -v \" B)\"");
        $traffic_rx = $traffic_rx->output();
        $traffic_tx = Process::run("netstat -e -n -i |  grep \"TX packets\" | grep -v \"TX packets 0\" | grep -v \" B)\"");
        $traffic_tx = $traffic_tx->output();
        $res = preg_split("/\r\n|\n|\r/", $traffic_rx);
        $upload="0"; $download="0";
        foreach ($res as $resline) {
            $resarray = explode(" ", $resline);
            if (!isset($resarray[13])) {
                $resarray[13] = null;
            }
            if (is_numeric($resarray[13])) {
                $download += $resarray[13];
            }

        }

        $res = preg_split("/\r\n|\n|\r/", $traffic_tx);
        foreach ($res as $resline) {
            $resarray = explode(" ", $resline);
            if (!isset($resarray[13])) {
                $resarray[13] = null;
            }
            $upload += $resarray[13];
        }
        function formatBytes($bytes)
        {
            if ($bytes > 0) {
                $i = floor(log($bytes) / log(1024));
                $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                return sprintf('%.02F', round($bytes / pow(1024, $i), 1)) * 1 . ' ' . @$sizes[$i];
            } else {
                return 0;
            }
        }
        $total = $download;
        $total = formatBytes($total);
        $cpu_free = round($cpu);
        $ram_free = round($usedperc);
        $disk_free = round($diskusage);
        $all_user = Users::count();
        $active_user = Users::where('status', 'active')->count();
        $deactive_user = Users::where('status', 'deactive')->count();
        $expired_user = Users::where('status', 'expired')->count();
        $traffic_user = Users::where('status', 'traffic')->count();
        $traffic_total = Traffic::sum('total');

        $traffic_total = formatBytes(($traffic_total*1024)*1024);

        $alluser=$all_user;
        $online_user=$u_online+$u_online_drop;
        return view('dashboard.home', compact('alluser','active_user','expired_user','traffic_user','deactive_user','online_user','cpu_free','ram_free','disk_free','traffic_total','total','high_usages'));
    }

    public function usage()
    {
        $free = shell_exec("free");
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem, function ($value) {
            return $value !== NULL && $value !== false && $value !== "";
        });
        $mem = array_merge($mem);
        $memtotal = round($mem[1] / 1000000, 2);
        $memused = round($mem[2] / 1000000, 2);
        $memfree = round($mem[3] / 1000000, 2);
        $memtotal = str_replace(" GB", "", $memtotal);
        $memused = str_replace(" GB", "", $memused);
        $memfree = str_replace(" GB", "", $memfree);
        $memtotal = str_replace(" MB", "", $memtotal);
        $memused = str_replace(" MB", "", $memused);
        $memfree = str_replace(" MB", "", $memfree);
        $usedperc = 100 / $memtotal * $memused;
        $exec_loads = sys_getloadavg();
        $exec_cores = Process::run("grep -P '^processor' /proc/cpuinfo|wc -l");
        $exec_cores = $exec_cores->output();
        $exec_cores = trim($exec_cores);
        $cpu = round($exec_loads[1] / ($exec_cores + 1) * 100, 0);
        $cpu_free = round($cpu);
        $ram_free = round($usedperc);
        echo json_encode(array("cpuLoad" => $cpu_free, "ramUsage" => $ram_free));

    }

}
