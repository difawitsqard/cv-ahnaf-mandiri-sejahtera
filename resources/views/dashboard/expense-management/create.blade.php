@extends('layouts.app')
@section('title')
    {{ __('Pengeluaran') }}
@endsection

@push('css')
    @if (importOnce('css-select2'))
        <link href="{{ URL::asset('build/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ URL::asset('build/plugins/select2/css/select2-bootstrap-5.min.css') }}" rel="stylesheet" />
    @endif
    @if (importOnce('css-flatpickr'))
        <link href="{{ URL::asset('build/plugins/flatpickr/css/flatpickr.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{ URL::asset('build/plugins/quill/quill.bubble.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">

    <style>
        .flatpickr-wrapper {
            display: block !important;
            /* Agar mengikuti lebar container */
            width: 100%;
            /* Pastikan lebar penuh */
        }

        .flatpickr-wrapper input {
            width: 100%;
            /* Pastikan input mengikuti lebar penuh */
        }
    </style>
@endpush

@section('content')
    <x-page-title title="Pengeluaran" subtitle="Tambah Pengeluaran" />

    <form action="{{ roleBasedRoute('expense.store', ['outlet' => $outlet->slug]) }}" method="POST"
        enctype="multipart/form-data" id="form-expense" onSubmit="return false">

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
                        <div class="btn-group position-static">
                            <div class="btn-group position-static">
                                <button type="reset" class="btn btn-outline-primary">
                                    <i class="bi bi-eraser me-2"></i>Reset
                                </button>
                            </div>
                            <div class="btn-group position-static">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-send me-2"></i>Submit
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="msg-container">
            @if (session('success'))
                <x-alert-message type="success" :messages="session('success')" />
            @endif

            @if ($errors->any())
                <x-alert-message type="danger" :messages="$errors->all()" />
            @endif
        </div>

        <div class="row">

            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <h6 class="mb-2">Nama Pengeluaran</h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') ?? 'Pengeluaran ' . date('d-m-y H:i') }}" placeholder="..."
                                        required>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Deskripsi Pengeluaran</h6>
                                    <div class="quill-description quill-input form-control" data-placeholder="...">
                                        {!! old('description') ?? ($menu->description ?? '') !!}
                                    </div>
                                    <input type="hidden" name="description" id="description">
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Tanggal Waktu<span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control date-time w-100" id="date_out"
                                        name="date_out" value="{{ old('date_out') ?? date('d M Y H:i') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body mb-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="">
                                <h5 class="mb-0 fw-bold">Daftar Item<span class="fw-light ms-2"></span></h5>
                            </div>
                            <button type="button"
                                class="btn btn-outline-primary btn-sm px-4 d-flex gap-2 fw-bold btn-add-item"><i
                                    class="bi bi-plus-lg"></i> Item</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle" id="expense-item-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="">
                                                    <img src="https://placehold.co/110x110/png" class="rounded-circle"
                                                        width="50" height="50" alt="">
                                                </div>
                                                <p class="mb-0">Sports Shoes</p>
                                            </div>
                                        </td>
                                        <td>$149</td>
                                        <td>Julia Sunota</td>
                                        <td>
                                            <p class="dash-lable mb-0 bg-success bg-opacity-10 text-success rounded-2">
                                                Completed</p>
                                        </td>
                                        <td>
                                            <p class="dash-lable mb-0 bg-success bg-opacity-10 text-success rounded-2">
                                                Completed</p>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <p class="mb-0">5.0</p>
                                                <i class="material-icons-outlined text-warning fs-6">star</i>
                                            </div>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-bottom">
                        <p class="mb-0 fw-bold">Total</p>
                        <p class="mb-0 fw-bold item-total">0</p>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection

