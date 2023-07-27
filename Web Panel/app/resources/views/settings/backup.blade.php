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
                                <h2 class="mb-0">Settings - Bckup</h2>
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
                                <div class="col-sm-12">
                                <form class="validate-me" action="{{route('settings.backup.make')}}" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            @csrf
                                            <input type="submit" class="btn btn-primary" value="Make backup">
                                        </div>
                                    </div>
                                </form>
                                <form class="validate-me" action="{{route('settings.backup.upload')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <div class="UppyInput form"><div class="uppy-Root uppy-FileInput-container">
                                                    <input class="uppy-FileInput-input form-control" type="file" name="file" multiple="" style="">
                                                    <small class="form-text text-muted">Select SQL file</small>
                                                    <br>
                                                    <input type="submit" class="btn btn-primary" value="Upload">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <hr>
                                <div class="col-sm-12">
                                    Import Only Users
                                    <form class="validate-me" action="{{route('settings.backup.old')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="UppyInput form"><div class="uppy-Root uppy-FileInput-container">
                                                        <input class="uppy-FileInput-input form-control" type="file" name="file" multiple="" style="">
                                                        <small class="form-text text-muted">Select SQL file</small>
                                                        <br>
                                                        <input type="submit"  class="btn btn-primary" value="Import">

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

                                                        <th>Name</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($lists as $list)
                                                        @if(!empty($list))
                                                    <tr>
                                                        <td>{{$list}}</td>
                                                        <td class="text-center">
                                                            <ul class="list-inline me-auto mb-0">
                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Downlod">
                                                                    <a href="{{ route('settings.backup.dl', ['name' => $list]) }}" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                        <i class="ti ti-download f-18"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Restore">
                                                                    <a href="{{ route('settings.backup.restore', ['name' => $list]) }}" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                        <i class="ti ti-refresh f-18"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
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