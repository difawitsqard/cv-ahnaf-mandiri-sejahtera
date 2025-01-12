@extends('layouts.app')
@section('title')
    {{ __('Pesanan') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <x-page-title title="Pesanan" subtitle="Riwayat Pesanan" />

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
                    <a class="btn btn-primary px-4 add-button"
                        href="{{ roleBasedRoute('order.create', ['outlet' => $outlet->slug]) }}"><i
                            class="bi bi-bag-plus me-2"></i>Pesanan Baru</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <x-alert-message type="success" :messages="session('success')" />
    @endif


    @if ($errors->any())
        <x-alert-message type="danger" :messages="$errors->all()" />
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-auto">
                    <div class="input-group">
                        <select class="form-select" id="table-order-length">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                        <label class="input-group-text" for="table-order-length">Entri per halaman</label>
                    </div>
                </div>
                <div class="col-12 col-md-auto ms-auto">
                    <div class="position-relative mb-3">
                        <input class="form-control px-5" type="search" id="table-order-search" placeholder="Cari...">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
            </div>

            <div class="customer-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle" id="table-order">
                        <thead class="bg-light">
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Oleh</th>
                                <th>Total Produk</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Waktu</th>
                                <th class="no-export">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ roleBasedRoute('order.show', ['outlet' => $outlet->slug, 'order' => $order->id]) }}">#{{ $order->id }}</a>
                                    </td>
                                    <td>{{ $order->user->name }}</td>
                                    <td><b>{{ $order->items->sum('quantity') }}</b> Produk</td>
                                    <td>{{ formatRupiah($order->total) }}</td>
                                    <td>
                                        @php
                                            $status = $order->status;

                                            switch ($status) {
                                                case 'pending':
                                                    $color = 'warning';
                                                    $status = 'Menunggu';
                                                    break;
                                                case 'completed':
                                                    $color = 'success';
                                                    $status = 'Sukses';
                                                    break;
                                                case 'canceled':
                                                    $color = 'danger';
                                                    $status = 'Dibatalkan';
                                                    break;

                                                default:
                                                    $color = 'secondary';
                                                    break;
                                            }
                                        @endphp

                                        <span
                                            class="lable-table bg-{{ $color }}-subtle text-{{ $color }} rounded border border-{{ $color }}-subtle font-text2 fw-bold">{{ $status }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y h:i') }}</td>
                                    <td class="no-export">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a type="button" class="dropdown-item edit-button"
                                                        href="{{ roleBasedRoute('order.show', ['outlet' => $outlet->slug, 'order' => $order->id]) }}">
                                                        Detail
                                                    </a>
                                                </li>
                                                {{-- <hr class="dropdown-divider"> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('modals')
@endpush

@push('script')
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#table-order').DataTable({
                lengthChange: false,
                lengthMenu: [10, 25, 50, 100, -1],
                order: [
                    [0, 'desc']
                ]
            });

            $('#table-order-length').on('change', function() {
                var selectedValue = $(this).val();
                table.page.len(selectedValue).draw();
            });

            // Hide default search box
            $('.dataTables_filter').hide();

            $('#table-order-search').on('input', function() {
                table.search($(this).val()).draw();
            });
        });
    </script>
@endpush
