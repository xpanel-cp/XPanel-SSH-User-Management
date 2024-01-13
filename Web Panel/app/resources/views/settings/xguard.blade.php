@extends('layouts.master')
@section('title','XPanel - '.__('settings-xguard-title'))
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
                <div class="col-sm-12">
                    <div class="card">
                        @include('layouts.setting_menu')
                        <div class="tab-content" id="myTabContent">
                            <div class="card-body">
                                <div class="alert alert-warning" role="alert">{{__('settings-xguard-desc')}}</div>
                                <div class="alert alert-danger" role="alert">{{__('settings-xguard-desc2')}}</div>
                                <div class="alert alert-warning" role="alert">{{__('settings-xguard-desc3')}}</div>
                                <span class="badge color-block bg-blue-500 border">
                                        {{__('settings-xguard-capacity-off')}} {{$response[0]['capacity']}}
                                            </span>
                                <span class="badge color-block bg-blue-500 border">
                                        {!! sprintf(__('settings-xguard-amount'), $response[0]['amount']) !!}

                                            </span>
                                <br>
                                @if(isset($response[0]['message']) and $response[0]['message']=='access')
                                    <ul class="list-inline pt-2">
                                        <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">Domain: <b>{{$response[0]['domain']}}</b></span></li>
                                        <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">Port Connection: <b>{{$response[0]['port_tunnel']}}</b></span></li>
                                        @if(env('APP_LOCALE', 'en')=='fa')
                                            <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">Expired: <b>{{Verta::instance($response[0]['end_license'])->format('Y-m-d')}}</b></span></li>
                                        @else
                                            <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">Expired: <b>{{$response[0]['end_license']}}</b></span></li>
                                        @endif
                                        <li class="list-inline-item">
                                            <a href="https://xguard.xpanel.pro/api/repay?domain={{$response[0]['domain']}}" class="btn btn-primary color-block bg-blue-500 border" >{{__('settings-xguard-tamdid')}}</a>

                                        </li>
                                    </ul><br>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            {!! __('settings-xguard-domain') !!}
                                        </div>
                                        <div class="col-lg-6">
                                            <form action="{{route('settings.xguard.domain')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-success" type="submit" name="submit">{{__('settings-port-success')}}</button>
                                                    <input type="text" class="form-control" name="domain_cname" placeholder="{{__('settings-xguard-doamin_lable')}}" value="{{$xguard[0]->domain}}">
                                                </div>
                                                <small>{{__('settings-xguard-doamin_lable')}}</small>
                                            </form>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <img src="/assets/images/cndxguard.jpg" style="width: -webkit-fill-available;border: 5px solid #ff00007d;border-radius: 9px;">
                                @else
                                    @if($response[0]['capacity']!='full')
                                        <form action="{{route('settings.xguard')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-lg-3">
                                                    <input type="text" name="email" class="form-control" value="" required="">
                                                    <small>{{__('settings-xguard-email')}}</small>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="ip" class="form-control"  value="{{$server_ip}}">
                                                        <small>{{__('settings-xguard-ip')}}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="port" class="form-control"  value="{{$portssh}}">
                                                        <small>{{__('settings-xguard-port')}}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('settings-xguard-success')}}</button>

                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-info" role="alert">{{__('settings-xguard-capacity')}}</div>
                                    @endif

                                @endif
                            </div>

                        </div>
                    </div>
                </div>


            </div>

            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @foreach ($xguard as $x)
                                <ul class="list-inline pt-2">
                                    <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">{{$x->email}}</span></li>
                                    <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">{{$x->domain}}</span></li>
                                    @if(env('APP_LOCALE', 'en')=='fa')
                                        <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">Expired: <b>{{Verta::instance($x->expired)->format('Y-m-d')}}</b></span></li>
                                    @else
                                        <li class="list-inline-item"><span class="bg-body rounded fs-6 p-2 border text-body">Expired: <b>{{$x->expired}}</b></span></li>
                                    @endif
                                    <li class="list-inline-item">
                                        <a href="{{ route('settings.xguard.delete', ['id' => $x->id]) }}"
                                           class="avtar avtar-xs btn-link-success btn-pc-default">
                                            <i class="ti ti-trash f-18"></i>
                                        </a>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
