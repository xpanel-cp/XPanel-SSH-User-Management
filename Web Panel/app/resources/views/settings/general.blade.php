@extends('layouts.master')
@section('title','MadoPanel - '.__('setting-general-title'))
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


@endsection
