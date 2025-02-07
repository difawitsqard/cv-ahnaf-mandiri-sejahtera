@extends('layouts.app')
@section('title')
    {{ __('Menu') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        .overflow-auto::-webkit-scrollbar {
            display: none;
        }
    </style>
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
                 @unlessrole('staff')
                    <div class="ms-auto">
                        <a class="btn btn-primary px-4 add-button"
                            href="{{ roleBasedRoute('menu.create', ['outlet' => $outlet->slug]) }}"><i
                                class="bi bi-plus-lg me-2"></i>Menu baru</a>
                    </div>
                @endunlessrole
            </div>
        </div>
    </div>

    @if (session('success'))
        <x-alert-message type="success" :messages="session('success')" />
    @endif


    @if ($errors->any())
        <x-alert-message type="danger" :messages="$errors->all()" />
    @endif

    @if (!$errors->any() && !session('success'))
        <div class="alert alert-info border-0 bg-grd-info alert-dismissible fade show msg-etalase">
            <div class="d-flex align-items-center">
                <div class="ms-3">
                    <h5 class="mb-0 text-white fw-bold">Informasi</h5>
                    <div class="text-white">
                        <ul class="mb-1">
                            <li>Menu yang <b>terkait <i class="bi bi-link"></i></b> dengan <strong>Item Etalase</strong>
                                memiliki ketersediaan yang
                                dipengaruhi oleh stok item di etalase.</li>
                            <li>Menu yang <b>tidak terkait</b> dengan <strong>Item Etalase</strong> Memiliki Ketersediaan
                                Tidak Terbatas <i class="bi bi-infinity"></i>.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-auto">
                    <div class="input-group">
                        <select class="form-select" id="table-menu-length">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                        <label class="input-group-text" for="table-menu-length">Entri per halaman</label>
                    </div>
                </div>
                <div class="col-12 col-md-auto ms-auto">
                    <div class="position-relative mb-3">
                        <input class="form-control px-5" type="search" id="table-menu-search" placeholder="Cari...">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
            </div>

            <div class="product-table">
                <div class="table-responsive white-space-nowrap">
                    <table class="table align-middle" id="table-menu">
                        <thead class="bg-light">
                            <tr>
                                <th width="2%">#</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Dipesan</th>
                                <th width="10%">Estimasi Ketersediaan</th>
                                <th>Status</th>
                                <th>Link</th>
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
                                                <a class="product-title" href="#">{{ $menu['name'] }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{!! Str::limit(strip_tags($menu['description']), 32) !!}</td>
                                    <td>{{ formatRupiah($menu['price']) }}</td>
                                    <td>{{ $menu->ordered_quantity }}x</td>
                                    <td>{!! $menu->max_order_quantity == PHP_INT_MAX
                                        ? '<i class="bi bi-infinity fs-4"></i>'
                                        : '<strong>' . $menu->max_order_quantity . '</strong>' . ' Menu' !!}</td>
                                    <td>
                                        @php
                                            $status = $menu->max_order_quantity < 1 ? false : true;

                                            $color = $status ? 'success' : 'danger';
                                            $status = $status ? 'Tersedia' : 'Habis';
                                        @endphp

                                        <span
                                            class="lable-table bg-{{ $color }}-subtle text-{{ $color }} rounded border border-{{ $color }}-subtle font-text2 fw-bold">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="text-center" width="2%"
                                        @if ($menu->stockItems->count() >= 1) data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Terkait Dengan {{ $menu->stockItems->count() }} Item" @endif>
                                        @if ($menu->stockItems->count() >= 1)
                                            <i class="bi bi-link fs-4"></i>
                                        @endif
                                    </td>
                                    <td class="no-export">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item detail-menu"
                                                        data-menu-id="{{ $menu->id }}">
                                                        Detail
                                                    </button>
                                                </li>
                                                 @unlessrole('staff')
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
                                                            data-id="{{ $menu['id'] }}" data-msg="{{ $menu['name'] }}"
                                                            onclick="confirmDelete(this)">Hapus</button>
                                                    </li>
                                                @endunlessrole
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
    <div class="modal fade" id="detailMenuModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">Detail Menu</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <div class="modal-body pt-0">
                    <div class="card w-100 mb-0 shadow-none">
                        <div class="card-header border-0 p-3 row">

                            <div class="col-12 col-lg-4 mb-3">
                                <div id="carouselMenuControls" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner image-menu-css rounded-4">

                                    </div>
                                    <a class="carousel-control-prev" href="#carouselMenuControls" role="button"
                                        data-bs-slide="prev"> <span class="carousel-control-prev-icon"
                                            aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselMenuControls" role="button"
                                        data-bs-slide="next"> <span class="carousel-control-next-icon"
                                            aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-12 col-lg-8 d-flex flex-column justify-content-between mb-2">
                                <div>
                                    <h4 class="fw-bold menu-title mb-2"></h4>
                                    <div class="overflow-auto menu-desc"
                                        style="max-height: 150px; scrollbar-width: none; -ms-overflow-style: none;">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <h5 class="fw-bold menu-status"></h5>
                                    <h5 class="fw-bold menu-price"></h5>
                                </div>
                            </div>

                        </div>
                        <div class="card-body p-0">
                            <h6 class="fw-bold d-flex align-items-center gap-2 p-3 border-bottom border-top">
                                <span class="material-icons-outlined">link</span> Link Item Etalase
                            </h6>
                            <div class="link-item-scroll" style="position: relative; max-height: 150px;">
                                <div class="link-item"></div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent p-3">
                            <div class="d-flex align-items-center justify-content-between gap-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        var ps = new PerfectScrollbar(".link-item-scroll");

        $(document).ready(function() {
            var table = $('#table-menu').DataTable({
                lengthChange: false,
                lengthMenu: [10, 25, 50, 100, -1],
                order: [
                    [0, 'asc']
                ]
            });

            $('#table-menu-length').on('change', function() {
                var selectedValue = $(this).val();
                table.page.len(selectedValue).draw();
            });

            // Hide default search box
            $('.dataTables_filter').hide();

            $('#table-menu-search').on('input', function() {
                table.search($(this).val()).draw();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const Modal = document.querySelector('#detailMenuModal');
            const menuTitle = Modal.querySelector('.menu-title');
            const menuDesc = Modal.querySelector('.menu-desc');
            const menuPrice = Modal.querySelector('.menu-price');
            const menuImage = Modal.querySelector('.image-menu-css');
            const menuLink = Modal.querySelector('.link-item');
            const menuStatus = Modal.querySelector('.menu-status');

            function clearModal() {
                menuTitle.textContent = '';
                menuDesc.innerHTML = '';
                menuPrice.textContent = '';
                menuImage.innerHTML = '';
                menuLink.innerHTML = '';
            }

            document.querySelectorAll('.detail-menu').forEach(item => {
                item.addEventListener('click', function() {
                    var menuId = item.getAttribute('data-menu-id');
                    clearModal();

                    $.ajax({
                        url: `{{ roleBasedRoute('menu.fetch', ['outlet' => $outlet->slug, 'id' => ':id']) }}`
                            .replace(':id', menuId),
                        method: 'GET',
                        success: function(response) {
                            menuTitle.textContent = response.data.name;
                            menuDesc.innerHTML = response.data.description;
                            menuPrice.textContent =
                                `Rp. ${formatRupiahText(response.data.price)}`;

                            let menuImages = response.data.menu_images.length ? response
                                .data.menu_images : [{
                                    'image_url': '{{ asset('build/images/placeholder-image.webp') }}'
                                }];

                            menuStatus.innerHTML =
                                `<span class="lable-table p-2 bg-${response.data.max_order_quantity < 1 ? 'danger' : 'success'}-subtle text-${response.data.max_order_quantity < 1 ? 'danger' : 'success'} rounded border border-${response.data.max_order_quantity < 1 ? 'danger' : 'success'}-subtle font-text2 fw-bold">${response.data.max_order_quantity < 1 ? 'Habis' : 'Tersedia'}</span>`;

                            menuImages.forEach((image, index) => {
                                var div = document.createElement('div');
                                div.classList.add('carousel-item');
                                div.innerHTML = `
                                    <img src="${image.image_url}" class="d-block w-100 bg-white" style="min-height: 285px; object-fit: cover;" alt="${response.data.name}-${index}">
                                `;

                                if (index === 0) {
                                    div.classList.add('active');
                                }

                                menuImage.appendChild(div);
                            });

                            if (response.data.stock_items) {
                                var table = document.createElement('table');
                                table.classList.add('table', 'no-border', 'mb-2');
                                table.innerHTML =
                                    `<tbody>
                                ${response.data.stock_items.length ? response.data.stock_items.map(stockItem => {
                                    const statusLinkItem = stockItem.stock > stockItem.pivot.quantity ? ['success', 'Terpenuhi'] : ['danger', 'Tidak Terpenuhi'];
                                    return `
                                                        <tr role="row" style="border: none;">
                                                        <td class="align-middle" style="border: none;">
                                                            <div class="d-flex align-items-center gap-3">
                                                            <div class="product-box">
                                                                <img src="${stockItem.image_url}" style="width: 50px; height: 40px; object-fit: cover;" class="rounded-3" alt="${stockItem.name}">
                                                            </div>
                                                            <div class="product-info">
                                                                <b class="product-title">${stockItem.name}</b>
                                                                <p class="mb-0 product-category no-export">
                                                                <b>${stockItem.stock}</b> ${stockItem.unit.name}
                                                                </p>
                                                            </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle" style="border: none;">
                                                            <div class="product-info">
                                                            <b class="product-title">Dibutuhkan</b>
                                                            <p class="mb-0 product-category no-export">
                                                                <b>${stockItem.pivot.quantity}</b> ${stockItem.unit.name}
                                                            </p>
                                                            </div>    
                                                        </td>
                                                        <td class="align-middle text-end" style="border: none;">
                                                            <span class="lable-table me-3 p-2 bg-${statusLinkItem[0]}-subtle text-${statusLinkItem[0]} rounded border border-${statusLinkItem[0]}-subtle font-text2 fw-bold">${statusLinkItem[1]}</span>
                                                        </td>
                                                        </tr>
                                                    `;
                        }).join('') : '<tr><td colspan="3" style="border: none;" class="text-center">Tidak ada item yang terkait</td></tr>'}</tbody>`;
                                menuLink.appendChild(table);
                            }

                            ps.update();

                            setTimeout(function() {
                                $('#detailMenuModal').modal('show');
                            }, 100);
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });
            });
        });

         @unlessrole('staff')
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
        @endunlessrole
    </script>
@endpush
