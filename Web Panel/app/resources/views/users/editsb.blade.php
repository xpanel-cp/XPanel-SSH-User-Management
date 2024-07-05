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
                                <form class="modal-content" action="{{route('singbox.user.update')}}" method="POST" enctype="multipart/form-data"
                                      onsubmit="return confirm('{{__('allert-submit')}}');">
                                    @csrf
                                    <div class="">
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        @csrf
                                                        <input type="text" name="name" class="form-control"
                                                               placeholder="{{__('singbox-name')}}" autocomplete="off"
                                                               onkeyup="if (/[^|a-z0-9]+/g.test(this.value)) this.value = this.value.replace(/[^-a-z0-9_]+/g,'')"
                                                               value="{{$show->name}}" required>
                                                        <small
                                                            class="form-text text-muted">{{__('singbox-name-desc')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <select class="form-select" data-gtm-form-interact-field-id="0" disabled>
                                                            <option @if($show->protocol_sb=='vmess-ws') selected @endif value="vmess-ws">VMess ws</option>
                                                            <option @if($show->protocol_sb=='vless-reality') selected @endif value="vless-reality">VLess Reality</option>
                                                            <option @if($show->protocol_sb=='hysteria2') selected @endif value="hysteria2">Hysteria2</option>
                                                            <option @if($show->protocol_sb=='tuic') selected @endif value="tuic">Tuic</option>
                                                            <option @if($show->protocol_sb=='shadowsocks') selected @endif value="shadowsocks">Shadowsocks</option>

                                                        </select>
                                                        <small class="form-text text-muted">{{__('singbox-protocol-desc')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="text" name="email" class="form-control"
                                                               placeholder="{{__('user-pop-newuser-email')}}" value="{{$show->email}}">
                                                        <input type="hidden" name="port" value="{{$show->port_sb}}">
                                                        <small
                                                            class="form-text text-muted">{{__('user-pop-newuser-email-desc')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                            <input type="text" name="mobile" class="form-control"
                                                                   placeholder="{{__('user-pop-newuser-phone')}}" value="{{$show->phone}}">
                                                        </div>
                                                        <small
                                                            class="form-text text-muted">{{__('user-pop-newuser-phone-desc')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <input type="text" name="multiuser" class="form-control"
                                                               placeholder="{{__('user-pop-newuser-connect')}}" value="{{$show->multiuser}}" required>
                                                        <small
                                                            class="form-text text-muted">{{__('user-pop-newuser-connect-desc')}}</small>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <input type="text" name="sni" class="form-control" value="{{ $show->sni ?? 'www.bing.com' }}"
                                                               placeholder="SNI" required>
                                                        <small
                                                            class="form-text text-muted">SNI</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                            <input type="text" name="connection_start" class="form-control"
                                                                   placeholder="30" value="{{$show->date_one_connect}}">
                                                        </div>
                                                        <small
                                                            class="form-text text-muted">{{__('user-pop-newuser-connect-start-desc1')}}</small>
                                                        <small
                                                            style="color:red">{{__('user-pop-newuser-connect-start-desc2')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="text" name="traffic" class="form-control" value="{{$show->traffic}}">
                                                        <br>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary"
                                                                   name="type_traffic" value="mb" checked="">
                                                            <label class="form-check-label"
                                                                   for="customCheckinl311">MB</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary"
                                                                   name="type_traffic" value="gb">
                                                            <label class="form-check-label"
                                                                   for="customCheckinl32">GB</label>
                                                        </div>
                                                        <small
                                                            class="form-text text-muted">{{__('user-pop-newuser-traffic-desc')}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ti ti-calendar-time"></i></span>
                                                            @if(env('APP_LOCALE', 'en')=='fa')
                                                                <input type="text" name="expdate" class="form-control example1"  autocomplete="off" value="{{$end_date}}">
                                                            @else
                                                                <input type="date" class="form-control" value="{{$show->end_date}}" name="expdate" id="date" data-gtm-form-interact-field-id="0">
                                                            @endif
                                                        </div>
                                                        <small class="form-text text-muted">{{__('user-pop-newuser-date-desc1')}}</small>
                                                        <small style="color:red">{{__('user-pop-newuser-date-desc2')}}</small>
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
                                        <div class="form-group">
                                            <label class="form-label">{{__('user-pop-newuser-desc')}}</label>
                                            <textarea class="form-control" rows="3" name="desc" placeholder="{{__('edit-user-desc')}}">{{$show->desc}}</textarea>

                                        </div>
                                        <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('edit-user-save')}}</button>
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
