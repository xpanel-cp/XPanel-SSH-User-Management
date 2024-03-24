<?php

namespace App\Http\Controllers;

use App\Models\Fixer;
use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Trafficsb;
use App\Models\Users;
use App\Models\Singbox;
use App\Models\LogConnection;
use App\Models\Xguard;
use App\Models\Ipadapter;
use App\Models\Adapterlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Verta;
use DateTime;
class FixerController extends Controller
{

    public function cronexp()
    {
        if(env('ANTI_USER')=='active') {
            $list = Process::run("ls /home");
            $output = $list->output();
            $list_user = preg_split("/\r\n|\n|\r/", $output);
            foreach ($list_user as $us) {
                $check_user = Users::where('username', $us)->count();
                if ($check_user < 1 && $us != 'videocall' && $us != 'ubuntu') {
                    Process::run("sudo killall -u {$us}");
                    Process::run("sudo pkill -u {$us}");
                    Process::run("sudo timeout 10 pkill -u {$us}");
                    Process::run("sudo timeout 10 killall -u {$us}");
                    Process::run("sudo userdel -r {$us}");
                }

            }
        }
        $users = Users::where('status', 'active')->get();
        $users_sb = Singbox::where('status', 'active')->get();
        $activeUserCount = Users::where('status', 'active')->count();
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
                        $linesToRemove = ["Match User {$username}", "Banner /var/www/html/app/storage/banner/{$username}-detail"];
                        $filename = "/etc/ssh/sshd_config";
                        $fileContent = file($filename);
                        $newFileContent = [];
                        foreach ($fileContent as $line) {
                            if (!in_array(trim($line), $linesToRemove) && trim($line) !== '') {
                                $newFileContent[] = $line;
                            }
                        }
                        file_put_contents($filename, implode('', $newFileContent));
                        Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                        Process::run("sudo service ssh restart");
                        Users::where('username', $us->username)
                            ->update(['status' => 'expired']);
                    }
                }
            }
        }

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
                        $linesToRemove = ["Match User {$username}", "Banner /var/www/html/app/storage/banner/{$username}-detail"];
                        $filename = "/etc/ssh/sshd_config";
                        $fileContent = file($filename);
                        $newFileContent = [];
                        foreach ($fileContent as $line) {
                            if (!in_array(trim($line), $linesToRemove) && trim($line) !== '') {
                                $newFileContent[] = $line;
                            }
                        }
                        file_put_contents($filename, implode('', $newFileContent));
                        Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                        Process::run("sudo service ssh restart");
                        Users::where('username', $us->username)
                            ->update(['status' => 'traffic']);
                    }

                }
            }

        }
        foreach ($users_sb as $us)
        {
            if (!empty($us->end_date)) {
                $expiredate = strtotime(date("Y-m-d", strtotime($us->end_date)));
                if ($expiredate < strtotime(date("Y-m-d")) || $expiredate == strtotime(date("Y-m-d"))) {
                    $validatedData = [
                        'port'=>$us->port_sb
                    ];

                    ProController::deactive_singbox($validatedData);
                    Singbox::where('id', $us->id)
                        ->update(['status' => 'expired']);
                }
            }
        }
    }

    public function multiuser()
    {
        $setting = Settings::first();
        if (!$setting || $setting->multiuser !== 'active') {
            return;
        }

        // Retrieve SSH users
        $list = Process::run("sudo lsof -i :" . env('PORT_SSH') . " -n | grep -v root | grep ESTABLISHED");
        $output = $list->output();
        $onlineuserlist = preg_split("/\r\n|\n|\r/", $output);
        $onlinelist = [];
        foreach ($onlineuserlist as $user) {
            $user = preg_replace('/\s+/', ' ', $user);
            $userarray = explode(" ", $user);
            $onlinelist[] = $userarray[2] ?? null;
        }

        // Read JSON file
        $list_drop = Process::run("sudo lsof -i :" . env('PORT_DROPBEAR') . " -n | grep ESTABLISHED");
        $output_drop = $list_drop->output();
        $onlineuserlist_drop = preg_split("/\r\n|\n|\r/", $output_drop);
        $jsonFilePath = '/var/www/html/app/storage/dropbear.json';

        if (file_exists($jsonFilePath)) {
            $jsonData = file_get_contents($jsonFilePath);
            $dataArray = json_decode($jsonData, true);

            foreach ($onlineuserlist_drop as $user) {
                $user = preg_replace('/\s+/', ' ', $user);
                $userarray = explode(" ", $user);
                $pid = $userarray[1] ?? null;

                // Check if the PID is in the JSON data
                $userFound = false;
                foreach ($dataArray as $item) {
                    if ($item['PID'] === $pid) {
                        $userFound = true;
                        $onlinelist[] = $item['user'];
                        break;
                    }
                }
            }
        }
        // Remove duplicates
        $onlinelist = array_replace($onlinelist, array_fill_keys(array_keys($onlinelist, null), ''));
        $onlinecount = array_count_values($onlinelist);
        $onlinelist_uniq = array_unique($onlinelist);
        $allUsersCO = LogConnection::pluck('username')->toArray();
        foreach ($allUsersCO as $username) {
            // اگر کاربر در لیست آنلاین نبود
            if (!in_array($username, $onlinelist_uniq)) {
                LogConnection::where('username', $username)->update(['connection' => 0]);

            }
        }
        foreach ($onlinelist_uniq as $useron) {

            $users = Users::where('username', $useron)->get();
            foreach ($users as $row) {
                $limitation = $row->multiuser ?: 0;
                $username = $row->username;
                $startdate = $row->start_date;
                $finishdate_one_connect = $row->date_one_connect;

                if (empty($startdate)) {
                    $start_inp = now()->toDateString();
                    $end_inp = now()->addDays($finishdate_one_connect)->toDateString();
                    Users::where('username', $username)->update(['start_date' => $start_inp, 'end_date' => $end_inp]);
                }
                $UserCount = LogConnection::where('username', $username)->count();
                $date_time=date("Y-m-d H:i");
                if($UserCount>0)
                {
                    LogConnection::where('username', $username)->update(['connection' => $onlinecount[$username], 'datecon' => $date_time]);
                }
                else
                {
                    LogConnection::create([
                        'username' => $username,
                        'connection' => $onlinecount[$username],
                        'datecon' => $date_time
                    ]);
                }
                if ($limitation !== 0 && $onlinecount[$username] > $limitation) {

                    if (file_exists($jsonFilePath)) {
                        foreach ($dataArray as $item) {
                            if (isset($item['user']) && $item['user'] === $username) {

                                $pid = $item['PID'];
                                Process::run("sudo kill -9 {$pid}");
                                Process::run("sudo killall -u {$username}");                            }
                        }
                    }
                    Process::run("sudo killall -u {$username}");
                    //Process::run("sudo timeout 20 killall -u {$username}");
                }
            }
        }
    }

    public function other()
    {
        if(env('CRON_TRAFFIC', 'active')=='active') {
            $this->synstraffics();
            $this->cronexp_traffic();
            $this->synstraffics_drop();
        }
        $this->trafiic_end_sb();
    }
    public function trafiic_end_sb()
    {
        $users_sb = Singbox::where('status', 'active')->get();
        foreach ($users_sb as $us)
        {
            $traffic = Trafficsb::where('port_sb', $us->port_sb)->get();
            foreach ($traffic as $usernamet) {
                $total = $usernamet->total_sb;
                if($total>0 and empty($us->start_date) and !empty($us->date_one_connect))
                {
                    $end_inp = now()->addDays($us->date_one_connect)->toDateString();
                    $start_inp = now()->toDateString();
                    Singbox::where('port_sb', $us->port_sb)->update(['start_date' => $start_inp, 'end_date' => $end_inp]);
                }
                if ($us->traffic < $total && !empty($us->traffic) && $us->traffic > 0) {
                    $validatedData = [
                        'port'=>$us->port_sb
                    ];

                    ProController::deactive_singbox($validatedData);
                    Singbox::where('port_sb', $us->port_sb)->update(['status' => 'traffic']);
                }
            }
        }
    }
    public function cronexp_traffic()
    {
        $inactiveUsers = Users::where('status', '!=', 'active')->get();
        $users = Users::where('status', 'active')->get();
        $activeUserCount = Users::where('status', 'active')->count();
        $targetActiveUserCount=0;
        foreach ($users as $us) {
            $targetActiveUserCount++;
            $traffic = Traffic::where('username', $us->username)->get();
            foreach ($traffic as $usernamet) {
                $total = $usernamet->total;

                if ($us->traffic < $total && !empty($us->traffic) && $us->traffic > 0) {
                    $username = $us->username;
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess = Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $us->username)
                            ->update(['status' => 'traffic']);
                    }
                }
                //traffic log html
                if (env('STATUS_LOG', 'deactive') == 'active') {
                    if (!empty($us->end_date)) {
                        $start_inp = date("Y-m-d");
                        $today = new DateTime($start_inp);
                        $futureDate = new DateTime($us->end_date);
                        $interval = $today->diff($futureDate);
                        $daysDifference_day = $interval->days;
                    }
                    if (env('APP_LOCALE', 'en') == 'fa') {
                        $startdate = Verta::instance($us->start_date)->formatWord('ds F');
                        if (!empty($us->end_date)) {
                            $finishdate = Verta::instance($us->end_date)->formatWord('ds F');
                        } else {
                            $finishdate = 'بدون محدودیت';
                        }

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
                        $day = "";
                        if ($us->status == 'active' and !empty($us->end_date)) {
                            $day = "\n• اشتراک شما <span style='color: #e99c26'><b>$daysDifference_day</b></span> روز دیگر پایان خواهد یافت.\n";
                        }
                        if ($us->status == 'deactive') {
                            $day = "\n• اشتراک شما <span style='color: #e92626'><b>غیرفعال</b></span> است.\n";
                        }
                        if ($us->status == 'expired') {
                            $day = "\n• اشتراک شما <span style='color: #e98826'><b>منقضی شده</b></span> است.\n";
                        }
                        if ($us->status == 'traffic') {
                            $day = "\n• اشتراک شما <span style='color: #26aee9'><b>ترافیک تمام کرده</b></span> است.\n";
                        }

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
                    } else {
                        $startdate = $us->start_date;
                        if (!empty($us->end_date)) {
                            $finishdate = $us->end_date;
                        } else {
                            $finishdate = 'Unlimited';
                        }
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
                        $day = "";
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
                    $filePath = "/var/www/html/app/storage/banner/$us->username-detail";
                    $command = "echo \"$traffic_log\" > $filePath";
                    exec($command, $output, $returnCode);

                    if ($returnCode === 0) {
                        echo "";
                    }
                    $replacement = "Match User {$us->username}\nBanner /var/www/html/app/storage/banner/{$us->username}-detail\nMatch all";
                    $file = fopen("/etc/ssh/sshd_config", "r+");
                    $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                    if (strpos($fileContent, "#Match all") !== false) {
                        $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    } elseif (strpos($fileContent, "Match User {$us->username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                        $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    }
                    fclose($file);
                    if ($activeUserCount == $targetActiveUserCount) {
                        Process::run("sudo service ssh restart");
                    }
                } else {
                    if (file_exists("/var/www/html/app/storage/banner/{$us->username}-detail")) {
                        $linesToRemove = ["Match User {$us->username}", "Banner /var/www/html/app/storage/banner/{$us->username}-detail"];
                        $filename = "/etc/ssh/sshd_config";
                        $fileContent = file($filename);
                        $newFileContent = [];
                        foreach ($fileContent as $line) {
                            if (!in_array(trim($line), $linesToRemove) && trim($line) !== '') {
                                $newFileContent[] = $line;
                            }
                        }
                        file_put_contents($filename, implode('', $newFileContent));
                        Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$us->username}-detail");
                        if ($activeUserCount == $targetActiveUserCount) {
                            Process::run("sudo service ssh restart");
                        }
                    }
                }
            }
        }
        foreach ($inactiveUsers as $user) {
            if (file_exists("/var/www/html/app/storage/banner/{$user->username}-detail")) {
                $file = fopen("/etc/ssh/sshd_config", "r+");
                $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                if (strpos($fileContent, "Match User {$user->username}\n") !== false) {
                    $linesToRemove = ["Match User {$user->username}", "Banner /var/www/html/app/storage/banner/{$user->username}-detail"];
                    $filename = "/etc/ssh/sshd_config";
                    $fileContent = file($filename);
                    $newFileContent = [];
                    foreach ($fileContent as $line) {
                        if (!in_array(trim($line), $linesToRemove) && trim($line) !== '') {
                            $newFileContent[] = $line;
                        }
                    }
                    file_put_contents($filename, implode('', $newFileContent));
                    Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$user->username}-detail");
                    if ($activeUserCount == $targetActiveUserCount) {
                        Process::run("sudo service ssh restart");
                    }
                }
            }
        }
    }
    public function synstraffics_drop()
    {
        $trafficLogFilePath = "/var/www/html/app/storage/out.json";
        $dropbearJsonFilePath = "/var/www/html/app/storage/dropbear.json";

        if (file_exists($trafficLogFilePath) && file_exists($dropbearJsonFilePath)) {
            $trafficLog = file_get_contents($trafficLogFilePath);
            $trafficEntries = explode(PHP_EOL, $trafficLog);
            $trafficEntries = array_filter($trafficEntries); // حذف خطوط خالی
            $lastEntry = end($trafficEntries); // استفاده از end برای آخرین مورد معتبر

            $trafficData = json_decode($lastEntry, true);
            $traffic_base = env('TRAFFIC_BASE');

            if (is_array($trafficData)) {
                $dropbearData = json_decode(file_get_contents($dropbearJsonFilePath), true);

                foreach ($trafficData as $value) {
                    $PID = $value['PID'];
                    $TX = round($value['TX'], 0);
                    $RX = round($value['RX'], 0);
                    $name = $value['name'];

                    if ($name == '/usr/sbin/dropbear') {
                        $matchingDropbearUsers = array_filter($dropbearData, function ($item) use ($PID) {
                            return isset($item['PID']) && $item['PID'] == $PID;
                        });

                        foreach ($matchingDropbearUsers as $item) {
                            $username = $item['user'];

                            $traffic = Traffic::where('username', $username)->first();

                            if ($traffic) {
                                $userdownload = $traffic->download;
                                $userupload = $traffic->upload;
                                $usertotal = $traffic->total;

                                $rx = round(($RX / 10) / $traffic_base * 100);
                                $tx = round(($TX / 10) / $traffic_base * 100);
                                $tot = $rx + $tx;

                                $lastdownload = $userdownload + $rx;
                                $lastupload = $userupload + $tx;
                                $lasttotal = $usertotal + $tot;

                                if ($traffic->exists) {
                                    $traffic->update([
                                        'download' => $lastdownload,
                                        'upload' => $lastupload,
                                        'total' => $lasttotal,
                                    ]);
                                } else {
                                    Traffic::create([
                                        'username' => $username,
                                        'download' => $lastdownload,
                                        'upload' => $lastupload,
                                        'total' => $lasttotal,
                                    ]);
                                }
                                $totalInEnv = floatval(env('TRAFFIC_SERVER', 0));
                                $total = $totalInEnv + $lasttotal;
                                Process::run("sed -i \"s/TRAFFIC_SERVER=.*/TRAFFIC_SERVER=$total/g\" /var/www/html/app/.env");
                            }
                        }
                    }
                }
            }
        }
    }

    public function synstraffics()
    {

        // Retrieve NetHogs process ID
        $nethogsPID = trim(Process::run("pgrep nethogs")->output());

        // Check if the traffic log file exists
        $trafficLogFilePath = "/var/www/html/app/storage/out.json";
        if (file_exists($trafficLogFilePath)) {
            $trafficLog = file_get_contents($trafficLogFilePath);
            $trafficEntries = preg_split("/\r\n|\n|\r/", $trafficLog);
            $trafficEntries = array_filter($trafficEntries);
            $lastEntry = end($trafficEntries);
            $trafficData = json_decode($lastEntry, true);

            if (is_array($trafficData)) {
                $trafficBase = env('TRAFFIC_BASE');
                $newarray = [];

                foreach ($trafficData as $entry) {
                    $TX = round($entry["TX"]);
                    $RX = round($entry["RX"]);
                    $PID = round($entry["PID"]);
                    $name = preg_replace("/\\s+/", "", $entry["name"]);

                    // Filter out undesired names
                    $filteredNames = ["sshd", "root", "/usr/bin/stunnel4", "unknown TCP", "/usr/sbin/apache2", "[net]", "[accepted]", "[rexeced]", "@notty", "root:sshd", "/sbin/sshd", "[priv]", "@pts/1"];
                    if (empty($name) || in_array($name, $filteredNames) || ($RX < 1 && $TX < 1)) {
                        continue;
                    }

                    // Remove "sshd:" prefix
                    $name = str_replace("sshd:", "", $name);

                    if (!empty($name)) {
                        if (isset($newarray[$name])) {
                            $newarray[$name]["TX"] += $TX;
                            $newarray[$name]["RX"] += $RX;
                            $newarray[$name]["PID"] += $PID;
                        } else {
                            $newarray[$name] = ["RX" => $RX, "TX" => $TX, "Total" => $RX + $TX, "PID" => $PID];
                        }
                    }
                }

                foreach ($newarray as $username => $usr) {
                    $traffic = Traffic::where('username', $username)->first();

                    if ($traffic) {
                        $userdownload = $traffic->download;
                        $userupload = $traffic->upload;
                        $usertotal = $traffic->total;

                        $rx = round($usr["RX"]);
                        $rx = ($rx / 10);
                        $rx = round(($rx / $trafficBase) * 100);

                        $tx = round($usr["TX"]);
                        $tx = ($tx / 10);
                        $tx = round(($tx / $trafficBase) * 100);

                        $tot = $rx + $tx;
                        $lastdownload = $userdownload + $rx;
                        $lastupload = $userupload + $tx;
                        $lasttotal = $usertotal + $tot;

                        if (empty($traffic->username)) {
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
                        $totalInEnv = floatval(env('TRAFFIC_SERVER', 0));
                        $total = $totalInEnv + $lasttotal;
                        Process::run("sed -i \"s/TRAFFIC_SERVER=.*/TRAFFIC_SERVER=$total/g\" /var/www/html/app/.env");
                    }
                }
            }
        }

        // Continue with the rest of the function for online users
        $settings = Settings::find(1);
        $multiuser = $settings->multiuser;
        $portSSH = env('PORT_SSH');
        $onlineUsers = $this->getOnlineSSHUsers($portSSH);

        foreach ($onlineUsers as $user) {
            $username = $user['username'];
            $onlineCount = $user['onlineCount'];

            $users = Users::where('username', $username)->get();

            foreach ($users as $row) {
                $limitation = $row->multiuser ?: 0;
                $startdate = $row->start_date;
                $finishdate_one_connect = $row->date_one_connect;

                if (empty($startdate) && $onlineCount > 0) {
                    $start_inp = now()->toDateString();
                    $end_inp = now()->addDays($finishdate_one_connect)->toDateString();
                    Users::where('username', $username)->update(['start_date' => $start_inp, 'end_date' => $end_inp]);
                }

                if ($limitation !== 0 && $onlineCount > $limitation) {
                    if ($multiuser == 'on') {
                        Process::run("sudo killall -u {$username}");
                        Process::run("sudo pkill -u {$username}");
                        Process::run("sudo timeout 10 pkill -u {$username}");
                        Process::run("sudo timeout 10 killall -u {$username}");
                    }
                }
            }

            Process::run("sudo kill -9 $nethogsPID");
            Process::run("sudo killall -9 nethogs");
        }

        Process::run("sudo rm -rf $trafficLogFilePath");
        Process::run("sudo nethogs -j -v3 -c6 > $trafficLogFilePath");
        Process::run("sudo pkill nethogs");
    }

    private function getOnlineSSHUsers($portSSH)
    {
        $list = Process::run("sudo lsof -i :$portSSH -n | grep -v root | grep ESTABLISHED");
        $output = $list->output();
        $onlineuserlist = preg_split("/\r\n|\n|\r/", $output);
        $onlineUsers = [];

        foreach ($onlineuserlist as $user) {
            $user = preg_replace('/\s+/', ' ', $user);
            $userarray = explode(" ", $user);
            $username = $userarray[2] ?? null;

            if (!isset($onlineUsers[$username])) {
                $onlineUsers[$username] = 1;
            } else {
                $onlineUsers[$username]++;
            }
        }

        return array_map(function ($username, $onlineCount) {
            return ['username' => $username, 'onlineCount' => $onlineCount];
        }, array_keys($onlineUsers), array_values($onlineUsers));
    }

    public function check_filter()
    {
        ProController::check_filter();
    }

    public function check_traffic()
    {
        ProController::check_traffic();
    }
    public function check_hourly()
    {
        ProController::check_hourly();
    }
    public function send_email_detail_acc_3day()
    {
        ProController::detail_acc();
    }

    public function send_email_detail_acc()
    {
        ProController::detail2_acc();
    }

}
