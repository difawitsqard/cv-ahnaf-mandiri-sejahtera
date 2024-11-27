@extends('layouts.app')
@section('title')
    {{ __('Pengguna') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <x-page-title title="Pengguna" subtitle="Manajemen Pengguna" />

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
                    <button class="btn btn-primary px-4 add-button" data-bs-toggle="modal" data-bs-target="#MyModal"
                        data-add-url="{{ route('outlet.store') }}">
                        <i class="bi bi-plus-lg me-2"></i>
                        Pengguna baru
                    </button>
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
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                        <label class="input-group-text" for="table-stock-item-length">Entri per halaman</label>
                    </div>
                </div>
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
                <div class="col-auto">
                    <div class="position-relative mb-3">
                        <input class="form-control px-5" type="search" id="table-stock-item-search" placeholder="Cari...">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
            </div>


            <div class="table-responsive white-space-nowrap">
                <table class="table align-middle" id="table-user">
                    <thead class="table-light">
                        <tr>
                            <th width="2%">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor Hp</th>
                            <th>Role</th>
                            <th>Verify</th>
                            <th class="no-export">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $num => $user)
                            <tr>
                                <td class="text-center fw-bold" width="2%">{{ $num + 1 }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center pe-0">
                                        <img src="{{ URL::asset('build/images/default-avatar.jpg') }}"
                                            alt="{{ $user->name }}" width="36px" height="36px"
                                            class="rounded-circle shadow-sm" style="object-fit: cover;">
                                        <b class="d-none d-md-block ps-2">{{ $user->name }}</b>
                                    </div>
                                </td>
                                <td>
                                    {{ $user['email'] }}
                                </td>
                                <td>
                                    {{ $user->mobile_phone_number }}
                                </td>
                                <td class="fst-italic fw-bolder">
                                    {{ isset($user->roles[0]->name) ? ucwords($user->roles[0]->name) : 'Unknown' }}</td>
                                <td>
                                    @if ($user->hasVerifiedEmail())
                                        <i class="bi bi-check-circle" style="color: green;"></i>
                                    @endif
                                </td>
                                <td class="no-export">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                            type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                            <i class="material-icons-outlined d-flex">more_horiz</i>
                                        </button>
                                        <ul class="dropdown-menu"
                                            style="position: fixed; top: auto; left: auto; transform: translate(0, 0); z-index: 1050;"
                                            data-bs-popper="none">
                                            {{-- <li>
                                                <a type="button" class="dropdown-item"
                                                    href="{{ roleBasedRoute('stock-item.show', ['outlet' => $outlet->slug, 'stock_item' => $user->id]) }}">
                                                    Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a type="button" class="dropdown-item edit-button"
                                                    href="{{ roleBasedRoute('stock-item.edit', ['outlet' => $outlet->slug, 'stock_item' => $user->id]) }}">
                                                    Edit
                                                </a>
                                            </li> --}}
                                            <hr class="dropdown-divider">
                                            <li>
                                                <form id="delete-form-{{ $user['id'] }}"
                                                    action="{{ roleBasedRoute('user.destroy', ['outlet' => $outlet->slug, 'user' => $user->id]) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="dropdown-item text-danger"
                                                    data-id="{{ $user['id'] }}" data-msg="{{ $user['name'] }}"
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
@endsection

<!-- Modal -->
@push('modals')
    <div class="modal fade" id="MyModal" tabindex="-1" aria-labelledby="MyModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MyModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card mb-1 d-none">
                    <ul class="list-group block">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-5">
                            Bergabung
                            <span class="badge bg-dark rounded-pill" id="profile-join"></span>
                        </li>
                    </ul>
                </div>
                <form action="{{ roleBasedRoute('user.store', ['outlet' => $outlet->slug]) }}" method="POST">
                    <div class="modal-body ">
                        <div class="row g-3">
                            @csrf
                            @method('POST')
                            <input type="hidden" id="itemId" name="id">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 for="name" class="form-label">Nama <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 for="role" class="form-label">Role <span class="text-danger">*</span></h6>
                                    <select id="role" class="form-select" name="role">
                                        <option disabled>Pilih Role...</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucwords($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-0">
                                <div class="mb-3">
                                    <h6 for="email" class="form-label">Email <span class="text-danger">*</span></h6>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="..." required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-0">
                                <div class="mb-3">
                                    <label for="mobile_phone_number" class="form-label">Nomor Hp</label>
                                    <input type="number" class="form-control" id="mobile_phone_number"
                                        name="mobile_phone_number" placeholder="628/08...">
                                </div>
                            </div>
                            <div class="mb-3 mt-0">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" name="address" placeholder="..." rows="3"></textarea>
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

            var table = $('#table-user').DataTable({
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

        function confirmDelete(button) {
            const stockItemId = button.getAttribute('data-id');
            const stockItemMsg = button.getAttribute('data-msg');

            Swal.fire({
                title: 'Apa kamu yakin?',
                html: `Ingin menghapus Pengguna <b>${stockItemMsg}</b>`,
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
