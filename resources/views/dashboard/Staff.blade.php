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

        <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
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
                                    <p class="mb-3">Penjualan Saya Hari Ini</p>
                                </div>
                                <div class="vr"></div>
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center">
                                        <small class="fs-6 fw-medium me-1">Rp</small>
                                        {{ formatRupiah($todayExpense) }}
                                    </h4>
                                    <p class="mb-3">Pengeluaran Saya Hari Ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-xxl-2 col-xxl-2 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="">
                            <h5 class="mb-0 fw-bold" style="color: #17ad37;">
                                {{ collect($orderDailyCurrentMonth)->pluck('total_orders')->sum() }}</h5>
                            <p class="mb-0">Pesanan Selesai Saya</p>
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
                            <p class="mb-0">Total Penjualan Saya</p>
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
                            <p class="mb-0">Total Pengeluaran Saya</p>
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

    </div>
@endsection
