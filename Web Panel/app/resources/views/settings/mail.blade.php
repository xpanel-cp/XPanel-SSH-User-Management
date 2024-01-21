@extends('layouts.master')
@section('title','XPanel - '.__('mail-setting-title'))
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
                                <h2 class="mb-0">{{__('mail-setting-title')}}</h2>
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
                                <div class="form-group row">
                                    <div class="col-lg-12">

                                        <form action="{{route('settings.mail')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                    <input type="text" name="host" class="form-control" value="{{env('MAIL_HOST')}}" placeholder="smtp.example.com" required="">
                                                    <small>{{__('mail-setting-smtp-host')}}</small>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" name="port" class="form-control" value="{{env('MAIL_PORT')}}" placeholder="2525">
                                                        <small>{{__('mail-setting-smtp-port')}}</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" name="username" class="form-control" value="{{env('MAIL_USERNAME')}}">
                                                        <small>{{__('mail-setting-smtp-user')}}</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" name="password" class="form-control" value="{{env('MAIL_PASSWORD')}}" >
                                                        <small>{{__('mail-setting-smtp-pass')}}</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" name="email" class="form-control" value="{{env('MAIL_FROM_ADDRESS')}}" placeholder="ex@example.com">
                                                        <small>{{__('mail-setting-smtp-email')}}</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" name="name" class="form-control" value="{{env('MAIL_FROM_NAME')}}" placeholder="XPanel">
                                                        <small>{{__('mail-setting-smtp-name')}}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_service" value="on" id="flexRadioDefault11" @if(env('MAIL_STATUS')== 'on') checked @endif >
                                                        <label class="form-check-label" for="flexRadioDefault11"> {{__('dashboard-active-user')}} </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_service" value="off" id="flexRadioDefault12" @if(env('MAIL_STATUS') == 'off' || empty(env('MAIL_STATUS'))) checked @endif >
                                                        <label class="form-check-label" for="flexRadioDefault12"> {{__('dashboard-deactive-user')}} </label>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('mail-setting-save')}}</button>
                                                <br>
                                                <br>
                                                    <a href=" https://elasticemail.com/referral-reward?r=921decb1-8638-47f6-b42d-a9eb05b34c44" target="_blank">{{__('mail-setting-smtp-desc')}}</a>
                                                </div>


                                            </div>
                                        </form>
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

@endsection
