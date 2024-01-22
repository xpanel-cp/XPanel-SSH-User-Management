<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use App\Models\Users;

class ProccessController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');
    }
    public function prcs_active_user()
    {
        $users = Users::where('status','active')->get();

        $processes = [];

        foreach ($users as $user) {
            $username = $user->username;
            $password = $user->password;

            $process1 = new Process(["sudo", "adduser", "--disabled-password", "--gecos", "''", "--shell", "/usr/sbin/nologin", $username]);
            $process1->run();
            $processes[] = $process1;

            $process2 = new Process(["sudo", "passwd", $username]);
            $process2->setInput("{$password}\n{$password}\n");
            $process2->start();
            $processes[] = $process2;
        }

        foreach ($processes as $process) {
            $process->wait();
        }
        return redirect()->intended(route('settings', ['name' => 'backup']))->with('alert', __('allert-success'));
    }
}
