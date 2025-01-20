@extends('layouts.app')
@section('title')
    {{ __('Etalase') }}
@endsection

@section('content')
    <x-page-title title="Etalase" subtitle="Detail Item" />


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
                            <a type="button"
                                href="{{ roleBasedRoute('stock-item.index', ['outlet' => $outlet->slug, 'stock_item' => $stockItem->id]) }}"
                                class="btn btn-outline-primary">
                                Etalase
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="card h-100">
                <img src="{{ $stockItem->image_url }}" style="width: 100%; height: 179px; object-fit: cover;"
                    class="card-img-top" id="image-preview" alt="">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">{{ $stockItem->name }}</h5>
                    <p>{!! $stockItem->description !!}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-9">

            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Detail Data Item</h5>
                    <div class="product-table">
                        <table class="table table-striped">
                            <tr>
                                <td width="150">ID</td>
                                <td width="10">:</td>
                                <td>#{{ $stockItem->id }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $stockItem->name }}</td>
                            </tr>
                            <tr>
                                <td>Stok</td>
                                <td>:</td>
                                <td>{{ $stockItem->stock }} {{ $stockItem->unit->name }}</td>
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
                            <tr>
                                <td>Kategori</td>
                                <td>:</td>
                                <td>
                                    @if ($stockItem['category']->is_static)
                                        <span
                                            class="lable-table bg-info-subtle text-info rounded border border-info-subtle font-text2 fw-bold">{{ $stockItem['category']->name }}</span>
                                    @else
                                        <span
                                            class="lable-table bg-secondary-subtle text-secondary rounded border border-secondary-subtle font-text2 fw-bold">{{ $stockItem['category']->name }}</span>
                                    @endif
                                </td>
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
