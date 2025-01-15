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
    <x-page-title title="Pesanan" subtitle="Buat Pesanan" />

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
                    <div class="step" data-target="#test-l-3">
                        <div class="step-trigger" role="tab" id="stepper1trigger3" aria-controls="test-l-3">
                            <div class="bs-stepper-circle">3</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Pembayaran</h5>
                                <p class="mb-0 steper-sub-title">Selesaikan Pesanan</p>
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
                                                        <h5 class="card-title fw-bold">{{ $menu['name'] }}</h5>
                                                        <p class="card-text fw-lighter" style="min-height: 42px;">
                                                            <small>
                                                                {{ str()->limit(strip_tags($menu['description']), 50, '...') }}
                                                            </small>
                                                        </p>
                                                        <h6 class="fw-bold menu-price">
                                                            {{ $menu['price'] == 0 ? 'Gratis' : formatRupiah($menu['price']) }}
                                                        </h6>
                                                        <div class="mt-auto d-flex align-items-center justify-content-end">
                                                             <button type="button"
                                                                class="btn btn-primary btn-circle raised d-flex gap-2 rounded-circle wh-48 add-button mt-2"
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
                                    onclick="stepper1.next()">Berikutnya
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>

                        </div>

                        <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">

                            <div class="row g-3">
                                <div class="col-12 col-lg-7">
                                    <div class="card w-100 mb-0">
                                        <div class="card-body p-0">

                                            <div class="d-flex justify-content-between p-3">
                                                <h5 class="mb-3 fw-bold">Rangkuman Pesanan</h5>
                                                <a href="javascript;"
                                                    onclick="event.preventDefault(); stepper1.previous()"
                                                    class="fw-semi-bold text-right">
                                                    Tambah Pesanan
                                                </a>
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
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row pb-3 border-bottom mb-3">
                                                <label for="name" class="col-sm-3 col-form-label fw-bold">Nama <span
                                                        class="fw-light"></span></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Nama Order (Opsional)">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="summary p-0"></div>
                                                <div class="d-flex justify-content-between border-top pt-3">
                                                    <h5 class="mb-0 fw-bold">Total :</h5>
                                                    <h5 class="mb-0 fw-bold total-value">0</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-12 d-flex justify-content-end gap-3">
                                <button class="btn btn-secondary px-4" onclick="stepper1.previous()"><i
                                        class="bi bi-arrow-left me-2"></i> Menu</button>
                                <button class="btn btn-primary px-4"onclick="stepper1.next()">Berikutnya
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        <!---end row-->

                        <div id="test-l-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
                            <div class="row g-3">

                                <div class="col-12 col-lg-7">
                                    <div class="card w-100 mb-0">
                                        <div class="card-body">
                                            <h5 class="mb-3 fw-bold">Rincian Pesanan</h5>
                                            <div class="product-table">
                                                <table class="table table-striped">
                                                    <tbody class="table-summary"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-5">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3 fw-bold">Pembayaran</h5>
                                            <label class="form-label">Jumlah Pembayaran Diterima</label>
                                            <input type="text" name="paid_amount" class="form-control mb-3"
                                                placeholder="...">
                                            <label class="form-label">Metode Pembayaran</label>
                                            <select class="form-select mb-3" name="payment_method" id="payment_method"
                                                required="">
                                                <option value="cash">Tunai</option>
                                                <option value="credit_card">Kartu Kredit</option>
                                                <option value="transfer_bank">Transfer Bank</option>
                                                <option value="other">Lainnya</option>
                                            </select>
                                            <label class="form-label">Jumlah Kembalian</label>
                                            <input type="text" name="change_amount" class="form-control"
                                                placeholder="..." value="0" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-3">
                                    <button class="btn btn-secondary px-4" onclick="stepper1.previous()"><i
                                            class="bi bi-arrow-left me-2"></i> Kembali</button>
                                    </button>
                                    <button class="btn btn-primary px-4 submit-button">Pesan <i
                                            class="bi bi-arrow-right ms-2"></i></button>
                                </div>
                            </div><!---end row-->

                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@push('script')
    <script src="{{ URL::asset('build/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>

    <script>
        var stepper1;

        $(document).ready(function() {
            stepper1 = new Stepper(document.querySelector('#stepper1'), {
                animation: true
            });

            var cart = [];
            const discountRate = {{ $outlet->discount / 100 }};
            const taxRate = {{ $outlet->tax / 100 }};
            const $summaryList = $(".list-group");
            const $tableSummary = $(".table-summary");
            const $nextButton = $(".next-button");

            function saveCart() {
                localStorage.setItem('cart', JSON.stringify(cart));
            }

            function loadCart() {
                const savedCart = localStorage.getItem('cart');
                if (savedCart) {
                    cart.push(...JSON.parse(savedCart));
                    cart.forEach(item => {
                        const maxOrder = $(`[data-menu-id="${item.id}"]`).data('max-order');
                        const menuName = $(`[data-menu-id="${item.id}"]`).find(
                                '.card-title').text() ||
                            "Nama Menu Tidak Diketahui";
                        const menuPrice = parseInt($(`[data-menu-id="${item.id}"]`).find(
                            '.menu-price').text().replace(
                            /\./g, '')) || 0;

                        if ($(`[data-menu-id="${item.id}"]`).length === 0) {
                            cart = cart.filter(cartItem => cartItem.id !== item.id);
                            return;
                        }

                        item.maxOrder = maxOrder;
                        item.name = menuName;
                        item.price = menuPrice;
                        item.quantity = Math.min(item.quantity, maxOrder);
                        const inputGroupHtml = createInputGroupHtml(item.id, item.quantity);
                        $(`[data-menu-id="${item.id}"]`).find('.add-button').replaceWith(inputGroupHtml);
                    });
                    if (cart.length > 0) stepper1.to(2);
                    updateCartSummary();
                    updateNextButton();
                }

                if (cart.length < 1) {
                    $('.qty-control').replaceWith(
                        `<button type="button" class="btn btn-primary btn-circle raised d-flex gap-2 rounded-circle wh-48 add-button mt-2"><i class="bi bi-plus-lg"></i></button>`
                    );
                }
            }

            function createInputGroupHtml(menuId, quantity) {
                return $(`
            <div class="input-group w-auto d-inline-flex mt-2 qty-control">
                <button class="btn btn-outline-secondary decrement-button" data-menu-id="${menuId}"><i class="bi bi-dash-lg"></i></button>
                <input type="text" class="form-control text-center quantity-input shadow-none" value="${quantity}" style="max-width: 60px; border: 1px solid #6c757d; padding: 0.375rem 0.75rem;" readonly>
                <button class="btn btn-outline-secondary increment-button" data-menu-id="${menuId}"><i class="bi bi-plus-lg"></i></button>
            </div>
        `);
            }

            function updateNextButton() {
                const hasItems = cart.some(item => item.quantity > 0);
                if (hasItems) {
                    $nextButton.show(400).html(`
                <span class="badge rounded-pill bg-light text-primary me-2">${cart.reduce((acc, item) => acc + item.quantity, 0)}</span> Berikutnya <i class="bi bi-arrow-right ms-2"></i>
            `);
                } else {
                    $nextButton.hide(400);
                }
                saveCart();
            }

            function calculateTotals() {
                const subtotal = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                const discount = parseInt(subtotal * discountRate);
                const tax = parseInt((subtotal - discount) * taxRate);
                const total = (subtotal - discount) + tax;

                const itemSummary = (label, val) => $(`
            <div class="d-flex justify-content-between">
                <p class="fw-semi-bold">${label} :</p>
                <p class="fw-semi-bold">${val}</p>
            </div>
        `);

                const itemTable = (label, val) => $(`
            <tr>
                <td width="200">${label}</td>
                <td width="10">:</td>
                <td>${val}</td>
            </tr>
        `);

                $tableSummary.empty()
                    .append(itemTable('Jumlah Produk',
                        `<span class="badge rounded-pill bg-primary">${cart.reduce((acc, item) => acc + item.quantity, 0)}</span>`
                    ));

                $('.summary').empty().append(itemSummary('Subtotal', subtotal.toLocaleString()));
                if (discount > 0) {
                    $('.summary').append(itemSummary('Diskon',
                        `-${discount.toLocaleString()} (${discountRate * 100}%)`));
                    $tableSummary.append(itemTable('Diskon',
                        `-${discount.toLocaleString()} (${discountRate * 100}%)`));
                }
                if (tax > 0) {
                    $('.summary').append(itemSummary('Pajak', `${tax.toLocaleString()} (${taxRate * 100}%)`));
                    $tableSummary.append(itemTable('Pajak', `${tax.toLocaleString()} (${taxRate * 100}%)`));
                }
                $tableSummary.append(itemTable('<b>Total Keseluruhan</b>', `<b>${total.toLocaleString()}</b>`));
                $(".total-value").text(total.toLocaleString());
                $('input[name="paid_amount"]').val(formatRupiahText(total));
            }

            function updateCartSummary() {
                $summaryList.empty();
                cart.forEach(item => {
                    const total = item.price * item.quantity;
                    const itemHtml = $(`
                <li class="list-group-item d-flex align-items-start p-3" data-menu-id="${item.id}">
                    <div class="me-3">
                        <span class="badge text-primary fs-6 border">${item.quantity}x</span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">${item.name}</h6>
                        <span class="add-note"></span>
                    </div>
                    <div class="ms-4">
                        <p>${total.toLocaleString()}</p>
                    </div>
                </li>
            `);

                    const inputNote = $('<input>')
                        .addClass('form-control form-control-sm border-0 rounded-0 shadow-none p-0')
                        .attr('type', 'text')
                        .attr('maxlength', 200)
                        .attr('placeholder', 'Tambah catatan')
                        .val(item.note);

                    inputNote.on('change', function() {
                        const menuId = $(this).closest('[data-menu-id]').data('menu-id');
                        const item = cart.find(item => item.id === menuId);
                        if (item) item.note = $(this).val();
                        saveCart();
                    });

                    itemHtml.find(".add-note").replaceWith(inputNote);
                    $summaryList.append(itemHtml);
                });
                calculateTotals();
            }

            $(document).on("click", ".add-button", function() {
                const menuId = $(this).closest('[data-menu-id]').data('menu-id');
                const menuName = $(this).closest('.card').find('.card-title').text() ||
                    "Nama Menu Tidak Diketahui";
                const maxOrderQuantity = $(this).closest('[data-max-order]').data('max-order');
                const menuPrice = parseInt($(this).closest('.card').find('.menu-price').text().replace(
                    /\./g, '')) || 0;

                if (!cart.some(item => item.id === menuId)) {
                    cart.push({
                        id: menuId,
                        name: menuName,
                        maxOrder: maxOrderQuantity,
                        note: null,
                        price: menuPrice,
                        quantity: 1
                    });
                }

                const inputGroupHtml = createInputGroupHtml(menuId, 1);
                $(this).parent().html(inputGroupHtml);
                updateCartSummary();
                updateNextButton();
            });

            $(document).on("click", ".decrement-button", function() {
                const menuId = $(this).data("menu-id");
                const inputField = $(this).siblings(".quantity-input");
                let quantity = parseInt(inputField.val());

                if (quantity > 1) {
                    quantity--;
                    inputField.val(quantity);
                    const item = cart.find(item => item.id === menuId);
                    if (item) item.quantity = quantity;
                } else {
                    cart = cart.filter(item => item.id !== menuId);
                    $(this).parent().parent().html(`<button type="button" class="btn btn-primary btn-circle raised d-flex gap-2 rounded-circle wh-48 add-button mt-2">
                <i class="bi bi-plus-lg"></i>
            </button>`);
                }
                updateCartSummary();
                updateNextButton();
            });

            $(document).on("click", ".increment-button", function() {
                const menuId = $(this).data("menu-id");
                const inputField = $(this).siblings(".quantity-input");
                let quantity = parseInt(inputField.val());
                const item = cart.find(item => item.id === menuId);

                if (item && quantity < item.maxOrder) {
                    quantity++;
                    inputField.val(quantity);
                    item.quantity = quantity;
                    updateCartSummary();
                    updateNextButton();
                }
            });

            $(document).on("change", "input[name='name']", function() {
                if ($.trim($(this).val()) !== "") {
                    $('.table-summary').prepend(`<tr class="detail-order-name">
                <td width="200">Nama Order</td>
                <td width="10">:</td>
                <td>${$(this).val()}</td>
            </tr>`);
                } else {
                    $('.detail-order-name').remove();
                }
            });

            $(document).on("input", `input[name="paid_amount"]`, function(e) {
                formatRupiahElement(e.target);
                const total = parseInt($(".total-value").text().replace(/[^\d]/g, '')) || 0;
                const paidAmount = parseInt($(this).val().replace(/[^\d]/g, '')) || 0;
                const change = paidAmount - total;

                if (change >= 0) {
                    $(`input[name="change_amount"]`).val(change.toLocaleString('id-ID'));
                } else {
                    $(`input[name="change_amount"]`).val(0);
                }

                if (paidAmount >= total) {
                    $(this).removeClass("is-invalid");
                } else {
                    $(this).addClass("is-invalid");
                }
            });

            $(document).on("click", ".submit-button", function() {
                let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                Swal.fire({
                    title: 'Apa kamu yakin',
                    html: `Ingin menyelesaikan pesanan ini ?`,
                    input: "checkbox",
                    inputValue: 1,
                    inputPlaceholder: `Cetak resi pesanan`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#fc185a',
                    confirmButtonText: 'Ya, Pesan',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const printReceipt = result.value;

                        $.ajax({
                            url: "{{ roleBasedRoute('order.store', ['outlet' => $outlet->slug]) }}",
                            type: "POST",
                            data: {
                                name: $("input[name='name']").val() ?? null,
                                paid: parseInt($("input[name='paid_amount']").val()
                                    .replace(/[^\d]/g, '')) || 0,
                                payment_method: $("select[name='payment_method']").val(),
                                cart: cart,
                                print_receipt: printReceipt,
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                if (response.status) {
                                    cart = [];
                                    saveCart();
                                    stepper1.reset();
                                    loadCart();

                                    if (printReceipt) {
                                        $.get(`{{ roleBasedRoute('order.index', ['outlet' => $outlet->slug]) }}/${response.data.order_id}/print`,
                                            function(data) {
                                                if (/Android|iPhone|iPad|iPod/i
                                                    .test(navigator.userAgent)) {
                                                    window.location.href = data;
                                                } else {
                                                    var socket = new WebSocket(
                                                        "ws://127.0.0.1:40213/");
                                                    socket.bufferType =
                                                        "arraybuffer";
                                                    socket.onerror = function(
                                                        error) {
                                                        console.error(
                                                            "Error: " +
                                                            error);
                                                    };
                                                    socket.onopen = function() {
                                                        socket.send(data);
                                                        socket.close(1000,
                                                            "Work complete");
                                                    };
                                                }
                                            }).fail(function() {
                                            console.error(
                                                "Failed to print receipt.");
                                        });
                                    }

                                    Swal.fire({
                                        title: 'Berhasil!',
                                        html: `Pesanan berhasil dibuat!`,
                                        confirmButtonColor: '#0d6efd',
                                        icon: 'success',
                                        showCancelButton: true,
                                        confirmButtonText: 'Lihat Pesanan',
                                        cancelButtonText: 'Tutup',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href =
                                                `{{ roleBasedRoute('order.index', ['outlet' => $outlet->slug]) }}/${response.data.order_id}`;
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                if (xhr.responseJSON.errors) {
                                    let errorMessages = Object.values(xhr.responseJSON
                                        .errors)[0];
                                    Swal.fire({
                                        title: 'Upps!',
                                        confirmButtonColor: '#0d6efd',
                                        html: errorMessages,
                                        icon: 'error',
                                    });
                                }
                                console.error(error);
                            }
                        });
                    }
                });
            });

            loadCart();
        });
    </script>
@endpush
