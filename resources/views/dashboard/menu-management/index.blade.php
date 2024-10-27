@extends('layouts.app')
@section('title')
    {{ __('Menu') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <x-page-title title="Menu" subtitle="Daftar Menu" />

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
                    <a class="btn btn-primary px-4 add-button"
                        href="{{ roleBasedRoute('menu.create', ['outlet' => $outlet->slug]) }}"><i
                            class="bi bi-plus-lg me-2"></i>Menu baru</a>
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

            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle" id="table-stock-item">
                        <thead class="table-light">
                            <tr>
                                <th width="2%">#</th>
                                <th>Nama</th>
                                {{-- <th>Deskripsi</th> --}}
                                <th>Harga</th>
                                <th class="no-export">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $num => $menu)
                                <tr>
                                    <td class="text-center fw-bold" width="2%">{{ $num + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-box">
                                                <img src="{{ $menu->menuImages->first()->image_url ?? asset('build/images/placeholder-image.webp') }}"
                                                    style="width: 70px; height: 53px; object-fit: cover;" class="rounded-3"
                                                    alt="">
                                            </div>
                                            <div class="product-info">
                                                <span class="product-title">{{ $menu['name'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td>{{ $menu['description'] }}</td> --}}
                                    <td>{{ formatRupiah($menu['price']) }}</td>
                                    <td class="no-export">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    {{-- <a type="button" class="dropdown-item"
                                                        href="{{ route('outlet.stock-item.show', ['outlet' => $outlet->slug, 'stock_item' => $stockItem->id]) }}">
                                                        Detail
                                                    </a> --}}
                                                </li>
                                                <li>
                                                    <a type="button" class="dropdown-item edit-button"
                                                        href="{{ roleBasedRoute('menu.edit', ['outlet' => $outlet->slug, 'menu' => $menu->id]) }}">
                                                        Edit
                                                    </a>
                                                </li>
                                                <hr class="dropdown-divider">
                                                <li>
                                                    <form id="delete-form-{{ $menu['id'] }}"
                                                        action="{{ roleBasedRoute('menu.destroy', ['outlet' => $outlet->slug, 'menu' => $menu->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        data-id="{{ $menu['id'] }}"
                                                        data-msg="{{ $menu['name'] }}"
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

        function confirmDelete(button) {
            const menuId = button.getAttribute('data-id');
            const menuMsg = button.getAttribute('data-msg');

            Swal.fire({
                title: 'Apa kamu yakin?',
                html: `Ingin menghapus Menu <b>${menuMsg}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#fc185a',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + menuId).submit();
                }
            })
        }
    </script>
@endpush
