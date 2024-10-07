@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection

@section('content')
    <x-page-title title="Stock" subtitle="Manajemen Stock" />

    <div class="row g-3">
        <div class="col-auto">
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle me-1" type="button" id="dropdownPerPage"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        {{ $stockItems->total() < 30 ? 'disabled' : '' }}>
                        {{ $stockItems->perPage() }} entri per halaman
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownPerPage">
                        @php
                            $perPageOptions = [10, 20, 50, 100]; // Opsi jumlah entri per halaman
                        @endphp
                        @foreach ($perPageOptions as $page)
                            @if ($stockItems->perPage() == $page || $stockItems->perPage() == null || $stockItems->total() < $page)
                                @continue
                            @endif
                            <a class="dropdown-item"
                                href="{{ request()->url() . '?' . http_build_query(array_merge(request()->except(['page', 'perPage']), ['perPage' => $page])) }}">{{ $page }}
                                entri per halaman</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-auto flex-grow-1 overflow-auto">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary px-4 add-button" data-bs-toggle="modal" data-bs-target="#MyModal"
                    data-add-url="{{ route('outlet.store') }}"><i class="bi bi-plus-lg me-2"></i>Tambah</button>
            </div>
        </div>
        <div class="col-auto">
            <form action="{{ request()->fullUrlWithoutQuery(['page']) }}" class="position-relative" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Cari..."
                       value="{{ request()->get('search', '') }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox">
                                </th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>kuantitas</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockItems as $num => $stockItem)
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-box">
                                                <img src="https://placehold.co/200x150/png" width="70" class="rounded-3"
                                                    alt="">
                                            </div>
                                            <div class="product-info">
                                                <span class="product-title">{{ $stockItem['item_name'] }}</span>
                                                <p class="mb-0 product-category">Category : Fashion</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>IDR {{ formatRupiah($stockItem['price']) }}</td>
                                    <td>{{ $stockItem['quantity'] }}</td>
                                    <td>{{ $stockItem['quantity'] }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Action</a></li>
                                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $stockItems->links() }}
        </div>
    </div>
@endsection
@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
