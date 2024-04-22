<?php

namespace App\Providers;

use App\Models\Admins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Verta;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.topnav', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $detail_admin = Admins::where('id',$user->id)->first();
                if (!empty($detail_admin->end_date)) {
                    if (env('APP_LOCALE', 'en') == 'fa') {
                        $end_date = Verta::instance($detail_admin->end_date)->format('Y/m/d');
                    } else {
                        $end_date = $detail_admin->end_date;
                    }
                }
                else{
                    $end_date=''; }
                $view->with('end_date', $end_date)->with('count_account', $detail_admin->count_account)->with('username', $detail_admin->username);
                if($detail_admin->status!='active')
                {
                    echo redirect()->intended(route('user.logout'));
                }

            }

        });
    }
}
