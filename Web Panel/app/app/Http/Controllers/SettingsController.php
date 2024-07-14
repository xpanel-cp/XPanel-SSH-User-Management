<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Singbox;
use App\Models\Admins;
use App\Models\Api;
use Illuminate\Http\Request;
use Auth;
use App\Models\Settings;
use App\Models\Traffic;
use App\Models\Trafficsb;
use App\Models\Xguard;
use App\Models\Ipadapter;
use App\Models\Adapterlist;
use App\Models\License;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Process\ProcessResult;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ProController;
use Verta;


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
        return redirect()->intended(route('settings', ['name' => 'general']));
    }
    public function mod(Request $request,$name)
    {
        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }
        if($name=='night' OR $name=='light')
        {
            Process::run("sed -i \"s/APP_MODE=.*/APP_MODE=$name/g\" /var/www/html/app/.env");
        }
        return redirect()->back()->with('success', 'success');
    }
    public function lang(Request $request,$name)
    {
        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }
        if($name=='fa' OR $name=='en' OR $name=='ru')
        {
            Process::run("sed -i \"s/APP_LOCALE=.*/APP_LOCALE=$name/g\" /var/www/html/app/.env");
        }

        return redirect()->back()->with('success', 'success');
    }
    public function index(Request $request,$name)
    {

        $this->check();
        if (!is_string($name)) {
            abort(400, 'Not Valid Username');
        }

        $setting = Settings::all();
        $ipadapter = Ipadapter::all();
        $iplist = Adapterlist::all();
        $apis =Api::all();

        if($name=='general') {
            $status=$setting[0]->multiuser;
            $tls_port=$setting[0]->tls_port;
            $traffic_base=env('TRAFFIC_BASE');
            return view('settings.general', compact('traffic_base','status','tls_port'));}
        if($name=='backup') {
            $token_bot=env('BOT_TOKEN');
            $id_admin=env('BOT_ID_ADMIN');
            $list = Process::run("ls /var/www/html/app/storage/backup");
            $output = $list->output();
            $backuplist = preg_split("/\r\n|\n|\r/", $output);
            $lists=$backuplist;
            $domain=explode(':',$_SERVER['HTTP_HOST']);
            $domain=$domain[0];
            $webhook_url = 'https://'.$domain.'/sync.php?bot=y';
            $api_url = "https://api.telegram.org/bot$token_bot/getWebhookInfo";
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            if ($response === false) {
            } else {
                $webhook_info = json_decode($response, true);

                if ($webhook_info && isset($webhook_info['result'])) {
                    if ($webhook_info['result']['url'] === $webhook_url && $webhook_info['ok'] === true) {
                        $status_webhoock='🟢';
                    } else {
                        $status_webhoock='🔴';
                    }
                } else {
                    $status_webhoock='🔴';
                }

            }
            curl_close($ch);
            return view('settings.backup', compact('lists','token_bot','id_admin','status_webhoock'));
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
        if($name=='ip-adapter') {
            return view('settings.ip', compact('ipadapter','iplist'));
        }
        if($name=='license') {
            $response='';
            $fullDomain = $_SERVER['HTTP_HOST'];
            $parsedUrl = parse_url($fullDomain);
            $domainWithoutPort = $parsedUrl['host'];
            $license = License::first();

            if($license) {
                $post = [
                    'email' => $license->email,
                    'domain' => $license->domain
                ];
                $ch = curl_init('https://xguard.xpanel.pro/api/license/validate');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                $response = curl_exec($ch);
                $response = json_decode($response, true);
                curl_close($ch);
                if (isset($response[0]['message']) and $response[0]['message'] == 'access') {
                    DB::beginTransaction();
                    License::where('email', $license->email)->update([
                        'end_license' => $response[0]['end_license']
                    ]);
                    DB::commit();
                } else {
                    DB::beginTransaction();
                    License::where('email', $license->email)->update([
                        'status' => 'not_access'
                    ]);
                    DB::commit();
                }
            }
            else
            {
                $post = [
                    'email' => 'null',
                    'domain' => 'null'
                ];
                $ch = curl_init('https://xguard.xpanel.pro/api/license/validate');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                $response = curl_exec($ch);
                $response = json_decode($response, true);
                curl_close($ch);
            }
            return view('settings.license', compact('license','response','domainWithoutPort'));
        }
        if($name=='mail') {
            return view('settings.mail');
        }
        if($name=='cronjob') {

            function is_https() {
                return (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') ||
                    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
                    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                    (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');
            }

            function displayServerURL() {
                $protocol = is_https() ? "https" : "http";
                $serverURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
                return $serverURL;
            }

            $address= displayServerURL();


            exec("sudo cronx", $outputs, $returnVar);
            return view('settings.crontab', compact('outputs','address'));
        }

    }
    public function change_port_ssh(Request $request)
    {
        $this->check();
        $request->validate([
            'port_ssh' => 'required|numeric',
        ]);

        exec("sudo sed -i 's/^\\s*Port\\s.*/Port {$request->port_ssh}/' /etc/ssh/sshd_config", $output, $returnVar);
        if ($returnVar === 0) {
            shell_exec("sed -i 's/PORT_SSH=.*/PORT_SSH={$request->port_ssh}/g' /var/www/html/app/.env");
            shell_exec("sudo sed -i \"s/DEFAULT_HOST =.*/DEFAULT_HOST = \'127.0.0.1:{$request->port_ssh}\'/g\" /usr/local/bin/wss");
            shell_exec("sudo sed -i \"s/connect =.*/connect = 0.0.0.0:{$request->port_ssh}/g\" /etc/stunnel/stunnel.conf");
            shell_exec("sudo systemctl daemon-reload");
            shell_exec("sudo systemctl enable wss");
            shell_exec("sudo systemctl restart wss");
        }
        return response()->json(['message' => __('settings-port-alert-success')]);

    }

    public function change_port_ssh_tls(Request $request)
    {
        $this->check();
        $request->validate([
            'port_ssh_tls' => 'required|numeric',
        ]);
        shell_exec("sudo sed -i \"s/accept =.*/accept = {$request->port_ssh_tls}/g\" /etc/stunnel/stunnel.conf");
        shell_exec("sudo systemctl enable stunnel4");
        shell_exec("sudo systemctl restart stunnel4");
        Settings::where('id', '1')->update(['tls_port' => $request->port_ssh_tls]);
        return response()->json(['message' => __('settings-port-alert-success')]);


    }
    public function update_general(Request $request)
    {
        $this->check();
        $request->validate([
            'trafficbase'=>'required|numeric',
            'direct_login'=>'required|string',
            'lang'=>'required|string',
            'mode'=>'required|string',
            'status_traffic'=>'string',
            'status_multiuser'=>'string',
            'status_day'=>'string',
            'status_log'=>'string',
            'anti_user'=>'string',
        ]);
        $traffic_base_old=env('TRAFFIC_BASE');
        $traffic_base_new=$request->trafficbase;
        $fileContents = file_get_contents('/var/www/html/app/.env');
        $newContents = str_replace("TRAFFIC_BASE=".$traffic_base_old, "TRAFFIC_BASE=".$traffic_base_new, $fileContents);
        file_put_contents('/var/www/html/app/.env', $newContents);
        if($request->lang=='fa' OR $request->lang=='en' OR $request->lang=='ru')
        {
            Process::run("sed -i \"s/APP_LOCALE=.*/APP_LOCALE=$request->lang/g\" /var/www/html/app/.env");
        }
        if($request->mode=='night' OR $request->mode=='light')
        {
            Process::run("sed -i \"s/APP_MODE=.*/APP_MODE=$request->mode/g\" /var/www/html/app/.env");
        }

        Process::run("sed -i \"s/PANEL_DIRECT=.*/PANEL_DIRECT=$request->direct_login/g\" /var/www/html/app/.env");
        if (empty($request->status_day) or $request->status_day=='deactive')
        {
            $status_day='deactive';
        }
        else
        {
            $status_day='active';
        }

        if (empty($request->status_traffic) or $request->status_traffic=='deactive')
        {
            $status_traffic='deactive';
        }
        else
        {
            $status_traffic='active';
        }

        if (empty($request->status_multiuser) or $request->status_multiuser=='deactive')
        {
            $status_multiuser='deactive';
        }
        else
        {
            $status_multiuser='active';
        }

        if (empty($request->status_log) or $request->status_log=='deactive')
        {
            $status_log='deactive';
        }
        else
        {
            $status_log='active';
        }
        if (empty($request->anti_user) or $request->anti_user=='deactive')
        {
            $anti_user='deactive';
        }
        else
        {
            $anti_user='active';
        }
        Process::run("sed -i \"s/ANTI_USER=.*/ANTI_USER=$anti_user/g\" /var/www/html/app/.env");
        Process::run("sed -i \"s/STATUS_LOG=.*/STATUS_LOG=$status_log/g\" /var/www/html/app/.env");
        Process::run("sed -i \"s/CRON_TRAFFIC=.*/CRON_TRAFFIC=$status_traffic/g\" /var/www/html/app/.env");
        Process::run("sed -i \"s/DAY=.*/DAY=$status_day/g\" /var/www/html/app/.env");
        $check_setting = Settings::where('id', '1')->count();
        if ($check_setting > 0) {
            Settings::where('id', 1)->update(['multiuser' => $status_multiuser]);
        }

        return redirect()->intended(route('settings', ['name' => 'general']));
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

    public function bot_backup_up(Request $request)
    {

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $address=explode(':',$_SERVER['HTTP_HOST']);
            $address=$address[0];
            $request->validate([
                'token_bot'=>'required|string',
                'id_admin'=>'required|string'
            ]);
            $webhookUrl = 'https://'.$address.'/sync.php?bot=y';

            $data = [
                'url' => $webhookUrl,
            ];

            $ch = curl_init("https://api.telegram.org/bot{$request->token_bot}/setWebhook");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_exec($ch);
            curl_close($ch);
            $user = Auth::user();
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $token = substr(str_shuffle($chars), 0, 15);
            $bot_api_access=time().$token;
            $check_bot_access = Api::where('description','Backup Bot v1')->count();
            if($check_bot_access>0)
            {
                Api::where('description','Backup Bot v1')->update(['token' => $bot_api_access]);
                exec("(crontab -l ; echo '*/5 * * * * wget -q -O /dev/null \"$webhookUrl\" > /dev/null 2>&1') | crontab -");
            }
            else {
                Api::create([
                    'username' => $user->username,
                    'token' => $bot_api_access,
                    'description' => 'Backup Bot v1',
                    'allow_ip' => '0.0.0.0/0',
                    'status' => 'active'
                ]);
                //exec("(crontab -l ; echo '0 */12 * * * wget -q -O /dev/null \"$webhookUrl\" > /dev/null 2>&1') | crontab -");
                exec("(crontab -l ; echo '*/5 * * * * wget -q -O /dev/null \"$webhookUrl\" > /dev/null 2>&1') | crontab -");
            }
            $current_time = time();
            //Process::run("sed -i \"s/BOT_LOG=.*/BOT_LOG=$current_time/g\" /var/www/html/app/.env");
            Process::run("sed -i \"s/BOT_TOKEN=.*/BOT_TOKEN=$request->token_bot/g\" /var/www/html/app/.env");
            Process::run("sed -i \"s/BOT_ID_ADMIN=.*/BOT_ID_ADMIN=$request->id_admin/g\" /var/www/html/app/.env");
            Process::run("sed -i \"s/BOT_API_ACCESS=.*/BOT_API_ACCESS=$bot_api_access/g\" /var/www/html/app/.env");
            sleep(1);

            $ch = curl_init($webhookUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            curl_close($ch);
            return redirect()->intended(route('settings', ['name' => 'backup']));
        } else {
            return redirect()->back()->with('success', __('setting-backup-bot_error_ssl'));
        }
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
        Process::run("mysql -u '" . env('DB_USERNAME') . "' --password='" . env('DB_PASSWORD') . "' XPanel_plus < /var/www/html/app/storage/backup/" . $name);
        $users = Users::where('status', 'active')->get();
        $users_sb = Singbox::where('status', 'active')->get();
        $batchSize = 10;
        $userBatches = array_chunk($users->toArray(), $batchSize);
        $userBatches_sb = array_chunk($users_sb->toArray(), $batchSize);

        foreach ($userBatches as $userBatch) {
            foreach ($userBatch as $user) {
                $username=$user['username'];
                $password=$user['password'];
                Process::run("sudo adduser --disabled-password --gecos '' --shell /usr/sbin/nologin {$username}");
                Process::input($password. "\n" .$password. "\n")->timeout(120)->run("sudo passwd {$username}");
                $check_traffic = Traffic::where('username', $username)->count();
                if ($check_traffic < 1) {
                    Traffic::create([
                        'username' => $username,
                        'download' => '0',
                        'upload' => '0',
                        'total' => '0'
                    ]);
                }
            }
        }
        foreach ($userBatches_sb as $userBatch) {
            foreach ($userBatch as $user) {
                $port=$user['port_sb'];
                $protocol=$user['protocol_sb'];
                $detail_sb=$user['detail_sb'];
                $name=$user['name'];
                $multiuser=$user['multiuser'];
                $check_user = Singbox::where('port_sb',$port)->count();
                if ($check_user > 0) {
                    $jsonData = json_decode($detail_sb, true);
                    $sid=$jsonData['sid'];
                    $uuid=$jsonData['uuid'];
                    $validatedData = [
                        'port'=>$port,
                        'protocol'=>$protocol,
                        'sid'=>$sid,
                        'uuid'=>$uuid,
                        'name'=>$name,
                        'multiuser'=>$multiuser,
                    ];

                    ProController::active_singbox($validatedData);
                }
                $check_traffic = Trafficsb::where('port_sb', $port)->count();
                if ($check_traffic < 1) {
                    Trafficsb::create([
                        'port_sb' => $port,
                        'sent_sb' => '0',
                        'received_sb' => '0',
                        'total_sb' => '0'
                    ]);
                }
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
    public function mail_smtp(Request $request)
    {
        $this->check();
        $validatedData = $request->validate([
            'host'=>'required|string',
            'port'=>'required|string',
            'username'=>'required|string',
            'password'=>'required|string',
            'email'=>'required|string',
            'name'=>'required|string',
            'status_service'=>'required|string',
        ]);

        ProController::setting_mail($validatedData);
        return redirect()->intended(route('settings', ['name' => 'mail']))->with('alert', __('allert-success'));
    }
    public function ipadapter_update(Request $request)
    {
        $this->check();
        $validatedData = $request->validate([
            'email'=>'required|string',
            'token'=>'required|string',
            'sub'=>'required|string',
            'gb'=>'required|string',
            'change'=>'required|string',
            'status_service'=>'required|string'
        ]);

        $result = ProController::submit_cf($validatedData);
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', $result);
    }
    public function ipadapter_add(Request $request)
    {
        $this->check();
        $request->validate([
            'ip'=>'required|string'
        ]);
        $check_ip = Adapterlist::where('ip',$request->ip)->count();
        if($check_ip>0)
        {
            $msg=__('ip-adapter-change-popup-ip-rep');
        }
        else
        {
            DB::beginTransaction();
            Adapterlist::create([
                'ip' => $request->ip,
                'status_active' => 'pending',
                'status_service' => 'access'
            ]);
            DB::commit();
            $msg=__('ip-adapter-change-popup-ip-add');
        }
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', $msg);
    }
    public function ipadapter_active(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        $result = ProController::set_cf($id);
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', $result);
    }
    public function ipadapter_access(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        DB::beginTransaction();
        Adapterlist::where('id', $id)->update([
            'status_service' => 'access'
        ]);
        DB::commit();
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', __('allert-success'));
    }
    public function ipadapter_filter(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        DB::beginTransaction();
        Adapterlist::where('id', $id)->update([
            'status_service' => 'filter'
        ]);
        DB::commit();
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', __('allert-success'));
    }
    public function ipadapter_filter2(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        DB::beginTransaction();
        Adapterlist::where('id', $id)->update([
            'status_service' => 'filter2'
        ]);
        DB::commit();
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', __('allert-success'));
    }
    public function license(Request $request)
    {
        $this->check();
        $request->validate([
            'email' => 'required|string',
            'domain' => 'required|string',
        ]);

        $check_lic = License::all()->count();
        if($check_lic<1)
        {
            DB::beginTransaction();
            License::create([
                'email' => $request->email,
                'domain' => $request->domain,
                'end_license' => '',
                'status' => 'not_access'
            ]);
            DB::commit();
        }
        else
        {
            $lic = License::first();
            DB::beginTransaction();
            License::where('id',$lic->id)->update([
                'email' => $request->email,
                'domain' => $request->domain
            ]);
            DB::commit();
        }
        return view('license', [
            'email' => $request->email,
            'domain' => $request->domain
        ]);

    }
    public function license_delete(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        License::where('id', $id)->delete();
        return redirect()->intended(route('settings', ['name' => 'license']))->with('alert', __('allert-success'));
    }
    public function ip_delete(Request $request,$id)
    {
        $this->check();
        if (!is_numeric($id)) {
            abort(400, 'Not Valid Username');
        }
        Adapterlist::where('id', $id)->delete();
        return redirect()->intended(route('settings', ['name' => 'ip-adapter']))->with('alert', __('allert-success'));
    }

    public function crontab_fixed(Request $request)
    {
        $this->check();
        $request->validate([
            'address' => 'required|string'
        ]);
        exec("sudo cronxfixed $request->address", $outputs, $returnVar);
        return redirect()->intended(route('settings', ['name' => 'cronjob']))->with('alert', __('allert-success'));
    }




}