@push('modals')
    <div class="modal fade" id="AddItem" aria-labelledby="AddItemLabel" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddItemLabel">Tambah Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" onSubmit="return false">
                    <div class="modal-body pb-4">
                        <div class="row">
                            <div class="col-12 mb-3" id="nama-item">
                                <h6 class="mb-2">Nama <span class="text-danger">*</span></h6>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nama Item" required>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <h6 class="mb-2">Pilih Item <span class="text-danger">*</span></h6>
                                <select class="form-select select2-single select2-stock-item" id="stock_item_id"
                                    name="stock_item_id">
                                    <option id="add-outside-stock" value="">Diluar Item Stock</option>
                                    @foreach ($stockItems as $stockItem)
                                        <option value="{{ $stockItem->id }}"
                                            data-image-url="{{ $stockItem->image_url }}"
                                            data-description="{{ $stockItem->stock }} {{ $stockItem->unit->name }}"
                                            data-unit="{{ $stockItem->unit->name }}"
                                            data-price="{{ $stockItem->price }}" data-max-qty="{{ $stockItem->stock }}"
                                            {{ $stockItem->stock < 1 ? 'disabled' : '' }}>
                                            {{ $stockItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <h6 class="mb-2">Harga <span class="text-danger">*</span></h6>
                                <input type="text" class="form-control ignore" id="price" name="price"
                                    placeholder="" value="0" required>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <h6 class="mb-2">Jumlah <strong class="label-unit"></strong> <span
                                        class="text-danger">*</span></h6>

                                <div class="input-group w-auto qty-control">
                                    <button class="btn btn-inverse-secondary border decrement-button"><i
                                            class="bi bi-dash-lg"></i></button>
                                    <input type="text" id="quantity" name="quantity"
                                        class="form-control text-center shadow-none quantity-input ignore"
                                        placeholder="default '1'" value="1" required>
                                    <button class="btn  btn-inverse-secondary border increment-button"><i
                                            class="bi bi-plus-lg"></i></button>
                                </div>
                                {{-- 
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    placeholder="default '1'" value="1" required> --}}
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <h6 class="mb-2">Subtotal</h6>
                                <input type="text" class="form-control ignore" id="subtotal" name="subtotal"
                                    placeholder="" value="0" readonly disabled>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <h6 class="mb-2">Gambar ( Opsional )</h6>
                                <label class="picture-input-costum h-100" for="image"
                                    style="width: 100%; min-height: 120px;">
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

                            <div class="col-12 col-lg-9 mb-3 mt-4 mt-lg-0">
                                <h6 class="mb-2">Keterangan</h6>
                                <div class="quill-desc-expense-item quill-input form-control" data-placeholder="...">
                                </div>
                                <input type="hidden" name="description_item" id="description_item">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary add-item">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('script')
    @if (importOnce('js-select2'))
        <script src="{{ URL::asset('build/plugins/select2/js/select2.min.js') }}"></script>
    @endif
    <script src="{{ URL::asset('build/plugins/quill/quill.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/cropperjs/js/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/picture-input-costum.js?v=') . md5(time()) }}"></script>
    @if (importOnce('js-flatpickr'))
        <script src="{{ URL::asset('build/plugins/flatpickr/js/flatpickr.js') }}"></script>
    @endif

    <script>
        $(document).ready(function() {
            // modal atrribute
            const modalItem = $('#AddItem');
            const modalItemName = modalItem.find('[name="name"]');
            const modalItemPrice = modalItem.find('[name="price"]');
            const modalItemQuantity = modalItem.find('[name="quantity"]');
            const modalItemSubtotal = modalItem.find('[name="subtotal"]');
            const modalItemDescription = modalItem.find('[name="description_item"]');
            const modalItemImage = modalItem.find('[name="image"]');
            const modalItemStockId = modalItem.find('[name="stock_item_id"]');
            const modalItemUnit = modalItem.find('.label-unit');

            const expenseKey = 'expenseDataItemCreate';

            let expenseDataItem = [];
            let isPageLoaded = false;

            $(".date-time").flatpickr({
                static: true,
                enableTime: true,
                dateFormat: "d M Y H:i",
                defaultDate: {!! old('date_out') ? json_encode(date('d M Y H:i', strtotime(old('date_out')))) : 'new Date()' !!},
                minuteIncrement: 1,
                minDate: new Date().fp_incr(-7), // 7 hari ke belakang dari hari ini
                maxDate: new Date(), // Hari ini
            });

            let imageUploaderInstance = new ImageUploader({
                imageWidth: 400,
                imageHeight: 500,
                cropRatio: 4 / 5,
            });

            // inisialisasi Quill editor
            function initQuill(selector, inputName) {
                var quill = new Quill(selector, {
                    theme: 'bubble',
                    placeholder: $(selector).data('placeholder'),
                    modules: {
                        toolbar: [
                            [{
                                'header': 1
                            }, {
                                'header': 2
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['blockquote'],
                            ['link']
                        ]
                    },
                });

                // Atur tinggi minimum editor Quill
                $(selector).closest('.quill-description').find('.ql-editor').css('min-height', '100px');

                // Set nilai input tersembunyi saat form disubmit atau diubah
                $(selector).closest('form').on('submit input', function() {
                    $(`[name="${inputName}"]`).val(quill.root.innerHTML);
                });

                return quill;
            }

            // Inisialisasi editor
            var expenseDescription = initQuill('.quill-description', 'description');
            var expenseItemDescription = initQuill('.quill-desc-expense-item', 'description_item');

            // Inisialisasi Select2
            $('#stock_item_id').select2({
                dropdownParent: modalItem,
                theme: "bootstrap-5",
                templateResult: formatOption,
                templateSelection: formatSelection,
                escapeMarkup: function(markup) {
                    return markup; // Biarkan HTML ter-render
                }
            });

            // Fungsi untuk menampilkan dropdown dengan gambar, nama, dan deskripsi
            function formatOption(option) {
                if ($(option.element).attr('id') === "add-outside-stock") {
                    return `<div class="d-flex align-items-center">
                                <i class="bi bi-box-arrow-in-down me-2 fs-3 px-2"></i>
                                <span>Tambah item diluar stock</span>
                            </div>`;
                }

                const imageUrl = $(option.element).data('image-url') || "";
                const description = $(option.element).data('description');
                const maxQty = $(option.element).data('max-qty');
                const name = option.text;

                return `<div class="d-flex align-items-center">
                            <img src="${imageUrl}" alt="${name}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <div class="fw-bold">${name}</div>
                                <div class="small ${maxQty < 1 ? 'text-danger' : 'text-secondary'}">${description}</div>
                            </div>
                        </div>`;
            }

            // Fungsi untuk menampilkan elemen yang dipilih
            function formatSelection(option) {
                // Opsi kedua: Diluar Item Stock (menggunakan ikon Bootstrap)
                if ($(option.element).attr('id') === "add-outside-stock") {
                    return `<div style="display: flex; align-items: center;">
                                <i class="bi bi-box-arrow-in-down me-2"></i>
                                <span>Item Diluar Stock</span>
                            </div>`;
                }

                // Opsi lainnya (dengan gambar)
                const imageUrl = $(option.element).data('image-url') || "";
                const name = option.text;

                return `<div style="display: flex; align-items: center;">
                            <img src="${imageUrl}" alt="${name}" style="width: 20px; height: 20px; margin-right: 10px; object-fit: cover; border-radius: 4px;" />
                            <span>${name}</span>
                        </div>`;
            }


            //stock_item_id change
            modalItemStockId.on('change', function() {
                // console.log($(this).val());
                if ($(this).find(':selected').attr('id') === 'add-outside-stock') {
                    $('#nama-item').show();

                    modalItemName.val('');
                    modalItemPrice
                        .prop('disabled', false)
                        .prop('readonly', false).val('0');

                    modalItemSubtotal.val('0');
                    modalItemQuantity.removeAttr('max');
                    modalItemUnit.text('');
                    return;
                } else {
                    if (parseInt($(this).find(':selected').data('max-qty')) < parseInt(modalItemQuantity
                            .val())) {
                        modalItemQuantity.val($(this).find(':selected').data('max-qty'));
                    }
                    modalItemQuantity.attr('max', $(this).find(':selected').data('max-qty'));
                    modalItemUnit.text('/ ' + $(this).find(':selected').data('unit'));
                }

                $('#nama-item').hide();
                modalItemPrice
                    .prop('disabled', true)
                    .prop('readonly', true);

                let price = parseInt($(this).find(':selected').data('price'));
                let quantity = parseInt(modalItemQuantity.val()) || 0;
                modalItemPrice.val(formatRupiahText(price));
                modalItemSubtotal.val(formatRupiahText(price * quantity));
            });

            modalItemQuantity.on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                let price = modalItemPrice.val().replace(/[^0-9]/g, '');
                modalItemSubtotal.val(formatRupiahText(value * price));
            });

            modalItemPrice.on('input change', function(e) {
                formatRupiahElement(e.target);

                let quantity = parseInt(modalItemQuantity.val()) || 0;
                modalItemSubtotal.val(formatRupiahText(quantity * parseInt($(this)
                    .val().replace(/[^0-9]/g, ''))));
            });

            modalItemSubtotal.on('input change', function(e) {
                formatRupiahElement(e.target);
            });

            $('form').on('reset', function() {
                clearAlert();

                modalItemStockId.val("").trigger('change');
                modalItemPrice.prop('disabled', false).prop('readonly', false).val(
                    '0');
                modalItemDescription.val('');
                modalItem.find('#nama-item').show();
                modalItemName.val('');
                modalItem.find('[name="image"]').val('');
                expenseItemDescription.root.innerHTML = '';
            });


            // save expense item
            function saveExpenseItem() {
                localStorage.setItem(expenseKey, JSON.stringify(expenseDataItem));
            }

            // load expense item
            function loadExpenseItem() {
                expenseDataItem = JSON.parse(localStorage.getItem(expenseKey)) || [];
                expenseDataItem.forEach(function(item) {

                    if (!item.id) {
                        item.id = generateUniqueId();
                    }

                    if (item.stock_item_id) {
                        //console.log(item.stock_item_id);
                        const option = modalItem.find(
                            `[name="stock_item_id"] option[value="${item.stock_item_id}"]`);

                        if (option.length === 0) {
                            item.stock_item_id = null;
                        } else {
                            const maxQuantity = option.data('max-qty');

                            if (maxQuantity < 1) {
                                expenseDataItem = expenseDataItem.filter(function(item) {
                                    return item.stock_item_id !== item.stock_item_id;
                                });
                                return;
                            }

                            if (item.quantity > maxQuantity) {
                                item.quantity = maxQuantity;
                            }
                            item.subtotal = item.price * item.quantity;
                        }
                        item.price = option.data('price');
                        item.name = option.text().trim();
                    }

                    if (!isPageLoaded) item.image_upload = null;
                    rowExpenseItem(item);
                });

                // if (expenseDataItem.length === 0) {
                //     $('#expense-item-table > tbody').append(
                //         '<tr><td colspan="6" class="text-center">Belum ada item.</td></tr>');
                //     return;
                // }
                saveExpenseItem();
                totalItem();
            }

            // total item
            function totalItem() {
                let total = expenseDataItem.reduce((acc, item) => acc + item.subtotal, 0);
                $('.item-total').text(formatRupiahText(total));
            }

            function generateUniqueId() {
                return 'item-' + Math.random().toString(36).substr(2, 9);
            }

            function handleAddOrUpdateItem(modalItem, expenseItemDescription) {
                // Mengambil nilai dari form
                const stock_item_id = modalItemStockId.find(':selected').attr('id') ===
                    'add-outside-stock' ? null : modalItemStockId.val();
                const stock_item_name = stock_item_id === null ? modalItemName.val() :
                    modalItem.find('[name="stock_item_id"] option:selected').text().trim();
                const image = modalItem.find('[name="image"]').prop('files')[0];

                const stock_item_image = !stock_item_id ? null : modalItem.find(
                    '[name="stock_item_id"] option:selected').data('image-url');
                const image_upload = image ? URL.createObjectURL(image) : null;


                const price = parseInt(modalItemPrice.val().replace(/[^0-9]/g, '')) || 0;
                const quantity = parseInt(modalItemQuantity.val()) || 0;
                const subtotal = price * quantity;
                const description = expenseItemDescription.root.innerHTML;

                clearAlert();

                if (!stock_item_id && !stock_item_name) {
                    modalItem.find('.modal-body').alertError('Nama item harus diisi');
                    return;
                }
                if (!stock_item_id && !price) {
                    modalItem.find('.modal-body').alertError('Harga item harus diisi');
                    return;
                }
                if (!quantity) {
                    modalItem.find('.modal-body').alertError('Jumlah item harus diisi');
                    return;
                }

                // Mencari row yang sudah ada berdasarkan UID atau stock_item_id
                let existingRow = expenseDataItem.find(item => modalItem.data('uid') && item.id === modalItem.data(
                    'uid'));
                let existingRowStockItemId = expenseDataItem.find(item => item.stock_item_id !== null && item
                    .stock_item_id === stock_item_id);

                if (existingRow && existingRowStockItemId) {
                    // Jika kedua-duanya ada, periksa apakah ID mereka berbeda
                    if (existingRow.id !== existingRowStockItemId.id) {
                        existingRowStockItemId.quantity += quantity;
                        existingRowStockItemId.subtotal = existingRowStockItemId.quantity * existingRowStockItemId
                            .price;
                        expenseDataItem = expenseDataItem.filter(item => item.id !== existingRow.id && item
                            .stock_item_id !== existingRow.stock_item_id);
                    } else {
                        // Jika ID sama, update data yang ada
                        existingRow.stock_item_id = stock_item_id,
                            existingRow.name = stock_item_name;
                        existingRow.image = stock_item_image;
                        existingRow.price = price;
                        existingRow.quantity = quantity;
                        existingRow.subtotal = subtotal;
                        existingRow.image_upload = image_upload;
                        existingRow.description = description;
                    }
                } else if (existingRow && !existingRowStockItemId) {
                    // Jika hanya row yang ada, update data
                    existingRow.stock_item_id = stock_item_id,
                        existingRow.name = stock_item_name;
                    existingRow.image = stock_item_image;
                    existingRow.price = price;
                    existingRow.quantity = quantity;
                    existingRow.subtotal = subtotal;
                    existingRow.image_upload = image_upload;
                    existingRow.description = description;
                } else if (!existingRow && existingRowStockItemId) {
                    // Jika hanya stock_item_id yang ada, tambahkan quantity dan subtotal
                    existingRowStockItemId.quantity += quantity;
                    existingRowStockItemId.subtotal = existingRowStockItemId.quantity * existingRowStockItemId
                        .price;
                    existingRowStockItemId.image_upload = image_upload;
                } else {
                    // Jika tidak ada data yang ada, tambahkan item baru
                    expenseDataItem.push({
                        stock_item_id: stock_item_id,
                        name: stock_item_name,
                        image: stock_item_image,
                        image_upload: image_upload,
                        price: price,
                        quantity: quantity,
                        subtotal: subtotal,
                        description: description
                    });
                }

                // Reset data UID dan form
                modalItem.data('uid', null);
                saveExpenseItem();
                resetRowExpenseItem();
                loadExpenseItem();

                modalItem.find('form')[0].reset();
                modalItem.modal('hide');
            }

            // Find existing row based on stock_item_id
            function findExistingRow(stock_item_id) {
                return $('#expense-item-table > tbody tr').filter(function() {
                    return $(this).data('stockItemId') === stock_item_id;
                });
            }

            function resetRowExpenseItem() {
                $('#expense-item-table > tbody').empty();
            }

            // Add a new row to the table
            function rowExpenseItem(DataItem) {

                const btnRemove = $('<button>', {
                    type: 'button',
                    class: 'btn btn-outline-danger btn-sm remove',
                    html: '<i class="bi bi-trash"></i>'
                }).click(function() {
                    expenseDataItem = expenseDataItem.filter(function(item) {
                        return item.stock_item_id !== DataItem.stock_item_id;
                    });
                    saveExpenseItem();
                    totalItem();
                    $(this).closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                });

                const btnUpdate = $('<button>', {
                    type: 'button',
                    class: 'btn btn-outline-primary btn-sm update ms-2',
                    html: '<i class="bi bi-pencil"></i>'
                }).click(function() {
                    modalItem.find('.modal-title').text('Update Item');
                    showUpdateModal(DataItem);
                });

                const row = $('<tr>')
                    .data('itemId', DataItem.id)
                    .append(
                        `<td>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <img src="${!DataItem.image_upload ? (!DataItem.image ? "{{ asset('build/images/placeholder-image.webp') }}" : DataItem.image ) : DataItem.image_upload }" class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;" alt="${DataItem.name}">
                                </div>
                                <div class="product-info" >
                                    <div class="fw-bold">${DataItem.name}</div>
                                    <span class="badge rounded-pill ${!DataItem.stock_item_id ? 'bg-dark' : 'bg-primary'}">${!DataItem.stock_item_id ? 'Not Linked Stock' : 'Linked Stock'}</span>
                                </div>
                            </div>
                        </td>`,
                        `<td>${formatRupiahText(DataItem.price)}</td>`,
                        `<td class="text-center">${DataItem.quantity}</td>`,
                        `<td>${DataItem.description.replace(/(<([^>]+)>)/gi, "").substring(0, 16) + '...'}</td>`,
                        `<td>${formatRupiahText(DataItem.subtotal)}</td>`,
                        $('<td>').append(btnRemove, btnUpdate)
                    );

                $('#expense-item-table > tbody').append(row);
            }

            function showUpdateModal(DataItem) {
                const modalItem = $('#AddItem');

                modalItem.data('uid', DataItem.id);
                modalItemStockId.val(DataItem.stock_item_id).trigger('change');

                modalItemName.val(DataItem.name);
                modalItemQuantity.val(DataItem.quantity);
                modalItemSubtotal.val(formatRupiahText(DataItem.subtotal));

                if (DataItem.stock_item_id === null)
                    modalItemPrice.val(DataItem.price).trigger('change');
                expenseItemDescription.root.innerHTML = DataItem.description;

                if (DataItem.image_upload !== null) {
                    fetch(DataItem.image_upload)
                        .then(res => res.blob())
                        .then(blob => {
                            const file = new File([blob], "image.jpg", {
                                type: blob.type
                            });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            modalItem.find('#image')[0].files = dataTransfer.files;
                        });

                    modalItem.find('#image').attr('data-image-src', DataItem.image_upload);
                    imageUploaderInstance.updateImagePreview();
                }

                modalItem.modal('show');
            }

            // Event handler for Add Item button
            $('.add-item').click(function() {
                handleAddOrUpdateItem(modalItem, expenseItemDescription);
            });

            // Event handler for Add Item button
            $('.btn-add-item').click(function() {
                modalItem.find('.modal-title').text('Tambah Item');
                modalItem.data('uid', null);
                modalItem.find('form')[0].reset();
                modalItem.modal('show');
            });

            loadExpenseItem();
            isPageLoaded = true;

            $('#form-expense').on('submit', function(event) {
                let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                event.preventDefault();

                let formData = new FormData();

                // Tambahkan data form ke FormData
                formData.append('name', $(this).find('[name="name"]').val());
                formData.append('description', $(this).find('[name="description"]').val());
                formData.append('date_out', $(this).find('[name="date_out"]').val());

                // Fungsi untuk mengonversi URL gambar menjadi Blob
                function urlToBlob(url) {
                    return fetch(url)
                        .then(response => response.blob())
                        .then(blob => {
                            return blob;
                        });
                }

                // Tambahkan setiap item ke FormData
                const promises = expenseDataItem.map((item, index) => {
                    formData.append(`items[${index}][stock_item_id]`, item.stock_item_id);
                    formData.append(`items[${index}][name]`, item.name);
                    formData.append(`items[${index}][price]`, item.price);
                    formData.append(`items[${index}][quantity]`, parseInt(item.quantity));
                    formData.append(`items[${index}][subtotal]`, item.subtotal);
                    formData.append(`items[${index}][description]`, item.description);

                    // Jika ada gambar, tambahkan ke FormData
                    if (item.image_upload) {
                        let filePromise;

                        // Konversi URL gambar menjadi file jika belum berbentuk File
                        if (item.image_upload instanceof File) {
                            filePromise = Promise.resolve(item.image_upload);
                        } else {
                            filePromise = urlToBlob(item.image_upload).then(blob => {
                                return new File([blob], `image-${index}.jpg`, {
                                    type: blob.type
                                });
                            });
                        }

                        return filePromise.then(file => {
                            formData.append(`items[${index}][image]`, file);
                        });
                    }

                    return Promise.resolve();
                });

                clearAlert();

                // Tunggu semua gambar dikonversi sebelum mengirim data
                Promise.all(promises).then(() => {
                    // Kirim data menggunakan AJAX
                    $.ajax({
                        url: "{{ roleBasedRoute('expense.store', ['outlet' => $outlet->slug]) }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            if (response.status) {
                                expenseDataItem = [];
                                saveExpenseItem();
                                localStorage.removeItem(expenseKey);

                                window.location.href =
                                    "{{ roleBasedRoute('expense.index', ['outlet' => $outlet->slug]) }}";
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            let message = xhr.responseJSON.message;
                            let errorMessage = '';

                            if (errors || message) {
                                if (message) {
                                    errorMessage += message + '<br>';
                                }

                                for (let key in errors) {
                                    errorMessage += errors[key][0] + '<br>';
                                }

                                $('.msg-container').alertError(
                                    errorMessage
                                );
                            }
                        }
                    });
                });
            });


        });
    </script>
@endpush
