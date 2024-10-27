@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection

@section('content')
    <x-page-title title="Stock" subtitle="Manajemen Stock" />


    <div class="card mb-3">
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
                <div class="overflow-auto">
                    <div class="btn-group position-static">
                        <div class="btn-group position-static">
                            <a type="button" href="{{ route('outlet.stock-item.edit', ['outlet' => $outlet->slug, 'stock_item' => $stockItem->id]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil-square me-2"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="card">
                <img src="{{ $stockItem->image_url }}" style="width: 100%; height: 179px; object-fit: cover;"
                    class="card-img-top" id="image-preview" alt="">
                <div class="card-body">
                    <h5 class="mb-3">{{ $stockItem->name }}</h5>
                    <p>{{ $stockItem->description }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-9">

            <div class="card w-100">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Detail Data Item</h5>
                    <div class="product-table">
                        <table class="table table-striped">
                            <tr>
                                <td width="120">ID</td>
                                <td width="10">:</td>
                                <td>#{{ $stockItem->id }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $stockItem->name }}</td>
                            </tr>
                            <tr>
                                <td>Deskripsi</td>
                                <td>:</td>
                                <td>{{ $stockItem->description }}</td>
                            </tr>
                            <tr>
                                <td>Stok</td>
                                <td>:</td>
                                <td>{{ $stockItem->stock }}</td>
                            </tr>
                            <tr>
                                <td>Stok Minimum</td>
                                <td>:</td>
                                <td>{{ $stockItem->min_stock }}</td>
                            </tr>
                            <tr>
                                <td>Satuan</td>
                                <td>:</td>
                                <td>{{ $stockItem->unit->name }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal -->
@push('modals')
@endpush

@push('script')
@endpush
