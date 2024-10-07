@extends('layouts.app')
@section('title')
    Cards
@endsection
@section('content')
    <x-page-title title="Outlet" subtitle="Cards" />
    <div class="row g-3">
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
    </div>

    <div class="row row-cols-1 row-cols-xl-3 mt-4">
        @foreach ($Outlets as $outlet)
            <div class="col d-lg-flex align-items-lg-stretch">
                <div class="card rounded-4 flex-grow-1">
                    <div class="row g-0 align-items-center h-100">
                        <div class="col-md-4 border-end h-100">
                            <div class="p-0 align-self-center h-100">
                                <img src="{{ $outlet['image_path'] }}" style="width: 80px; height: 100%; object-fit: cover;"
                                    class="w-100 rounded-start" alt="...">
                            </div>
                        </div>
                        <div class="col-md-8 h-100">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 class="card-title">{{ $outlet['name'] }}</h5>
                                    <p class="card-text">{{ $outlet['address'] }}</p>
                                </div>
                                <div class="mt-4 mb-0 d-flex justify-content-end">
                                    <div class="btn-group">
                                        <a type="button" class="btn btn-outline-primary d-flex"><i
                                                class="material-icons-outlined">home</i>
                                        </a>
                                        <button type="button" class="btn btn-outline-primary d-flex edit-button"
                                            data-bs-toggle="modal" data-bs-target="#MyModal"
                                            data-id="{{ $outlet->id }}"><i class="material-icons-outlined">edit</i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger d-flex"><i
                                                class="material-icons-outlined">delete</i>
                                        </button>
                                    </div>
                                </div>
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MyModalLabel">Tambah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('outlet.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="itemId" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Outlet</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="..."
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Outlet</label>
                            <textarea class="form-control" id="address" name="address" placeholder="..." rows="5"></textarea>
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
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

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
                    Modal.querySelector('#MyModalLabel').textContent = 'Tambah';
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

                    Modal.querySelector('#MyModalLabel').textContent = isEdit ? 'Edit' : 'Detail';

                    fetch(`{{ route('outlet.index') }}/${itemId}`)
                        .then(response => response.json())
                        .then(data => {
                            nameInput.value = data.name;
                            addressTextarea.value = data.address;
                        });

                });
            });
        });
    </script>
@endpush
