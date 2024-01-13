<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Users;
use App\Models\LogConnection;
use App\Models\Xguard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Verta;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;



class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');
    }
    public function generateQRCode($data)
    {
        $data=base64_decode($data);
        return response(QrCode::size(300)->margin(5)->generate($data));
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $searchBy = $request->input('search_by');
        $status = $request->input('status');

        $keyword = $request->input('keyword');
        $searchBy = $request->input('search_by');
        $status = $request->input('status');

        $query = Users::orderBy('id', 'desc');

        if ($keyword) {
            $query->where(function ($query) use ($keyword, $searchBy) {
                $query->where($searchBy, 'like', "%$keyword%");
            });
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        $users = $query->paginate(25);

        $xguard = Xguard::all();
        if(env('XGUARD')=='active' AND !empty($xguard[0]->domain))
        {
            $xguard_status='active';
            $sshaddress=$xguard[0]->domain;
            $port_ssh=$xguard[0]->port;
            $websiteaddress = $_SERVER['HTTP_HOST'];
            $websiteaddress = parse_url($websiteaddress, PHP_URL_HOST);
        }
        else {
            $xguard_status='deactive';
            $websiteaddress = $_SERVER['HTTP_HOST'];
            $sshaddress = parse_url($websiteaddress, PHP_URL_HOST);
            $websiteaddress = parse_url($websiteaddress, PHP_URL_HOST);

            $port_ssh=env('PORT_SSH');
        }

        $user = Auth::user();
        $password_auto = Str::random(8);

        $settings = Settings::all();
        return view('users.home', compact('users', 'settings','password_auto','websiteaddress','port_ssh','sshaddress','xguard_status'));
    }
    public function index_sort($status)
    {
        if (!empty($status) and !is_string($status)) {
            abort(400, 'Not Valid Username');
        }
        $xguard = Xguard::all();
        if(env('XGUARD')=='active' AND !empty($xguard[0]->domain))
        {
            $xguard_status='active';
            $sshaddress=$xguard[0]->domain;
            $port_ssh=$xguard[0]->port;
            $websiteaddress = $_SERVER['HTTP_HOST'];
            $websiteaddress = parse_url($websiteaddress, PHP_URL_HOST);
        }
        else {
            $xguard_status='deactive';
            $websiteaddress = $_SERVER['HTTP_HOST'];
            $sshaddress = parse_url($websiteaddress, PHP_URL_HOST);
            $websiteaddress = parse_url($websiteaddress, PHP_URL_HOST);

            $port_ssh=env('PORT_SSH');
        }

        $user = Auth::user();
        $password_auto = Str::random(8);
        if($user->permission=='admin')
        {
            $users = Users::where('status',$status)->orderBy('id', 'desc')->paginate(25);

        }
        else{

            $users = Users::where('status',$status)->where('customer_user', $user->username)->orderby('id', 'desc')->paginate(25);
        }
        $settings = Settings::all();
        return view('users.home', compact('users', 'settings','password_auto','websiteaddress','port_ssh','sshaddress','xguard_status'));
    }
    public function getUsersData(Request $request)
    {
        $xguard = Xguard::all();
        $xguard_status = (env('XGUARD') == 'active' && !empty($xguard[0]->domain)) ? 'active' : 'deactive';
        $sshaddress = ($xguard_status == 'active') ? $xguard[0]->domain : parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
        $port_ssh = ($xguard_status == 'active') ? $xguard[0]->port : env('PORT_SSH');

        $user = Auth::user();
        $password_auto = Str::random(8);

        $uid = 0; // افزودن متغیر $uid اینجا
        $users = ($user->permission == 'admin') ? Users::orderBy('id', 'desc')->paginate(25) : Users::where('customer_user', $user->username)->orderBy('id', 'desc')->paginate(25);
        $settings = Settings::all();

        $users = $users->map(function ($user) use ($settings, &$uid) { // اضافه کردن &$uid به تابع مپ

            $total_exo = 0;
            foreach ($user->traffics as $traffic) {
                $total_exo = $traffic->total;
            }

            $total = ($total_exo >= 1024) ? round($total_exo / 1024, 3) . ' GB' : $total_exo . ' MB';

            $traffic_user = ($user->traffic > 0) ? (($user->traffic >= 1024) ? round($user->traffic / 1024, 3) . ' GB' : $user->traffic . ' MB') : 'Unlimited';

            $value2 = $user->traffic;
            $value1 = $total_exo;
            $percentageDifference = ($value2 > 0) ? intval(($value1 / $value2) * 100) : 100;
            $percentageBG = ($value2 > 0) ? '' : 'bg-success';

            $customer_user = (empty($user->customer_user) or $user->customer_user == 'NULL') ? env('DB_USERNAME') : $user->customer_user;
            $tls_port = (empty($settings->tls_port) or $settings->tls_port == 'NULL') ? '444' : $settings->tls_port;

            $startdate = (!empty($user->start_date) and $user->start_date != 'NULL') ? $user->start_date : '';
            $finishdate = (!empty($user->end_date) and $user->end_date != 'NULL') ? $user->end_date : '';

            $today = strtotime(date("Y-m-d"));
            $futureDate = strtotime($finishdate);
            $daysDifference_day = ($futureDate > $today) ? round(($futureDate - $today) / (60 * 60 * 24)) : -round(($today - $futureDate) / (60 * 60 * 24));

            $connection = (!empty($user->conections)) ? $user->conections->connection : '0';
            $datecon = (!empty($user->conections) and !empty($user->conections->datecon)) ? $user->conections->datecon : '';

            $st_date = (!empty($startdate)) ? (app()->getLocale() == 'fa' ? "StartTime:" . Verta::instance($startdate)->format('Y/m/d') : "StartTime:$startdate") : '';
            $en_date = (!empty($finishdate)) ? (app()->getLocale() == 'fa' ? "EndTime:" . Verta::instance($finishdate)->format('Y/m/d') : "EndTime:$finishdate") : '';

            return [
                'uid' => ++$uid,
                'total' => $total,
                'traffic_user' => $traffic_user,
                'percentageDifference' => $percentageDifference,
                'percentageBG' => $percentageBG,
                'status' => $user->status,
                'customer_user' => $customer_user,
                'tls_port' => $tls_port,
                'startdate' => $startdate,
                'finishdate' => $finishdate,
                'daysDifference_day' => $daysDifference_day,
                'connection' => $connection,
                'datecon' => $datecon,
                'st_date' => $st_date,
                'en_date' => $en_date,
            ];
        });

        return response()->json(['users' => $users, 'settings' => $settings, 'password_auto' => $password_auto, 'websiteaddress' => $sshaddress, 'port_ssh' => $port_ssh, 'xguard_status' => $xguard_status]);
    }

    public function index()
    {

        $xguard = Xguard::all();
        if(env('XGUARD')=='active' AND !empty($xguard[0]->domain))
        {
            $xguard_status='active';
            $sshaddress=$xguard[0]->domain;
            $port_ssh=$xguard[0]->port;
            $websiteaddress = $_SERVER['HTTP_HOST'];
            $websiteaddress = parse_url($websiteaddress, PHP_URL_HOST);
        }
        else {
            $xguard_status='deactive';
            $websiteaddress = $_SERVER['HTTP_HOST'];
            $sshaddress = parse_url($websiteaddress, PHP_URL_HOST);
            $websiteaddress = parse_url($websiteaddress, PHP_URL_HOST);

            $port_ssh=env('PORT_SSH');
        }

        $user = Auth::user();
        $password_auto = Str::random(8);
        if($user->permission=='admin')
        {
            $users = Users::orderBy('id', 'desc')->paginate(25);
        }
        else{
            $users = Users::where('customer_user', $user->username)->orderby('id', 'desc')->paginate(25);
        }
        $settings = Settings::all();
        return view('users.home', compact('users', 'settings','password_auto','websiteaddress','port_ssh','sshaddress','xguard_status'));
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
            'expdate'=>'nullable|string',
            'type_traffic'=>'required|string',
            'desc'=>'nullable|string'
        ]);
        if(env('APP_LOCALE', 'en')=='fa') {
            if (!empty($request->expdate)) {
                $end_date=$this->persianToenglishNumbers($request->expdate);
                $end_date = Verta::parse($end_date)->datetime()->format('Y-m-d');
            } else {
                $end_date = '';
            }
        }
        else
        {
            $end_date= $request->expdate;
        }
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
                'end_date' => $end_date,
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
            if (env('STATUS_LOG', 'deactive') == 'active') {
                $replacement = "Match User {$user->username}\nBanner /var/www/html/app/storage/banner/{$user->username}-detail\nMatch all";
                $file = fopen("/etc/ssh/sshd_config", "r+");
                $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                if (strpos($fileContent, "#Match all") !== false) {
                    $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                    rewind($file);
                    fwrite($file, $modifiedContent);
                } elseif (strpos($fileContent, "Match User {$user->username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                    $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                    rewind($file);
                    fwrite($file, $modifiedContent);
                }
                fclose($file);
                Process::run("sudo service ssh restart");
            }
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
                    'customer_user' => $user_s->username,
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
                if (env('STATUS_LOG', 'deactive') == 'active') {
                    $replacement = "Match User {$user->username}\nBanner /var/www/html/app/storage/banner/{$user->username}-detail\nMatch all";
                    $file = fopen("/etc/ssh/sshd_config", "r+");
                    $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                    if (strpos($fileContent, "#Match all") !== false) {
                        $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    } elseif (strpos($fileContent, "Match User {$user->username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                        $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    }
                    fclose($file);
                    Process::run("sudo service ssh restart");
                }
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
                if (env('STATUS_LOG', 'deactive') == 'active') {
                    $replacement = "Match User {$username}\nBanner /var/www/html/app/storage/banner/{$username}-detail\nMatch all";
                    $file = fopen("/etc/ssh/sshd_config", "r+");
                    $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                    if (strpos($fileContent, "#Match all") !== false) {
                        $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    } elseif (strpos($fileContent, "Match User {$username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                        $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    }
                    fclose($file);
                    Process::run("sudo service ssh restart");
                }
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
                if (env('STATUS_LOG', 'deactive') == 'active') {
                    $replacement = "Match User {$username}\nBanner /var/www/html/app/storage/banner/{$username}-detail\nMatch all";
                    $file = fopen("/etc/ssh/sshd_config", "r+");
                    $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                    if (strpos($fileContent, "#Match all") !== false) {
                        $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    } elseif (strpos($fileContent, "Match User {$username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                        $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    }
                    fclose($file);
                    Process::run("sudo service ssh restart");
                }
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
        $activeUserCount = Users::where('status', 'active')->count();
        if($user->permission=='admin') {
            $check_user = Users::where('username',$username)->count();
            if ($check_user > 0) {
                if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                }
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
                if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                }
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
                if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
                    Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                }
            }
        }
        else
        {
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                Traffic::where('username', $username)->update(['download' => '0', 'upload' => '0', 'total' => '0']);

                if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
                    Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                }
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
        $activeUserCount = Users::where('status', 'active')->count();
        if($user->permission=='admin')
        {
            $check_user = Users::where('username',$username)->count();
            $status_user = Users::where('username',$username)->get();
            if ($check_user > 0) {
                if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                }
                if($status_user[0]->status=='active') {
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess = Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $username)->delete();
                        Traffic::where('username', $username)->delete();
                    }
                }
                else
                {
                    Users::where('username', $username)->delete();
                    Traffic::where('username', $username)->delete();
                }
            }
        }
        else {
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            $status_user = Users::where('username',$username)->get();
            if ($check_user > 0) {
                if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                }
                if ($status_user[0]->status == 'active') {
                    Process::run("sudo killall -u {$username}");
                    Process::run("sudo pkill -u {$username}");
                    Process::run("sudo timeout 10 pkill -u {$username}");
                    Process::run("sudo timeout 10 killall -u {$username}");
                    $userdelProcess = Process::run("sudo userdel -r {$username}");
                    if ($userdelProcess->successful()) {
                        Users::where('username', $username)->delete();
                        Traffic::where('username', $username)->delete();
                    }
                }
                else
                {
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
                $status_user = Users::where('username',$username)->get();
                if ($check_user > 0) {
                    if($request->action=='delete') {
                        if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                        }
                        if ($status_user[0]->status == 'active') {
                            Process::run("sudo killall -u {$username}");
                            Process::run("sudo pkill -u {$username}");
                            Process::run("sudo timeout 10 pkill -u {$username}");
                            Process::run("sudo timeout 10 killall -u {$username}");
                            $userdelProcess = Process::run("sudo userdel -r {$username}");
                            if ($userdelProcess->successful()) {
                                Users::where('username', $username)->delete();
                                Traffic::where('username', $username)->delete();
                            }
                        } else {
                            Users::where('username', $username)->delete();
                            Traffic::where('username', $username)->delete();
                        }
                    }
                    if($request->action=='active') {

                        Users::where('username', $username)->update(['status' => 'active']);

                        $user = Users::where('username',$username)->get();
                        $password=$user[0]->password;
                        if (env('STATUS_LOG', 'deactive') == 'active') {
                            $replacement = "Match User {$username}\nBanner /var/www/html/app/storage/banner/{$username}-detail\nMatch all";
                            $file = fopen("/etc/ssh/sshd_config", "r+");
                            $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                            if (strpos($fileContent, "#Match all") !== false) {
                                $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                                rewind($file);
                                fwrite($file, $modifiedContent);
                            } elseif (strpos($fileContent, "Match User {$username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                                $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                                rewind($file);
                                fwrite($file, $modifiedContent);
                            }
                            fclose($file);
                            Process::run("sudo service ssh restart");
                        }
                        Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                        Process::input($password."\n".$password."\n")->timeout(120)->run("sudo passwd {$username}");
                    }
                    if($request->action=='deactive') {
                        if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                        }
                        Users::where('username', $username)->update(['status' => 'deactive']);
                        Process::run("sudo killall -u {$username}");
                        Process::run("sudo pkill -u {$username}");
                        Process::run("sudo timeout 10 pkill -u {$username}");
                        Process::run("sudo timeout 10 killall -u {$username}");
                        Process::run("sudo userdel -r {$username}");
                    }
                    if($request->action=='retraffic') {
                        Traffic::where('username', $username)->update(['download' => '0', 'upload' => '0', 'total' => '0']);
                        if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
                            Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                        }
                    }
                }
            }
        } else {
            foreach ($request->usernamed as $username) {
                $status_user = Users::where('username', $username)->get();
                $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
                if ($check_user > 0) {
                    if($request->action=='delete') {
                        if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                        }
                        if ($status_user[0]->status == 'active') {
                            Process::run("sudo killall -u {$username}");
                            Process::run("sudo pkill -u {$username}");
                            Process::run("sudo timeout 10 pkill -u {$username}");
                            Process::run("sudo timeout 10 killall -u {$username}");
                            $userdelProcess = Process::run("sudo userdel -r {$username}");
                            if ($userdelProcess->successful()) {
                                Users::where('username', $username)->delete();
                                Traffic::where('username', $username)->delete();
                            }
                        } else {
                            Users::where('username', $username)->delete();
                            Traffic::where('username', $username)->delete();
                        }
                    }
                    if($request->action=='active') {

                        Users::where('username', $username)->update(['status' => 'active']);

                        $user = Users::where('username',$username)->get();
                        $password=$user[0]->password;
                        if (env('STATUS_LOG', 'deactive') == 'active') {
                            $replacement = "Match User {$username}\nBanner /var/www/html/app/storage/banner/{$username}-detail\nMatch all";
                            $file = fopen("/etc/ssh/sshd_config", "r+");
                            $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                            if (strpos($fileContent, "#Match all") !== false) {
                                $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                                rewind($file);
                                fwrite($file, $modifiedContent);
                            } elseif (strpos($fileContent, "Match User {$username}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                                $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                                rewind($file);
                                fwrite($file, $modifiedContent);
                            }
                            fclose($file);
                            Process::run("sudo service ssh restart");
                        }
                        Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                        Process::input($password."\n".$password."\n")->timeout(120)->run("sudo passwd {$username}");
                    }
                    if($request->action=='deactive') {
                        if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
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
                        }
                        Users::where('username', $username)->update(['status' => 'deactive']);
                        Process::run("sudo killall -u {$username}");
                        Process::run("sudo pkill -u {$username}");
                        Process::run("sudo timeout 10 pkill -u {$username}");
                        Process::run("sudo timeout 10 killall -u {$username}");
                        Process::run("sudo userdel -r {$username}");
                    }
                    if($request->action=='retraffic') {
                        Traffic::where('username', $username)->update(['download' => '0', 'upload' => '0', 'total' => '0']);
                        if (file_exists("/var/www/html/app/storage/banner/{$username}-detail")) {
                            Process::run("sudo rm -rf /var/www/html/app/storage/banner/{$username}-detail");
                        }
                    }
                }
            }
        }
        Process::run("sudo service ssh restart");
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
                if (env('STATUS_LOG', 'deactive') == 'active') {
                    $replacement = "Match User {$request->username_re}\nBanner /var/www/html/app/storage/banner/{$request->username_re}-detail\nMatch all";
                    $file = fopen("/etc/ssh/sshd_config", "r+");
                    $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                    if (strpos($fileContent, "#Match all") !== false) {
                        $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    } elseif (strpos($fileContent, "Match User {$request->username_re}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                        $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    }
                    fclose($file);
                    Process::run("sudo service ssh restart");
                }
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
                if (env('STATUS_LOG', 'deactive') == 'active') {
                    $replacement = "Match User {$request->username_re}\nBanner /var/www/html/app/storage/banner/{$request->username_re}-detail\nMatch all";
                    $file = fopen("/etc/ssh/sshd_config", "r+");
                    $fileContent = fread($file, filesize("/etc/ssh/sshd_config"));
                    if (strpos($fileContent, "#Match all") !== false) {
                        $modifiedContent = str_replace("#Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    } elseif (strpos($fileContent, "Match User {$request->username_re}\n") === false and strpos($fileContent, "#Match all\n") === false) {
                        $modifiedContent = str_replace("Match all", $replacement, $fileContent);
                        rewind($file);
                        fwrite($file, $modifiedContent);
                    }
                    fclose($file);
                    Process::run("sudo service ssh restart");
                }
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
                if(env('APP_LOCALE', 'en')=='fa')
                {
                    if(!empty($show->end_date)){$end_date=Verta::instance($show->end_date)->format('Y-m-d');
                        $end_date=$this->englishToPersianNumbers($end_date);}
                    else
                    {
                        $end_date=''  ;
                    }
                }
                else
                {
                    $end_date= $show->end_date;
                }
                return view('users.edit', compact('show','end_date'));
            } else {
                return redirect()->back()->with('success', 'Not User');
            }
        }
        else{
            $check_user = Users::where('username', $username)->where('customer_user', $user->username)->count();
            if ($check_user > 0) {
                $user = Users::where('username', $username)->get();
                $show = $user[0];
                if(env('APP_LOCALE', 'en')=='fa')
                {
                    if(!empty($show->end_date)){$end_date=Verta::instance($show->end_date)->format('Y-m-d');
                        $end_date=$this->englishToPersianNumbers($end_date);}
                    else
                    {
                        $end_date=''  ;
                    }
                }
                else
                {
                    $end_date= $show->end_date;
                }
                return view('users.edit', compact('show','end_date'));
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
            'expdate'=>'nullable|string',
            'type_traffic'=>'required|string',
            'activate'=>'required|string',
            'desc'=>'nullable|string'
        ]);
        if ($request->type_traffic == "gb") {
            $traffic = $request->traffic * 1024;
        } else {
            $traffic = $request->traffic;
        }
        if(env('APP_LOCALE', 'en')=='fa') {
            if (!empty($request->expdate)) {
                $end_date=$this->persianToenglishNumbers($request->expdate);
                $end_date = Verta::parse($end_date)->datetime()->format('Y-m-d');
            } else {
                $end_date = '';
            }
        }
        else
        {
            $end_date= $request->expdate;
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
                        'end_date' => $end_date,
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
                        'end_date' => $end_date,
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
    public function englishToPersianNumbers($input)
    {
        $persianNumbers = [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ];

        return strtr($input, $persianNumbers);
    }

    public function persianToenglishNumbers($input)
    {
        $persianNumbers = [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
        ];

        return strtr($input, $persianNumbers);
    }

}
