@extends('layouts.master')
@section('title','XPanel - '.__('setting-general-title'))
@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{__('setting-general-title')}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <form action="{{route('settings.general')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-sm-12">
                        <div class="card">
                            @include('layouts.setting_menu')
                            <div class="tab-content" id="myTabContent">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <input type="text" name="trafficbase" class="form-control" value="{{$traffic_base}}" required="">
                                            <small>{{__('setting-general-base')}}</small>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" name="direct_login" class="form-control"  value="{{env('PANEL_DIRECT')}}">
                                                <small>{{__('setting-general-direct')}}</small>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <select class="form-select" name="lang" id="exampleFormControlSelect1">
                                                    @if(env('APP_LOCALE')=='fa')
                                                        <option value="fa">فارسی</option>
                                                    @elseif(env('APP_LOCALE')=='en')
                                                        <option value="en">English</option>
                                                    @endif
                                                    <optgroup label="------">
                                                        <option value="fa">فارسی</option>
                                                        <option value="en">English</option>
                                                    </optgroup>
                                                </select>
                                                <small>{{__('setting-general-lang')}}</small>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <select class="form-select" name="mode" id="exampleFormControlSelect1">

                                                    @if(env('APP_MODE')=='light')
                                                        <option value="light">{{__('setting-general-light')}}</option>
                                                    @elseif(env('APP_MODE')=='night')
                                                        <option value="night">{{__('setting-general-night')}}</option>
                                                    @endif
                                                    <optgroup label="------">
                                                        <option value="light">{{__('setting-general-light')}}</option>
                                                        <option value="night">{{__('setting-general-night')}}</option>
                                                    </optgroup>
                                                </select>
                                                <small>{{__('setting-general-mod')}}</small>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-success" type="button" id="change_port_ssh">{{__('settings-port-success')}}</button>
                                                    <input type="text" class="form-control" name="port_ssh" id="port_ssh" placeholder="Port SSH" aria-label="Example text with button addon" aria-describedby="button-addon1" value="{{env('PORT_SSH')}}">
                                                    <small>{{__('settings-port-ssh')}}</small>
                                                    <div id="resultssh"></div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-success" type="button" id="change_port_ssh_tls">{{__('settings-port-success')}}</button>
                                                    <input type="text" class="form-control" name="port_ssh_tls" id="port_ssh_tls" placeholder="Port SSH Tls" aria-label="Example text with button addon" aria-describedby="button-addon1" value="{{$tls_port}}">
                                                    <small>{{__('settings-port-ssh-tls')}}</small>
                                                    <div id="resultsshtls"></div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- [ sample-page ] end -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 pb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1">{{__('setting-status-traffic')}}</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox" role="switch" name="status_traffic" id="status_traffic" @if(env('CRON_TRAFFIC', 'active')=='active')value="active" checked=""@else value="deactive"@endif>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item px-0 pb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1">{{__('setting-status-miltiu')}}</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox" role="switch" name="status_multiuser" id="status_multiuser" @if($status=='active')value="active" checked=""@else value="deactive"@endif>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 pb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1">{{__('user-table-day')}}</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox" role="switch" name="status_day" id="status_day" @if(env('DAY', 'deactive')=='active')value="active" checked=""@else value="deactive"@endif>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 pb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1">{{__('setting-status-log')}}</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox" role="switch" name="status_log" id="status_log" @if(env('STATUS_LOG', 'deactive')=='active')value="active" checked=""@else value="deactive"@endif>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item px-0 pb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1">{!! __('settings-custom-user') !!}</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox" role="switch" name="anti_user" id="anti_user" @if(env('ANTI_USER')=='active')value="active" checked=""@else value="deactive"@endif>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('setting-save')}}</button>
                    </div>
                </form>

            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- افزودن jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#change_port_ssh').click(function () {
                var newPortSSH = $('#port_ssh').val();

                // ارسال درخواست به سمت سرور
                $.ajax({
                    url: '{{ route("settings.change.port.ssh") }}',
                    type: 'POST',
                    data: {
                        port_ssh: newPortSSH,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        // نمایش پاسخ در داخل یک عنصر با شناسه resultContainer
                        $('#resultssh').html('<div class="alert alert-success d-flex align-items-center" role="alert">' +
                            '<i class="bi flex-shrink-0 me-2 ti ti-refresh" style="font-size: 30px"></i>' +
                            '<div><small>' + response.message + '</small></div>' +
                            '</div>');
                        setTimeout(function () {
                            $('#resultssh').empty(); // یا می‌توانید از hide() استفاده کنید
                        }, 15000);
                    },
                    error: function (error) {
                        // نمایش پیام خطا در داخل یک عنصر با شناسه resultContainer
                        $('#resultssh').text('خطا: ' + error.statusText);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#change_port_ssh_tls').click(function () {
                var newPortSSHtls = $('#port_ssh_tls').val();

                // ارسال درخواست به سمت سرور
                $.ajax({
                    url: '{{ route("settings.change.port.ssh.tls") }}',
                    type: 'POST',
                    data: {
                        port_ssh_tls: newPortSSHtls,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        // نمایش پاسخ در داخل یک عنصر با شناسه resultContainer
                        $('#resultsshtls').html('<div class="alert alert-success d-flex align-items-center" role="alert">' +
                            '<i class="bi flex-shrink-0 me-2 ti ti-refresh" style="font-size: 30px"></i>' +
                            '<div><small>' + response.message + '</small></div>' +
                            '</div>');
                        setTimeout(function () {
                            $('#resultsshtls').empty(); // یا می‌توانید از hide() استفاده کنید
                        }, 15000);
                    },
                    error: function (error) {
                        // نمایش پیام خطا در داخل یک عنصر با شناسه resultContainer
                        $('#resultsshtls').text('خطا: ' + error.statusText);
                    }
                });
            });
        });
    </script>

@endsection
