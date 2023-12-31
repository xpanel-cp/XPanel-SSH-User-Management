@extends('layouts.master')
@section('title','XPanel - '.__('user-title'))
@section('content')
    <!-- [ Main Content ] start -->

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

                    <div class="card table-card">
                        <div class="card-body">
                            <form action="{{route('user.delete.bulk')}}" method="post" enctype="multipart/form-data"
                                  onkeydown="return event.key != 'Enter';">
                                @csrf

                                <div class="p-4 pb-0 d-flex flex-wrap gap-2">
                                    <a href="javascript:void(0);"
                                       class="btn btn-primary d-inline-flex align-items-center"
                                       data-bs-toggle="modal"
                                       data-bs-target="#customer_add-modal">
                                        <i class="ti ti-plus f-18"></i>{{__('user-modal-user')}}
                                    </a>

                                    <a href="javascript:void(0);"
                                       class="btn btn-primary d-inline-flex align-items-center"
                                       data-bs-toggle="modal"
                                       data-bs-target="#customer_bulk-modal">
                                        <i class="ti ti-plus f-18"></i>{{__('user-modal-bulkuser')}}</a>
                                    <button type="submit" id="btndl"
                                            class="btn btn-danger d-inline-flex align-items-center"
                                            value="delete" name="delete">{{__('user-bulk-delete')}}
                                    </button>
                                </div>


                                <div class="table-responsive">
                                    <table class="table table-hover" id="pc-dt-simple">
                                        <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>{{__('user-table-customer')}}</th>
                                            <th>{{__('user-table-username')}}/{{__('user-table-password')}}</th>
                                            <th>{{__('user-table-traffic')}}</th>
                                            <th>{{__('user-table-limit-user')}}</th>
                                            <th>{{__('user-table-contact')}}</th>
                                            @if(env('DAY', 'deactive')=='active')
                                                <th>{{__('user-table-day')}}</th>
                                            @else
                                                <th>{{__('user-table-date')}}</th>
                                            @endif
                                            <th>{{__('user-table-status')}}</th>
                                            <th>{{__('user-table-desc')}}</th>
                                            <th class="text-center">{{__('user-table-action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody id="online-table">
                                        @php
                                            $uid = 0;
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
                                            <tr>
                                                <td><input name="usernamed[]" id="checkItem" type="checkbox"
                                                           class="checkItem form-check-input"
                                                           value="{{$user->username}}"/> {{$uid}}
                                                </td>
                                                <td>{{$customer_user}}</td>
                                                <td>{{$user->username}}<br><small>{{$user->password}}</small></td>
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
                                                <td>{!! $status !!}</td>
                                                <td style="width: 50px"><small>
                                                        <div style="text-wrap: pretty;">{{$user->desc}}</div>
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
                                                                   href="{{ route('user.active', ['username' => $user->username]) }}">{{__('user-table-active')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.deactive', ['username' => $user->username]) }}">{{__('user-table-deactive')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.reset', ['username' => $user->username]) }}">{{__('user-table-reset')}}</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.delete', ['username' => $user->username]) }}">{{__('user-table-delete')}}</a>
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
                                                            <section>
                                                                <div class="button hire{{$user->username}}">
                                                                    <i class="bx bxs-envelope"></i>
                                                                    <a class="avtar avtar-xs btn-link-success btn-pc-default" href="javascript:void(0);"><i class="ti ti-info-square f-18"></i></a>
                                                                </div>

                                                                <!-- popup box start -->
                                                                <div class="modal fade bd-example-modal-lg popup-outer popup-outer-{{$user->username}}">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row" style="margin-left:0;margin-right:0">

                                                                                    <div class="col-sm-5 ms-auto">
                                                                                        <i class="ti ti-copy"></i>{{__('detail-pop-user-config')}}
                                                                                        <br><div class="btn-group" role="group" aria-label="button groups">
                                                                                            <button type="button" class="btn copy-txt btn-light-secondary"
                                                                                                    data-clipboard-text="Host:{{$sshAddress}}&nbsp;
Port:{{$port_ssh}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
@if (!empty($startdate))
                                                                                                    @if(env('APP_LOCALE', 'en')=='fa')
                                                                                                        StartTime:{{Verta::instance($startdate)->format('Y/m/d')}}
                                                                                                    @else
                                                                                                        StartTime:{{$startdate}}&nbsp;
@endif
                                                                                                    @endif
                                                                                                    @if (!empty($finishdate))
                                                                                                    @if(env('APP_LOCALE', 'en')=='fa')
                                                                                                        EndTime:{{Verta::instance($finishdate)->format('Y/m/d')}}
                                                                                                    @else
                                                                                                        EndTime:{{$finishdate}}
                                                                                                    @endif  @endif">@if($xguard_status=='active')<i class="ti ti-shield-check"></i> @endif Direct</button>
                                                                                            <button type="button" class="btn copy-txt btn-light-secondary"
                                                                                                    data-clipboard-text="Host:{{$websiteAddress}}&nbsp;
TLS Port:{{$tls_port}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
@if (!empty($startdate))
                                                                                                    @if(env('APP_LOCALE', 'en')=='fa')
                                                                                                        StartTime:{{Verta::instance($startdate)->format('Y/m/d')}}
                                                                                                    @else
                                                                                                        StartTime:{{$startdate}}&nbsp;
@endif
                                                                                                    @endif
                                                                                                    @if (!empty($finishdate))
                                                                                                    @if(env('APP_LOCALE', 'en')=='fa')
                                                                                                        EndTime:{{Verta::instance($finishdate)->format('Y/m/d')}}
                                                                                                    @else
                                                                                                        EndTime:{{$finishdate}}
                                                                                                    @endif  @endif">TLS</button>
                                                                                            <button type="button" class="btn copy-txt btn-light-secondary"
                                                                                                    data-clipboard-text="Host:{{$websiteAddress}}&nbsp;
Port:{{env('PORT_DROPBEAR')}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
@if (!empty($startdate))
                                                                                                    @if(env('APP_LOCALE', 'en')=='fa')
                                                                                                        StartTime:{{Verta::instance($startdate)->format('Y/m/d')}}
                                                                                                    @else
                                                                                                        StartTime:{{$startdate}}&nbsp;
@endif
                                                                                                    @endif
                                                                                                    @if (!empty($finishdate))
                                                                                                    @if(env('APP_LOCALE', 'en')=='fa')
                                                                                                        EndTime:{{Verta::instance($finishdate)->format('Y/m/d')}}
                                                                                                    @else
                                                                                                        EndTime:{{$finishdate}}
                                                                                                    @endif  @endif">Dropbear</button>
                                                                                        </div><br><br>
                                                                                        <i class="ti ti-copy"></i>{{__('detail-pop-user-link')}}
                                                                                        @php
                                                                                            $at="@";
                                                                                        @endphp
                                                                                        <br><div class="btn-group" role="group" aria-label="button groups">
                                                                                            <button type="button" class="btn btn-light-primary copy-txt"
                                                                                                    data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$sshAddress}}:{{$port_ssh}}/#{{$user->username}}">
                                                                                                @if($xguard_status=='active')<i class="ti ti-shield-check"></i> @endif Direct
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-light-primary copy-txt"
                                                                                                    data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteAddress}}:{{$tls_port}}/#{{$user->username}}">
                                                                                                TLS
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-light-primary copy-txt"
                                                                                                    data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteAddress}}:{{env('PORT_DROPBEAR')}}/#{{$user->username}}">
                                                                                                Dropbear
                                                                                            </button></div>
                                                                                        <!-- Slideshow container -->
                                                                                        <div class="slideshow-container">

                                                                                            <!-- Full-width images with number and caption text -->
                                                                                            <div class="mySlides">
                                                                                                <img style="width:100%" src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$sshAddress}}:{{$port_ssh}}/#{{$user->username}}&choe=UTF-8" title="{{$user->username}}" />
                                                                                                <div class="text" style="text-align: center;">QR Scan ssh Direct</div>
                                                                                            </div>

                                                                                            <div class="mySlides">
                                                                                                <img style="width:100%" src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteAddress}}:{{$tls_port}}/#{{$user->username}}&choe=UTF-8" title="{{$user->username}}" />
                                                                                                <div class="text" style="text-align: center;">QR Scan ssh Tls</div>
                                                                                            </div>

                                                                                            <div class="mySlides">
                                                                                                <img style="width:100%" src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$websiteAddress}}:{{env('PORT_DROPBEAR')}}/#{{$user->username}}&choe=UTF-8" title="{{$user->username}}" />
                                                                                                <div class="text" style="text-align: center;">QR Scan ssh Dropbear</div>
                                                                                            </div>

                                                                                            <!-- Next and previous buttons -->
                                                                                            <a class="next" onclick="plusSlides(-1)">&#10094;</a>
                                                                                            <a class="prev" onclick="plusSlides(1)">&#10095;</a>
                                                                                        </div>
                                                                                        <br>

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
                                                            </section>
                                                            <script>
                                                                document.addEventListener("DOMContentLoaded", function() {
                                                                    const hireBtn = document.querySelector(".hire{{$user->username}}");
                                                                    const closeBtns = document.querySelectorAll(".close, .cancel");
                                                                    const section = document.querySelector(".popup-outer-{{$user->username}}");
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
                                                        </li>

                                                    </ul>
                                                </td>
                                            </tr>


                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>


            <!-- [ Main Content ] end -->
        </div>

    </div>

    <!-- qr -->
    <div class="modal fade" id="qr-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="display: -webkit-inline-box; text-align: center;">
                <div><br>
                    SSH DIRECT<br><span id="idHolderSSH"></span>
                </div>
                <div><br>
                    SSH TLS<br><span id="idHolderTLS"></span>
                </div>
                <div><br>
                    SSH Dropbear<br><span id="idHolderDROP"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- renewal -->
    <div class="modal fade" id="renewal-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('new.renewal')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('Are you sure you want to perform this operation?');">
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
            <form class="modal-content" action="{{route('new.user')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('Are you sure you want to perform this operation?');">

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
                                                       name="type_traffic" value="mb" checked="">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">MB</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="type_traffic" value="gb">
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
                  onsubmit="return confirm('Are you sure you want to perform this operation?');">

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
                                                       name="type_traffic" value="mb" checked="">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">MB</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="type_traffic" value="gb">
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
                                name="bulk">{{__('user-pop-bulkuser-submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex + n);
        }

        function currentSlide(n) {
            showSlides(n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");

            if (n > slides.length) {
                slideIndex = 1;
            } else if (n < 1) {
                slideIndex = slides.length;
            } else {
                slideIndex = n;
            }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }
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
    <script src="https://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).on("click", ".re_user", function () {
            var username = $(this).data('user');
            $('input[name=username_re]').val(username);

        });
    </script>
    <script>
        const section = document.querySelector("div"),
            hireBtn = section.querySelector("#hireBtn"),
            closeBtn = section.querySelectorAll("#close"),
            textArea = section.querySelector("textarea");

        hireBtn.addEventListener("click" , () =>{
            section.classList.add("show");
        });

        closeBtn.forEach(cBtn => {
            cBtn.addEventListener("click" , ()=>{
                section.classList.remove("show");
                textArea.value = "";
            });
        });
    </script>

@endsection
