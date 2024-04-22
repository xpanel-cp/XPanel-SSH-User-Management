@extends('layouts.master')
@section('title','XPanel - '.__('manager-title'))
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
                                <h2 class="mb-0">{{__('manager-title')}}</h2>
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
                    <div class="card table-card">
                        <div class="card-body">
                            <div class="text-end p-4 pb-0">
                                <a href="#" class="btn btn-primary d-inline-flex align-items-center"
                                   style="margin-bottom: 5px;" data-bs-toggle="modal" data-bs-target="#customer_add-modal">
                                    <i class="ti ti-plus f-18"></i>{{__('manager-newuser')}}
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>{{__('manager-username')}}</th>
                                        <th>{{__('manager-count-account')}}</th>
                                        <th>{{__('user-pop-newuser-date-desc1')}}</th>
                                        <th>{{__('manager-status')}}</th>
                                        <th class="text-center">{{__('manager-action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $uid=0;
                                    @endphp
                                    @foreach ($admins as $admin)
                                        @php
                                            $uid++;
                                        @endphp
                                        @if ($admin->status == "active")
                                            @php $status = "<span class='badge bg-light-success rounded-pill f-12'>".__('manager-active')."</span>";@endphp
                                        @endif
                                        @if ($admin->status == "deactive")
                                            @php $status = "<span class='badge bg-light-danger rounded-pill f-12'>".__('manager-deactive')."</span>"; @endphp
                                        @endif
                                        @if (!empty($admin->end_date))
                                            @if(env('APP_LOCALE', 'en')=='fa')
                                                @php $st_date=Verta::instance($admin->end_date)->format('Y/m/d');@endphp
                                            @else
                                                @php $st_date=$admin->end_date;@endphp
                                            @endif

                                        @else
                                            @php $st_date=''; @endphp
                                        @endif
                                        <tr>
                                            <td>{{$uid}}</td>
                                            <td>{{$admin->username}}</td>
                                            <td>{{$admin->count_account}}</td>
                                            <td>{{$st_date}}</td>
                                            <td>{!! $status !!}</td>

                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" >
                                                        <button class="avtar avtar-xs btn-link-success btn-pc-default" style="border:none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-adjustments f-18"></i></button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('admin.active', ['username' => $admin->username]) }}">{{__('manager-active')}}</a>
                                                            <a class="dropdown-item" href="{{ route('admin.deactive', ['username' => $admin->username]) }}">{{__('manager-deactive')}}</a>
                                                            <a class="dropdown-item" href="{{ route('admin.delete', ['username' => $admin->username]) }}">{{__('manager-delete')}}</a>
                                                        </div>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="{{__('manager-tog-edit')}}">
                                                        <a href="{{ route('admin.edit', ['username' => $admin->username]) }}"
                                                           class="avtar avtar-xs btn-link-success btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <div class="modal fade" id="customer_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{route('admin.new')}}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('Are you sure you want to perform this operation?');">
                <div class="modal-header">
                    <h5 class="mb-0">{{__('manager-newuser')}}</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @csrf
                                            <input type="text" name="username" class="form-control"
                                                   placeholder="{{__('manager-pop-newuser')}}" required>
                                            <small class="form-text text-muted">{{__('manager-pop-newuser-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                                <input type="text" name="password" class="form-control"
                                                       placeholder="{{__('manager-pop-password')}}" required>
                                            </div>
                                            <small class="form-text text-muted">{{__('manager-pop-password-desc')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @csrf
                                            <input type="number" name="count_account" class="form-control"
                                                   placeholder="{{__('manager-count-account')}}" onkeyup="this.value = this.value.replace(/[^\d]+/g, '')">
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
                                                    <input type="text" name="end_date" class="form-control example1"
                                                           autocomplete="off"/>
                                                @else
                                                    <input type="date" class="form-control" name="end_date" id="date"
                                                           data-gtm-form-interact-field-id="0">
                                                @endif
                                            </div>
                                            <small class="form-text text-muted">{{__('user-pop-newuser-date-desc1')}}</small>
                                            <br><small style="color: red">{{__('manager-detail')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">{{__('manager-pop-cancel')}}
                        </button>
                        <button type="submit" class="btn btn-primary" value="submit" name="submit">{{__('manager-pop-submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- [ Main Content ] end -->


@endsection
