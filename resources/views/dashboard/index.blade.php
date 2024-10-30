@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    
    <x-page-title title="Dashboard" subtitle="Analysis" />

    <div class="row">
        <div class="col-xxl-8 d-flex align-items-stretch">
            <div class="card w-100 overflow-hidden rounded-4">
                <div class="card-body position-relative p-4">
                    <div class="row">
                        <div class="col-12 col-sm-7">
                            <div class="d-flex align-items-center gap-3 mb-5">
                                <img src="https://placehold.co/110x110/png" class="rounded-circle bg-grd-info p-1"
                                    width="60" height="60" alt="user">
                                <div class="">
                                    <p class="mb-0 fw-semibold">Welcome back</p>
                                    <h4 class="fw-semibold fs-4 mb-0">{{ auth()->user()->name }}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-5">
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center">$65.4K<i
                                            class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h4>
                                    <p class="mb-3">Today's Sales</p>
                                    <div class="progress mb-0" style="height:5px;">
                                        <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="vr"></div>
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center">78.4%<i
                                            class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h4>
                                    <p class="mb-3">Growth Rate</p>
                                    <div class="progress mb-0" style="height:5px;">
                                        <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 60%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-5">
                            <div class="welcome-back-img pt-4">
                                <img src="{{ URL::asset('build/images/gallery/welcome-back-3.png') }}" height="180" alt="">
                            </div>
                        </div>
                    </div><!--end row-->
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-1">
                        <div class="">
                            <h5 class="mb-0">42.5K</h5>
                            <p class="mb-0">Active Users</p>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <span class="material-icons-outlined fs-5">more_vert</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart-container2">
                        <div id="chart1"></div>
                    </div>
                    <div class="text-center">
                        <p class="mb-0 font-12">24K users increased from last month</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="">
                            <h5 class="mb-0">97.4K</h5>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <span class="material-icons-outlined fs-5">more_vert</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart-container2">
                        <div id="chart2"></div>
                    </div>
                    <div class="text-center">
                        <p class="mb-0 font-12"><span class="text-success me-1">12.5%</span> from last month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/peity/jquery.peity.min.js') }}"></script>
    <script>
        $(".data-attributes span").peity("donut")
    </script>
    <script src="{{ URL::asset('build/js/dashboard1.js') }}"></script>
    <script>
        new PerfectScrollbar(".user-list")
    </script>
@endpush
