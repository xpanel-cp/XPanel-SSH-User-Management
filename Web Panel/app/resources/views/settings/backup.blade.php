@extends('layouts.master')
@section('title','XPanel - '.__('setting-backup-title'))
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
                                <h2 class="mb-0">{{__('setting-backup-title')}}</h2>
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
                                <div class="card-header">
                                    <h5>{{__('setting-backup-bot_title')}}</h5>
                                </div>
                                <form class="row align-items-center" action="{{route('settings.backup.bot.up')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" id="inlineFormInputName" name="token_bot" placeholder="Bot Token" value="{{$token_bot}}">
                                        <small>@BotFather</small>
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" id="inlineFormInputName" name="id_admin" placeholder="ID" value="{{$id_admin}}">
                                        <small>@userinfobot</small>
                                    </div>

                                    <div class="col-lg-2">
                                        <p>
                                            <label class="form-check-label" for="inlineFormCheck"> {{$status_webhoock}} </label> &nbsp; <button type="submit" class="btn btn-primary">{{__('setting-backup-bot_submit')}}</button>
                                        </p>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <form class="validate-me" action="{{route('settings.backup.make')}}" method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                @csrf
                                                <input type="submit" class="btn btn-primary" value="{{__('setting-backup-make')}}">
                                            </div>
                                        </div>
                                    </form>
                                    <form class="validate-me" action="{{route('settings.backup.upload')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="UppyInput form">
                                                    <div class="uppy-Root uppy-FileInput-container">
                                                        <input class="uppy-FileInput-input form-control" type="file" name="file" multiple="" style="">
                                                        <small class="form-text text-muted">{{__('setting-backup-desc')}}</small>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="UppyInput form">
                                                    <div class="uppy-Root uppy-FileInput-container">
                                                        <input type="submit" class="btn btn-primary" value="{{__('setting-backup-upload')}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="UppyInput form">
                                                    <div class="uppy-Root uppy-FileInput-container">
                                                        <a href="{{route('proccess.active.all')}}" type="submit" class="btn btn-success" >{{__('setting-user-all-fixed')}}</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                                <div class="col-sm-12">
                                    <div class="card table-card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="pc-dt-simple">
                                                    <thead>
                                                    <tr>

                                                        <th>{{__('setting-backup-name')}}</th>
                                                        <th class="text-center">{{__('setting-backup-action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($lists as $list)
                                                        @if(!empty($list))
                                                            <tr>
                                                                <td>{{$list}}</td>
                                                                <td class="text-center">
                                                                    <ul class="list-inline me-auto mb-0">
                                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="{{__('setting-backup-action-dl')}}">
                                                                            <a href="{{ route('settings.backup.dl', ['name' => $list]) }}" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                                <i class="ti ti-download f-18"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="{{__('setting-backup-action-restore')}}">
                                                                            <a href="{{ route('settings.backup.restore', ['name' => $list]) }}" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                                <i class="ti ti-refresh f-18"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="{{__('setting-backup-action-delete')}}">
                                                                            <a href="{{ route('settings.backup.delete', ['name' => $list]) }}" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                                <i class="ti ti-trash f-18"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
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


@endsection
