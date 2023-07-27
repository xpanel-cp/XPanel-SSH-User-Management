<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Admins;
use App\Models\Api;
use Illuminate\Http\Request;
use Auth;
use App\Models\Settings;
use App\Models\Traffic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Process\ProcessResult;

class SettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');

    }
    public function check()
    {
        $user = Auth::user();
        $check_admin = Admins::where('id', $user->id)->get();
        if($check_admin[0]->permission=='reseller')
        {
            exit(view('access'));
        }
    }
    public function defualt()
    {
        $this->check();
        return redirect()->intended(route('settings', ['name' => 'user']));
    }
    public function index(Request $request,$name)
    {
        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }
        $setting = Settings::all();
        $apis =Api::all();
        if($name=='user') {
            $status=$setting[0]->multiuser;
            return view('settings.index', compact('status'));}

        if($name=='backup') {
            $list = Process::run("ls /var/www/html/app/storage/backup");
            $output = $list->output();
            $backuplist = preg_split("/\r\n|\n|\r/", $output);
            $lists=$backuplist;
            return view('settings.backup', compact('lists'));
        }
        if($name=='api') {
            $apis=$apis;
            return view('settings.api', compact('apis'));}
        if($name=='block') {
            $check_status = Process::run("sudo iptables -L OUTPUT");
            $output = $check_status->output();
            $output = preg_split("/\r\n|\n|\r/", $output);
            $output = count($output) - 3;
            $status=$output;
            return view('settings.block', compact('status'));
        }
        if($name=='fakeaddress') {return view('settings.fake');}
        if($name=='wordpress') {
            $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
            $http_host=$_SERVER['HTTP_HOST'];
            $output=$http_host.'/';
            $output=explode(':',$output);
            $output=$protocol.'://'.$output[0];
            $address=$output;
            return view('settings.wordpress', compact('address'));
        }

    }

    public function update_multiuser(Request $request)
    {
        $this->check();
        $request->validate([
            'status'=>'required|string'
        ]);
        $check_setting = Settings::where('id','1')->count();
        if ($check_setting > 0) {
            Settings::where('id', 1)->update(['multiuser' => $request->status]);
        } else {
            Settings::create([
                'multiuser' => $request->status
            ]);
        }
        return redirect()->intended(route('settings', ['name' => 'user']));
    }

    public function update_telegram(Request $request)
    {
        $this->check();
        $request->validate([
            'tokenbot'=>'required|string',
            'idtelegram'=>'required|string'
        ]);
        $check_setting = Settings::where('id','1')->count();
        if ($check_setting > 0) {
            Settings::where('id', 1)->update(['t_token' => $request->tokenbot,'t_id' => $request->idtelegram]);
        } else {
            Settings::create([
                't_token' => $request->tokenbot,'t_id' => $request->idtelegram
            ]);
        }
        return redirect()->intended(route('settings', ['name' => 'telegram']));
    }

    public function import_old(Request $request)
    {
        $this->check();
        $request->validate([
            'file'=>'required|mimetypes:text/plain'
        ]);
        if($request->file('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->move('/var/www/html/app/storage/backup/', $filename);
            $sqlScript = file('/var/www/html/app/storage/backup/' . $filename);
            foreach ($sqlScript as $line) {

                $startWith = substr(trim($line), 0, 2);
                $endWith = substr(trim($line), -1, 1);

                if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                    continue;
                }
                if (strpos($line, 'INSERT INTO `users` VALUES') !== false) {
                    //echo $line;
                    $list = explode("VALUES ", $line);
                    $list = explode("),", $list[1]);
                    foreach ($list as $li) {
                        $li = str_replace("(", "", $li);
                        $li = str_replace("'", "", $li);
                        $li = str_replace(");", "", $li);
                        $data = explode(",", $li);
                        $check_traffic = Users::where('username', $data[1])->count();
                        if ($check_traffic < 1) {
                            if($data[10]=='true')
                            { $status='active';}
                            if($data[10]=='false')
                            { $status='deactive';}
                            if($data[10]!='true' and $data[10]!='false')
                            { $status=$data[10];}
                            Users::create([
                                'username' => $data[1],
                                'password' => $data[2],
                                'email' => $data[3],
                                'mobile' => $data[4],
                                'multiuser' => $data[5],
                                'start_date' => $data[6],
                                'end_date' => $data[7],
                                'date_one_connect' => $data[8],
                                'customer_user' => $data[9],
                                'status' => $status,
                                'traffic' => $data[11],
                                'referral' => $data[12],
                                'desc' => $data[13]
                            ]);
                        }
                    }


                }

                if (strpos($line, 'INSERT INTO `Traffic` VALUES') !== false) {
                    //echo $line;
                    $list = explode("VALUES ", $line);
                    $list = explode("),", $list[1]);
                    foreach ($list as $li) {
                        $li = str_replace("(", "", $li);
                        $li = str_replace("'", "", $li);
                        $li = str_replace(");", "", $li);
                        $data = explode(",", $li);
                        $check_traffic =Traffic::where('username', $data[0])->count();
                        if ($check_traffic < 1) {
                            if (!is_numeric($data[0])) {
                                Traffic::create([
                                    'username' => $data[0],
                                    'download' => $data[1],
                                    'upload' => $data[2],
                                    'total' => $data[3]
                                ]);
                            }
                            else
                            {
                                Traffic::create([
                                    'username' => $data[1],
                                    'download' => $data[2],
                                    'upload' => $data[3],
                                    'total' => $data[4]
                                ]);
                            }
                        }
                    }


                }

                if (strpos($line, 'INSERT INTO `traffic` VALUES') !== false) {
                    //echo $line;
                    $list = explode("VALUES ", $line);
                    $list = explode("),", $list[1]);
                    foreach ($list as $li) {
                        $li = str_replace("(", "", $li);
                        $li = str_replace("'", "", $li);
                        $li = str_replace(");", "", $li);
                        $data = explode(",", $li);
                        $check_traffic =Traffic::where('username', $data[0])->count();
                        if ($check_traffic < 1) {
                            if (!is_numeric($data[0])) {
                                Traffic::create([
                                    'username' => $data[0],
                                    'download' => $data[1],
                                    'upload' => $data[2],
                                    'total' => $data[3]
                                ]);
                            }
                            else
                            {
                                Traffic::create([
                                    'username' => $data[1],
                                    'download' => $data[2],
                                    'upload' => $data[3],
                                    'total' => $data[4]
                                ]);
                            }
                        }
                    }


                }

            }
            Process::run("rm -rf /var/www/html/app/storage/backup/".$filename);
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user->username}");
                Process::input($user->password."\n".$user->password."\n")->timeout(120)->run("sudo passwd {$user->username}");
                $check_traffic =Traffic::where('username', $user->username)->count();
                if ($check_traffic < 1) {
                    Traffic::create([
                        'username' => $user->username,
                        'download' => '0',
                        'upload' => '0',
                        'total' => '0'
                    ]);
                }
            }


        }
        return redirect()->intended(route('settings', ['name' => 'backup']));
    }

    public function upload_backup(Request $request)
    {
        $this->check();
        $request->validate([
            'file'=>'required|mimetypes:text/plain'
        ]);
        if($request->file('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->move('/var/www/html/app/storage/backup/', $filename);

        }
        return redirect()->intended(route('settings', ['name' => 'backup']));
    }

    public function delete_backup(Request $request,$name)
    {
        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }
        Process::run("rm -rf /var/www/html/app/storage/backup/".$name);
        return redirect()->intended(route('settings', ['name' => 'backup']));

    }

    public function restore_backup(Request $request,$name)
    {
        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }
        Process::run("mysql -u '" .env('DB_USERNAME'). "' --password='" .env('DB_PASSWORD'). "' XPanel_plus < /var/www/html/app/storage/backup/".$name);
        $users =Users::all();
        foreach ($users as $user) {
            Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$user->username}");
            Process::input($user->password."\n".$user->password."\n")->timeout(120)->run("sudo passwd {$user->username}");
            $check_traffic =Traffic::where('username', $user->username)->count();
            if ($check_traffic < 1) {
                Traffic::create([
                    'username' => $user->username,
                    'download' => '0',
                    'upload' => '0',
                    'total' => '0'
                ]);
            }
        }
        return redirect()->intended(route('settings', ['name' => 'backup']));

    }

    public function make_backup()
    {
        $this->check();
        $date = date("Y-m-d---h-i-s");
        Process::run("mysqldump -u '" .env('DB_USERNAME'). "' --password='" .env('DB_PASSWORD'). "' XPanel_plus > /var/www/html/app/storage/backup/XPanel-".$date.".sql");
        return redirect()->intended(route('settings', ['name' => 'backup']));
    }
    public function download_backup(Request $request,$name)
    {
        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }
        $fileName = $name;
        $filePath = storage_path('backup/'.$fileName);

        if (file_exists('/var/www/html/app/storage/backup/'.$fileName)) {
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment',
            ])->deleteFileAfterSend(true);
        }

        abort(404);
        return redirect()->intended(route('settings', ['name' => 'backup']));
    }

    public function insert_api(Request $request)
    {
        $this->check();
        $user = Auth::user();
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $token = substr(str_shuffle($chars), 0, 15);
        $request->validate([
            'desc'=>'required|string',
            'allowip'=>'required|string'
        ]);
        Api::create([
            'username' => $user->username,
            'token' => time().$token,
            'description' => $request->desc,
            'allow_ip' => $request->allowip,
            'status' => 'active'
        ]);
        return redirect()->intended(route('settings', ['name' => 'api']));
    }

    public function renew_api(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $token_new = substr(str_shuffle($chars), 0, 15);
        Api::where('id', $id)->update(['token' => time().$token_new]);
        return redirect()->intended(route('settings', ['name' => 'api']));
    }

    public function delete_api(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        Api::where('id', $id)->delete();
        return redirect()->intended(route('settings', ['name' => 'api']));
    }

    public function block(Request $request)
    {
        $this->check();
        $request->validate([
            'status'=>'required|string'
        ]);
        if($request->status=='active')
        {
            Process::run("sudo iptables -A OUTPUT -m geoip -p tcp --destination-port 80 --dst-cc IR -j DROP");
            Process::run("sudo iptables -A OUTPUT -m geoip -p tcp --destination-port 443 --dst-cc IR -j DROP");
        }
        else
        {
            Process::run("sudo iptables -F");

        }

        return redirect()->intended(route('settings', ['name' => 'block']));
    }

    public function fakeurl(Request $request)
    {
        $this->check();
        $request->validate([
            'fake_address'=>'required|string'
        ]);
        $txt = '
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
function curl_get_contents($url) {
    $ch = curl_init();
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,font/woff,font/woff2,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5,application/font-woff,*";
    $header[] = "Access-Control-Allow-Origin: *";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    // I have added below two lines
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
$site = "' . $request->fake_address . '";
echo curl_get_contents("$site");
        ';
        file_put_contents("/var/www/html/example/index.php", $txt);
        return redirect()->intended(route('settings', ['name' => 'fakeaddress']));
    }



}
