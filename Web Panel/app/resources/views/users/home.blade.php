@extends('layouts.master')
@section('title','XPanel - Users')
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
                                <h2 class="mb-0">Users</h2>
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
                            <form action="{{route('user.delete.bulk')}}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="text-end p-4 pb-0">
                                    <a href="javascript:void(0);" class="btn btn-primary d-inline-flex align-items-center"
                                       style="margin-bottom: 5px;" data-bs-toggle="modal" data-bs-target="#customer_add-modal">
                                        <i class="ti ti-plus f-18"></i>New User
                                    </a>

                                    <a href="javascript:void(0);" class="btn btn-primary d-inline-flex align-items-center"
                                       data-bs-toggle="modal"
                                       data-bs-target="#customer_bulk-modal">
                                        <i class="ti ti-plus f-18"></i> Bulk User</a>
                                    <button type="submit" class="btn btn-danger d-inline-flex align-items-center"
                                            value="delete" name="delete">Delete
                                    </button>
                                </div>


                                <div class="table-responsive">
                                    <table class="table table-hover" id="pc-dt-simple">
                                        <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Customer</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Traffic</th>
                                            <th>Limit User</th>
                                            <th>Contacts</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $uid = 0;
                                        @endphp
                                        @foreach ($users as $user)
                                            @php
                                                $uid++
                                            @endphp
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

                                            @foreach($user->traffics as $traffic)
                                                @if (1024 <= $traffic->total)

                                                    @php
                                                        $trafficValue = floatval($traffic->total);
                                                        $total = round($trafficValue / 1024, 3) . ' GB';  @endphp
                                                @else
                                                    @php $total = $traffic->total . ' MB'; @endphp
                                                @endif
                                            @endforeach

                                            @if ($user->status == "active" or $user->status == "true")
                                                @php $status = "<span class='badge bg-light-success rounded-pill f-12'>Active</span>"; @endphp
                                            @endif
                                            @if ($user->status == "deactive" or $user->status == "false")
                                                @php $status = "<span class='badge bg-light-danger rounded-pill f-12'>Deactive</span>"; @endphp
                                            @endif
                                            @if ($user->status == "expired")
                                                @php $status = "<span class='badge bg-light-warning rounded-pill f-12'>Expired</span>"; @endphp
                                            @endif
                                            @if ($user->status == "traffic")
                                                @php $status = "<span class='badge bg-light-primary rounded-pill f-12'>Traffic</span>"; @endphp
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


                                            <tr>
                                                <td><input name="usernamed[]" id="checkItem" type="checkbox"
                                                           class="checkItem form-check-input"
                                                           value="{{$user->username}}"/> {{$uid}}
                                                </td>
                                                <td>{{$customer_user}}</td>
                                                <td>{{$user->username}}</td>
                                                <td>{{$user->password}}</td>
                                                <td>{{$traffic_user}}
                                                    <br>
                                                    <small>

                                                <span
                                                    style="background: #4a9afe; padding: 2px; color: #fff; border-radius: 5px;"><i
                                                        class="ti ti-cloud-download"></i> {{$total}}</span>
                                                    </small></td>
                                                <td>{{$user->multiuser}}</td>
                                                <td>{{$user->mobile}}<br>
                                                    <small>{{$user->email}}</small></td>
                                                <td><small>
                                                        Register Date: {{$startdate}}
                                                        <br>
                                                        Expired Date: {{$finishdate}}
                                                    </small></td>
                                                <td>{!! $status !!}</td>
                                                <td class="text-center">
                                                    <ul class="list-inline me-auto mb-0">
                                                        <li class="list-inline-item align-bottom">
                                                            <button class="avtar avtar-xs btn-link-success btn-pc-default"
                                                                    style="border:none" type="button"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"><i
                                                                    class="ti ti-adjustments f-18"></i></button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.active', ['username' => $user->username]) }}">Active</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.deactive', ['username' => $user->username]) }}">Deactive</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.reset', ['username' => $user->username]) }}">Reset Traffic</a>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('user.delete', ['username' => $user->username]) }}">Delete</a>
                                                            </div>
                                                        </li>
                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <a href="{{ route('user.edit', ['username' => $user->username]) }}"
                                                               class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                            title="Renewal">
                                                            <a href="javascript:void(0);" data-user="{{$user->username}}" data-bs-toggle="modal"
                                                               data-bs-target="#renewal-modal"
                                                               class="re_user avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-calendar-plus f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom">
                                                            <button class="avtar avtar-xs btn-link-success btn-pc-default"
                                                                    style="border:none" type="button"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"><i class="ti ti-share f-18"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a href="javascript:void(0);" class="dropdown-item" style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="Host:{{$_SERVER["SERVER_NAME"]}}&nbsp;
