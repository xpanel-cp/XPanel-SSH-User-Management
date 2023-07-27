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
                                <h2 class="mb-0">Settings - Telegram Bot</h2>
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
                                <form class="validate-me" action="{{route('settings.telegram')}}" method="post" enctype="multipart/form-data">
                                   @csrf
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            1- Connect your active domain without filter to server IP
                                            <BR>
                                            2- Use GitHub command to install SSL
                                            <BR>
                                            3- Put the created robot token and the numerical ID of the Telegram account in the following fields
                                            <BR>
                                            <a href="https://github.com/Alirezad07/X-Panel-SSH-User-Management">Github link to provide SSL</a>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <input type="text" name="tokenbot" class="form-control" value="{{$token}}" required="">
                                            <small class="form-text text-muted">Put the robot token in the field</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" name="idtelegram" value="{{$id}}" required="">
                                            <small class="form-text text-muted">Enter the numeric ID of the Telegram account in the field</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-4 col-form-label"></div>
                                        <div class="col-lg-6">
                                            <input type="submit" name="submitbot" class="btn btn-primary" value="Save">
                                        </div>
                                    </div>
                                </form>
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