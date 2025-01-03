@extends('layouts.app')
@section('title')
    {{ __('Outlet') }}
@endsection
@section('content')
    <x-page-title title="Outlet" subtitle="Manajemen Outlet" />
    {{-- <div class="row g-3">
        <div class="col-auto">
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle me-1" type="button" id="dropdownPerPage"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        {{ $Outlets->total() < 30 ? 'disabled' : '' }}>
                        {{ $Outlets->perPage() }} entri per halaman
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownPerPage">
                        @php
                            $perPageOptions = [10, 20, 50, 100]; // Opsi jumlah entri per halaman
                        @endphp
                        @foreach ($perPageOptions as $page)
                            @if ($Outlets->perPage() == $page || $Outlets->perPage() == null || $Outlets->total() < $page)
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

    <div class="card mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-lg-row flex-column align-items-start align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <form action="{{ request()->fullUrlWithoutQuery(['page']) }}" class="position-relative" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari..."
                                value="{{ request()->get('search', '') }}">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="overflow-auto">
                    <button class="btn btn-primary px-4 add-button" data-bs-toggle="modal" data-bs-target="#MyModal"
                        data-add-url="{{ route('outlet.store') }}"><i class="bi bi-plus-lg me-2"></i>Outlet Baru</button>
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

    <div class="row row-cols-1 row-cols-xl-3">
        @foreach ($Outlets as $outlet)
            <div class="col d-lg-flex align-items-lg-stretch">
                <div class="card rounded-4 flex-grow-1 position-relative"
                    style="cursor: pointer; transition: background-color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''">
                    <a href="{{ route('outlet.dashboard', ['outlet' => $outlet->slug]) }}" class="stretched-link"></a>
                    <div class="row g-0 align-items-center h-100">
                        <div class="col-md-4 border-end h-100">
                            <div class="p-0 align-self-center h-100">
                                <img src="{{ $outlet['image_url'] }}" style="width: 80px; height: 100%; object-fit: cover;"
                                    class="w-100 rounded-start-4" alt="{{ $outlet->name }}">
                            </div>
                        </div>
                        <div class="col-md-8 h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-start justify-content-between">
                                    <h5 class="card-title">{{ $outlet['name'] }}</h5>
                                    <div class="dropdown">
                                        <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                            data-bs-toggle="dropdown" style="position: relative; z-index: 1;">
                                            <span class="material-icons-outlined fs-5">more_vert</span>
                                        </a>
                                        <ul class="dropdown-menu"
                                            style="position: absolute; top: 100%; right: 0; z-index: 1000;">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('outlet.dashboard', ['outlet' => $outlet->slug]) }}">Dashboard</a>
                                            </li>
                                            <li><button class="dropdown-item edit-button" data-bs-toggle="modal"
                                                    data-bs-target="#MyModal" data-add-url="{{ route('outlet.store') }}"
                                                    data-id="{{ $outlet->id }}">Edit</button></li>
                                            <hr class="dropdown-divider">
                                            <li>
                                                <form id="delete-form-{{ $outlet->id }}"
                                                    action="{{ route('outlet.destroy', ['outlet' => $outlet->id]) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="dropdown-item text-danger"
                                                    data-id="{{ $outlet->id }}" data-msg="{{ $outlet->name }}"
                                                    onclick="confirmDelete(this)">Hapus</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="card-text">{{ $outlet['address'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $Outlets->links() }}
    <!--end row-->
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
                    <div class="modal-body pb-1 row">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="itemId" name="id">

                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <img src="{{ asset('build/images/placeholder-image.webp') }}"
                                    style="width: 100%; height: 179px; object-fit: cover;" class="card-img-top"
                                    id="image-preview" alt="">
                                <div class="card-body">
                                    <h6 class="mb-3">Gambar</h6>
                                    <input type="file" class="form-control form-control-sm" id="image"
                                        name="image" accept="image/*">
                                </div>
                                <script>
                                    document.getElementById('image').addEventListener('change', function(e) {
                                        var file = e.target.files[0];
                                        var reader = new FileReader();
                                        reader.onloadend = function() {
                                            document.getElementById('image-preview').src = reader.result;
                                        }
                                        reader.readAsDataURL(file);
                                    });
                                </script>
                            </div>
                        </div>

                        <div class="col-12 col-lg-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Outlet <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="..." required>
                            </div>
                            <div class="mb-">
                                <label for="address" class="form-label">Alamat Outlet <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" placeholder="..." rows="6" required></textarea>
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
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var Modal = document.querySelector('#MyModal');
            var modalForm = Modal.querySelector('form');
            var inputs = modalForm.querySelectorAll('input, textarea');
            var methodInput = modalForm.querySelector('input[name="_method"]');
            var itemIdInput = modalForm.querySelector('input[name="id"]');
            var nameInput = modalForm.querySelector('input[name="name"]');
            var addressTextarea = modalForm.querySelector('textarea[name="address"]');
            var submitButton = modalForm.querySelector('button[type="submit"]');
            var imgPreview = modalForm.querySelector('#image-preview');

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
                    setFieldsReadOnly(false);
                    methodInput.value = 'POST';
                    Modal.querySelector('#MyModalLabel').textContent = 'Tambah Outlet';
                    modalForm.action = button.getAttribute('data-add-url');
                    imgPreview.src = "{{ asset('build/images/placeholder-image.webp') }}";
                    modalForm.reset();
                    itemIdInput.value = '';
                });
            });

            document.querySelectorAll('.edit-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var isEdit = button.classList.contains('edit-button');
                    var itemId = button.getAttribute('data-id');

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
                                imgPreview.src = response.data.image_url;
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
