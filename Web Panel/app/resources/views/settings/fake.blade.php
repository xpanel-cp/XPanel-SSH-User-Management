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
                                <h2 class="mb-0">Settings - Fake Address</h2>
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
                                <form class="validate-me" action="{{route('settings.fakeurl')}}" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <p class="form-text text-muted"><b style="color:red">Attention, by activating the fake address, it will be deleted if you have installed WordPress.</b></p>
                                            @csrf
                                            <input type="text" name="fake_address" id="confirm-password" placeholder="Fake address" value="" class="form-control" >
                                            <small class="form-text text-muted">Website address</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-4 col-form-label"></div>
                                        <div class="col-lg-6">
                                            <input type="submit" class="btn btn-primary" value="Save">
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