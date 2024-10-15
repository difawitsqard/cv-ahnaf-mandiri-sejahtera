@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

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
                    <a class="btn btn-primary px-4 add-button"
                        href="{{ route('outlet.stock-item.create', ['outlet' => $outlet->slug]) }}"><i
                            class="bi bi-plus-lg me-2"></i>Item baru</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <x-alert-message type="success" :messages="session('success')" />
            @endif
            <div class="row g-3 mb-4">
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

            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle" id="table-stock-item">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox">
                                </th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Biaya</th>
                                <th>Stok</th>
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
                                                <img src="{{ $stockItem['image_url'] }}"
                                                    style="width: 70px; height: 53px; object-fit: cover;" class="rounded-3"
                                                    alt="">
                                            </div>
                                            <div class="product-info">
                                                <span class="product-title">{{ $stockItem['name'] }}</span>
                                                <p class="mb-0 product-category"><b>{{ $stockItem['stock'] }}</b>
                                                    {{ $stockItem['unit']->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $stockItem['description'] }}</td>
                                    <td>{{ formatRupiah($stockItem['price']) }}</td>
                                    <td>{{ $stockItem['stock'] }}</td>
                                    <td>{{ $stockItem['unit']->name }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a type="button" class="dropdown-item edit-button"
                                                        href="{{ route('outlet.stock-item.edit', ['outlet' => $outlet->slug, 'stock_item' => $stockItem->id]) }}">
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form id="delete-form-{{ $stockItem['id'] }}"
                                                        action="{{ route('outlet.stock-item.destroy', ['outlet' => $outlet->slug, 'stock_item' => $stockItem->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        data-id="{{ $stockItem['id'] }}"
                                                        data-msg="{{ $stockItem['name'] }}"
                                                        onclick="confirmDelete(this)">Hapus</button>
                                                </li>
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

<!-- Modal -->
@push('modals')
    <div class="modal fade" id="MyModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen p-3">
            <div class="modal-content rounded">
                <div class="modal-header border-bottom-0 py-2 rounded-top bg-light">
                    <h5 class="modal-title">Tambah Item</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <form action="{{ route('outlet.stock-item.store', ['outlet' => $outlet->slug]) }}"
                    style="display: flex; flex-direction: column; height: 100%; margin-top: 0; unicode-bidi: isolate; overflow-y: auto;"
                    method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @if ($errors->any())
                            <x-alert-message type="danger" :messages="$errors->all()" />
                        @endif
                        @csrf
                        @method('POST')
                        <input type="hidden" id="itemId" name="id">
                        <div class="row">

                            <div class="col-12 col-lg-2">

                                <div class="card">
                                    <img src="{{ asset('build/images/placeholder-image.webp') }}"
                                        style="width: 100%; height: 179px; object-fit: cover;" class="card-img-top"
                                        id="image-preview" alt="">
                                    <div class="card-body">
                                        <h6 class="mb-3">Gambar <small>(Opsional)</small></h6>
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

                            <div class="col-12 col-lg-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6 class="mb-3">Nama Item</h6>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" placeholder="..." required>
                                        </div>
                                        <div class="mb-4">
                                            <h6 class="mb-3">Deskripsi Item</h6>
                                            <textarea class="form-control" id="description" name="description" cols="4" rows="3"
                                                placeholder="...">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-5">

                                <div class="card">
                                    <div class="card-body">

                                        <h6 class="mb-3">Tambahkan ke Stok</h6>
                                        <div class="row g-3">
                                            <div class="col-sm-7">
                                                <input class="form-control" type="number" placeholder="Jumlah">
                                            </div>
                                            <div class="col-sm">
                                                <button class="btn btn-outline-primary"><i
                                                        class="bi bi-check2 me-2"></i>Confirm</button>
                                            </div>
                                        </div>
                                        <table class="ms-lg-4 mt-2 mb-3">
                                            <thead>
                                                <tr>
                                                    <th style="width: 200px;"></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-1000 py-1">Stock Saat Ini:
                                                    </td>
                                                    <td class="text-700 fw-semi-bold py-1">
                                                        $2,059<button class="btn p-0 ms-2" type="button"><i
                                                                class="bi bi-arrow-clockwise"></i></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-1000 py-1">Terakhir Restock:
                                                    </td>
                                                    <td class="text-700 fw-semi-bold py-1">25th March,
                                                        2020</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-1000 py-1">Total stok sepanjang masa:</td>
                                                    <td class="text-700 fw-semi-bold py-1">50,000</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="row g-3">
                                            <div class="col-12 col-lg-6">
                                                <h6 class="mb-2">Stok Awal</h6>
                                                <input class="form-control" type="number" id="stock" name="stock"
                                                    placeholder="Stok Awal" value="{{ old('stock') }}" required>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <h6 class="mb-2">Peringatan Stok</h6>
                                                <div class="input-group">
                                                    <span class="input-group-text text-danger"><i
                                                            class="material-icons-outlined fs-5">priority_high</i></span>
                                                    <input type="text" class="form-control" id="input29"
                                                        placeholder="Default '0'">
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-12">
                                                <h6 class="mb-2">Satuan / Unit</h6>
                                                <select class="form-control select2" id="unit_id" name="unit_id"
                                                    required>
                                                    <option disabled selected>Pilih Satuan</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-12">
                                                <h6 class="mb-2">Biaya</h6>
                                                <input class="form-control" type="text" id="cost" name="cost"
                                                    value="{{ old('cost') }}" placeholder="IDR" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#table-stock-item').DataTable({
                buttons: ['copy', 'excel', 'pdf', 'print'],
                lengthMenu: [10, 25, 50, 100]
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');

            // Update dropdown text and change page length on selection
            $('#dropdownPerPage .dropdown-item').on('click', function() {
                var value = $(this).data('value');
                table.page.len(value).draw();
                $('#dropdownPerPage').text(value + ' entri per halaman');
            });
        });
    </script>

    <script>
        function confirmDelete(button) {
            const stockItemId = button.getAttribute('data-id');
            const stockItemMsg = button.getAttribute('data-msg');

            Swal.fire({
                title: 'Apa kamu yakin?',
                html: `Ingin menghapus item <b>${stockItemMsg}</b>`,
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

        document.addEventListener('DOMContentLoaded', function() {

            @if ($errors->any())
                var tambahItemModal = new bootstrap.Modal(document.getElementById('MyModal')).show();
                tambahItemModal.show();
            @endif

            var Modal = document.querySelector('#MyModal');
            var modalTitle = Modal.querySelector('.modal-title')
            var modalForm = Modal.querySelector('form');
            var inputs = modalForm.querySelectorAll('input, textarea');
            var methodInput = modalForm.querySelector('input[name="_method"]');

            var itemIdInput = modalForm.querySelector('input[name="id"]');
            var nameInput = modalForm.querySelector('input[name="name"]');
            var descriptionTextarea = modalForm.querySelector('textarea[name="description"]');
            var submitButton = modalForm.querySelector('button[type="submit"]');

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
                    modalTitle.textContent = 'Tambah Item';
                    modalForm.action = button.getAttribute('data-add-url');
                    modalForm.reset();
                    itemIdInput.value = '';
                });
            });

            document.querySelectorAll('.show-button, .edit-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var isEdit = button.classList.contains('edit-button');
                    var itemId = button.getAttribute('data-id');

                    if (isEdit) {
                        modalForm.action =
                            `{{ route('outlet.stock-item.index', ['outlet' => $outlet->slug]) }}/${itemId}`;
                        methodInput.value = 'PUT';
                        submitButton.style.display = 'block';
                        setFieldsReadOnly(false);
                    } else {
                        methodInput.value = '';
                        modalForm.action = '';
                        submitButton.style.display = 'none';
                        setFieldsReadOnly(true);
                    }

                    modalTitle.textContent = isEdit ? 'Edit' : 'Detail';

                    fetch(
                            `{{ route('outlet.stock-item.index', ['outlet' => $outlet->slug]) }}/${itemId}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            nameInput.value = data.name;
                            descriptionTextarea.value = data.description;
                        });

                });
            });
        });
    </script>
@endpush
