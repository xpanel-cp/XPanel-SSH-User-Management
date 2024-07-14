@extends('layouts.master')
@section('title','XPanel - '.__('dashboard-title'))
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{__('dashboard-title')}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                @if(env('APP_LOCALE') == 'fa')
                    @php
                        $json = file_get_contents('https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/version.json');
                        $obj = json_decode($json);
                        $github='https://github.com/xpanel-cp/XPanel-SSH-User-Management/blob/master/README.md#installation-guide';
                    @endphp
                @elseif(env('APP_LOCALE') == 'en')
                    @php
                        $json = file_get_contents('https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/version.json');
                        $obj = json_decode($json);
                        $github='https://github.com/xpanel-cp/XPanel-SSH-User-Management/blob/master/README-EN.md#installation-guide';
                    @endphp
                @elseif(env('APP_LOCALE') == 'ru')
                    @php
                        $json = file_get_contents('https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/version.json');
                        $obj = json_decode($json);
                        $github='https://github.com/xpanel-cp/XPanel-SSH-User-Management/blob/master/README-RU.md#installation-guide';
                    @endphp
                @endif
                @if($obj->last_version>399)
                    <div class="alert alert-success" role="alert" style="color: #2b2f32;">{{__('alert-update')}} <a href="{!! $github !!}" target="_blank">Github</a> </div>
                @endif
                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="my-n4" style="width: 130px">
                                        <div id="total-earning-graph-cpu"></div>
                                    </div>
                                    <br>
                                    <h6 class="mb-1">{{__('dashboard-cpu')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="my-n4" style="width: 130px">
                                        <div id="total-earning-graph-ram"></div>
                                    </div>
                                    <br>
                                    <h6 class="mb-1">{{__('dashboard-ram')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="my-n4" style="width: 130px">
                                        <div id="total-earning-graph-hard"></div>
                                    </div>
                                    <br>
                                    <h6 class="mb-1">{{__('dashboard-disk')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="my-n4" style="width: 130px">
                                        <h5 style="margin-top: 10px; text-align: center;"><small>{{__('dashboard-server')}}</small><br>{{$total}}</h5>
                                        <h5 style="margin-top: 10px; text-align: center;"><small>{{__('dashboard-client')}}</small><br>{{$traffic_total}}</h5>
                                    </div>
                                    <br>
                                    <br>
                                    <h6 class="mb-1">{{__('dashboard-bandwidth')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xxl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="my-3">
                                <div id="overview-product-graph"></div>
                            </div>
                            <div class="row g-3 text-center">
                                <div class="col-6 col-lg-3 col-xxl-3">
                                    <a href="{{ route('users.sort', ['status' => 'active']) }}">
                                        <div class="overview-product-legends">
                                            <p class="text-dark mb-1"><i class="ti ti-filter"></i> <span>{{__('dashboard-active-user')}}</span></p>
                                            <h6 class="mb-0">{{$active_user}}</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-lg-3 col-xxl-3">
                                    <a href="{{ route('users.sort', ['status' => 'expired']) }}">
                                        <div class="overview-product-legends">
                                            <p class="text-dark mb-1"><i class="ti ti-filter"></i> <span>{{__('dashboard-expired-user')}}</span></p>
                                            <h6 class="mb-0">{{$expired_user}}</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-lg-3 col-xxl-3">
                                    <a href="{{ route('users.sort', ['status' => 'traffic']) }}">
                                        <div class="overview-product-legends">
                                            <p class="text-dark mb-1"><i class="ti ti-filter"></i> <span>{{__('dashboard-traffic-user')}}</span></p>
                                            <h6 class="mb-0">{{$traffic_user}}</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-lg-3 col-xxl-3">
                                    <a href="{{ route('users.sort', ['status' => 'deactive']) }}">
                                        <div class="overview-product-legends">
                                            <p class="text-dark mb-1"><i class="ti ti-filter"></i> <span>{{__('dashboard-deactive-user')}}</span></p>
                                            <h6 class="mb-0">{{$deactive_user}}</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-12 col-lg-12 col-xxl-12">
                                    <div class="overview-product-legends">
                                        <p class="text-secondary mb-1"><span>{{__('dashboard-online-user')}}</span></p>
                                        <h6 class="mb-0">{{$online_user}}</h6>
                                    </div>
                                </div>
                                <h6>{{__('dashboard-all-user')}}: {{$alluser}}</h6>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card table-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5>{{__('dashboard-high-usage')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{__('user-table-username')}}</th>
                                        <th>{{__('user-table-traffic')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($high_usages as $val)

                                        @php
                                            $trafficValue = floatval($val->total);
                                            $total = round($trafficValue / 1024, 3) . ' GB';  @endphp

                                        <tr>
                                            <td>{{$val->username}}</td>
                                            <td>{{$total}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <script>
        'use strict';
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                floatchart();
            }, 500);
        });

        function floatchart() {
            (function () {
                var options = {
                    chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
                    colors: ['#4680FF'],
                    plotOptions: { bar: { columnWidth: '80%' } },
                    series: [
                        {
                            data: [
                                10, 30, 40, 20, 60, 50, 20, 15, 20, 25, 30, 25
                            ]
                        }
                    ],
                    xaxis: { crosshairs: { width: 1 } },
                    tooltip: {
                        fixed: { enabled: false },
                        x: { show: false },
                        y: {
                            title: {
                                formatter: function (seriesName) {
                                    return '';
                                }
                            }
                        },
                        marker: { show: false }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-1"), options);
                chart.render();
            })();
            (function () {
                var options = {
                    series: [{{$cpu_free}}],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '60%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '#DC262650',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '#DC2626',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['#DC2626'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-cpu"), options);
                chart.render();
            })();
            (function () {
                var options = {
                    series: [{{$ram_free}}],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '60%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '#DC262650',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '#DC2626',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['#DC2626'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-ram"), options);
                chart.render();
            })();
            (function () {
                var options = {
                    series: [{{$disk_free}}],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '60%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '#DC262650',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '#DC2626',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['#DC2626'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-hard"), options);
                chart.render();
            })();
            (function () {
                var options = {
                    series: [45],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '60%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '#DC262650',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '#DC2626',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['#DC2626'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-band"), options);
                chart.render();
            })();
            (function () {
                var options = {
                    series: [30],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '60%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '#DC262650',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '#DC2626',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['#DC2626'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-2"), options);
                chart.render();
            })();
        }

    </script>
@endsection
