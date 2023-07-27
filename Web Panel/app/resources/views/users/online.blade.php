@extends('layouts.master')
@section('title','XPanel - Online User')
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
                                <h2 class="mb-0">Online User</h2>
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>IP</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $val)
                                        <tr>
                                            <td>{{$val->username}}<i
                                                    style="color:{{$val->color}}!important;"
                                                    class="ti ti-live-photo"></i></td>
                                            <td>{{$val->ip}}<br><small>Protocol:SSH</small></td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom">
                                                        <a href="{{ route('online.kill.pid', ['pid' => $val->pid]) }}"
                                                           class="btn btn-light-primary">
                                                            Kill ID
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom">
                                                        <a href="{{ route('online.kill.username', ['username' => $val->username]) }}"
                                                           class="btn btn-light-danger">
                                                            Kill USER
                                                        </a>
                                                    </li>

                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

@endsection
