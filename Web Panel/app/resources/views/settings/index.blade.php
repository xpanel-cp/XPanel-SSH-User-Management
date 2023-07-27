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
                                <h2 class="mb-0">Settings - Multi User</h2>
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
                                        Status:
                                        @if ($status=='active') 
                                        <span class="badge bg-light-success rounded-pill f-12" style="width:100px">Active</span>
                                        @else
                                            <span class="badge bg-light-danger rounded-pill f-12" style="width:100px">Deactive</span>
                                        @endif
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <form class="validate-me" action="{{route('settings.multiuser')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                Active
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="status" value="active" checked="">
                                                Deactive
                                                <input type="radio" class="form-check-input input-primary"
                                                       name="status" value="deactive" >
                                                <br>
                                                <br>
                                                <button type="submit" class="btn btn-primary" value="submit" >Save</button>
                                            </form>
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