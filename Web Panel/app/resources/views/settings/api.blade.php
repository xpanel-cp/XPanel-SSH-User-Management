@extends('layouts.master')
@section('title','XPanel - Settings')
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
                                <h2 class="mb-0">Settings - Api</h2>
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
                        @include('layouts.setting_menu')
                        <div class="tab-content" id="myTabContent">
                            <div class="card-body">
                                <form class="validate-me" action="{{route('settings.api')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <input type="text" name="desc" class="form-control" required="">
                                            <small class="form-text text-muted">Description</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" name="allowip" required="" value="0.0.0.0/0">
                                            <small class="form-text text-muted">Allowed IPs</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-4 col-form-label"></div>
                                        <div class="col-lg-6">
                                            <input type="submit" class="btn btn-primary" value="Add">
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="col-sm-12">
                                    <div class="card table-card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="pc-dt-simple">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Token</th>
                                                        <th>IP</th>
                                                        <th class="text-center">Renew</th>
                                                        <th class="text-center">Delete</th>
                                                    </tr>

                                                    @foreach($apis as $api)
                                                        <td>#</td>
                                                        <td>{{$api->token}}</td>
                                                        <td>{{$api->allow_ip}}<br><small>{{$api->description}}</small></td>
                                                        <td class="text-center">
                                                            <a href="{{ route('settings.token.renew', ['id' => $api->id]) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-refresh f-18"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('settings.token.delete', ['id' => $api->id]) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                <i class="ti ti-trash f-18"></i>
                                                            </a></td>
                                                    </tr>
                                                    @endforeach
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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