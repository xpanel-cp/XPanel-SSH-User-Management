@extends('layouts.master')
@section('title','XPanel - '.__('premium'))
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
                                <h2 class="mb-0">{{__('premium')}}</h2>
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
                                         <span class="badge color-block bg-orange-500 border">
                                                    {!! sprintf(__('premium-amount'), $response[0]['amount']) !!}
                                                    </span>
                                        <br>
                                        <br>
                                        @if (isset($response[0]['message']) and $response[0]['message'] == 'access')
                                            @if(env('APP_LOCALE', 'en')=='fa')
                                                @php $end_date=Verta::instance($response[0]['end_license'])->format('Y/m/d');@endphp
                                            @else
                                                @php $end_date=$response[0]['end_license'];@endphp
                                            @endif
                                                @if ($response[0]['status'] == "active")
                                                    @php $status = "<span class='badge bg-light-success rounded-pill f-12'>".__('user-table-status-active')."</span>"; @endphp
                                                @else
                                                    @php $status = "<span class='badge bg-light-danger rounded-pill f-12'>".__('user-table-status-deactive')."</span>"; @endphp
                                                @endif
                                            <div class="card">
                                                <div class="card-body border-bottom pb-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h5 class="mb-0">{{__('premium-acc-detail')}}</h5>
                                                    </div>

                                                </div>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div class="d-flex align-items-center">

                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div class="row g-1">
                                                                            <div class="col-4">
                                                                                <h6 class="mb-0">{!! $status !!} {{__('premium-acc-email')}}: {{$license->email}}</h6>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <h6 class="mb-0">{{__('premium-acc-domain')}}: {{$license->domain}}</h6>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <h6 class="mb-0">{{__('premium-acc-xp')}}: {{$end_date}}</h6>
                                                                            </div>
                                                                            <div class="col-1 text-end">
                                                                                <a href="{{ route('settings.license.delete', ['id' => $license->id]) }}"
                                                                                   class="avtar avtar-xs btn-link-success btn-pc-default">
                                                                                    <i class="ti ti-trash f-18"></i>
                                                                                </a>
                                                                                 </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="row text-center">
                                                            <div class="d-grid">
                                                                <a href="https://xguard.xpanel.pro/api/license/repay?domain={{$license->domain}}" target="_blank" class="btn btn-primary d-grid"><span class="text-truncate w-100">{{__('premium-acc-renew')}}</span></a>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <form action="{{route('settings.license')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <input type="text" name="email" class="form-control" value="{{ optional($license)->email }}" placeholder="mail@example.com" required="">
                                                    <small>{{__('premium-email')}}</small>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" name="domain" class="form-control" value="@if(empty($license->domain)){{$domainWithoutPort}}@else{{ optional($license)->domain }}@endif" placeholder="sub.example.com">
                                                        <small>{{__('premium-domain')}}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">

                                                    <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('settings-xguard-success')}}</button>
                                                </div>


                                            </div>
                                        </form>
                                        @endif
                                    </div>
                                    <small>{{__('premium-desc1')}}</small><br>
                                    <div class="card">
                                        <div class="card-body pc-component">
                                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            <i class="material-icons-two-tone text-warning pc-icon-check" style="font-size: 15px;">star</i> {{__('premium-desc2')}}
                                                        </button>
                                                    </h2>
                                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                                                        <div class="accordion-body">{{__('premium-desc2-1')}}<br>{{__('premium-desc2-2')}}<br>{{__('premium-desc2-3')}}</div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingTwo">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                                            <i class="material-icons-two-tone pc-icon-check" style="font-size: 15px;">star</i> {{__('premium-desc3')}}
                                                        </button>
                                                    </h2>
                                                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" style="">
                                                        <div class="accordion-body">{{__('premium-desc3-1')}}</div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingThree">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                                            <i class="material-icons-two-tone pc-icon-check" style="font-size: 15px;">star</i>{{__('premium-desc4')}}
                                                        </button>
                                                    </h2>
                                                    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample" style="">
                                                        <div class="accordion-body">{{__('premium-desc4-1')}}</div>
                                                    </div>
                                                </div>

                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-heading4">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                                            <i class="material-icons-two-tone text-warning pc-icon-check" style="font-size: 15px;">star</i> {{__('premium-desc5')}}
                                                        </button>
                                                    </h2>
                                                    <div id="flush-collapse4" class="accordion-collapse collapse" aria-labelledby="flush-heading4" data-bs-parent="#accordionFlushExample" style="">
                                                        <div class="accordion-body">{{__('premium-desc5-1')}}</div>
                                                    </div>
                                                </div>

                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-heading5">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse5" aria-expanded="false" aria-controls="flush-collapse5">
                                                            <i class="material-icons-two-tone text-warning pc-icon-check" style="font-size: 15px;">star</i> {{__('premium-desc6')}}
                                                        </button>
                                                    </h2>
                                                    <div id="flush-collapse5" class="accordion-collapse collapse" aria-labelledby="flush-heading5" data-bs-parent="#accordionFlushExample" style="">
                                                        <div class="accordion-body">{{__('premium-desc6-1')}}</div>
                                                    </div>
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

@endsection
