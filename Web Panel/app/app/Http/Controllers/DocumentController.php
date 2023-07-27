<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DocumentController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admins');
    }
    public function index()
    {
        $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $http_host=$_SERVER['HTTP_HOST'];
        $path=$protocol.'://'.$http_host.'/';
        return view('dashboard.doc', compact('path'));
    }


}
