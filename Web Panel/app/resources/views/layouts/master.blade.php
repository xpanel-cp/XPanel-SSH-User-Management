<!DOCTYPE html>
<html lang="fa-IR" class="no-js">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
@include('layouts.header')
@php $selectedLanguage = env('APP_MODE', 'light'); @endphp
@if($selectedLanguage=='light')
    <body>
    @elseif($selectedLanguage=='night')
        <body data-pc-theme="dark">
        @endif
        <!-- [ Pre-loader ] start -->
        <div class="loader-bg">
            <div class="loader-track">
                <div class="loader-fill"></div>
            </div>
        </div>
        <!-- [ Pre-loader ] End -->
        @include('layouts.topnav')

        @if(!empty(session('alert')))
            <div class="p-4 mb-2" style="position: fixed;z-index: 9999;left: 0;">
                <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="/assets/images/xlogo.png" class="img-fluid m-r-5" alt="xpanel" style="width: 17px">
                        <strong class="me-auto">XPanel</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">{{ session('alert') }} </div>
                </div>
            </div>
        @endif
        @yield('content')
        @include('layouts.footer')
        @if(env('APP_LOCALE')=='fa')
            <style>
                .xguard_loc{
                    left: 5px; position: fixed; width: 40px; bottom: 50px;
                }
            </style>
        @else
            <style>
                .xguard_loc{
                    right: 5px; position: fixed; width: 40px; bottom: 50px;
                }
            </style>
        @endif
        </body>

</html>

