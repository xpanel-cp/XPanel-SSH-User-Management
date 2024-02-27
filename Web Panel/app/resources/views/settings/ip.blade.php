@extends('layouts.master')
@section('title','XPanel - '.__('ip-adapter-change'))
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
                                <h2 class="mb-0">{{__('ip-adapter-change')}}</h2>
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
                                        <form action="{{route('settings.ipadapter.update')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-lg-3">
                                                    <input type="text" name="email" class="form-control" value="@if($ipadapter->count() > 0) {{ optional($ipadapter[0])->email_cf }} @endif" placeholder="mail@example.com" required>
                                                    <small>{{__('ip-adapter-change-email-cf')}}</small>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" name="token" class="form-control" value="@if($ipadapter->count() > 0) {{ optional($ipadapter[0])->token_cf }} @endif">
                                                        <small>{{__('ip-adapter-change-token-cf')}}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="sub" class="form-control" value="@if($ipadapter->count() > 0) {{ optional($ipadapter[0])->sub_cf }} @endif" placeholder="sub.example.com">
                                                        <small>{{__('ip-adapter-change-sub-cf')}}</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <input type="text" name="gb" class="form-control" value="{{env('GB_CHANGE')}}" placeholder="30">
                                                        <small>{{__('ip-adapter-change-gb-cf')}}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group mb-0">
                                                        <div class="row mb-2">
                                                            <div class="col-lg-4">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" name="change" value="hourly" class="form-check-input input-primary" id="customCheckdef1" data-gtm-form-interact-field-id="1" @if($ipadapter->count() > 0 AND optional($ipadapter[0])->status_chanched == 'hourly') checked @endif>
                                                                        <label class="form-check-label d-block" for="customCheckdef1">
                              <span>
                                <span class="h5 d-block"><strong class="float-end"></strong>{{__('ip-adapter-change-hourly')}}</span>
                                <span class="f-12 text-muted">
                                  {{__('ip-adapter-change-hourly-desc')}}
                                </span>
                              </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" name="change" value="traffic" class="form-check-input input-primary" id="customCheckdef2" data-gtm-form-interact-field-id="1"  @if($ipadapter->count() > 0 AND optional($ipadapter[0])->status_chanched == 'traffic' || empty($ipadapter[0]->status_chanched)) checked @endif >
                                                                        <label class="form-check-label d-block" for="customCheckdef2">
                              <span>
                                <span class="h5 d-block"><strong class="float-end"></strong>{{__('ip-adapter-change-traffic')}}</span>
                                <span class="f-12 text-muted">
                                  {{__('ip-adapter-change-traffic-desc')}}
                                </span>
                              </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <input type="radio" name="change" value="filter" class="form-check-input input-primary" id="customCheckdef3" data-gtm-form-interact-field-id="1" @if($ipadapter->count() > 0 AND optional($ipadapter[0])->status_chanched == 'filter') checked @endif disabled>
                                                                        <label class="form-check-label d-block" for="customCheckdef3">
                              <span>
                                <span class="h5 d-block"><strong class="float-end"></strong>{{__('ip-adapter-change-filtering')}}</span>
                                <span class="f-12 text-muted">
                                  {{__('ip-adapter-change-filtering-desc')}}
                                </span>
                              </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_service" value="on" id="flexRadioDefault11" @if($ipadapter->count() > 0 AND optional($ipadapter[0])->status_active == 'on' || empty($ipadapter[0]->status_active)) checked @endif >
                                                        <label class="form-check-label" for="flexRadioDefault11"> {{__('dashboard-active-user')}} </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_service" value="off" id="flexRadioDefault12" @if($ipadapter->count() > 0 AND $ipadapter[0]->status_active == 'off') checked @endif >
                                                        <label class="form-check-label" for="flexRadioDefault12"> {{__('dashboard-deactive-user')}} </label>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('setting-save')}}</button>
                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                    <small>{{__('ip-adapter-change-ip-desc')}}</small><br>
                                    <small style="color: red">{{__('ip-adapter-change-ip-desc2')}}</small><br>
                                    <small style="color: red">{{__('ip-adapter-change-ip-desc3')}}</small><br>
                                    <small>{{__('ip-adapter-change-ip-desc4')}}</small><br>
                                    <div class="card">
                                        <div class="card-body border-bottom pb-0">

                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">{{__('ip-adapter-change-list')}}</h5>
                                                <div class="dropdown">
                                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical f-18"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a href="javascript:void(0);"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#ip-modal"
                                                           class="dropdown-item" >
                                                            {{__('ip-adapter-change-add')}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($iplist as $val)
                                                        @php $status_service='';@endphp
                                                        @if($val->status_service=='access')
                                                            @php $status_service=__('ip-adapter-change-status-access');@endphp
                                                        @elseif($val->status_service=='filter')
                                                            @php $status_service=__('ip-adapter-change-status-filter');@endphp
                                                        @elseif($val->status_service=='filter2')
                                                            @php $status_service=__('ip-adapter-change-status-filter2');@endphp
                                                        @endif
                                                        @php $status_service_icon='';@endphp
                                                        @if($val->status_service=='access')
                                                            @php $status_service_icon="<div class=\"avtar avtar-s border bg-light-success\" data-bs-toggle=\"tooltip\" data-bs-title=\"$status_service\"><i class=\"ti ti-shield-check\"></i></div>";@endphp
                                                        @elseif($val->status_service=='filter')
                                                            @php $status_service_icon="<div class=\"avtar avtar-s border bg-light-danger\" data-bs-toggle=\"tooltip\" data-bs-title=\"$status_service\"><i class=\"ti ti-shield-x\"></i></div>";@endphp
                                                        @elseif($val->status_service=='filter2')
                                                            @php $status_service_icon="<div class=\"avtar avtar-s border bg-light-warning\" data-bs-toggle=\"tooltip\" data-bs-title=\"$status_service\"><i class=\"ti ti-shield\"></i></div>";@endphp
                                                        @endif

                                                        <li class="list-group-item">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    {!! $status_service_icon !!}
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <div class="row g-1">
                                                                        <div class="col-6">

                                                                            <h6 class="mb-0">
                                                                                @if($val->status_active=='on')
                                                                                    <span class="p-1 d-block bg-success rounded-circle" style="width: 5px">
                                                                        <span class="visually-hidden">{{$val->ip}}</span>
                                                                        </span>
                                                                                @else
                                                                                    <span class="p-1 d-block bg-warning rounded-circle" style="width: 5px">
                                                                        <span class="visually-hidden">{{$val->ip}}</span>
                                                                        </span>
                                                                                @endif
                                                                                {{$val->ip}}</h6>
                                                                            <p class="text-muted mb-0"><small>{{$status_service}}</small> ({{__('ip-adapter-change-status-ping')}} @if(env('APP_LOCALE', 'en')=='fa'){{Verta::instance($val->updated_at)->format('Y/m/d H:i:s')}}@else{{$val->updated_at}}@endif)</p>
                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <ul class="list-inline me-auto mb-0">
                                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="{{__('ip-adapter-change-status-def')}}">
                                                                                    <a href="{{ route('settings.ipadapter.active', ['id' => $val->id]) }}" class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                                                        <i class="ti ti-brand-tinder f-18"></i>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="{{__('ip-adapter-change-status')}}{{__('ip-adapter-change-status-access')}}">
                                                                                    <a href="{{ route('settings.ipadapter.access', ['id' => $val->id]) }}" class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                                                        <i class="ti ti-shield-check f-18"></i>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{__('ip-adapter-change-status')}}{{__('ip-adapter-change-status-filter')}}">
                                                                                    <a href="{{ route('settings.ipadapter.filter', ['id' => $val->id]) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                                        <i class="ti ti-shield-x f-18"></i>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="{{__('ip-adapter-change-status')}}{{__('ip-adapter-change-status-filter2')}}">
                                                                                    <a href="{{ route('settings.ipadapter.filter2', ['id' => $val->id]) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                                        <i class="ti ti-shield f-18"></i>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="{{__('user-table-delete')}}">
                                                                                    <a href="{{ route('settings.ipadapter.delete', ['id' => $val->id]) }}" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                                        <i class="ti ti-trash f-18"></i>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </div>
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
    <div class="modal fade" id="ip-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('settings.ipadapter.add')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('{{__('allert-submit')}}');">
                @csrf
                <div class="modal-header">
                    <h5 class="mb-0">{{__('ip-adapter-change-popup-title')}}</h5>
                    <a href="javascript:void(0);" class="avtar avtar-s btn-link-danger btn-pc-default"
                       data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        @csrf
                                        <input type="text" name="ip" class="form-control" placeholder="{{__('ip-adapter-change-popup-ip')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default"
                                data-bs-dismiss="modal">{{__('user-pop-renewal-cancel')}}
                        </button>
                        <button type="submit" class="btn btn-primary" value="submit"
                                name="renewal_date">{{__('ip-adapter-change-add')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
