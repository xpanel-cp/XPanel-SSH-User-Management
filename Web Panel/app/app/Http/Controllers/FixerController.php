<?php

namespace App\Http\Controllers;

use App\Models\Fixer;
use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

class FixerController extends Controller
{

    public function cronexp()
    {
        $list = Process::run("ls /home");
        $output = $list->output();
        $list_user = preg_split("/\r\n|\n|\r/", $output);
        foreach ($list_user as $us)
        {
            $check_user = DB::table('users')->where('username', $us)->count();
            if ($check_user < 0) {
                Process::run("sudo killall -u {$us}");
                Process::run("sudo pkill -u {$us}");
                Process::run("sudo timeout 10 pkill -u {$us}");
                Process::run("sudo timeout 10 killall -u {$us}");
                Process::run("sudo userdel -r {$us}");
            }

        }

        $users = Users::where('status', 'active')->get();
        foreach ($users as $us) {
            if (!empty($us->end_date)) {
                $expiredate = strtotime(date("Y-m-d", strtotime($us->end_date)));
                if ($expiredate < strtotime(date("Y-m-d")) || $expiredate == strtotime(date("Y-m-d"))) {
                    $username=$us->username;
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess =Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $us->username)
                            ->update(['status' => 'expired']);
                    }
                }
            }
        }

        $users = Users::all();
        foreach ($users as $us) {
            $traffic = Traffic::where('username', $us->username)->get();
            foreach ($traffic as $usernamet)
            {
                $total=$usernamet->total;

                if ($us->traffic < $total && !empty($us->traffic) && $us->traffic > 0) {
                    Users::where('username', $us->username)
                        ->update(['status' => 'traffic']);
                    $username=$us->username;
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess =Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $us->username)
                            ->update(['status' => 'traffic']);
                    }

                }
            }

        }
    }

    public function multiuser()
    {

        $setting = Settings::all();
        $multiuser = $setting[0]->multiuser;


        $list = Process::run("sudo lsof -i :" . env('PORT_SSH') . " -n | grep -v root | grep ESTABLISHED");
        $output = $list->output();
        $onlineuserlist = preg_split("/\r\n|\n|\r/", $output);
        foreach ($onlineuserlist as $user) {
            $user = preg_replace('/\s+/', ' ', $user);
            $userarray = explode(" ", $user);
            if (!isset($userarray[2])) {
                $userarray[2] = null;
            }
            $onlinelist[] = $userarray[2];
        }
        //print_r($onlinelist);
        $onlinelist = array_replace($onlinelist, array_fill_keys(array_keys($onlinelist, null), ''));
        $onlinecount = array_count_values($onlinelist);

        foreach ($onlinelist as $useron) {
            $users = Users::where('username', $useron)->get();
            foreach ($users as $row) {
                $limitation = $row->multiuser;
                $username = $row->username;
                $startdate = $row->start_date;
                $finishdate_one_connect = $row->date_one_connect;
                if (empty($limitation)) {
                    $limitation = "0";
                }
                $userlist[$username] = $limitation;
                if (empty($startdate)) {
                    $use_active = $username . "|" . $onlinecount[$username];
                    $act_explode = explode("|", $use_active);
                    if ($act_explode[1] > 0) {
                        $start_inp = date("Y-m-d");
                        $end_inp = date('Y-m-d', strtotime($start_inp . " + $finishdate_one_connect days"));
                        Users::where('username', $act_explode[0])
                            ->update(['start_date' => $start_inp,'end_date' => $end_inp]);
                    }

                }
                if ($limitation !== "0" && $onlinecount[$username] > $limitation){
                    if ($multiuser == 'active') {
                        Process::run("sudo killall -u {$username}");
                        Process::run("sudo pkill -u {$username}");
                        Process::run("sudo timeout 10 pkill -u {$username}");
                        Process::run("sudo timeout 10 killall -u {$username}");
                    }
                }


                //header("Refresh:1");
            }
        }
        $this->synstraffics();
        $this->cronexp_traffic();
    }

    public function cronexp_traffic()
    {
        $users = Users::where('status', 'active')->get();
        foreach ($users as $us) {
            $traffic = Traffic::where('username', $us->username)->get();
            foreach ($traffic as $usernamet) {
                $total = $usernamet->total;

                if ($us->traffic < $total && !empty($us->traffic) && $us->traffic > 0) {
                    $username = $us->username;
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess =Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $us->username)
                            ->update(['status' => 'traffic']);
                    }
                }
            }
        }
    }

    public function synstraffics()
    {
        $list = Process::run("pgrep nethogs");
        $output = $list->output();
        $pid = preg_replace("/\\s+/", "", $output);
        // print_r($pid);
        if (file_exists("/var/www/html/app/storage/out.json")) {
            $out = file_get_contents("/var/www/html/app/storage/out.json");
            $trafficlog = preg_split("/\r\n|\n|\r/", $out);
            $trafficlog = array_filter($trafficlog);
            $lastdata = end($trafficlog);
            $json = json_decode($lastdata, true);

            $newarray = [];
            if (is_array($json)) {
                foreach ($json as $value) {
                    $TX = round($value["TX"], 0);
                    $RX = round($value["RX"], 0);
                    $name = preg_replace("/\\s+/", "", $value["name"]);
                    if (strpos($name, "sshd") === false) {
                        $name = "";
                    }
                    if (strpos($name, "root") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "/usr/sbin/dropbear") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "/usr/bin/stunnel4") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "unknown TCP") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "/usr/sbin/apache2") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "[net]") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "[accepted]") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "[rexeced]") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "@notty") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "root:sshd") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "/sbin/sshd") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "[priv]") !== false) {
                        $name = "";
                    }
                    if (strpos($name, "@pts/1") !== false) {
                        $name = "";
                    }
                    if ($value["RX"] < 1 && $value["TX"] < 1) {
                        $name = "";
                    }
                    $name = str_replace("sshd:", "", $name);
                    if (!empty($name)) {
                        if (isset($newarray[$name])) {
                            $newarray[$name]["TX"] + $TX;
                            $newarray[$name]["RX"] + $RX;
                        } else {
                            $newarray[$name] = ["RX" => $RX, "TX" => $TX, "Total" => $RX + $TX];
                        }
                    }
                }
                //$newarray= json_encode($newarray);
                foreach ($newarray as $username => $usr) {
                    $traffic = Traffic::where('username', $username)->get();
                    $user = $traffic[0];
                    $userdownload = $user->download;
                    $userupload = $user->upload;
                    $usertotal = $user->total;
                    $rx = round($usr["RX"]);
                    $rx = ($rx) / 10;
                    $rx = round(($rx / 12) * 100);
                    $tx = round($usr["TX"]);
                    $tx = ($tx) / 10;
                    $tx = round(($tx / 12) * 100);
                    $tot = $rx + $tx;
                    $lastdownload = $userdownload + $rx;
                    $lastupload = $userupload + $tx;
                    $lasttotal = $usertotal + $tot;

                    $check_traffic = Traffic::where('username', $username)->count();

                    if ($check_traffic < 1) {

                        Traffic::create([
                            'username' => $username,
                            'download' => $lastdownload,
                            'upload' => $lastupload,
                            'total' => $lasttotal
                        ]);

                    } else {
                        Traffic::where('username', $username)
                            ->update(['download' => $lastdownload, 'upload' => $lastupload, 'total' => $lasttotal]);
                    }
                }
            }
        }
        $settings = Settings::where('id', '1')->get();
        $multiuser = $settings[0]->multiuser;

        $list = Process::run("sudo lsof -i :" . env('PORT_SSH') . " -n | grep -v root | grep ESTABLISHED");
        $output = $list->output();
        $onlineuserlist = preg_split("/\r\n|\n|\r/", $output);
        foreach ($onlineuserlist as $user) {
            $user = preg_replace('/\s+/', ' ', $user);
            $userarray = explode(" ", $user);
            if (!isset($userarray[2])) {
                $userarray[2] = null;
            }

            $onlinelist[] = $userarray[2];
        }

        $onlinelist = array_replace($onlinelist, array_fill_keys(array_keys($onlinelist, null), ''));
        $onlinecount = array_count_values($onlinelist);

        foreach ($onlinelist as $useron) {
            $users = Users::where('username', $useron)->get();
            foreach ($users as $row) {
                $limitation = $row->multiuser;
                $username = $row->username;
                $startdate = $row->start_date;
                $finishdate_one_connect = $row->date_one_connect;
                if (empty($limitation)) {
                    $limitation = "0";
                }
                $userlist[$username] = $limitation;
                if (empty($startdate)) {

                    $use_active = $username . "|" . $onlinecount[$username];
                    $act_explode = explode("|", $use_active);
                    if ($act_explode[1] > 0) {
                        $start_inp = date("Y-m-d");
                        $end_inp = date('Y-m-d', strtotime($start_inp . " + $finishdate_one_connect days"));
                        Users::where('username', $act_explode[0])
                            ->update(['start_date' => $start_inp,'end_date' => $end_inp]);
                    }

                }
                if ($limitation !== "0" && $onlinecount[$username] > $limitation) {
                    if ($multiuser == 'on') {
                        Process::run("sudo killall -u {$username}");
                        Process::run("sudo pkill -u {$username}");
                        Process::run("sudo timeout 10 pkill -u {$username}");
                        Process::run("sudo timeout 10 killall -u {$username}");
                    }
                }
                //header("Refresh:1");
            }
            Process::run("sudo kill -9 {$pid}");
            Process::run("sudo killall -9 nethogs");

        }


        Process::run("sudo rm -rf /var/www/html/app/storage/out.json");
        Process::run("sudo nethogs -j -v3 -c6 > /var/www/html/app/storage/out.json");
        Process::run("sudo pkill nethogs");
    }


}
