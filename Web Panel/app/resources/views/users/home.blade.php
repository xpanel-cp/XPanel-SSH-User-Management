@extends('layouts.master')
@section('title','XPanel - '.__('user-title'))
@section('content')
    <!-- [ Main Content ] start -->
    @if (!empty($detail_admin->end_date))
        @if(env('APP_LOCALE', 'en')=='fa')
            @php $end_date=Verta::instance($detail_admin->end_date)->format('Y/m/d');@endphp
        @else
            @php $end_date=$detail_admin->end_date;@endphp
        @endif

    @else
        @php $end_date=''; @endphp
    @endif
    <script src="/assets/js/clipboard.min.js"></script>
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{__('user-title')}}</h2>
                                <small>{{__('manager-count-account')}}: <b style="font-size: medium;">@if(!empty($detail_admin->count_account)){{$detail_admin->count_account}} @else ♾️@endif</b> &nbsp;&nbsp; {{__('user-pop-newuser-date-desc1')}}: <b style="font-size: medium;">@if(!empty($end_date)){{$end_date}} @else ♾️@endif </b></small>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('search')}}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{route('users.search')}}" method="get" enctype="multipart/form-data" class="row row-cols-md-auto g-3 align-items-center">
                                <div class="col-12">
                                    <input type="text" name="keyword" class="form-control" id="inlineFormInputName" value="{{ request('keyword') }}" placeholder="{{__('search')}}...">
                                </div>
                                <div class="col-12">
                                    <select class="form-select" id="inlineFormSelectPref" name="search_by">
                                        <option value="username"{{ request('search_by') == 'username' ? ' selected' : '' }}>{{__('search-username')}}</option>
                                        <option value="customer_user"{{ request('search_by') == 'customer_user' ? ' selected' : '' }}>{{__('search-customer')}}</option>
                                        <option value="email"{{ request('search_by') == 'name' ? ' email' : '' }}>{{__('search-email')}}</option>
                                        <option value="mobile"{{ request('search_by') == 'name' ? ' mobile' : '' }}>{{__('search-phone')}}</option>
                                        <option value="desc"{{ request('search_by') == 'name' ? ' desc' : '' }}>{{__('search-desc')}}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <select name="status" class="form-select" id="inlineFormSelectPref">
                                        <option value="all"{{ request('status') == 'all' ? ' selected' : '' }}>{{__('search-user-all')}}</option>
                                        <option value="active"{{ request('status') == 'active' ? ' selected' : '' }}>{{__('user-table-status-active')}}</option>
                                        <option value="deactive"{{ request('status') == 'deactive' ? ' selected' : '' }}>{{__('user-table-status-deactive')}}</option>
                                        <option value="expired"{{ request('status') == 'expired' ? ' selected' : '' }}>{{__('user-table-status-exp')}}</option>
                                        <option value="traffic"{{ request('status') == 'traffic' ? ' selected' : '' }}>{{__('user-table-status-traffic')}}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{__('search')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card table-card">
                        <div class="card-body">
                            <form action="{{route('user.action.bulk')}}" method="post" enctype="multipart/form-data"
                                  onkeydown="return event.key != 'Enter';" onsubmit="return confirm('{{__('allert-submit')}}');">
                                @csrf

                                <div class="table-btn gap-2">
                                    <a href="javascript:void(0);"
                                       class="btn btn-primary d-inline-flex text-center"
                                       data-bs-toggle="modal"
                                       data-bs-target="#customer_add-modal">
                                        <i class="ti ti-plus f-18"></i>{{__('user-modal-user')}}
                                    </a>

                                    <a href="javascript:void(0);"
                                       class="btn btn-primary d-inline-flex text-center"
                                       data-bs-toggle="modal"
                                       data-bs-target="#customer_bulk-modal">
                                        <i class="ti ti-plus f-18"></i>{{__('user-modal-bulkuser')}}</a>
                                    <button type="submit" id="btndl"
                                            class="btn btn-danger d-inline-flex text-center"
                                            value="delete" name="action">{{__('user-bulk-delete')}}
                                    </button>
                                    <button type="submit" id="btnactive"
                                            class="btn btn-success d-inline-flex text-center"
                                            value="active" name="action">{{__('user-table-active')}}
                                    </button>
                                    <button type="submit" id="btndeactive"
                                            class="btn btn-warning d-inline-flex text-center"
                                            value="deactive" name="action">{{__('user-table-deactive')}}
                                    </button>

                                    <button type="submit" id="retraffic"
                                            class="btn btn-info d-inline-flex align-items-center"
                                            value="retraffic" name="action">{{__('user-table-reset')}}
                                    </button>
                                    <button type="button" id="renewbulk" class="btn btn-primary" onclick="openModal()">{{__('user-table-tog-renewal')}}</button>

                                </div>
                                <br>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="example" >
                                        <thead>
                                        <tr>
                                            <th><input class="form-check-input" type="checkbox" id="selectAll"> #</th>
                                            <th>{{__('user-table-username')}}/{{__('user-table-password')}}</th>
                                            <th>{{__('user-table-traffic')}}</th>
                                            <th>{{__('user-table-limit-user')}}</th>
                                            <th>{{__('user-table-contact')}}</th>
                                            @if(env('DAY', 'deactive')=='active')
                                                <th>{{__('user-table-day')}}</th>
                                            @else
                                                <th>{{__('user-table-date')}}</th>
                                            @endif
                                            <th>{{__('user-table-desc')}}</th>
                                            <th class="text-center">{{__('user-table-action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody id="online-table">
                                        @php
                                            $uid = 0;
                                            $at='@';
                                            function sagernet_link_generator(string $server_address, int $port, string $username, string $password): string {
    // Initialize Kryo bytes
    $kryo_bytes = "\x00\x00\x00\x00";

    // Encode server address
    $server_address_bytes = str_split($server_address);
    $last_char = array_pop($server_address_bytes);
    $server_address_bytes[] = chr(ord($last_char) + 128);
    $kryo_bytes .= implode('', $server_address_bytes);

    // Encode port
    $kryo_bytes .= pack("v", $port);
    $kryo_bytes .= "\x00\x00";

    // Encode username
    $username_bytes = str_split($username);
    $last_char = array_pop($username_bytes);
    $username_bytes[] = chr(ord($last_char) + 128);
    $kryo_bytes .= implode('', $username_bytes);
    $kryo_bytes .= "\x01\x00\x00\x00";

    // Encode password
    $password_bytes = str_split($password);
    $last_char = array_pop($password_bytes);
    $password_bytes[] = chr(ord($last_char) + 128);
    $kryo_bytes .= implode('', $password_bytes);
    $kryo_bytes .= "\x81\x01\x00\x00\x00\xa1";

    // Encode title
    $title = sprintf("(%s)", $username);
    $kryo_bytes .= $title . "\x00\x00\x00\x00";

    // Compress Kryo bytes using zlib
    $zlib_compressed = gzcompress($kryo_bytes);

    // Encode in Base64 URL safe format
    $base64_urlsafe = rtrim(strtr(base64_encode($zlib_compressed), '+/', '-_'), '=');

    return "sn://ssh?" . $base64_urlsafe;
}
                                        @endphp
                                        @foreach ($users as $user)
                                            @php
                                                $uid++
                                            @endphp
                                            @foreach($user->traffics as $traffic)
                                                @php $total_exo=$traffic->total;@endphp

                                                @if (1024 <= $traffic->total)

                                                    @php
                                                        $trafficValue = floatval($traffic->total);
                                                        $total = round($trafficValue / 1024, 3) . ' GB';  @endphp
                                                @else
                                                    @php $total = $traffic->total . ' MB'; @endphp
                                                @endif
                                            @endforeach

                                            @if ($user->traffic > 0)
                                                @if (1024 <= $user->traffic)
                                                    @php
                                                        $trafficValue = floatval($user->traffic);
                                                        $traffic_user = round($trafficValue / 1024,3) . ' GB';
                                                    @endphp
                                                @else
                                                    @php
                                                        $traffic_user = $user->traffic . ' MB';

                                                    @endphp
                                                @endif
                                                @php

                                                    // تعیین مقادیر
                                                    $value2 = $user->traffic;
                                                    $value1 = $total_exo;
                                                    $percentageDifference = intval(($value1 / $value2) * 100);
                                                    $percentageBG='';
                                                @endphp
                                            @else
                                                @php
                                                    $traffic_user = 'Unlimited';
                                                    $percentageDifference=100;
                                                    $percentageBG='bg-success';

                                                @endphp
                                            @endif



                                            @if ($user->status == "active" or $user->status == "true")
                                                @php $status = "<span class='badge bg-light-success rounded-pill f-12'>".__('user-table-status-active')."</span>"; @endphp
                                            @endif
                                            @if ($user->status == "deactive" or $user->status == "false")
                                                @php $status = "<span class='badge bg-light-danger rounded-pill f-12'>".__('user-table-status-deactive')."</span>"; @endphp
                                            @endif
                                            @if ($user->status == "expired")
                                                @php $status = "<span class='badge bg-light-warning rounded-pill f-12'>".__('user-table-status-exp')."</span>"; @endphp
                                            @endif
                                            @if ($user->status == "traffic")
                                                @php $status = "<span class='badge bg-light-primary rounded-pill f-12'>".__('user-table-status-traffic')."</span>"; @endphp
                                            @endif
                                            @if (empty($user->customer_user) OR $user->customer_user=='NULL')
                                                @php $customer_user = env('DB_USERNAME'); @endphp
                                            @else
                                                @php $customer_user = $user->customer_user; @endphp
                                            @endif

                                            @if (empty($settings->tls_port) || $settings->tls_port == 'NULL')
                                                @php $tls_port = '444'; @endphp
                                            @else
                                                @php $tls_port=$settings->tls_port; @endphp
                                            @endif
                                            @if (empty($user->start_date) || $user->start_date == 'NULL')
                                                @php $startdate = ''; @endphp
                                            @else
                                                @php $startdate=$user->start_date; @endphp
                                            @endif
                                            @if (empty($user->end_date) || $user->end_date == 'NULL')
                                                @php $finishdate = ''; @endphp
                                            @else
                                                @php $finishdate=$user->end_date; @endphp
                                            @endif


                                            @if(!empty($finishdate))
                                                @php
                                                    $start_inp = date("Y-m-d");
                                                    $today = new DateTime($start_inp); // تاریخ امروز
                                                    $futureDate = new DateTime($finishdate);
                                                @endphp
                                                @if ($today > $futureDate)
                                                    @php
                                                        $interval = $futureDate->diff($today);
                                                        $daysDifference_day = -1 * $interval->days; // تعداد روزهای منفی برای تاریخ‌های گذشته
                                                    @endphp
                                                @else
                                                    @php
                                                        $interval = $today->diff($futureDate);
                                                        $daysDifference_day = $interval->days;
                                                    @endphp
                                                @endif
                                            @else
                                                @php $daysDifference_day='Unlimit'; @endphp
                                            @endif
                                            @if(!empty($user->conections))
                                                @php $connection = $user->conections->connection; @endphp
                                                @php $datecon = $user->conections->datecon;
                                                if(env('APP_LOCALE', 'en')=='fa')
                                                    {
                                                $datecon=Verta::instance($datecon)->format('Y/m/d H:i');
                                                }
                                                @endphp
                                            @else
                                                @php $connection = '0'; @endphp
                                                @php $datecon = ''; @endphp

                                            @endif
                                            @php $st_date = ''; @endphp
                                            @php $en_date = ''; @endphp
                                            @if (!empty($startdate))
                                                @if(env('APP_LOCALE', 'en')=='fa')
                                                    @php $st_date="StartTime:".Verta::instance($startdate)->format('Y/m/d');@endphp
                                                @else
                                                    @php $st_date="StartTime:$startdate";@endphp
                                                @endif
                                            @endif
                                            @if (!empty($finishdate))
                                                @if(env('APP_LOCALE', 'en')=='fa')
                                                    @php $en_date="EndTime:".Verta::instance($finishdate)->format('Y/m/d');@endphp
                                                @else
                                                    @php $en_date="EndTime:$finishdate";@endphp
                                                @endif  @endif
                                            <tr>
                                                <td><input name="usernamed[]" id="checkItem" type="checkbox"
                                                           class="checkItem form-check-input"
                                                           value="{{$user->username}}"/> {{$uid}}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="row g-1">
                                                                <div class="col-12">
                                                                    <h6 class="mb-0">{{$user->username}}</h6>
                                                                    <p class="text-muted mb-0"><small>{{$user->password}}</small></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            {!! $status !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><small>{{$total}} {{__('user-from')}} {{$traffic_user}}</small><br>
                                                    <div class="progress" style="height: 7px">
                                                        <div class="progress-bar {{$percentageBG}}" role="progressbar" style="width: {{$percentageDifference}}%" aria-valuenow="{{$percentageDifference}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div></td>

                                                <td><span class="badge bg-light-secondary f-12" style="width: -webkit-fill-available;">{{$connection}} {{__('user-from')}} {{$user->multiuser}}</span></td>
                                                <td>{{$user->mobile}}<br>
                                                    <small>{{$user->email}}</small></td>
                                                <td>
                                                    @if(env('DAY', 'deactive')=='active')
                                                        {{$daysDifference_day}}
                                                    @else
                                                        <small>
                                                            @if(env('APP_LOCALE', 'en')=='fa')
                                                                {{__('user-table-date-start')}}: @if(!empty($startdate))
                                                                    <span
                                                                        style="display: inline-block;">{{Verta::instance($startdate)->format('Y-m-d')}}</span>@endif
                                                                <br>
                                                                {{__('user-table-date-end')}}: @if(!empty($finishdate))
                                                                    <span
                                                                        style="display: inline-block;">{{Verta::instance($finishdate)->format('Y-m-d')}}</span>@endif
                                                            @else
                                                                {{__('user-table-date-start')}}: <span
                                                                    style="display: inline-block;">{{$startdate}}</span>
                                                                <br>
                                                                {{__('user-table-date-end')}}: <span
                                                                    style="display: inline-block;">{{$finishdate}}</span>
                                                            @endif
                                                        </small>
                                                    @endif
                                                </td>
                                                <td style="width: 50px"><small>
                                                        <div style="text-wrap: pretty;">{{__('user-table-customer')}}:{{$customer_user}}<br>{{$user->desc}}</div>
                                                    </small></td>
                                                <td class="text-center">
                                                    <ul class="list-inline me-auto mb-0">
                                                        <li class="list-inline-item align-bottom">
                                                            <button
                                                                class="avtar avtar-xs btn-link-success btn-pc-default"
                                                                style="border:none" type="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false"><i
                                                                    class="ti ti-adjustments f-18"></i></button>
                                                            <div class="dropdown-menu" data-bs-auto-close="false">
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.active', ['username' => $user->username]) }}">{{__('user-table-active')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.deactive', ['username' => $user->username]) }}">{{__('user-table-deactive')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.reset', ['username' => $user->username]) }}">{{__('user-table-reset')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.delete', ['username' => $user->username]) }}">{{__('user-table-delete')}}</a>
                                                            </div>
                                                        </li>
                                                        <li class="list-inline-item align-bottom">
                                                            <button
                                                                class="avtar avtar-xs btn-link-success btn-pc-default"
                                                                style="border:none" type="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false"><i class="ti ti-share f-18"></i>
                                                            </button>

                                                            <div class="dropdown-menu" data-bs-auto-close="false">
                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="Host:{{$sshaddress}}&nbsp;
Port:{{$port_ssh}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
Port UDPGW:{{env('PORT_UDPGW')}}&nbsp;
{{$st_date}}&nbsp;{{$en_date}}">{{__('user-table-copy')}}
                                                                    (Direct)</a>
                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="Host:{{$websiteaddress}}&nbsp;
TLS Port:{{$tls_port}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
Port UDPGW:{{env('PORT_UDPGW')}}&nbsp;
{{$st_date}}&nbsp;{{$en_date}}">{{__('user-table-copy')}} (TLS)</a>
                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="Host:{{$websiteaddress}}&nbsp;
Port:{{env('PORT_DROPBEAR')}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
Port UDPGW:{{env('PORT_UDPGW')}}&nbsp;
{{$st_date}}&nbsp;{{$en_date}}">{{__('user-table-copy')}}
                                                                    (Dropbear)</a>
                                                                @php
                                                                    $at = "@";
                                                                @endphp

                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{$port_ssh}}/#{{$user->username}}">{{__('user-table-link')}}
                                                                    SSH
                                                                </a>
                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{$tls_port}}/#{{$user->username}}">{{__('user-table-link')}}
                                                                    SSH TLS
                                                                </a>
                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{env('PORT_DROPBEAR')}}/#{{$user->username}}">{{__('user-table-link')}}
                                                                    SSH Dropbear
                                                                </a>
                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="{{ sagernet_link_generator($websiteaddress,$port_ssh,$user->username,$user->password) }}">{{__('user-table-link')}}
                                                                    SSH Sagernet app
                                                                </a>

                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="{{ sagernet_link_generator($websiteaddress,$tls_port,$user->username,$user->password) }}">{{__('user-table-link')}}
                                                                    SSH TLS Sagernet app
                                                                </a>

                                                                <a href="javascript:void(0);" class="dropdown-item copy-txt"
                                                                   style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="{{ sagernet_link_generator($websiteaddress,env('PORT_DROPBEAR'),$user->username,$user->password) }}">{{__('user-table-link')}}
                                                                    SSH Dropbear Sagernet app
                                                                </a>

                                                            </div>
                                                        </li>

                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('user-table-tog-edit')}}">
                                                            <a href="{{ route('user.edit', ['username' => $user->username]) }}"
                                                               class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('user-table-tog-renewal')}}">
                                                            <a href="javascript:void(0);"
                                                               data-user="{{$user->username}}" data-bs-toggle="modal"
                                                               data-bs-target="#renewal-modal"
                                                               class="re_user avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-calendar-plus f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('detail-pop-user-togle')}}">
                                                            <a href="javascript:void(0);"
                                                               data-tls="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{$tls_port}}/#{{$user->username}}"
                                                               data-id="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{$port_ssh}}/#{{$user->username}}"
                                                               data-drop="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{env('PORT_DROPBEAR')}}/#{{$user->username}}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#{{$user->username}}-modal"
                                                               class="qrs  qr-container re_user avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-info-square f-18"></i>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </td>
                                            </tr>

                                            <!-- popup box start -->
                                            <div id="{{$user->username}}-modal" class="modal fade">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="margin-left:0;margin-right:0">
                                                                <style>
                                                                    #slider-container-outer-{{$user->username}} {
                                                                        overflow: hidden;
                                                                    }

                                                                    #slider-container-{{$user->username}} {
                                                                        display: flex;
                                                                        flex-wrap: nowrap;
                                                                        flex-direction: row;
                                                                    }

                                                                    /* CSS transition applied when translation of items happen */
                                                                    .slider-container-transition-{{$user->username}} {
                                                                        transition: transform 0.7s ease-in-out;
                                                                    }

                                                                    .slider-item-{{$user->username}} {
                                                                        width: 100%;
                                                                        flex-shrink: 0;
                                                                    }
                                                                </style>
                                                                <div class="col-sm-5 ms-auto">
                                                                    <div id="slider-container-outer-{{$user->username}}" style="direction: ltr">
                                                                        <div id="slider-container-{{$user->username}}" class="slider-container-transition-{{$user->username}} qr-container" data-drop="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteaddress}}:{{env('PORT_DROPBEAR')}}/#{{$user->username}}">
                                                                            <div class="slider-item-{{$user->username}}"  data-position="1">
                                                                                <div class="idHolderSSH"></div>
                                                                                <div class="rounded p-1 border text-center">SSH Direct</div>
                                                                            </div>
                                                                            <div class="slider-item-{{$user->username}}"  data-position="2">
                                                                                <div class="idHolderTLS"></div>
                                                                                <div class="rounded p-1 border text-center">SSH Tls</div>
                                                                            </div>
                                                                            <div class="slider-item-{{$user->username}}" data-position="3">
                                                                                <div class="idHolderDROP"></div>
                                                                                <div class="rounded p-1 border text-center">SSH Dropbear</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-center pt-2">
                                                                        <button type="button" id="move-button-{{$user->username}}" class="btn btn-secondary btn-sm">{{__('detail-pop-user-next')}}</button>
                                                                    </div>

                                                                    <script>
                                                                        const FlexSlider{{$user->username}} = {
                                                                            // total no of items
                                                                            num_items: document.querySelectorAll(".slider-item-{{$user->username}}").length,

                                                                            // position of current item in view
                                                                            current: 1,

                                                                            init: function() {
                                                                                // set CSS order of each item initially
                                                                                document.querySelectorAll(".slider-item-{{$user->username}}").forEach(function(element, index) {
                                                                                    element.style.order = index+1;
                                                                                });

                                                                                this.addEvents();
                                                                            },

                                                                            addEvents: function() {
                                                                                var that = this;

                                                                                // click on move item button
                                                                                document.querySelector("#move-button-{{$user->username}}").addEventListener('click', () => {
                                                                                    this.gotoNext();
                                                                                });

                                                                                // after each item slides in, slider container fires transitionend event
                                                                                document.querySelector("#slider-container-{{$user->username}}").addEventListener('transitionend', () => {
                                                                                    this.changeOrder();
                                                                                });
                                                                            },

                                                                            changeOrder: function() {
                                                                                // change current position
                                                                                if(this.current == this.num_items)
                                                                                    this.current = 1;
                                                                                else
                                                                                    this.current++;

                                                                                let order = 1;

                                                                                // change order from current position till last
                                                                                for(let i=this.current; i<=this.num_items; i++) {
                                                                                    document.querySelector(".slider-item-{{$user->username}}[data-position='" + i + "']").style.order = order;
                                                                                    order++;
                                                                                }

                                                                                // change order from first position till current
                                                                                for(let i=1; i<this.current; i++) {
                                                                                    document.querySelector(".slider-item-{{$user->username}}[data-position='" + i + "']").style.order = order;
                                                                                    order++;
                                                                                }

                                                                                // translate back to 0 from -100%
                                                                                // we don't need transitionend to fire for this translation, so remove transition CSS
                                                                                document.querySelector("#slider-container-{{$user->username}}").classList.remove('slider-container-transition-{{$user->username}}');
                                                                                document.querySelector("#slider-container-{{$user->username}}").style.transform = 'translateX(0)';
                                                                            },

                                                                            gotoNext: function() {
                                                                                // translate from 0 to -100%
                                                                                // we need transitionend to fire for this translation, so add transition CSS
                                                                                document.querySelector("#slider-container-{{$user->username}}").classList.add('slider-container-transition-{{$user->username}}');
                                                                                document.querySelector("#slider-container-{{$user->username}}").style.transform = 'translateX(-100%)';
                                                                            }
                                                                        };

                                                                        FlexSlider{{$user->username}}.init();
                                                                    </script>
                                                                </div>


                                                                <div class="col-sm-7 ms-auto" style="text-align: start;">
                                                                    <div class="row" style="margin-left:0;margin-right:0">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-username')}}: {{$user->username}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-password')}}: {{$user->password}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-traffic')}}: {{$total}} {{__('user-from')}} {{$traffic_user}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-limit-user')}}: {{$connection}} {{__('user-from')}} {{$user->multiuser}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('detail-pop-user-connect')}}: {{$datecon}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-day')}}: {{$daysDifference_day}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-status')}}: {!! $status !!}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-desc')}}: {{$user->desc}}
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{ $users->appends(request()->query())->links() }}

                </div>
                <!-- [ sample-page ] end -->
            </div>


            <!-- [ Main Content ] end -->
        </div>

    </div>


    <!-- renewal -->
    <div class="modal fade" id="renewalbulk-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('new.renewal.bulk')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('{{__('allert-submit')}}');">
                <div class="modal-header">
                    <h5 class="mb-0">{{__('user-pop-renewal-title')}}</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default"
                       data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        @csrf
                                        <spa  id="selectedUsersList"></spa>
                                        <input type="text" name="day_date" class="form-control" placeholder="30">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <small>{{__('user-pop-renewal-today')}}</small>
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_date" value="yes"
                                                   class="form-check-input input-primary" checked>
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-yes')}}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_date" value="no"
                                                   class="form-check-input input-primary">
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-no')}}</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <small>{{__('user-pop-renewal-reset')}}</small>
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_traffic" value="yes"
                                                   class="form-check-input input-primary" checked>
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-yes')}}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_traffic" value="no"
                                                   class="form-check-input input-primary">
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-no')}}</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default"
                                data-bs-dismiss="modal">{{__('user-pop-renewal-cancel')}}
                        </button>
                        <button type="submit" class="btn btn-primary" value="submit"
                                name="renewal_date">{{__('user-pop-renewal-submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="renewal-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('new.renewal')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('{{__('allert-submit')}}');">
                <div class="modal-header">
                    <h5 class="mb-0">{{__('user-pop-renewal-title')}} [<span id="selected_username"></span>]</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default"
                       data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        @csrf
                                        <input type="text" name="day_date" class="form-control" placeholder="30">
                                        <input type="hidden" name="username_re" id="input_user" value=""
                                               class="input_user form-control" placeholder="Username">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <small>{{__('user-pop-renewal-today')}}</small>
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_date" value="yes"
                                                   class="form-check-input input-primary" checked>
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-yes')}}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_date" value="no"
                                                   class="form-check-input input-primary">
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-no')}}</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <small>{{__('user-pop-renewal-reset')}}</small>
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_traffic" value="yes"
                                                   class="form-check-input input-primary" checked>
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-yes')}}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_traffic" value="no"
                                                   class="form-check-input input-primary">
                                            <label class="form-check-label"
                                                   for="customCheckinl311">{{__('user-pop-renewal-no')}}</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default"
                                data-bs-dismiss="modal">{{__('user-pop-renewal-cancel')}}
                        </button>
                        <button type="submit" class="btn btn-primary" value="submit"
                                name="renewal_date">{{__('user-pop-renewal-submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="customer_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('new.user')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('{{__('allert-submit')}}');">

                <div class="modal-header">
                    <h5 class="mb-0">{{__('user-pop-newuser-title')}}</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default"
                       data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @csrf
                                            <input type="text" name="username" class="form-control"
                                                   placeholder="{{__('user-pop-newuser-username')}}" autocomplete="off"
                                                   onkeyup="if (/[^|a-z0-9]+/g.test(this.value)) this.value = this.value.replace(/[^-a-z0-9_]+/g,'')"
                                                   required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-username-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                <input type="text" name="password" class="form-control"
                                                       placeholder="{{__('user-pop-newuser-password')}}"
                                                       autocomplete="off"
                                                       value="{{$password_auto}}" required>
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-password-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="email" class="form-control"
                                                   placeholder="{{__('user-pop-newuser-email')}}">
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-email-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" name="mobile" class="form-control"
                                                       placeholder="{{__('user-pop-newuser-phone')}}">
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-phone-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="multiuser" class="form-control" value="1"
                                                   placeholder="{{__('user-pop-newuser-connect')}}" required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-connect-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" name="connection_start" class="form-control"
                                                       placeholder="30">
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-connect-start-desc1')}}</small>
                                            <small
                                                style="color:red">{{__('user-pop-newuser-connect-start-desc2')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="traffic" class="form-control" value="0">
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="type_traffic" value="mb">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">MB</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="type_traffic" value="gb" checked="">
                                                <label class="form-check-label"
                                                       for="customCheckinl32">GB</label>
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-traffic-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="ti ti-calendar-time"></i></span>
                                                @if(env('APP_LOCALE', 'en')=='fa')
                                                    <input type="text" name="expdate" class="form-control example1"
                                                           autocomplete="off"/>
                                                @else
                                                    <input type="date" class="form-control" name="expdate" id="date"
                                                           data-gtm-form-interact-field-id="0">
                                                @endif
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-date-desc1')}}</small>
                                            <small style="color:red">{{__('user-pop-newuser-date-desc2')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('user-pop-newuser-desc')}}</label>
                                <textarea class="form-control" rows="3" name="desc"
                                          placeholder="{{__('user-pop-newuser-desc')}}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default"
                                data-bs-dismiss="modal">{{__('user-pop-newuser-cancel')}}
                        </button>

                        <button type="submit" class="btn btn-primary"
                                value="submit">{{__('user-pop-newuser-submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk -->
    <div class="modal fade" id="customer_bulk-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('new.bulkuser')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('{{__('allert-submit')}}');">

                <div class="modal-header">
                    <h5 class="mb-0">{{__('user-pop-bulkuser-title')}}</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default"
                       data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @csrf
                                            <input type="text" name="count_user" class="form-control" value="5"
                                                   placeholder="{{__('user-pop-bulkuser-count')}}" required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-count-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="start_user" class="form-control" value="xpuser"
                                                   placeholder="{{__('user-pop-bulkuser-name')}}" required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-name-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="start_number" class="form-control" value="100"
                                                   placeholder="{{__('user-pop-bulkuser-start')}}" required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-start-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                <input type="text" name="password" class="form-control"
                                                       placeholder="{{__('user-pop-bulkuser-password')}}">
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-password-desc')}}</small>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="pass_random" value="number" checked="">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">{{__('user-pop-bulkuser-pass-number')}}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="pass_random" value="nmuber_az">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">{{__('user-pop-bulkuser-pass-number2')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" name="char_pass" class="form-control" value="6"
                                                       placeholder="{{__('user-pop-bulkuser-chars')}}" required>
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-chars-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="multiuser" class="form-control" value="1"
                                                   placeholder="{{__('user-pop-bulkuser-connect')}}" required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-connect-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" name="connection_start" class="form-control"
                                                       value="30" placeholder="30" required>
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-date-desc1')}}</small>
                                            <small style="color:red">{{__('user-pop-bulkuser-date-desc2')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="traffic" class="form-control" value="0" required>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="type_traffic" value="mb">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">MB</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="type_traffic" value="gb" checked="">
                                                <label class="form-check-label"
                                                       for="customCheckinl32">GB</label>
                                            </div>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-bulkuser-traffic')}}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning" role="alert">
                                                {{__('user-pop-bulkuser-alert')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default"
                                data-bs-dismiss="modal">{{__('user-pop-bulkuser-cancel')}}
                        </button>
                        <button type="submit" class="btn btn-primary" value="bulk"
                                name="bulk">{{__('user-pop-newuser-submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/assets/js/jquery-2.2.4.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hireBtn = document.querySelector(".hireBtn");
            const closeBtns = document.querySelectorAll(".close, .cancel");
            const section = document.querySelector(".popup-outer");
            const textArea = document.querySelector("textarea");

            hireBtn.addEventListener("click", function() {
                section.classList.add("show");
            });

            closeBtns.forEach(function(btn) {
                btn.addEventListener("click", function() {
                    section.classList.remove("show");
                    textArea.value = "";
                });
            });
        });


    </script>
    <script>
        var colorBlock = new ClipboardJS('.copy-txt');

        colorBlock.on('success', function (e) {
            var targetElement = e.trigger;
            var popoverContent = 'Copied'; // متن پاپ‌آپ
            $(targetElement).popover({ // اعمال پاپ‌آپ با استفاده از کتابخانه Bootstrap Popover
                trigger: 'manual',
                content: popoverContent,
                placement: 'top',
            });
            $(targetElement).popover('show'); // نمایش پاپ‌آپ
            setTimeout(function () {
                $(targetElement).popover('hide'); // مخفی کردن پاپ‌آپ بعد از 3 ثانیه
            }, 3000);
        });

        colorBlock.on('error', function (e) {
            var targetElement = e.trigger;
            var popoverContent = 'Error'; // متن پاپ‌آپ
            $(targetElement).popover({ // اعمال پاپ‌آپ با استفاده از کتابخانه Bootstrap Popover
                trigger: 'manual',
                content: popoverContent,
                placement: 'top-start',
            });
            $(targetElement).popover('show'); // نمایش پاپ‌آپ
            setTimeout(function () {
                $(targetElement).popover('hide'); // مخفی کردن پاپ‌آپ بعد از 3 ثانیه
            }, 3000);
        });
    </script>
    <script>
        $(document).on("click", ".qrs", function () {
            var container = $(this).closest('.qr-container');
            var eventId = container.data('id');
            var eventIdtls = container.data('tls');
            var eventIddrop = container.data('drop');
            container.find('.loading-container').show();

            generateQRCode(eventId, '.idHolderSSH', container);
            generateQRCode(eventIdtls, '.idHolderTLS', container);
            generateQRCode(eventIddrop, '.idHolderDROP', container);
        });

        function generateQRCode(data, containerSelector, context) {
            var base64Data = btoa(data);
            $(containerSelector).html(`<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><br></div>`);
            $.ajax({
                url: '/{{env('PANEL_DIRECT')}}/users/qr/' + base64Data,
                method: 'GET',
                responseType: 'blob',
            })
                .done(function(response) {
                    context.find('.loading-container').hide();
                    $(containerSelector).html(`${response}`);
                })
                .fail(function(error) {
                    console.error(error);
                });
        }
    </script>
    <script type="text/javascript">
        $(document).on("click", ".re_user", function () {
            var username = $(this).data('user');
            $('input[name=username_re]').val(username);
            $('#selected_username').text(username);

        });
    </script>

    <script>
        $(document).ready(function(){
            $("#selectAll").change(function(){
                var isChecked = $(this).is(":checked");

                $(".checkItem").prop("checked", isChecked);
                updateButtonState();
            });

            $(".checkItem").change(function(){
                updateButtonState();
            });
            document.getElementById("btndl").disabled = true;
            document.getElementById("btnactive").disabled = true;
            document.getElementById("btndeactive").disabled = true;
            document.getElementById("retraffic").disabled = true;
            document.getElementById("renewbulk").disabled = true;
            function updateButtonState() {
                var anyChecked = $(".checkItem:checked").length > 0;

                if (anyChecked) {
                    document.getElementById("btndl").disabled = false;
                    document.getElementById("btnactive").disabled = false;
                    document.getElementById("btndeactive").disabled = false;
                    document.getElementById("retraffic").disabled = false;
                    document.getElementById("renewbulk").disabled = false;
                } else {
                    document.getElementById("btndl").disabled = true;
                    document.getElementById("btnactive").disabled = true;
                    document.getElementById("btndeactive").disabled = true;
                    document.getElementById("retraffic").disabled = true;
                    document.getElementById("renewbulk").disabled = true;
                }
            }


        });
    </script>

    <script>
        // تابع باز کردن Modal
        function openModal() {
            // جمع‌آوری اطلاعات کاربران انتخاب شده
            var selectedUsers = getSelectedUsers();

            // نمایش کاربران انتخاب شده در Modal
            showSelectedUsers(selectedUsers);

            // باز کردن Modal
            $('#renewalbulk-modal').modal('show');
        }

        // تابع جمع‌آوری اطلاعات کاربران انتخاب شده
        function getSelectedUsers() {
            var selectedUsers = [];
            $('input[name="usernamed[]"]:checked').each(function() {
                var userId = $(this).val();
                var username = $(this).closest('tr').find('td:nth-child(2)').text(); // دریافت نام کاربر از ستون دوم
                selectedUsers.push({ id: userId, name: username });
            });
            return selectedUsers;
        }

        // تابع نمایش کاربران انتخاب شده در Modal
        function showSelectedUsers(users) {
            var html = '';
            for (var i = 0; i < users.length; i++) {
                html += '<input type="hidden" name="bulkrenew[]" value="'+users[i].id+'" >';
            }
            $('#selectedUsersList').html(html);
        }
    </script>
@endsection
