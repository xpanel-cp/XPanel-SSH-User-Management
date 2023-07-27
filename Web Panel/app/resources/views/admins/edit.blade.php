@extends('layouts.master')
@section('title','XPanel - Edit Manager')
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
                            <h2 class="mb-0">Edit Manager</h2>
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
                            {{$show->password}}
                                <form class="modal-content" action="{{route('admin.update')}}" method="POST" enctype="multipart/form-data"
                                      onsubmit="return confirm('Are you sure you want to perform this operation?');">
                                    @csrf
                                <div class="">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" class="form-control" placeholder="Username" value="{{$show->username}}" disabled="">
                                                    <input type="hidden" class="form-control" name="username" value="{{$show->username}}">
                                                    <small class="form-text text-muted">Username</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                        <input type="text" name="password" class="form-control" placeholder="New Password" required="" value="">
                                                    </div>
                                                    <small class="form-text text-muted">Enter New Password</small>
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