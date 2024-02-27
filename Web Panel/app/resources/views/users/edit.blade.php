@extends('layouts.master')
@section('title','XPanel - '.__('edit-user-title'))
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
                            <h2 class="mb-0">{{__('edit-user-title')}}</h2>
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
                                      onsubmit="return confirm('{{__('allert-submit')}}');">
                                    @csrf
                                <div class="">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" class="form-control" placeholder="{{__('edit-user-username')}}" value="{{$show->username}}" disabled="">
                                                    <input type="hidden" class="form-control" name="username" value="{{$show->username}}">
                                                    <small class="form-text text-muted">{{__('edit-user-username-desc')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                        <input type="text" name="password" class="form-control" placeholder="{{__('edit-user-password')}}" required="" value="{{$show->password}}">
                                                    </div>
                                                    <small class="form-text text-muted">{{__('edit-user-password-desc')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" name="email" class="form-control" placeholder="{{__('edit-user-email')}}" value="{{$show->email}}">
                                                    <small class="form-text text-muted">{{__('edit-user-email-desc')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <input type="text" name="mobile" class="form-control" placeholder="{{__('edit-user-phone')}}" value="{{$show->mobile}}">
                                                    </div>
                                                    <small class="form-text text-muted">{{__('edit-user-phone-desc')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="text" name="multiuser" class="form-control" placeholder="{{__('edit-user-connect')}}" required="" value="{{$show->multiuser}}">
                                                    <small class="form-text text-muted">{{__('edit-user-connect-desc')}}</small>
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
                                                    <small class="form-text text-muted">{{__('edit-user-traffic')}}</small>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                            @if(env('APP_LOCALE', 'en')=='fa')
                                                                <input type="text" name="expdate" class="form-control example1"  autocomplete="off" value="{{$end_date}}">
                                                            @else
                                                                <input type="date" class="form-control" value="{{$show->end_date}}" name="expdate" id="date" data-gtm-form-interact-field-id="0">
                                                            @endif

                                                        </div>
                                                        <small class="form-text text-muted">{{__('edit-user-date')}}</small>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6" >
                                                <div class="row">
                                                    <div class="col-lg-12" style="margin-right:2%">
                                                        <br>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary" name="activate" value="active" @if($show->status=='active')checked=""@endif>
                                                            <label class="form-check-label" for="customCheckinl311">{{__('edit-user-active')}}</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary" name="activate" value="deactive"  @if($show->status!='active')checked=""@endif>
                                                            <label class="form-check-label" for="customCheckinl32">{{__('edit-user-deactive')}}</label>
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
                                                    <label class="form-label">{{__('edit-user-desc')}}</label>
                                                    <textarea class="form-control" rows="3" name="desc" placeholder="{{__('edit-user-desc')}}">{{$show->desc}}</textarea>
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
