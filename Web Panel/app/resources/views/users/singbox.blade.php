@extends('layouts.master')
@section('title','XPanel - '.__('user-title').'SingBox')
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
                                <h2 class="mb-0">{{__('user-title')}} SingBox</h2>
                                <small>{{__('manager-count-account')}}: <b style="font-size: medium;">@if(!empty($detail_admin->count_account)){{$detail_admin->count_account}} @else ♾️@endif </b> &nbsp;&nbsp; {{__('user-pop-newuser-date-desc1')}}: <b style="font-size: medium;">@if(!empty($end_date)){{$end_date}} @else ♾️@endif </b></small>

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
                            <form action="{{route('singbox.users.search')}}" method="get" enctype="multipart/form-data" class="row row-cols-md-auto g-3 align-items-center">
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
                                    <select name="protocol" class="form-select" id="inlineFormSelectPref">
                                        <option value="all"{{ request('protocol') == 'all' ? ' selected' : '' }}>{{__('search-user-all')}}</option>
                                        <option value="vmess-ws"{{ request('protocol') == 'vmess-ws' ? ' selected' : '' }}>VMess ws</option>
                                        <option value="vless-reality"{{ request('protocol') == 'vless-reality' ? ' selected' : '' }}>VLess Reality</option>
                                        <option value="hysteria2"{{ request('protocol') == 'hysteria2' ? ' selected' : '' }}>Hysteria2</option>
                                        <option value="tuic"{{ request('protocol') == 'tuic' ? ' selected' : '' }}>Tuic</option>
                                        <option value="shadowsocks"{{ request('protocol') == 'shadowsocks' ? ' selected' : '' }}>Shadowsocks</option>

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

                                </div>
                                <br>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="example" >
                                        <thead>
                                        <tr>
                                            <th><input class="form-check-input" type="checkbox" id="selectAll"> #</th>
                                            <th>{{__('singbox-name')}}</th>
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
                                        @endphp
                                        @foreach ($users as $user)
                                            @php
                                                $uid++;
                                            @endphp

                                            @if ($user->xtraffic)
                                                @if (1024 <= round($user->xtraffic->sent_sb))
                                                    @php $sent = round($user->xtraffic->sent_sb / 1024,3).' GB'; @endphp
                                                @else
                                                    @php $sent = round($user->xtraffic->sent_sb).' MB'; @endphp
                                                @endif

                                                @if (1024 <= round($user->xtraffic->received_sb))
                                                    @php $received = round($user->xtraffic->received_sb / 1024,3).' GB'; @endphp
                                                @else
                                                    @php $received = round($user->xtraffic->received_sb).' MB'; @endphp
                                                @endif

                                                @if (1024 <= round($user->xtraffic->total_sb))
                                                    @php $total = round($user->xtraffic->total_sb / 1024,3).' GB'; @endphp
                                                @else
                                                    @php $total = round($user->xtraffic->total_sb).' MB'; @endphp
                                                @endif
                                            @else
                                                @php $sent = '0'; @endphp
                                                @php $received = '0'; @endphp
                                                @php $total = '0'; @endphp

                                            @endif
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
                                            @else
                                                @php
                                                    $traffic_user = 'Unlimited';

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
                                            @php $jsonData = json_decode($user->detail_sb, true);@endphp
                                            @if(!empty($jsonData['sid']))
                                                @php $sid=$jsonData['sid']; @endphp
                                            @else
                                                @php $sid=time(); @endphp
                                            @endif

                                            @if(!empty($jsonData['pbk']))
                                                @php $pbk=$jsonData['pbk']; @endphp
                                            @else
                                                @php $pbk='none'; @endphp
                                            @endif
                                            @if(!empty($user->sni))
                                                @php
                                                    $sni=$user->sni;
                                                @endphp
                                            @else
                                                @php
                                                    $sni='www.bing.com';
                                                @endphp
                                            @endif
                                            @if($user->protocol_sb=='vmess-ws')
                                                @php
                                                    $val_vm='{"add":"'.$address.'","aid":"0","host":"'.$sni.'","id":"'.$jsonData['uuid'].'","net":"ws","path":"'.$jsonData['uuid'].'-vm","port":"'.$jsonData['port'].'","ps":"'.$user->name.'","tls":"","type":"none","v":"2"}';
                                                    $prt='vmess://'.base64_encode($val_vm);
                                                @endphp
                                            @endif
                                            @if($user->protocol_sb=='vless-reality')
                                                @php
                                                    $prt="vless://".$jsonData['uuid']."@$address:".$jsonData['port']."?encryption=none&flow=xtls-rprx-vision&security=reality&sni=$sni&fp=chrome&pbk=$pbk&sid=$sid&type=tcp&headerType=none#$user->name"
                                                @endphp
                                            @endif
                                            @if($user->protocol_sb=='hysteria2')
                                                @php
                                                    $prt="hysteria2://".$jsonData['uuid']."@$address:".$jsonData['port']."?insecure=1&mport=".$jsonData['port']."&sni=$sni#$user->name";
                                                @endphp
                                            @endif
                                            @if($user->protocol_sb=='tuic')
                                                @php
                                                    $prt="tuic://".$jsonData['uuid'].":".$jsonData['uuid']."@$address:".$jsonData['port']."?congestion_control=bbr&udp_relay_mode=native&alpn=h3&sni=$sni&allow_insecure=1#$user->name";
                                                @endphp
                                            @endif
                                            @if($user->protocol_sb=='shadowsocks')
                                                @php
                                                    $encode=base64_encode("2022-blake3-aes-256-gcm:".$jsonData['uuid']);
                                                        $prt="ss://".$encode."@$address:".$jsonData['port']."?type=tcp#$user->name";
                                                @endphp
                                            @endif
                                            <tr>
                                                <td><input name="usernamed[]" id="checkItem" type="checkbox"
                                                           class="checkItem form-check-input"
                                                           value="{{$user->id}}"/> {{$uid}}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="row g-1">
                                                                <div class="col-12">
                                                                    <h6 class="mb-0">{{$user->name}}</h6>
                                                                    <p class="text-muted mb-0"><small>
                                                                            @if($user->protocol_sb=='vmess-ws')VMess ws @endif
                                                                            @if($user->protocol_sb=='vless-reality')VLess Reality @endif
                                                                            @if($user->protocol_sb=='hysteria2')Hysteria2 @endif
                                                                            @if($user->protocol_sb=='tuic')Tuic @endif
                                                                            @if($user->protocol_sb=='shadowsocks')Shadowsocks @endif
                                                                        </small></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            {!! $status !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><small>{{$total}} {{__('user-from')}} {{$traffic_user}}</small><br>
                                                    <span class="badge bg-light-primary rounded-pill f-12"><i class="ti ti-cloud-upload"></i> {{$received}}</span>
                                                    <span class="badge bg-light-success rounded-pill f-12"><i class="ti ti-cloud-download"></i> {{$sent}}</span>
                                                </td>

                                                <td><span class="badge bg-light-secondary f-12" style="width: -webkit-fill-available;">{{$user->multiuser}}</span></td>
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
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                   href="{{ route('singbox.user.active', ['port' => $user->port_sb]) }}">{{__('user-table-active')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('singbox.user.deactive', ['port' => $user->port_sb]) }}">{{__('user-table-deactive')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('singbox.user.reset', ['port' => $user->port_sb]) }}">{{__('user-table-reset')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('singbox.user.delete', ['port' => $user->port_sb]) }}">{{__('user-table-delete')}}</a>
                                                            </div>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('user-table-tog-edit')}}">
                                                            <a href="{{ route('singbox.user.edit', ['port' => $user->port_sb]) }}"
                                                               class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('user-table-tog-renewal')}}">
                                                            <a href="javascript:void(0);"
                                                               data-user="{{$user->port_sb}}" data-name="{{$user->name}}" data-bs-toggle="modal"
                                                               data-bs-target="#renewal-modal"
                                                               class="re_user avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-calendar-plus f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('singbox-protocol-copy')}}">
                                                            <a href="javascript:void(0);" class="re_user avtar avtar-xs btn-link-success btn-pc-default"
                                                               style="border:none"
                                                               data-clipboard="true"
                                                               data-clipboard-text="{{$prt}}">
                                                                <i class="ti ti-copy f-18"></i>
                                                            </a>
                                                        </li>

                                                        <li class="list-inline-item align-bottom"
                                                            data-bs-toggle="tooltip"
                                                            title="{{__('detail-pop-user-togle')}}">
                                                            <a href="javascript:void(0);"
                                                               data-qr="{{$prt}}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#sb{{$user->id}}-modal"
                                                               class="qrs2  qr-container re_user avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-info-square f-18"></i>
                                                            </a>
                                                        </li>


                                                    </ul>
                                                </td>
                                            </tr>

                                            <!-- popup box start -->
                                            <div id="sb{{$user->id}}-modal" class="modal fade">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row" style="margin-left:0;margin-right:0">
                                                                <style>
                                                                    #slider-container-outer-{{$user->id}} {
                                                                        overflow: hidden;
                                                                    }

                                                                    #slider-container-{{$user->id}} {
                                                                        display: flex;
                                                                        flex-wrap: nowrap;
                                                                        flex-direction: row;
                                                                    }

                                                                    /* CSS transition applied when translation of items happen */
                                                                    .slider-container-transition-{{$user->id}} {
                                                                        transition: transform 0.7s ease-in-out;
                                                                    }

                                                                    .slider-item-{{$user->id}} {
                                                                        width: 100%;
                                                                        flex-shrink: 0;
                                                                    }
                                                                </style>
                                                                <div class="col-sm-5 ms-auto">
                                                                    <div class="idqr"></div>
                                                                </div>


                                                                <div class="col-sm-7 ms-auto" style="text-align: start;">
                                                                    <div class="row" style="margin-left:0;margin-right:0">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('user-table-username')}}: {{$user->name}}
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
                                                                                    {{__('user-table-limit-user')}}: {{$user->multiuser}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="bg-body rounded fs-6 p-2 border text-body">
                                                                                    {{__('detail-pop-user-connect')}}: null
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
    <div class="modal fade" id="renewal-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('singbox.new.renewal')}}" method="POST" enctype="multipart/form-data"
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
                                               class="input_user form-control" placeholder="30">
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
            <form class="modal-content" action="{{route('sb.new.user')}}" method="POST" enctype="multipart/form-data"
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
                                            <input type="text" name="name" class="form-control"
                                                   placeholder="{{__('singbox-name')}}" autocomplete="off"
                                                   onkeyup="if (/[^|a-z0-9]+/g.test(this.value)) this.value = this.value.replace(/[^-a-z0-9_]+/g,'')"
                                                   required>
                                            <small
                                                class="form-text text-muted">{{__('singbox-name-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <select name="protocol" class="form-select" data-gtm-form-interact-field-id="0">
                                                <option value="vmess-ws">VMess ws</option>
                                                <option value="vless-reality">VLess Reality</option>
                                                <option value="hysteria2" selected>Hysteria2</option>
                                                <option value="tuic">Tuic</option>
                                                <option value="shadowsocks">Shadowsocks</option>
                                            </select>
                                            <small class="form-text text-muted">{{__('singbox-protocol-desc')}}</small>
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
                                        <div class="col-lg-6">
                                            <input type="text" name="multiuser" class="form-control" value="1"
                                                   placeholder="{{__('user-pop-newuser-connect')}}" required>
                                            <small
                                                class="form-text text-muted">{{__('user-pop-newuser-connect-desc')}}</small>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" name="sni" class="form-control" value="www.bing.com"
                                                   placeholder="SNI" required>
                                            <small
                                                class="form-text text-muted">SNI</small>
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
            var iconBadge = document.createElement('span');
            iconBadge.setAttribute('class', 'ic-badge badge bg-success float-end');
            iconBadge.innerHTML = 'Copied';
            targetElement.append(iconBadge);
            setTimeout(function () {
                targetElement.removeChild(iconBadge);
            }, 3000);
        });

        colorBlock.on('error', function (e) {
            var targetElement = e.trigger;
            var iconBadge = document.createElement('span');
            iconBadge.setAttribute('class', 'ic-badge badge bg-danger float-end');
            iconBadge.innerHTML = 'Error';
            targetElement.append(iconBadge);
            setTimeout(function () {
                targetElement.removeChild(iconBadge);
            }, 3000);
        });

    </script>

    <script>
        var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        $(document).on("click", ".qrs2", function () {
            var container = $(this).closest('.qr-container');
            var eventId = container.data('qr');
            container.find('.loading-container').show();

            generateQRCode(eventId, '.idqr', container, csrfToken);
        });

        function generateQRCode(data, containerSelector, context, csrfToken) {
            var base64Data = btoa(data);

            $(containerSelector).html(`<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><br></div>`);

            // Create a FormData object
            var formData = new FormData();
            formData.append('base64Data', base64Data);

            // Sending data as POST request
            $.ajax({
                url: '/{{env('PANEL_DIRECT')}}/singbox/qr',
                method: 'POST',
                data: formData,
                processData: false,  // Don't process the data (already in FormData)
                contentType: false,  // Don't set contentType (it will be set automatically)
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
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
            var name = $(this).data('name');
            $('input[name=username_re]').val(username);
            $('#selected_username').text(name);
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
