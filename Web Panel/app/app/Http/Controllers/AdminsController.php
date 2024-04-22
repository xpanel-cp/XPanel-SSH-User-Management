<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Verta;


class AdminsController extends Controller
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
    public function index()
    {
        $this->check();
        $admins = Admins::where('permission', 'reseller')->orderBy('id', 'desc')->get();

        return view('admins.index', compact('admins'));
    }

    public function insert(Request $request)
    {
        $this->check();
        $request->validate([
            'username'=>'required|string',
            'password'=>'required|string',
            'end_date'=>'nullable|string',
            'count_account'=>'nullable|string'
        ]);
        if(env('APP_LOCALE', 'en')=='fa') {
            if (!empty($request->end_date)) {
                $end_date=$this->persianToenglishNumbers($request->end_date);
                $end_date = Verta::parse($end_date)->datetime()->format('Y-m-d');
            } else {
                $end_date = '';
            }
        }
        else
        {
            $end_date= $request->end_date;
        }
        $hashedPassword = Hash::make($request->password);
        $check_user = Admins::where('username',$request->username)->count();
        if ($check_user < 1) {
            Admins::create([
                'username' => $request->username,
                'password' => $hashedPassword,
                'permission' => 'reseller',
                'credit' => '0',
                'end_date' => $end_date,
                'count_account' => $request->count_account,
                'status' => 'active'
            ]);
        }

        return redirect()->intended(route('admins'));
    }

    public function activeadmin(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            Admins::where('username', $username)
                ->update(['status' => 'active']);
        }
        return redirect()->back()->with('success', 'Activated');
    }

    public function deactiveadmin(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            Admins::where('username', $username)
                ->update(['status' => 'deactive']);
        }
        return redirect()->back()->with('success', 'Deactivated');
    }

    public function deleteadmin(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();
        if ($check_user > 0) {
            Admins::where('username', $username)->delete();
        }
        return redirect()->back()->with('success', 'Deleted');
    }

    public function edit(Request $request,$username)
    {
        if (!is_string($username)) {
            abort(400, 'Not Valid Username');
        }
        $check_user = Admins::where('username',$username)->count();

        if ($check_user > 0) {
            $user = Admins::where('username', $username)
                ->get();
            $user = $user[0];
            if(env('APP_LOCALE', 'en')=='fa')
            {
                if(!empty($user->end_date)){$end_date=Verta::instance($user->end_date)->format('Y-m-d');
                    $end_date=$this->englishToPersianNumbers($end_date);}
                else
                {
                    $end_date=''  ;
                }
            }
            else
            {
                $end_date= $user->end_date;
            }
            return view('admins.edit', compact('user','end_date'));
        }
        else
        {
            return redirect()->back()->with('success', 'Not User');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'nullable|string',
            'end_date'=>'nullable|string',
            'count_account'=>'nullable|string'
        ]);
        if(env('APP_LOCALE', 'en')=='fa') {
            if (!empty($request->end_date)) {
                $end_date=$this->persianToenglishNumbers($request->end_date);
                $end_date = Verta::parse($end_date)->datetime()->format('Y-m-d');
            } else {
                $end_date = '';
            }
        }
        else
        {
            $end_date= $request->end_date;
        }
        if(!empty($request->password)) {
            $hashedPassword = Hash::make($request->password);
            Admins::where('username', $request->username)
                ->where('permission', 'reseller')
                ->update(['password' => $hashedPassword,'end_date' => $end_date,'count_account' => $hashedPassword]);
        }
        else
        {
            Admins::where('username', $request->username)
                ->where('permission', 'reseller')
                ->update(['end_date' => $end_date,'count_account' => $request->count_account]);
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
