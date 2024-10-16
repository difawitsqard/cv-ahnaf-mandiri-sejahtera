@extends('layouts.app')
@section('title')
    {{ __('Unit') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <x-page-title title="Unit" subtitle="Manajemen Unit" />

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-auto flex-grow-1 overflow-auto">
                    <div class="col-auto flex-grow-1 overflow-auto">
                        <div class="btn-group position-static">
                            <div class="btn-group position-static">
                                <button type="button" class="btn btn-outline-secondary" id="table-stock-item-excel">
                                    Excel
                                </button>
                            </div>
                            <div class="btn-group position-static">
                                <button type="button" class="btn btn-outline-secondary" id="table-stock-item-pdf">
                                    PDF
                                </button>
                            </div>
                            <div class="btn-group position-static">
                                <button type="button" class="btn btn-outline-secondary" id="table-stock-item-print">
                                    Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-primary px-4 add-button" data-bs-toggle="modal" data-bs-target="#MyModal"
                            data-add-url="{{ route('unit.store') }}"><i class="bi bi-plus-lg me-2"></i>Satuan
                            Baru</button>
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

    <div class="card">
        <div class="card-body">

            <div class="row g-3">
                <div class="col-auto">
                    <div class="input-group mb-3">
                        <select class="form-select" id="table-stock-item-length">
                            <option value="10" selected="">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                        <label class="input-group-text" for="table-stock-item-length">Entri per halaman</label>
                    </div>
                </div>
                <div class="col-auto flex-grow-1 overflow-auto">

                </div>
                <div class="col-auto">
                    <div class="position-relative mb-3">
                        <input class="form-control px-5" type="search" id="table-stock-item-search" placeholder="Cari...">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
            </div>

            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle" id="table-stock-item">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="85%">Nama</th>
                                <th class="no-export">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $num => $unit)
                                <tr>
                                    <td class="text-center fw-bold" width="2%">{{ $num + 1 }}</td>
                                    <td>{{ $unit['name'] }}</td>
                                    <td class="no-export">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu"
                                                style="position: absolute; top: 100%; right: 0; z-index: 1000;">
                                                <li><button class="dropdown-item edit-button" data-bs-toggle="modal"
                                                        data-bs-target="#MyModal" data-add-url="{{ route('unit.store') }}"
                                                        data-id="{{ $unit->id }}">Edit</button></li>
                                                <hr class="dropdown-divider">
                                                <li>
                                                    <form id="delete-form-{{ $unit->id }}"
                                                        action="{{ route('unit.destroy', ['unit' => $unit->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        data-id="{{ $unit->id }}" data-msg="{{ $unit->name }}"
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
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="MyModal" tabindex="-1" aria-labelledby="MyModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MyModalLabel">Tambah</h5>
                    <button type="button" class="btn-close" data-add-url="{{ route('unit.store') }}"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('unit.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="itemId" name="id">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Satuan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="..."
                                required>
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
                buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: ':not(.no-export)',
                            format: {
                                body: function(data, row, column, node) {
                                    return $(node).find('.no-export').remove().end()
                                        .text();
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':not(.no-export)',
                            format: {
                                body: function(data, row, column, node) {
                                    return $(node).find('.no-export').remove().end()
                                        .text();
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(.no-export)',
                            format: {
                                body: function(data, row, column, node) {
                                    return $(node).find('.no-export').remove().end()
                                        .text();
                                }
                            }
                        }
                    }
                ],
                lengthChange: false,
                lengthMenu: [10, 25, 50, 100, -1],
                order: [
                    [0, 'asc']
                ]
            });

            $('#table-stock-item-length').on('change', function() {
                var selectedValue = $(this).val();
                table.page.len(selectedValue).draw();
            });

            // Hide default search box
            $('.dataTables_filter').hide();

            $('#table-stock-item-search').on('input', function() {
                table.search($(this).val()).draw();
            });

            $('#table-stock-item-excel').on('click', function() {
                table.button('.buttons-excel').trigger();
            });

            $('#table-stock-item-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });

            $('#table-stock-item-print').on('click', function() {
                table.button('.buttons-print').trigger();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var Modal = document.querySelector('#MyModal');
            var modalForm = Modal.querySelector('form');
            var inputs = modalForm.querySelectorAll('input, textarea');
            var methodInput = modalForm.querySelector('input[name="_method"]');
            var itemIdInput = modalForm.querySelector('input[name="id"]');
            var nameInput = modalForm.querySelector('input[name="name"]');
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
                    Modal.querySelector('#MyModalLabel').textContent = 'Tambah Satuan';
                    modalForm.action = button.getAttribute('data-add-url');
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
                            `{{ route('unit.index') }}/${itemId}`;
                        methodInput.value = 'PUT';
                        submitButton.style.display = 'block';
                        setFieldsReadOnly(false);
                    } else {
                        methodInput.value = '';
                        modalForm.action = '';
                        submitButton.style.display = 'none';
                        setFieldsReadOnly(true);
                    }

                    Modal.querySelector('#MyModalLabel').textContent = isEdit ? 'Edit Satuan' :
                        'Detail';

                    fetch(`/unit/${itemId}/fetch`)
                        .then(response => response.json())
                        .then(response => {
                            if (response.status) {
                                nameInput.value = response.data.name;
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
                html: `Ingin menghapus satuan <b>${stockItemMsg}</b>`,
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
