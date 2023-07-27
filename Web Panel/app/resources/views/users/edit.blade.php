@extends('layouts.master')
@section('title','XPanel - Edit')
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
                            <h2 class="mb-0">Edit User</h2>
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
                        <div class="row">
                                <form class="modal-content" action="{{route('user.update')}}" method="POST" enctype="multipart/form-data"
                                      onsubmit="return confirm('Are you sure you want to perform this operation?');">
                                    @csrf
                                <div class="">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" class="form-control" placeholder="Username" value="{{$show->username}}" disabled="">
                                                    <input type="hidden" class="form-control" name="username" value="{{$show->username}}">
                                                    <small class="form-text text-muted">Enter Username</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                        <input type="text" name="password" class="form-control" placeholder="Password" required="" value="{{$show->password}}">
                                                    </div>
                                                    <small class="form-text text-muted">Enter Password</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{$show->email}}">
                                                    <small class="form-text text-muted">Enter Email</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <input type="text" name="mobile" class="form-control" placeholder="Phone" value="{{$show->mobile}}">
                                                    </div>
                                                    <small class="form-text text-muted">Enter Phone</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" name="multiuser" class="form-control" placeholder="Concurrent Users" required="" value="{{$show->multiuser}}">
                                                    <small class="form-text text-muted">Enter number of concurrent users</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" name="traffic" class="form-control"  value="{{$show->traffic}}">
                                                    <br>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input input-primary" name="type_traffic" value="mb" checked="">
                                                        <label class="form-check-label" for="customCheckinl311">MB</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input input-primary" name="type_traffic" value="gb">
                                                        <label class="form-check-label" for="customCheckinl32">GB</label>
                                                    </div>
                                                    <small class="form-text text-muted">Enter Traffic</small>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">

                                                            <input type="date" class="form-control" value="{{$show->end_date}}" name="expdate" id="date" data-gtm-form-interact-field-id="0">

                                                        </div>
                                                        <small class="form-text text-muted">Expiration date</small>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6" >
                                                <div class="row">
                                                    <div class="col-lg-12" style="margin-right:2%">
                                                        <br>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary" name="activate" value="active" @if($show->status=='active')checked=""@endif>
                                                            <label class="form-check-label" for="customCheckinl311">Active</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary" name="activate" value="deactive"  @if($show->status!='active')checked=""@endif>
                                                            <label class="form-check-label" for="customCheckinl32">Deactive</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" rows="3" name="desc" placeholder="Description">{{$show->desc}}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Save</button>                        </div>
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