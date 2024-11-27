@extends('layouts.app')
@section('title')
    {{ __('Order') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet">

    <style>
        .card.disabled {
            cursor: not-allowed !important;
            opacity: 0.6;
        }

        .card.disabled .card-body {
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <x-page-title title="Pesan" subtitle="Pesan Menu" />

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

    <div id="stepper1" class="bs-stepper">
        <div class="card">

            <div class="card-header">
                <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between" role="tablist">
                    <div class="step" data-target="#test-l-1">
                        <div class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="test-l-1">
                            <div class="bs-stepper-circle">1</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Pilih Menu</h5>
                                <p class="mb-0 steper-sub-title">Pilih menu yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bs-stepper-line"></div>
                    <div class="step" data-target="#test-l-2">
                        <div class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="test-l-2">
                            <div class="bs-stepper-circle">2</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Tinjau Pesanan</h5>
                                <p class="mb-0 steper-sub-title">Periksa detail pesanan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bs-stepper-line"></div>
                    <div class="step">
                        <div class="step-trigger" role="tab">
                            <div class="bs-stepper-circle">3</div>
                            <div class="">
                                <h5 class="mb-0 steper-title fw-bold">Selesai</h5>
                                <p class="mb-0 steper-sub-title"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body bg-light">
                <div class="bs-stepper-content">
                    <form onSubmit="return false">
                        <div id="test-l-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                            <div class="row row-cols-1 row-cols-xl-3">
                                @foreach ($menus as $menu)
                                    <div class="col d-flex align-items-stretch" data-menu-id="{{ $menu->id }}"
                                        data-max-order="{{ $menu->max_order_quantity }}">
                                        <div class="card rounded-4 flex-grow-1 position-relative {{ $menu->max_order_quantity < 1 ? 'disabled' : '' }}"
                                            style="cursor: pointer; transition: background-color 0.3s ease;">
                                            @if ($menu->max_order_quantity < 1)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-danger">Stock Habis</span>
                                                </div>
                                            @endif
                                            <div class="row g-0 align-items-center h-100">
                                                <div class="col-4 border-end h-100">
                                                    <div class="p-0 align-self-center h-100">
                                                        <img src="{{ $menu->menuImages->first()->image_url ?? asset('build/images/placeholder-image.webp') }}"
                                                            style="width: 100px; height: 100%; object-fit: cover;"
                                                            class="w-100 rounded-start-4" alt="{{ $menu['name'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-8 h-100">
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <h5 class="card-title">{{ $menu['name'] }}</h5>
                                                        <p class="card-text" style="min-height: 42px;">
                                                            {{ str()->limit(strip_tags($menu['description']), 50, '...') }}
                                                        </p>
                                                        <h6 class="fw-bold menu-price">
                                                            {{ $menu['price'] == 0 ? 'Gratis' : formatRupiah($menu['price']) }}
                                                        </h6>
                                                        <div class="mt-auto d-flex align-items-center justify-content-end">
                                                            <button type="button"
                                                                class="btn btn-primary btn-circle raised rounded-circle d-flex gap-2 wh-48 add-button"
                                                                {{ $menu->max_order_quantity < 1 ? 'disabled' : '' }}>
                                                                <i class="bi bi-plus-lg"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($menus->isEmpty())
                                <div class="col-12 d-flex justify-content-center p-4">
                                    <h6>Tidak ada menu yang tersedia...</h6>
                                </div>
                            @endif

                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-primary px-4 next-button" style="display: none;"
                                    onclick="stepper1.next()">Selanjutnya
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>

                        </div>

                        <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">

                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <div class="card w-100">
                                        <div class="card-body p-0">

                                            <div class="d-flex justify-content-between p-3">
                                                <h5 class="mb-3 fw-bold">Rangkuman Pesanan</h5>
                                                <a href="#" onclick="stepper1.previous()"
                                                    class="fw-semi-bold">Tambah
                                                    Pesanan</a>
                                            </div>
                                            <ul class="list-group list-group-flush p-0">
                                                <!-- Item 1 -->
                                                <li class="list-group-item d-flex align-items-start">
                                                    <div class="me-3">
                                                        <span class="badge text-success fs-6">2x</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">Mie Kocok Komplit (Loci, Daging, Tahu)
                                                        </h6>
                                                        {{-- <p class="mb-1 small">Satukan Kuah</p> --}}
                                                        <a href="#" class="text-primary small">Edit</a>
                                                    </div>
                                                    <div class="ms-4">
                                                        <p>32.000</p>
                                                    </div>
                                                </li>
                                                <!-- Item 2 -->
                                                <li class="list-group-item d-flex align-items-start">
                                                    <div class="me-3">
                                                        <span class="badge text-success fs-6">1x</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">Mie Kocok Komplit (Loci, Daging)</h6>
                                                        {{-- <p class="mb-1 small">Satukan Kuah</p> --}}
                                                        <a href="#" class="text-primary small">Edit</a>
                                                    </div>
                                                    <div class="ms-4">
                                                        <p>16.000</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row pb-3 border-bottom mb-3">
                                                <label for="name" class="col-sm-3 col-form-label fw-bold">Nama <span
                                                        class="fw-light">( Opsional )</span></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Nama pelanggan">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="d-flex justify-content-between">
                                                    <p class="fw-semi-bold">Subtotal :</p>
                                                    <p class="fw-semi-bold subtotal-value">0</p>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <p class="fw-semi-bold">Discount :</p>
                                                    <p class="text-danger fw-semi-bold discount-value">-0</p>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <p class="fw-semi-bold">Tax :</p>
                                                    <p class="fw-semi-bold tax-value">0</p>
                                                </div>
                                                <div class="d-flex justify-content-between border-top pt-4">
                                                    <h5 class="mb-0 fw-bold">Total :</h5>
                                                    <h5 class="mb-0 fw-bold total-value">0</h5>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn btn-secondary px-4" onclick="stepper1.previous()"><i
                                                class="bi bi-arrow-left me-2"></i> Pilih Menu</button>
                                        <button class="btn btn-primary px-4 submit-button">Pesan</button>
                                    </div>
                                </div>
                            </div><!---end row-->

                        </div>
                    </form>
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
    <script src="{{ URL::asset('build/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/bs-stepper/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Array untuk menyimpan data keranjang
            let cart = [];
            let discountRate = 0.0; 
            let taxRate = 0.0; 

            function updateNextButton() {
                const hasItems = cart.some(item => item.quantity > 0);
                if (hasItems) {
                    $(".next-button").show(400); 
                    $(".next-button").html(
                        `<span class="badge rounded-pill bg-light text-primary me-2">${cart.length}</span> Selanjutnya <i class="bi bi-arrow-right"></i>`
                    );
                } else {
                    $(".next-button").hide(400); 
                }
            }

            function calculateTotals() {
                // Hitung subtotal
                let subtotal = cart.reduce((acc, item) => {
                    const price = parseFloat(item.price) || 0; 
                    const quantity = parseInt(item.quantity) || 0; 
                    return acc + (price * quantity);
                }, 0);

    
                let discount = Math.floor(subtotal * discountRate);

                let tax = Math.floor((subtotal - discount) * taxRate);

                // Hitung total
                let total = subtotal - discount + tax;

                // // Bulatkan hasil ke dua desimal
                // subtotal = parseFloat(subtotal.toFixed(2));
                // discount = parseFloat(discount.toFixed(2));
                // tax = parseFloat(tax.toFixed(2));
                // total = parseFloat(total.toFixed(2));

                // Log hasil perhitungan untuk debugging
                // console.log("Cart Data:", cart);
                // console.log("Subtotal:", subtotal);
                // console.log("Discount:", discount);
                // console.log("Tax:", tax);
                // console.log("Total:", total);

                // Update elemen HTML
                $(".subtotal-value").text(subtotal.toLocaleString());
                $(".discount-value").text(`-${discount.toLocaleString()}`);
                $(".tax-value").text(tax.toLocaleString());
                $(".total-value").text(total.toLocaleString());
            }


            function updateCartSummary() {
                const summaryList = $(".list-group");
                summaryList.empty();

                cart.forEach(item => {
                    const total = item.price * item.quantity;
                    const itemHtml = `
                <li class="list-group-item d-flex align-items-start p-3">
                    <div class="me-3">
                        <span class="badge text-primary fs-6 border">${item.quantity}x</span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">${item.name}</h6>
                        <a href="#" class="text-primary small">Edit</a>
                    </div>
                    <div class="ms-4">
                        <p>${total.toLocaleString()}</p>
                    </div>
                </li>`;
                    summaryList.append(itemHtml);
                });

                calculateTotals();
            }

            // Handle "add-button" click
            $(document).on("click", ".add-button", function() {
                const menuId = $(this).closest('[data-menu-id]').data('menu-id');
                const menuName = $(this).closest('.card').find('.card-title').text() ||
                    "Nama Menu Tidak Diketahui";
                const maxOrderQuantity = $(this).closest('[data-max-order]').data('max-order');
                const menuPrice = parseInt($(this).closest('.card').find('.menu-price').text().replace(
                    /\./g, '')) || 0;

                // Tambahkan item ke keranjang jika belum ada
                if (!cart.some(item => item.menuId === menuId)) {
                    cart.push({
                        id: menuId,
                        name: menuName,
                        maxOrder: maxOrderQuantity,
                        price: menuPrice,
                        quantity: 1
                    });
                }

                // Replace button with input group
                const inputGroupHtml = $(`
                    <div class="input-group w-auto d-inline-flex">
                        <button class="btn btn-outline-secondary decrement-button" data-menu-id="${menuId}">-</button>
                        <input type="text" class="form-control text-center quantity-input" value="1" style="max-width: 60px;" readonly>
                        <button class="btn btn-outline-secondary increment-button" data-menu-id="${menuId}">+</button>
                    </div>
                `);
                $(this).parent().html(inputGroupHtml);

                // Update tombol "Selanjutnya"
                updateCartSummary();
                updateNextButton();
            });

            // Handle decrement button
            $(document).on("click", ".decrement-button", function() {
                const menuId = $(this).data("menu-id");
                const inputField = $(this).siblings(".quantity-input");
                let quantity = parseInt(inputField.val());

                if (quantity > 1) {
                    quantity--;
                    inputField.val(quantity);

                    // Update quantity in cart
                    const item = cart.find(item => item.id === menuId);
                    if (item) item.quantity = quantity;
                } else {
                    // Remove item from cart if quantity reaches 0
                    cart = cart.filter(item => item.id !== menuId);
                    $(this).parent().parent().html(`<button type="button" class="btn btn-primary btn-circle raised rounded-circle d-flex gap-2 wh-48 add-button">
                            <i class="bi bi-plus-lg"></i>
                        </button>`);
                }

                // Update tombol "Selanjutnya"
                updateCartSummary();
                updateNextButton();
            });

            // Handle increment button
            $(document).on("click", ".increment-button", function() {
                const menuId = $(this).data("menu-id");
                const inputField = $(this).siblings(".quantity-input");
                let quantity = parseInt(inputField.val());

                const item = cart.find(item => item.id === menuId);

                if (item && quantity <= (item.maxOrder - 1)) {
                    quantity++;
                    inputField.val(quantity);

                    if (item) item.quantity = quantity;

                    updateCartSummary();
                    updateNextButton();
                }
            });

            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            $(document).on("click", ".submit-button", function() {

                Swal.fire({
                    title: 'Apa kamu yakin?',
                    html: `Ingin memesan menu ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#fc185a',
                    confirmButtonText: 'Ya, Pesan',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.order.store') }}",
                            type: "POST",
                            //contentType: "application/json",
                            data: {
                                name: $("input[name='name']").val() ?? null,
                                cart: cart,
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken 
                            },
                            success: function(response) {
                                if (response.status) {
                                    window.location.href =
                                        `{{ route('admin.order.index') }}/${response.data.order_id}`;
                                }
                            },
                            error: function(xhr, status, error) {
                                // Gagal mengirim data
                                console.error("Error mengirim pesanan:", error);
                                alert("Gagal mengirim pesanan. Silakan coba lagi.");
                            }
                        });
                    }
                })

            });

        });
    </script>
@endpush
