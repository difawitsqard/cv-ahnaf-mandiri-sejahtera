@extends('layouts.guest')
@section('title')
    {{ __('Outlet') }}
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container px-4 py-5">
        {{-- <div class="row g-3">
        <div class="col-auto">
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle me-1" type="button" id="dropdownPerPage"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        {{ $_outletRows->total() < 30 ? 'disabled' : '' }}>
                        {{ $_outletRows->perPage() }} entri per halaman
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownPerPage">
                        @php
                            $perPageOptions = [10, 20, 50, 100]; // Opsi jumlah entri per halaman
                        @endphp
                        @foreach ($perPageOptions as $page)
                            @if ($_outletRows->perPage() == $page || $_outletRows->perPage() == null || $_outletRows->total() < $page)
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
    </div> --}}

        <div class="row">
            <div class="col-12 d-flex align-items-stretch">
                <div class="card w-100 rounded-4 mb-3">
                    <div class="card-body position-relative p-4">
                        <div class="row">
                            <div class="col-12 col-md-auto">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('build/images/default-avatar.jpg') }}"
                                        class="rounded-circle bg-secondary p-1" width="60" height="60"
                                        alt="{{ auth()->user()->name }}">
                                    <div class="">
                                        <p class="mb-0 fw-semibold">Selamat Datang</p>
                                        <h4 class="fw-semibold fs-4 mb-0">{{ auth()->user()->name }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-auto ms-auto text-end d-none d-md-block">
                                <div class="d-flex align-items-center justify-content-end gap-2 me-4">
                                    <div class="text-end">
                                        <p class="mb-1 fs-7">Total Outlet</p>
                                        <h3 class="mb-0 d-flex align-content-center justify-content-end fw-bold">
                                            {{ $totalOutlets }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown position-absolute top-0 end-0 mt-3 me-3">
                            <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <span class="material-icons-outlined fs-5">more_vert</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex align-items-stretch">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="">
                                <h5 class="mb-0">Daftar Outlet</h5>
                            </div>
                            <div class="overflow-auto">
                                <button class="btn btn-inverse-primary btn-sm px-4 add-button" data-bs-toggle="modal"
                                    data-bs-target="#MyModal" data-add-url="{{ route('outlet.store') }}"><i
                                        class="bi bi-plus-lg me-2"></i>Outlet
                                    Baru</button>
                            </div>
                        </div>

                        <form action="{{ request()->fullUrlWithoutQuery(['page']) }}"
                            class="order-search position-relative my-3 mb-4" method="GET">
                            <div class="input-group rounded-5">
                                <input type="text" class="form-control rounded-end rounded-5" name="search"
                                    placeholder="Cari..." value="{{ request()->get('search', '') }}">
                                <button type="submit" class="btn btn-primary rounded-start rounded-5"><i
                                        class="bi bi-search"></i></button>
                            </div>
                        </form>

                        @if (session('success'))
                            <x-alert-message type="success" :messages="session('success')" />
                        @endif

                        @if ($errors->any())
                            <x-alert-message type="danger" :messages="$errors->all()" />
                        @endif

                        @if (!session('success') && $errors->isEmpty())
                            @if ($Outlets->isEmpty() && request()->get('search'))
                                <div class="w-100 p-4 align-items-center text-center">
                                    <h5>Pencarian '{{ request()->get('search', '') }}' tidak ditemukan.</h5>
                                </div>
                            @elseif ($Outlets->isEmpty())
                                <div class="alert alert-warning border-0 bg-grd-warning alert-dismissible fade show">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-dark"><span
                                                class="material-icons-outlined fs-2">info</span>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-dark">Hei, {{ auth()->user()->name }}</h6>
                                            <div class="text-dark">Belum ada outlet yang tersedia. Untuk memulai, silakan
                                                buat outlet baru.</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @elseif (!request()->get('search'))
                                <div class="alert alert-info border-0 bg-grd-info alert-dismissible fade show">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-white"><span
                                                class="material-icons-outlined fs-2">info</span>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-white">Hei, {{ auth()->user()->name }}</h6>
                                            <div class="text-white">Untuk melanjutkan silakan pilih
                                                outlet.</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        @endif

                        <div class="row row-cols-1 row-cols-xl-3">
                            @foreach ($Outlets as $_outletRow)
                                <div class="col d-lg-flex align-items-lg-stretch">
                                    <div class="card rounded-4 flex-grow-1 position-relative border"
                                        style="cursor: pointer; transition: background-color 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='#f8f9fa'"
                                        onmouseout="this.style.backgroundColor=''">
                                        <a href="{{ route('outlet.dashboard', ['outlet' => $_outletRow->slug]) }}"
                                            class="stretched-link"></a>
                                        <div class="row g-0 align-items-center h-100">
                                            <div class="col-md-4 border-end h-100">
                                                <div class="p-0 align-self-center h-100">
                                                    <img src="{{ $_outletRow['image_url'] }}"
                                                        style="width: 80px; height: 100%; object-fit: cover;"
                                                        class="w-100 rounded-start-4" alt="{{ $_outletRow->name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-8 h-100">
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <div class="d-flex align-items-start justify-content-between">
                                                        <h5 class="card-title fw-bold">{{ $_outletRow['name'] }}</h5>
                                                        <div class="dropdown">
                                                            <a href="javascript:;"
                                                                class="dropdown-toggle-nocaret options dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                style="position: relative; z-index: 1;">
                                                                <span class="material-icons-outlined fs-5">more_vert</span>
                                                            </a>
                                                            <ul class="dropdown-menu"
                                                                style="position: absolute; top: 100%; right: 0; z-index: 1000;">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('outlet.dashboard', ['outlet' => $_outletRow->slug]) }}">Dashboard</a>
                                                                </li>
                                                                <li><button class="dropdown-item edit-button"
                                                                        data-bs-toggle="modal" data-bs-target="#MyModal"
                                                                        data-add-url="{{ route('outlet.store') }}"
                                                                        data-id="{{ $_outletRow->slug }}">Edit</button>
                                                                </li>
                                                                <hr class="dropdown-divider">
                                                                <li>
                                                                    <form id="delete-form-{{ $_outletRow->id }}"
                                                                        action="{{ route('outlet.destroy', ['outlet' => $_outletRow->slug]) }}"
                                                                        method="POST" style="display: none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger"
                                                                        data-id="{{ $_outletRow->id }}"
                                                                        data-msg="{{ $_outletRow->name }}"
                                                                        onclick="confirmDelete(this)">Hapus</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="card-text">{{ $_outletRow['address'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                        {{ $Outlets->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

<!-- Modal -->
@push('modals')
    <div class="modal fade" id="MyModal" tabindex="-1" aria-labelledby="MyModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MyModalLabel">Tambah</h5>
                    <button type="button" class="btn-close" data-add-url="{{ route('outlet.store') }}"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('outlet.store') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body row">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="itemId" name="id">

                        <div class="col-12 col-lg-4 mb-3">
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
                                accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp, image/bmp, image/gif, image/tiff, image/x-icon">
                        </div>

                        <div class="col-12 col-lg-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Outlet <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="..." required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Outlet <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" placeholder="..." rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nomor Telepon Outlet</label>
                                <input type="number" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="628XX/08XX">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="img-container"
                        style="border: 1px #111; width: 100%; height: 0; padding-bottom: 100%;  position: relative; overflow: hidden;">
                        <img class="modal-crop-canvas" src=""
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                            alt="Picture">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary d-flex" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary d-flex" id="rotateImageModal"><span
                            class="material-icons-outlined">refresh</span></button>
                    <button type="button" class="btn btn-primary d-flex" id="cropImageModal"><span
                            class="material-icons-outlined">crop</span></button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script src="{{ URL::asset('build/plugins/cropperjs/js/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/picture-input-costum.js?v=') . md5(time()) }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var Modal = document.querySelector('#MyModal');
            var modalForm = Modal.querySelector('form');
            var inputs = modalForm.querySelectorAll('input, textarea');
            var methodInput = modalForm.querySelector('input[name="_method"]');
            var itemIdInput = modalForm.querySelector('input[name="id"]');
            var nameInput = modalForm.querySelector('input[name="name"]');
            var addressTextarea = modalForm.querySelector('textarea[name="address"]');
            var phone_number = modalForm.querySelector('input[name="phone_number"]');
            var submitButton = modalForm.querySelector('button[type="submit"]');
            var imgPreview = modalForm.querySelector('#image-preview');

            let imageUploaderInstance = new ImageUploader({
                imageWidth: 400,
                imageHeight: 500,
                cropRatio: 4 / 5,
            });

            // Function to set all fields to read-only & Disabled
            function setFieldsReadOnly(isReadOnly) {
                inputs.forEach(function(input) {
                    input.readOnly = isReadOnly;
                    input.disabled = isReadOnly;
                });
            }

            document.querySelectorAll('.add-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    submitButton.style.display = 'block';
                    modalForm.querySelector('#image').removeAttribute('data-image-src');
                    modalForm.querySelector('#image').removeAttribute('data-image-id');
                    setFieldsReadOnly(false);
                    methodInput.value = 'POST';
                    Modal.querySelector('#MyModalLabel').textContent = 'Tambah Outlet';
                    modalForm.action = button.getAttribute('data-add-url');
                    modalForm.reset();
                    itemIdInput.value = '';
                });
            });

            document.querySelectorAll('.edit-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var isEdit = button.classList.contains('edit-button');
                    var itemId = button.getAttribute('data-id');

                    modalForm.reset();

                    if (isEdit) {
                        modalForm.action =
                            `{{ route('outlet.index') }}/${itemId}`;
                        methodInput.value = 'PUT';
                        submitButton.style.display = 'block';
                        setFieldsReadOnly(false);
                    } else {
                        methodInput.value = '';
                        modalForm.action = '';
                        submitButton.style.display = 'none';
                        setFieldsReadOnly(true);
                    }

                    Modal.querySelector('#MyModalLabel').textContent = isEdit ? 'Edit Outlet' :
                        'Detail';

                    fetch(`/outlet/${itemId}/fetch`)
                        .then(response => response.json())
                        .then(response => {
                            if (response.status) {
                                nameInput.value = response.data.name;
                                addressTextarea.value = response.data.address;
                                phone_number.value = response.data.phone_number;

                                if (isEdit) {
                                    if (response.data.image_path) {
                                        modalForm.querySelector('#image').setAttribute(
                                            'data-image-src', response.data.image_url);
                                        modalForm.querySelector('#image').setAttribute(
                                            'data-image-id', response.data.id);
                                    } else {
                                        modalForm.querySelector('#image').removeAttribute('data-image-src');
                                        modalForm.querySelector('#image').removeAttribute('data-image-id');
                                    }

                                    imageUploaderInstance.updateImagePreview();
                                }
                            } else {
                                console.error('Error:', 'Gagal memuat data.');
                            }
                        });

                });
            });
        });

        function confirmDelete(button) {
            const stockItemId = button.getAttribute('data-id');
            const stockItemMsg = button.getAttribute('data-msg');

            Swal.fire({
                title: 'Apa kamu yakin?',
                html: `Ingin menghapus Outlet <b>${stockItemMsg}</b><br/><small class="text-danger">Semua data yang terkait dengan outlet ini akan ikut dihapus. Data yang dihapus tidak dapat dikembalikan !!!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#fc185a',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + stockItemId).submit();
                }
            })
        }
    </script>
@endpush
