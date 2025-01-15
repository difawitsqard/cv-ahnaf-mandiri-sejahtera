@extends('layouts.app')
@section('title')
    {{ __('Outlet') }}
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <x-page-title title="Outlet" subtitle="Pengaturan Outlet" />

    <form action="@hasrole('admin'){{ route('admin.outlet.update')  }}@endhasrole @hasrole('superadmin'){{ route('outlet.update', ['outlet'=>$outlet->slug])  }}@endhasrole" method="POST" enctype="multipart/form-data">
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
            <div class="col-12 col-lg-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">

                        <div class="mb-3">
                            <h6 class="mb-2">Gambar ( Opsional )</h6>
                            <label class="picture-input-costum" for="image"
                                style="width: 100%; min-height: 80px; height:253px;">
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
                                @if ($outlet->image_path) data-image-src="{{ $outlet->image_url }}" data-image-id="{{ $outlet->id }}" @endif
                                accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp, image/bmp, image/gif, image/tiff, image/x-icon">
                        </div>

                        <h5 class="mb-2 fw-bold">{{ $outlet->name }}</h5>
                        <p>
                            {{ $outlet->address }}.
                            <br>
                            {{ $outlet->phone_number }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') ?? ($outlet->name ?? '') }}" placeholder="..." maxlength="55">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" placeholder="..." rows="5" required="">{{ old('address') ?? ($outlet->address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nomor Telepon</label>
                            <input type="number" class="form-control" id="phone_number" name="phone_number"
                                value="{{ old('phone_number') ?? ($outlet->phone_number ?? '') }}"
                                placeholder="628XX/08XX">
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">

                        <div class="alert alert-info border-0 bg-grd-info alert-dismissible fade show">
                            <div class="d-flex align-items-center">
                                <div class="font-35 text-white"><span class="material-icons-outlined fs-2">info</span>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0 text-white fw-bold">Info</h5>
                                    <div class="text-white">Pajak dan diskon akan diterapkan pada setiap pemesanan. Contoh:
                                        jika diisi 2.5, maka akan menjadi 2.5&#37;. Jika diisi 50, maka akan menjadi
                                        50&#37;.</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="mb-3">
                            <label for="tax" class="form-label">Pajak</label>
                            <input type="text" name="tax" class="form-control" id="tax"
                                value="{{ old('tax') ?? ($outlet->tax ?? '0') }}" placeholder="default 0">
                        </div>

                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="text" name="discount" class="form-control" id="discount"
                                value="{{ old('discount') ?? ($outlet->discount ?? '0') }}" placeholder="default 0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ URL::asset('build/plugins/cropperjs/js/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/picture-input-costum.js?v=') . md5(time()) }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const taxInput = document.getElementById('tax');
            const discountInput = document.getElementById('discount');

            new ImageUploader({
                imageWidth: 400,
                imageHeight: 500,
                cropRatio: 4 / 5,
            });

            taxInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
                if (parseFloat(this.value) > 100) {
                    this.value = '100';
                }
            });

            discountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
                if (parseFloat(this.value) > 100) {
                    this.value = '100';
                }
            });
        });
    </script>
@endpush
