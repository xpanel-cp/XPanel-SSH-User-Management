@extends('layouts.master')
@section('title','XPanel - '.__('setting-crontab-title'))
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
                                <h2 class="mb-0">{{__('setting-crontab-title')}}</h2>
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
                                        {{__('setting-crontab-desc')}}
                                        <br>
                                        <br>
                                        <form action="{{route('settings.crontab.fixed')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div class="input-group mb-3">
                                                        <button class="btn btn-success" type="submit" >{{__('setting-crontab-submit')}}</button>
                                                        <input type="text" class="form-control" name="address" placeholder="Address" aria-label="Example text with button addon" aria-describedby="button-addon1" value="{{$address}}">

                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <pre class="language-markup" tabindex="0">
<code class="language-markup" style="direction: ltr">
@foreach($outputs as $val)
  {{$val}}
@endforeach
</code>
</pre>
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
