@extends('layouts.master')
@section('title','XPanel - '.__('manager-edit'))
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
                                <h2 class="mb-0">{{__('manager-edit')}}</h2>
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
                                <form class="modal-content" action="{{route('admin.update')}}" method="POST" enctype="multipart/form-data"
                                      onsubmit="return confirm('Are you sure you want to perform this operation?');">
                                    @csrf
                                    <div class="">
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="text" class="form-control" placeholder="{{__('manager-pop-newuser')}}" value="{{$user->username}}" disabled="">
                                                        <input type="hidden" class="form-control" name="username" value="{{$user->username}}">
                                                        <small class="form-text text-muted">{{__('manager-pop-newuser')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                            <input type="text" name="password" class="form-control" placeholder="{{__('manager-new-pass')}}" value="">
                                                        </div>
                                                        <small class="form-text text-muted">{{__('manager-new-pass')}}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        @csrf
                                                        <input type="number" name="count_account" class="form-control"
                                                               placeholder="{{__('manager-count-account')}}" value="{{$user->count_account}}" onkeyup="this.value = this.value.replace(/[^\d]+/g, '')">
                                                        <small class="form-text text-muted">{{__('manager-count-account')}}</small>
                                                        <br><small style="color: red">{{__('manager-detail')}}</small>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="ti ti-calendar-time"></i></span>
                                                            @if(env('APP_LOCALE', 'en')=='fa')
                                                                <input type="text" name="end_date" value="{{$end_date}}" class="form-control example1"
                                                                       autocomplete="off"/>
                                                            @else
                                                                <input type="date" class="form-control" value="{{$user->end_date}}" name="end_date" id="date"
                                                                       data-gtm-form-interact-field-id="0">
                                                            @endif
                                                        </div>
                                                        <small class="form-text text-muted">{{__('user-pop-newuser-date-desc1')}}</small>
                                                        <br><small style="color: red">{{__('manager-detail')}}</small>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('edit-user-save')}}</button>                        </div>
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
