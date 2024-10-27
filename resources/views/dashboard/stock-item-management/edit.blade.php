@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/picture-input-costum.css') }}" rel="stylesheet">
@endpush

@section('content')
    <x-page-title title="Stock" subtitle="Manajemen Stock" />

    <form action="{{ roleBasedRoute('stock-item.update', ['outlet' => $outlet->slug, 'stock_item' => $stockItem->id]) }}"
        method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

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
                        <div class="btn-group position-static">
                            <div class="btn-group position-static">
                                <button type="reset" class="btn btn-outline-primary">
                                    <i class="bi bi-eraser me-2"></i>Reset
                                </button>
                            </div>
                            <div class="btn-group position-static">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-send me-2"></i>Submit
                                </button>
                            </div>

                        </div>
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

        <div class="row">

            <div class="col-12 col-lg-3">

                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-2">Gambar Item</h6>
                        <label class="picture-input-costum" for="image" style="width: 100%; height: 190px;">
                            <span class="picture__image"></span>
                            <span class="picture__text"><span
                                    class="material-icons-outlined">add_photo_alternate</span></span>
                            <div class="picture__buttons">
                                <button type="button" class="btn btn-light btn-sm d-flex crop-btn"><span
                                        class="material-icons-outlined">crop</span></button>
                                <button type="button" class="btn btn-light btn-sm d-flex delete-btn"><span
                                        class="material-icons-outlined">delete</span></button>
                            </div>
                        </label>
                        <input type="file" name="image" class="picture__input" id="image" 
                        @if ($stockItem->image_path)
                            data-image-src="{{ $stockItem->image_url }}" 
                            data-image-id="{{ $stockItem->id }}"
                        @endif>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="mb-3">
                                    <h6 class="mb-2">Nama Item <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $stockItem->name }}" placeholder="..." required>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Deskripsi Item</h6>
                                    <textarea class="form-control" id="description" name="description" cols="4" rows="3" placeholder="...">{{ $stockItem->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Minimal Stok
                                        <i class="bi bi-question-circle-fill text-info" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Jumlah minimum stok yang harus tersedia."></i>
                                    </h6>
                                    <div class="input-group">
                                        <span class="input-group-text text-danger"><i
                                                class="material-icons-outlined fs-5">priority_high</i></span>
                                        <input type="number" class="form-control" id="min_stock" name="min_stock"
                                            placeholder="Default '0'" value="{{ $stockItem->min_stock }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <x-unit-select :selectedUnit="$stockItem->unit_id" />
                                </div>
                            </div>

                            <div class="col-12 col-lg-8 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <x-restock-item-section :outlet="$outlet" :stockItem="$stockItem" />
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12 col-lg-12">
                                        <h6 class="mb-2">Harga</h6>
                                        <div class="input-group">
                                            <span class="input-group-text">IDR</span>
                                            <input type="text" class="form-control" id="price" name="price"
                                                placeholder="default '0'" value="{{ $stockItem->price }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

<!-- Modal -->
@push('modals')
@endpush


@push('script')
    <script src="{{ URL::asset('build/plugins/cropperjs/js/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/picture-input-costum.js?v=') . md5(time()) }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');

            // Format saat halaman dimuat
            formatRupiahElement(priceInput);

            // Tambahkan event listener untuk memformat saat input berubah
            priceInput.addEventListener('input', function(e) {
                formatRupiahElement(e.target);
            });

            new ImageUploader({
                imageWidth: 400,
                imageHeight: 225,
                cropRatio: 16 / 9,
            });
        });
    </script>
@endpush