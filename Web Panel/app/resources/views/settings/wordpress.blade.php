@extends('layouts.master')
@section('title','XPanel - '.__('setting-wordpress-title'))
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
                                <h2 class="mb-0">{{__('setting-wordpress-title')}}</h2>
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

                                        <p class="form-text text-muted"><b style="color:red">{{__('setting-wordpress-desc1')}}</b></p>
                                        <p class="form-text text-muted">{{__('setting-wordpress-desc2')}}</p>
                                        <p class="form-text text-muted">{{__('setting-wordpress-desc3')}}</p>
                                        <code>bash <(curl -Ls
                                            https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/wp-install.sh
                                            --ipv4)</code>
                                        <br>
                                        <p class="form-text text-muted">{{__('setting-wordpress-desc3')}}</p>
                                        <p class="form-text text-muted">{{__('setting-wordpress-desc4')}}</p>
                                        <p class="form-text text-muted">{{__('setting-wordpress-desc5')}}</p>
                                        <p class="form-text text-muted">{{__('setting-wordpress-desc6')}}</p>
                                        <p class="form-text text-muted"><b>{{__('setting-wordpress-desc7')}}</b></p>
                                        <p class="form-text text-muted"><a href="{{$address}}" target="_blank">{{__('setting-wordpress-start')}}</a> </p>
                                        <br>



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
