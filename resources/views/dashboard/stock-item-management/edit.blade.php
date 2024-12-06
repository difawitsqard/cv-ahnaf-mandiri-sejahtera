@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/quill/quill.bubble.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">
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
                        <label class="picture-input-costum" for="image" style="width: 100%; height: 220px;">
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
                            @if ($stockItem->image_path) data-image-src="{{ $stockItem->image_url }}" 
                            data-image-id="{{ $stockItem->id }}" @endif>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <div class="mb-3">
                                    <h6 class="mb-2">Nama Item <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $stockItem->name }}" placeholder="..." required>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Deskripsi Item</h6>
                                    <div class="quill-description quill-input form-control" data-placeholder="...">
                                        {!! old('description') ?? ($stockItem->description ?? '') !!}
                                    </div>
                                    <input type="hidden" name="description" id="description">
                                </div>
                                <div class="mb-3">
                                    <x-category-item-select :selectedCategory="$stockItem->category_id" />
                                </div>
                            </div>

                            <div class="col-12 col-lg-8 mb-3">

                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-lg-6">
                                        <h6 class="mb-2">Minimal Stok
                                            <i class="bi bi-question-circle-fill text-info" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Jumlah minimum stok yang harus tersedia."></i>
                                        </h6>
                                        <input class="form-control text-danger" type="number" id="min_stock"
                                            name="min_stock" placeholder="Default '0'" value="{{ $stockItem->min_stock }}">
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <x-unit-select :selectedUnit="$stockItem->unit_id"  />

                                    </div>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <x-restock-item-section :outlet="$outlet" :stockItem="$stockItem" />
                                    </div>
                                </div>

                                <div class="row">
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
    <script src="{{ URL::asset('build/plugins/quill/quill.js') }}"></script>
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

            //quill editor
            var quill = new Quill('.quill-description', {
                theme: 'bubble',
                placeholder: $('.quill-description').data('placeholder'),
                modules: {
                    toolbar: [
                        // [{ 'header': [1, 2, false] }],
                        [{
                            'header': 1
                        }, {
                            'header': 2
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['blockquote'],
                        ['link']
                    ]
                },
            });
            $('.quill-description .ql-editor').css('min-height', '100px');
            $('form').on('submit', function() {
                $('[name="description"]').val(quill.root.innerHTML);
            });
        });
    </script>
@endpush
