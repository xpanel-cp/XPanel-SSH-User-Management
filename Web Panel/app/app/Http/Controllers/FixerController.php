<?php

namespace App\Http\Controllers;

use App\Models\Fixer;
use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Verta;
use DateTime;
class FixerController extends Controller
{

    public function cronexp()
    {
        $list = Process::run("ls /home");
        $output = $list->output();
        $list_user = preg_split("/\r\n|\n|\r/", $output);
        foreach ($list_user as $us)
        {
            $check_user = Users::where('username', $us)->count();
            if ($check_user < 1 && $us!='videocall') {
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
                        $fileContent = file_get_contents("/etc/ssh/sshd_config");
                        $modifiedContent = str_replace("Match User {$username}", "", $fileContent);
                        $modifiedContent = str_replace("Banner /var/www/html/app/storage/banner/{$username}-detail", "", $modifiedContent);
                        $modifiedContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $modifiedContent);
                        file_put_contents("/etc/ssh/sshd_config", $modifiedContent);
                        Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                        Process::run("sudo service ssh restart");
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
                    $username=$us->username;
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess =Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        $fileContent = file_get_contents("/etc/ssh/sshd_config");
                        $modifiedContent = str_replace("Match User {$username}", "", $fileContent);
                        $modifiedContent = str_replace("Banner /var/www/html/app/storage/banner/{$username}-detail", "", $modifiedContent);
                        $modifiedContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $modifiedContent);
                        file_put_contents("/etc/ssh/sshd_config", $modifiedContent);
                        Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                        Process::run("sudo service ssh restart");
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

        if ($multiuser == 'active') {
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

            if (file_exists("/var/www/html/app/storage/dropbear.json")) {
                $jsonFilePath = '/var/www/html/app/storage/dropbear.json';
                $jsonData = file_get_contents($jsonFilePath);
                $dataArray = json_decode($jsonData, true);
                foreach ($dataArray as $item) {
                    $user = $item['user'];
                    $onlinelist[] = $user;
                }
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
                                ->update(['start_date' => $start_inp, 'end_date' => $end_inp]);
                        }

                    }
                    if ($limitation !== "0" && $onlinecount[$username] > $limitation) {

                        if (file_exists("/var/www/html/app/storage/dropbear.json")) {
                            foreach ($dataArray as $item) {
                                if (isset($item['user']) && $item['user'] === $username) {
                                    $pid = $item['PID'];
                                    Process::run("sudo kill -9 {$pid}");
                                }
                            }
                        }
                        Process::run("sudo killall -u {$username}");
                        Process::run("sudo pkill -u {$username}");
                        Process::run("sudo timeout 10 pkill -u {$username}");
                        Process::run("sudo timeout 10 killall -u {$username}");

                    }


                    //header("Refresh:1");
                }
            }
        }
        if(env('CRON_TRAFFIC', 'active')=='active') {
            $this->synstraffics();
            $this->cronexp_traffic();
            $this->synstraffics_drop();
        }
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
                //traffic log html
                if(!empty($us->end_date)) {
                    $start_inp = date("Y-m-d");
                    $today = new DateTime($start_inp);
                    $futureDate = new DateTime($us->end_date);
                    $interval = $today->diff($futureDate);
                    $daysDifference_day = $interval->days;
                }
                if(env('APP_LOCALE', 'en')=='fa') {
                    $startdate = Verta::instance($us->start_date)->formatWord('ds F');
                    if(!empty($us->end_date))
                    {$finishdate = Verta::instance($us->end_date)->formatWord('ds F');}
                    else
                    {$finishdate='بدون محدودیت';}

                    if ($us->traffic > 0)
                        if (1024 <= $us->traffic) {
                            $trafficValue = floatval($us->traffic);
                            $traffic_user = round($trafficValue / 1024, 3) . ' گیگابات';
                        } else {
                            $traffic_user = $us->traffic . ' مگابایت';
                        }
                    else {
                        $traffic_user = 'بدون محدودیت';
                    }
                    foreach ($us->traffics as $traffic) {
                        if (1024 <= $traffic->total) {

                            $trafficValue = floatval($traffic->total);
                            $total = round($trafficValue / 1024, 3) . ' گیگابایت';
                        } else {
                            $total = $traffic->total . ' مگابایت';
                        }
                    }
                    $day="";
                    if($us->status=='active' and !empty($us->end_date))
                    {$day="\n• اشتراک شما <span style='color: #e99c26'><b>$daysDifference_day</b></span> روز دیگر پایان خواهد یافت.\n";}
                    if($us->status=='deactive')
                    {$day= "\n• اشتراک شما <span style='color: #e92626'><b>غیرفعال</b></span> است.\n";}
                    if($us->status=='expired')
                    {$day= "\n• اشتراک شما <span style='color: #e98826'><b>منقضی شده</b></span> است.\n";}
                    if($us->status=='traffic')
                    {$day= "\n• اشتراک شما <span style='color: #26aee9'><b>ترافیک تمام کرده</b></span> است.\n";}

                    $traffic_log =
                        "
                        <div dir='rtl' style='text-align:right'>
کاربر <span style='color: #35cc00'><b>$us->username</b></span> عزیز
$day
┐ اطلاعات اشتراک شما
┤ تاریخ کانفیگ 🗓
&nbsp;⁞ ┤ تاریخ شروع کانفیگ: <span style='color: #e99c26'><b>$startdate</b></span>
&nbsp;⁞ ┘ تاریخ انقضای کانفیگ: <span style='color: #e99c26'><b>$finishdate</b></span>
&nbsp;⁞ 
┤ حجم کانفیگ 📊
&nbsp;⁞ ┤ ترافیک خریداری شده: <span style='color: #e99c26'><b>$traffic_user</b></span>
&nbsp;⁞ ┘ ترافیک مصرفی: <span style='color: #e99c26'><b>$total</b></span>
</div>
                        ";
                }
                else{
                    $startdate = $us->start_date;
                    if(!empty($us->end_date))
                    {$finishdate = $us->end_date;}
                    else
                    {$finishdate='Unlimited';}
                    if ($us->traffic > 0)
                        if (1024 <= $us->traffic) {
                            $trafficValue = floatval($us->traffic);
                            $traffic_user = round($trafficValue / 1024, 3) . ' GB';
                        } else {
                            $traffic_user = $us->traffic . ' MB';
                        }
                    else {
                        $traffic_user = 'Unlimited';
                    }
                    foreach ($us->traffics as $traffic) {
                        if (1024 <= $traffic->total) {

                            $trafficValue = floatval($traffic->total);
                            $total = round($trafficValue / 1024, 3) . ' GB';
                        } else {
                            $total = $traffic->total . ' MB';
                        }
                    }
                    $day="";
                    if ($us->status == 'active' && !empty($us->end_date)) {
                        $day = "\n• Your subscription will end in <span style='color: #e99c26'><b>$daysDifference_day</b></span> days.\n";
                    }
                    if ($us->status == 'deactive') {
                        $day = "\n• Your subscription is <span style='color: #e92626'><b>Inactive</b></span>.\n";
                    }
                    if ($us->status == 'expired') {
                        $day = "\n• Your subscription has <span style='color: #e98826'><b>Expired</b></span>.\n";
                    }
                    if ($us->status == 'traffic') {
                        $day = "\n• Your subscription has <span style='color: #26aee9'><b>Exhausted its traffic</b></span>.\n";
                    }

                    $traffic_log =
                        "
                        <div dir='ltr' style='text-align:left'>
Dear user <span style='color: #35cc00'><b>$us->username</b></span>,
$day
┐ Your subscription details
┤ Configuration Date 🗓
&nbsp;⁞ ┤ Start Date: <span style='color: #e99c26'><b>$startdate</b></span>
&nbsp;⁞ ┘ Expiry Date: <span style='color: #e99c26'><b>$finishdate</b></span>
&nbsp;⁞ 
┤ Configuration Quota 📊
&nbsp;⁞ ┤ Purchased Traffic: <span style='color: #e99c26'><b>$traffic_user</b></span>
&nbsp;⁞ ┘ Consumed Traffic: <span style='color: #e99c26'><b>$total</b></span>
</div>
";
                }
                $replacement = "Match User {$us->username}\nBanner /var/www/html/app/storage/banner/{$us->username}-detail\nMatch all";
                $file = fopen("/etc/ssh/sshd_config", "r+");
                $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                if (strpos($fileContent, "Match User {$us->username}") === false)
                {
                    $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                    rewind($file);
                    fwrite($file, $modifiedContent);
                }
                fclose($file);
                $filePath = "/var/www/html/app/storage/banner/$us->username-detail";
                $command = "echo \"$traffic_log\" > $filePath";
                exec($command, $output, $returnCode);

                if ($returnCode === 0) {
                    echo "";
                } else {
                    echo "";
                }
            }
        }
    }
    public function synstraffics_drop()
    {
        $newarray_drop=[];
        if (file_exists("/var/www/html/app/storage/out.json")) {
            $out = file_get_contents("/var/www/html/app/storage/out.json");
            $trafficlog = preg_split("/\r\n|\n|\r/", $out);
            $trafficlog = array_filter($trafficlog);
            $lastdata = end($trafficlog);
            $json = json_decode($lastdata, true);
            $traffic_base = env('TRAFFIC_BASE');
            if (file_exists("/var/www/html/app/storage/dropbear.json")) {

                if (is_array($json)) {
                    foreach ($json as $value) {
                        $TX = round($value["TX"], 0);
                        $RX = round($value["RX"], 0);
                        $PID = $value["PID"];
                        $name = $value["name"];
                        $jsonData = file_get_contents("/var/www/html/app/storage/dropbear.json");
                        $dataArray = json_decode($jsonData, true);
                        foreach ($dataArray as $item) {
                            if (isset($item['PID']) && $item['PID'] == $PID && $name=='/usr/sbin/dropbear') {
                                $username = $item['user'];
                                $traffic = Traffic::where('username', $username)->get();
                                $user = $traffic[0];
                                $userdownload = $user->download;
                                $userupload = $user->upload;
                                $usertotal = $user->total;
                                $rx = round($RX);
                                $rx = ($rx) / 10;
                                $rx = round(($rx / $traffic_base) * 100);
                                $tx = round($TX);
                                $tx = ($tx) / 10;
                                $tx = round(($tx / $traffic_base) * 100);
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
                    $PID = round($value["PID"], 0);

                    $name = preg_replace("/\\s+/", "", $value["name"]);
                    if (strpos($name, "sshd") === false) {
                        $name = "";
                    }
                    if (strpos($name, "root") !== false) {
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
                            $newarray[$name]["PID"] + $PID;
                        } else {
                            $newarray[$name] = ["RX" => $RX, "TX" => $TX, "Total" => $RX + $TX, "PID" => $PID];
                        }
                    }
                }
                //$newarray= json_encode($newarray);
                $traffic_base=env('TRAFFIC_BASE');
                foreach ($newarray as $username => $usr) {
                    $traffic = Traffic::where('username', $username)->get();
                    $user = $traffic[0];
                    $userdownload = $user->download;
                    $userupload = $user->upload;
                    $usertotal = $user->total;
                    $rx = round($usr["RX"]);
                    $rx = ($rx) / 10;
                    $rx = round(($rx / $traffic_base) * 100);
                    $tx = round($usr["TX"]);
                    $tx = ($tx) / 10;
                    $tx = round(($tx / $traffic_base) * 100);
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
