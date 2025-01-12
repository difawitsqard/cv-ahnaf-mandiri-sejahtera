@extends('layouts.app')
@section('title')
    Dashboard
@endsection

@push('css')
    <script src="{{ URL::asset('build/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script>
        function formatRupiah(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endpush

@section('content')
    <x-page-title title="Dashboard" subtitle="{{ ucfirst(Auth::user()->roles[0]->name) }}" />

    <div class="card rounded-4 mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-lg-row flex-column align-items-start align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="detail-icon fs-2">
                        <i class="bi bi-house-door-fill"></i>
                    </div>
                    <div class="detail-info">
                        <h4 class="fw-bold mb-1">{{ $outlet->name }}</h4>
                        <p class="mb-0">{{ $outlet->address }}</p>
                    </div>
                </div>
                <div class="ms-auto">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 col-xxl-3 d-flex align-items-stretch">
            <div class="card w-100 overflow-hidden rounded-4">
                <div class="card-body position-relative p-4">
                    <div class="row">
                        <div class="col-12 col-sm-7">
                            <div class="d-flex align-items-center gap-3 mb-5">
                                <img src="{{ URL::asset('build/images/default-avatar.jpg') }}"
                                    class="rounded-circle bg-secondary p-1" width="60" height="60" alt="user">
                                <div class="">
                                    <p class="mb-0 fw-semibold">Selamat Datang</p>
                                    <h4 class="fw-semibold fs-4 mb-0">{{ auth()->user()->name }}</h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-5">
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center">
                                        <small class="fs-6 fw-medium me-1">Rp</small>
                                        {{ formatRupiah($todayRevenue) }}
                                    </h4>
                                    <p class="mb-3">Pedapatan Hari Ini</p>
                                </div>
                                <div class="vr"></div>
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center">
                                        <small class="fs-6 fw-medium me-1">Rp</small>
                                        {{ formatRupiah($todayExpense) }}
                                    </h4>
                                    <p class="mb-3">Pengeluaran Hari Ini</p>
                                </div>
                            </div>
                        </div>
                    </div><!--end row-->
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-xxl-3 col-xxl-2 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="">
                            <h5 class="mb-0 fw-bold" style="color: #17ad37;">
                                {{ collect($orderDailyCurrentMonth)->pluck('total_orders')->sum() }}</h5>
                            <p class="mb-0">Pesanan Selesai</p>
                        </div>
                    </div>
                    <div class="chart-container2">
                        <div id="total_orders"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var options = {
                                    series: [{
                                        name: "Total Sales",
                                        data: @json(array_values(collect($orderDailyCurrentMonth)->pluck('total_orders')->toArray()))
                                    }],
                                    chart: {
                                        height: 120,
                                        type: 'area',
                                        sparkline: {
                                            enabled: !0
                                        },
                                        zoom: {
                                            enabled: false
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        width: 1.5,
                                        curve: "smooth"
                                    },
                                    fill: {
                                        type: 'gradient',
                                        gradient: {
                                            shade: 'dark',
                                            gradientToColors: ['#17ad37'],
                                            shadeIntensity: 1,
                                            type: 'vertical',
                                            opacityFrom: 0.7,
                                            opacityTo: 0.0,
                                        },
                                    },
                                    colors: ["#17ad37"],
                                    tooltip: {
                                        theme: "dark",
                                        fixed: {
                                            enabled: false
                                        },
                                        x: {
                                            show: true,
                                            formatter: function(val, opts) {
                                                return opts.w.globals.categoryLabels[opts.dataPointIndex];
                                            }
                                        },
                                        y: {
                                            title: {
                                                formatter: function() {
                                                    return "";
                                                }
                                            },
                                            formatter: function(val, opts) {
                                                var totalRevenue = @json(array_values(collect($orderDailyCurrentMonth)->pluck('total_revenue')->toArray()));
                                                return val.toLocaleString() + " Pesanan<br>Rp " + formatRupiah(totalRevenue[opts
                                                    .dataPointIndex].toLocaleString());
                                            }
                                        },
                                        marker: {
                                            show: false
                                        }
                                    },
                                    xaxis: {
                                        categories: @json(array_keys($orderDailyCurrentMonth->toArray())),
                                        labels: {
                                            formatter: function(val) {
                                                return val;
                                            }
                                        }
                                    }
                                };

                                var chart = new ApexCharts(document.querySelector("#total_orders"), options);
                                chart.render();
                            });
                        </script>
                    </div>
                    <div class="text-center">
                        <p class="mb-0 font-12"><span class="fw-bold">{{ date('F Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-xxl-3 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="">
                            <h5 class="mb-1 fw-semibold d-flex align-content-center">
                                <small class="fs-6 fw-medium me-1">Rp</small>
                                <span style="color: #0d6efd;">
                                    {{ formatRupiah(collect($orderDailyCurrentMonth)->pluck('total_revenue')->sum()) }}
                                </span>
                            </h5>
                            <p class="mb-0">Total Pendapatan</p>
                        </div>
                    </div>
                    <div class="chart-container2">
                        <div id="total_revenue"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var options = {
                                    series: [{
                                        name: "Total Sales",
                                        data: @json(array_values(collect($orderDailyCurrentMonth)->pluck('total_revenue')->toArray()))
                                    }],
                                    chart: {
                                        height: 120,
                                        type: 'area',
                                        sparkline: {
                                            enabled: !0
                                        },
                                        zoom: {
                                            enabled: false
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        width: 1.5,
                                        curve: "smooth"
                                    },
                                    fill: {
                                        type: 'gradient',
                                        gradient: {
                                            shade: 'dark',
                                            gradientToColors: ['#0d6efd'],
                                            shadeIntensity: 1,
                                            type: 'vertical',
                                            opacityFrom: 0.7,
                                            opacityTo: 0.0,
                                        },
                                    },
                                    colors: ["#0d6efd"],
                                    tooltip: {
                                        theme: "dark",
                                        fixed: {
                                            enabled: false
                                        },
                                        x: {
                                            show: true,
                                            formatter: function(val, opts) {
                                                return opts.w.globals.categoryLabels[opts.dataPointIndex];
                                            }
                                        },
                                        y: {
                                            title: {
                                                formatter: function() {
                                                    return "";
                                                }
                                            },
                                            formatter: function(val, opts) {
                                                var totalOrder = @json(array_values(collect($orderDailyCurrentMonth)->pluck('total_orders')->toArray()));
                                                return totalOrder[opts.dataPointIndex].toLocaleString() + " Pesanan<br>Rp " +
                                                    formatRupiah(val.toLocaleString());
                                            }
                                        },
                                        marker: {
                                            show: false
                                        }
                                    },
                                    xaxis: {
                                        categories: @json(array_keys($orderDailyCurrentMonth->toArray())),
                                        labels: {
                                            formatter: function(val) {
                                                return val;
                                            }
                                        }
                                    }
                                };

                                var chart = new ApexCharts(document.querySelector("#total_revenue"), options);
                                chart.render();
                            });
                        </script>
                    </div>
                    <div class="text-center">
                        <p class="mb-0 font-12"><span class="fw-bold">{{ date('F Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-xxl-3 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="">
                            <h5 class="mb-1 fw-semibold d-flex align-content-center">
                                <small class="fs-6 fw-medium me-1">Rp</small>
                                <span style="color: #e52d27;">
                                    {{ formatRupiah(collect($expenseDailyCurrentMonth)->pluck('total_expense_amount')->sum()) }}
                                </span>
                            </h5>
                            <p class="mb-0">Total Pengeluaran</p>
                        </div>
                    </div>
                    <div class="chart-container2">
                        <div id="total_expense"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var options = {
                                    series: [{
                                        name: "Total Sales",
                                        data: @json(array_values(collect($expenseDailyCurrentMonth)->pluck('total_expense_amount')->toArray()))
                                    }],
                                    chart: {
                                        height: 120,
                                        type: 'area',
                                        sparkline: {
                                            enabled: !0
                                        },
                                        zoom: {
                                            enabled: false
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        width: 1.5,
                                        curve: "smooth"
                                    },
                                    fill: {
                                        type: 'gradient',
                                        gradient: {
                                            shade: 'dark',
                                            gradientToColors: ['#e52d27'],
                                            shadeIntensity: 1,
                                            type: 'vertical',
                                            opacityFrom: 0.7,
                                            opacityTo: 0.0,
                                        },
                                    },
                                    colors: ["#e52d27"],
                                    tooltip: {
                                        theme: "dark",
                                        fixed: {
                                            enabled: false
                                        },
                                        x: {
                                            show: true,
                                            formatter: function(val, opts) {
                                                return opts.w.globals.categoryLabels[opts.dataPointIndex];
                                            }
                                        },
                                        y: {
                                            title: {
                                                formatter: function() {
                                                    return "";
                                                }
                                            },
                                            formatter: function(val, opts) {
                                                var totalOrder = @json(array_values(collect($expenseDailyCurrentMonth)->pluck('total_expenses')->toArray()));
                                                return totalOrder[opts.dataPointIndex].toLocaleString() +
                                                    " Pengeluaran<br>Rp " + formatRupiah(val.toLocaleString());
                                            }
                                        },
                                        marker: {
                                            show: false
                                        }
                                    },
                                    xaxis: {
                                        categories: @json(array_keys($orderDailyCurrentMonth->toArray())),
                                        labels: {
                                            formatter: function(val) {
                                                return val;
                                            }
                                        }
                                    }
                                };

                                var chart = new ApexCharts(document.querySelector("#total_expense"), options);
                                chart.render();
                            });
                        </script>
                    </div>
                    <div class="text-center">
                        <p class="mb-0 font-12"><span class="fw-bold">{{ date('F Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-0">Pendapatan Bulanan <span class="fw-bold">{{ date('Y') }}</span></h6>
                    </div>
                    <div class="mt-4" id="monthlyRevenue"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            function formatRupiah(value) {
                                return "Rp. " + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }

                            var options = {
                                series: [{
                                    name: "Total Revenue",
                                    data: @json(collect($salesData)->pluck('total_revenue'))
                                }],
                                chart: {
                                    foreColor: "#9ba7b2",
                                    height: 280,
                                    type: 'bar',
                                    toolbar: {
                                        show: false
                                    },
                                    sparkline: {
                                        enabled: false
                                    },
                                    zoom: {
                                        enabled: false
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    width: 1,
                                    curve: 'smooth'
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        borderRadius: 4,
                                        borderRadiusApplication: 'around',
                                        borderRadiusWhenStacked: 'last',
                                        columnWidth: '45%',
                                    }
                                },
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shade: 'dark',
                                        gradientToColors: ['#17ad37'],
                                        shadeIntensity: 1,
                                        type: 'vertical',
                                        opacityFrom: 1,
                                        opacityTo: 1,
                                        stops: [0, 100, 100, 100]
                                    },
                                },
                                colors: ["#17ad37"],
                                grid: {
                                    show: true,
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                },
                                xaxis: {
                                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                                        'Dec'
                                    ],
                                },
                                tooltip: {
                                    theme: "dark",
                                    x: {
                                        show: true,
                                        formatter: function(val, opts) {
                                            return opts.w.globals.labels[opts.dataPointIndex];
                                        }
                                    },
                                    y: {
                                        title: {
                                            formatter: function() {
                                                return "";
                                            }
                                        },
                                        formatter: function(val) {
                                            return formatRupiah(val);
                                        }
                                    },
                                    marker: {
                                        show: false
                                    }
                                },
                            };

                            var chart = new ApexCharts(document.querySelector("#monthlyRevenue"), options);
                            chart.render();
                        });
                    </script>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="text-center">
                        <h6 class="mb-0">Pengeluaran Bulanan <span class="fw-bold">{{ date('Y') }}</span></h6>
                    </div>
                    <div class="mt-4" id="monthlyExpenses"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            function formatRupiah(value) {
                                return "Rp. " + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }

                            var options = {
                                series: [{
                                    name: "Total Expenses",
                                    data: @json(collect($expenseData)->pluck('total_amount'))
                                }],
                                chart: {
                                    foreColor: "#9ba7b2",
                                    height: 280,
                                    type: 'bar',
                                    toolbar: {
                                        show: false
                                    },
                                    sparkline: {
                                        enabled: false
                                    },
                                    zoom: {
                                        enabled: false
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    width: 1,
                                    curve: 'smooth'
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        borderRadius: 4,
                                        borderRadiusApplication: 'around',
                                        borderRadiusWhenStacked: 'last',
                                        columnWidth: '45%',
                                    }
                                },
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shade: 'dark',
                                        gradientToColors: ['#db0000'],
                                        shadeIntensity: 1,
                                        type: 'vertical',
                                        opacityFrom: 1,
                                        opacityTo: 1,
                                        stops: [0, 100, 100, 100]
                                    },
                                },
                                colors: ["#db0000"],
                                grid: {
                                    show: true,
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                },
                                xaxis: {
                                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                                        'Dec'
                                    ],
                                },
                                tooltip: {
                                    theme: "dark",
                                    x: {
                                        show: true,
                                        formatter: function(val, opts) {
                                            return opts.w.globals.labels[opts.dataPointIndex];
                                        }
                                    },
                                    y: {
                                        title: {
                                            formatter: function() {
                                                return "";
                                            }
                                        },
                                        formatter: function(val) {
                                            return formatRupiah(val);
                                        }
                                    },
                                    marker: {
                                        show: false
                                    }
                                },
                            };

                            var chart = new ApexCharts(document.querySelector("#monthlyExpenses"), options);
                            chart.render();
                        });
                    </script>
                </div>
            </div>
        </div> --}}


        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="">
                            <h5 class="mb-0">Pendapatan & Pengeluaran 13 Bulan Terakhir</h5>
                        </div>
                    </div>
                    <div id="revenue_expenses"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {

                            var options = {
                                series: [{
                                        name: "Pendapatan",
                                        data: @json(collect($revenueAndExpenseLast13M)->pluck('total_revenue'))
                                    },
                                    {
                                        name: "Pengeluaran",
                                        data: @json(collect($revenueAndExpenseLast13M)->pluck('total_expense_amount'))
                                    }
                                ],
                                chart: {
                                    foreColor: "#9ba7b2",
                                    height: 235,
                                    type: 'bar',
                                    toolbar: {
                                        show: false,
                                    },
                                    sparkline: {
                                        enabled: false
                                    },
                                    zoom: {
                                        enabled: false
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    width: 4,
                                    curve: 'smooth',
                                    colors: ['transparent']
                                },
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shade: 'dark',
                                        gradientToColors: ['#0575E6', '#e52d27'],
                                        shadeIntensity: 1,
                                        type: 'vertical',
                                        stops: [0, 100, 100, 100]
                                    },
                                },
                                colors: ['#021B79', "#b31217"],
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        borderRadius: 4,
                                        borderRadiusApplication: 'around',
                                        borderRadiusWhenStacked: 'last',
                                        columnWidth: '55%',
                                    }
                                },
                                grid: {
                                    show: false,
                                    borderColor: 'rgba(0, 0, 0, 0.15)',
                                    strokeDashArray: 4,
                                },
                                tooltip: {
                                    theme: "dark",
                                    fixed: {
                                        enabled: true
                                    },
                                    x: {
                                        show: true
                                    },
                                    y: {
                                        title: {
                                            formatter: function(seriesName) {
                                                return seriesName + ": ";
                                            }
                                        },
                                        formatter: function(val) {
                                            return "Rp " + formatRupiah(val);
                                        }
                                    },
                                    marker: {
                                        show: false
                                    }
                                },
                                xaxis: {
                                    categories: @json(collect($revenueAndExpenseLast13M)->pluck('month_name')),
                                }
                            };

                            var chart = new ApexCharts(document.querySelector("#revenue_expenses"), options);
                            chart.render();
                        });
                    </script>
                    <div
                        class="d-flex flex-column flex-lg-row align-items-start justify-content-around border p-3 rounded-4 mt-3 gap-3">

                        <div class="d-flex align-items-center gap-2">
                            @php
                                $maxRevenue = collect($revenueAndExpenseLast13M)->max('total_revenue');
                                $maxRevenueMonth = collect($revenueAndExpenseLast13M)->firstWhere(
                                    'total_revenue',
                                    $maxRevenue,
                                )['month_name'];

                            @endphp
                            <div class="">
                                <p class="mb-1 fs-6 fw-bold">Pendapatan Tertinggi</p>
                                <h2 class="mb-0 d-flex align-content-center">
                                    <small class="fs-6 fw-medium me-1">Rp</small>
                                    {{ formatRupiah($maxRevenue) }}
                                </h2>
                                <p class="mb-0"><span class="me-2 fw-medium text-success">{{ $maxRevenueMonth }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="vr d-none d-sm-block"></div>
                        <div class="d-flex align-items-center gap-2">
                            @php
                                $maxExpense = collect($revenueAndExpenseLast13M)->max('total_expense_amount');
                                $maxExpenseMonth = collect($revenueAndExpenseLast13M)->firstWhere(
                                    'total_expense_amount',
                                    $maxExpense,
                                )['month_name'];
                            @endphp
                            <div class="">
                                <p class="mb-1 fs-6 fw-bold">Pengeluaran Tertinggi</p>
                                <h2 class="mb-0 d-flex align-content-center">
                                    <small class="fs-6 fw-medium me-1">Rp</small>
                                    {{ formatRupiah($maxExpense) }}
                                </h2>
                                <p class="mb-0"><span class="me-2 fw-medium text-danger">{{ $maxExpenseMonth }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
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
        </div> --}}

    </div>
@endsection
