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
                                <h2 class="mb-0">Settings - Install Wordpress</h2>
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

                                        <p class="form-text text-muted"><b style="color:red">Attention, the fake address will be deleted after installing WordPress.</b></p>
                                        <p class="form-text text-muted">After installing WordPress, when your domain address is entered in the browser without the login panel port, the WordPress website you installed will be loaded.</p>
                                        <p class="form-text text-muted">To install WordPress, first create the database through the command below, then install WordPress with the database specifications.</p>
                                        <code>bash <(curl -Ls
                                            https://raw.githubusercontent.com/Alirezad07/X-Panel-SSH-User-Management/main/wp-install.sh
                                            --ipv4)</code>
                                        <br>
                                        <p class="form-text text-muted">The Database Name step will be the name of your WordPress database</p>
                                        <p class="form-text text-muted">The Database Username step will be your WordPress database username</p>
                                        <p class="form-text text-muted">Password User step will be your WordPress database password</p>
                                        <p class="form-text text-muted">In the Enter password step, you must enter the root password of the server to create the database</p>
                                        <p class="form-text text-muted"><b>After completing the above steps, start installing WordPress. To install, just enter the link below and proceed with the installation steps</b></p>
                                        <p class="form-text text-muted"><a href="{{$address}}" target="_blank">Start installing WordPress</a> </p>
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