Port:{{env('PORT_SSH')}}&nbsp;
TLS Port:{{$tls_port}}&nbsp;
Username:{{$user->username}}&nbsp;
Password:{{$user->password}}&nbsp;
@if (!empty($startdate))
                                                                       StartTime:{{$startdate}}&nbsp;
@endif
                                                                   @if (!empty($finishdate))
                                                                       EndTime:{{$finishdate}}
                                                                   @endif">Copy Config</a>
                                                                @php
                                                                    $at="@";
                                                                @endphp

                                                                <a href="javascript:void(0);" class="dropdown-item" style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$_SERVER["SERVER_NAME"]}}:{{env('PORT_SSH')}}/#{{$user->username}}">Link SSH
                                                                </a>
                                                                <a href="javascript:void(0);" class="dropdown-item" style="border:none"
                                                                   data-clipboard="true"
                                                                   data-clipboard-text="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$_SERVER["SERVER_NAME"]}}:{{$tls_port}}/#{{$user->username}}">Link SSH TLS
                                                                </a>
                                                                <a href="javascript:void(0);" class="qrs dropdown-item"
                                                                   data-tls="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$_SERVER["SERVER_NAME"]}}:{{$tls_port}}/#{{$user->username}}"
                                                                   data-id="ssh://{{$user->username}}:{{$user->password}}{{$at}}{{$_SERVER["SERVER_NAME"]}}:{{env('PORT_SSH')}}/#{{$user->username}}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#qr-modal">
                                                                    QR
                                                                </a>

                                                            </div>
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
            <div class="modal-content" style="display: -webkit-inline-box; text-align: center;width: 600px;">
                <div><br>
                    SSH DIRECT<br><span id="idHolderSSH"></span>
                </div>
                <div><br>
                    SSH TLS<br><span id="idHolderTLS"></span>
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
                    <h5 class="mb-0">Renewal</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body" >
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        @csrf
                                        <input type="text" name="day_date" class="form-control" placeholder="30">
                                        <input type="hidden" name="username_re" id="input_user" value="" class="input_user form-control" placeholder="30">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <small>Registered from today</small>
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_date" value="yes" class="form-check-input input-primary" checked>
                                            <label class="form-check-label" for="customCheckinl311">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_date" value="no" class="form-check-input input-primary" >
                                            <label class="form-check-label" for="customCheckinl311">No</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <small>Reset the traffic</small>
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_traffic" value="yes" class="form-check-input input-primary" checked>
                                            <label class="form-check-label" for="customCheckinl311">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="re_traffic" value="no" class="form-check-input input-primary" >
                                            <label class="form-check-label" for="customCheckinl311">No</label>
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
                                data-bs-dismiss="modal">Ccancell
                        </button>
                        <button type="submit" class="btn btn-primary" value="submit"
                                name="renewal_date">Registration</button>
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
                    <h5 class="mb-0">New User</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
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
                                                   placeholder="Username" autocomplete="off"
                                                   onkeyup="if (/[^|a-z0-9]+/g.test(this.value)) this.value = this.value.replace(/[^-a-z0-9]+/g,'')"
                                                   required>
                                            <small class="form-text text-muted">Enter Username</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                <input type="text" name="password" class="form-control"
                                                       placeholder="Password" autocomplete="off"
                                                       value="{{$password_auto}}" required>
                                            </div>
                                            <small class="form-text text-muted">Enter Password</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="email" class="form-control"
                                                   placeholder="Email">
                                            <small class="form-text text-muted">Enter Email</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" name="mobile" class="form-control"
                                                       placeholder="Phone">
                                            </div>
                                            <small class="form-text text-muted">Enter Phone</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="multiuser" class="form-control" value="1"
                                                   placeholder="Concurrent Users" required>
                                            <small class="form-text text-muted">Enter number of concurrent users</small>
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
                                            <small class="form-text text-muted">Expiration Date (on first connection)</small>
                                            <small style="color:red">If you want to set the expiration date on the first connection, enter the number of validity days in the field above</small>
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
                                            <small class="form-text text-muted">Enter traffic</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti ti-calendar-time"></i></span>
                                                <input type="date" class="form-control" name="expdate" id="date"
                                                       data-gtm-form-interact-field-id="0">
                                            </div>
                                            <small class="form-text text-muted">Expiration Date</small>
                                            <small style="color:red">Leave this field empty if you set the expiration date automatically</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" name="desc"
                                          placeholder="Description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default"
                                data-bs-dismiss="modal">Cancell
                        </button>

                        <button type="submit" class="btn btn-primary" value="submit" >Add</button>
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
                    <h5 class="mb-0">Bulk User</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
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
                                                   placeholder="Number of user builds" required>
                                            <small class="form-text text-muted">Enter the number of user builds</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="start_user" class="form-control" value="xpuser"
                                                   placeholder="Initial phrase of username" required>
                                            <small class="form-text text-muted">Enter the initial phrase of the username</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="start_number" class="form-control" value="100"
                                                   placeholder="Starting number" required>
                                            <small class="form-text text-muted">This number is placed after the beginning of the username</small>
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
                                                       placeholder="Password">
                                            </div>
                                            <small class="form-text text-muted">If you want the password to be lang, leave the above field blank</small>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="pass_random" value="number" checked="">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">Combination of numbers</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="pass_random" value="nmuber_az">
                                                <label class="form-check-label"
                                                       for="customCheckinl311">Combination of letters and numbers</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <input type="text" name="char_pass" class="form-control" value="6"
                                                       placeholder="Number of password characters" required>
                                            </div>
                                            <small class="form-text text-muted">Enter the number of characters of the password</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="multiuser" class="form-control" value="1"
                                                   placeholder="Simultaneous user" required>
                                            <small class="form-text text-muted">Enter the number of simultaneous users</small>
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
                                            <small class="form-text text-muted">Expiration Date (on first connection)</small>
                                            <small style="color:red">If you want to set the expiration date on the first connection, enter the number of validity days in the field above</small>
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
                                            <small class="form-text text-muted">Enter traffic</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning" role="alert">
                                                Note that if the user is already registered, the system will not allow the registration
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
                                data-bs-dismiss="modal">Cancell
                        </button>
                        <button type="submit" class="btn btn-primary" value="bulk"
                                name="bulk">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery.min.js"></script>

    <!-- [ Main Content ] end -->
    <script type="text/javascript">
        $(document).on("click", ".qrs", function () {
            var eventId = $(this).data('id');
            var eventIdtls = $(this).data('tls');
            var qr = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" + eventId + "&choe=UTF-8\" title=" + eventId + " />";
            var qrtls = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" + eventIdtls + "&choe=UTF-8\" title=" + eventIdtls + " />";
            $('#idHolderSSH').html(qr);
            $('#idHolderTLS').html(qrtls);
        });
    </script>
    <script type="text/javascript">
        $(document).on("click", ".re_user", function () {
            var username = $(this).data('user');
            $('input[name=username_re]').val(username);

        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("btndl").disabled = true;
        });
        $(document).on("click", ".checkItem", function () {

            document.getElementById("btndl").disabled = false;

        });
    </script>

@endsection
