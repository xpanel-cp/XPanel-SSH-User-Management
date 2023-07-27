@extends('layouts.master')
@section('title','XPanel - Documents')
@section('content')
    <style>
        .pc-collapse {
            font-size: 11px;
            border-radius: 4px;
            padding: 2px 8px;
            background: #68c0ff;
            color: #fff;
            z-index: 1;
        }
    </style>
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Documents API</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="col-12">
                    <div class="accordion card" id="accordionExample">

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    All User
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    {APIKEY}=Token<br>
                                    <span class="pc-collapse">Method GET</span><br>
                                    <code>{{$path}}api/{APIKEY}/listuser</code><br>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Sort Users Status
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    {APIKEY}=Token<br>
                                    {STATUS}=true<br>
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b style="color:green">active</b> Active user <br>
                                            <b style="color:green">deactive</b> DeActive user <br>
                                            <b style="color:green">expired</b> Expired Date user <br>
                                            <b style="color:green">traffic</b> Conduct traffic user
                                        </code></div>
                                    <span class="pc-collapse">Method GET</span><br>
                                    <code>{{$path}}api/{APIKEY}/listuser/{STATUS}</code><br>

                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Add user
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/adduser</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                            <b>password</b> Required<br>
                                            <b>email</b> String<br>
                                            <b>mobile</b> String<br>
                                            <b>multiuser</b> Required<br>
                                            <b>traffic</b> Required<br>
                                            <b>type_traffic</b> Required(gb or mb)<br>
                                            <b>expdate</b> Required(format 2023-07-04)<br>
                                            <b>connection_start</b> String<br>
                                            <small>If you want to set the expdate on the first connection, enter the number of validity days in the field above</small><br>
                                            <b>desc</b> String<br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Delete user
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/delete</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Show Detail user
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    {APIKEY}=Token<br>
                                    {USERNAME}=User Account<br>
                                    <span class="pc-collapse">Method GET</span><br>
                                    <code>{{$path}}api/{APIKEY}/user/{USERNAME}</code><br>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    Edit user
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/edituser</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                            <b>password</b> Required<br>
                                            <b>email</b> String<br>
                                            <b>mobile</b> String<br>
                                            <b>multiuser</b> Required<br>
                                            <b>traffic</b> Required<br>
                                            <b>type_traffic</b> Required(gb or mb)<br>
                                            <b>activate</b> Required(active or deactive)<br>
                                            <b>expdate</b> Required(format 2023-07-04)<br>
                                            <b>desc</b> String<br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    Active user
                                </button>
                            </h2>
                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/active</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEight">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                    Dective user
                                </button>
                            </h2>
                            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/deactive</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingNine">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                    Reset Traffic user
                                </button>
                            </h2>
                            <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/retraffic</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTen">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                    Renewal Date and Expire date and traffic user
                                </button>
                            </h2>
                            <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#accordionExample">
                                <div class="accordion-body" dir="ltr">
                                    <a href="{{$path}}cp/settings/api" target="_blank">List API</a><br>
                                    <span class="pc-collapse">Method POST</span><br>
                                    <code>{{$path}}api/renewal</code><br>
                                    Send Data Post
                                    <div class="p-3 color-block bg-green-100">
                                        <code>
                                            <b>token</b> Required<br>
                                            <b>username</b> Required<br>
                                            <b>day_date</b> Required<br>
                                            <small>Credit in the form of days</small><br>
                                            <b>re_date</b> Required(yes or no)<br>
                                            <small>Update the registration date to today</small><br>
                                            <b>re_traffic</b> Required(yes or no)<br>
                                            <small>Reset the traffic</small><br>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

@endsection