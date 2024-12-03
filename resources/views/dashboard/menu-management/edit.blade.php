@extends('layouts.app')
@section('title')
    {{ __('Menu') }}
@endsection

@push('css')
    {{-- <link href="{{ URL::asset('build/plugins/quill/quill.snow.css') }}" rel="stylesheet" /> --}}
    <link href="{{ URL::asset('build/plugins/quill/quill.bubble.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/picture-input-costum.css') }}" rel="stylesheet">
@endpush

@section('content')
    <x-page-title title="Menu" subtitle="Edit Menu" />

    <form action="{{ roleBasedRoute('menu.update', ['outlet' => $outlet->slug, 'menu' => $menu->id]) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

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

        @if (session('success'))
            <x-alert-message type="success" :messages="session('success')" />
        @endif


        @if ($errors->any())
            <x-alert-message type="danger" :messages="$errors->all()" />
        @endif

        <div class="row">

            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-5">
                                <h6 class="mb-2">Gambar Menu</h6>
                                <div class="row">

                                    @php
                                        $existingImagesCount = $menu->menuImages->count();
                                        $placeholdersNeeded = max(0, 4 - $existingImagesCount);
                                    @endphp

                                    <!-- Tampilkan gambar yang ada -->
                                    @foreach ($menu->menuImages as $key => $menuImage)
                                        <div class="col-6 mb-2">
                                            <label class="picture-input-costum" for="menu_pict_{{ $key + 1 }}"
                                                style="width: 100%; height: 160px;">
                                                <span class="picture__image"></span>
                                                <span class="picture__text"><span
                                                        class="material-icons-outlined">add_photo_alternate</span></span>
                                                <div class="picture__buttons">
                                                    <button type="button"
                                                        class="btn btn-light btn-sm d-flex crop-btn"><span
                                                            class="material-icons-outlined">crop</span></button>
                                                    <button type="button"
                                                        class="btn btn-light btn-sm d-flex delete-btn"><span
                                                            class="material-icons-outlined">delete</span></button>
                                                </div>
                                            </label>
                                            <input type="file" name="menu_pict_{{ $key + 1 }}"
                                                class="picture__input" id="menu_pict_{{ $key + 1 }}"
                                                data-image-src="{{ $menuImage->image_url }}"
                                                data-image-id="{{ $menuImage->id }}">
                                        </div>
                                    @endforeach

                                    <!-- Tambahkan placeholder jika kurang dari 4 gambar -->
                                    @for ($i = 0; $i < $placeholdersNeeded; $i++)
                                        <div class="col-6 mb-2">
                                            <label class="picture-input-costum"
                                                for="menu_pict_{{ $existingImagesCount + $i + 1 }}"
                                                style="width: 100%; height: 160px;">
                                                <span class="picture__image"></span>
                                                <span class="picture__text"><span
                                                        class="material-icons-outlined">add_photo_alternate</span></span>
                                                <div class="picture__buttons">
                                                    <button type="button"
                                                        class="btn btn-light btn-sm d-flex crop-btn"><span
                                                            class="material-icons-outlined">crop</span></button>
                                                    <button type="button"
                                                        class="btn btn-light btn-sm d-flex delete-btn"><span
                                                            class="material-icons-outlined">delete</span></button>
                                                </div>
                                            </label>
                                            <input type="file" name="menu_pict_{{ $existingImagesCount + $i + 1 }}"
                                                class="picture__input" id="menu_pict_{{ $existingImagesCount + $i + 1 }}">
                                        </div>
                                    @endfor


                                </div>
                            </div>
                            <div class="col-12 col-lg-7">
                                <div class="mb-4">
                                    <h6 class="mb-2">Nama Menu <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $menu->name }}" placeholder="..." required>
                                </div>
                                <div class="mb-4">
                                    <h6 class="mb-2">Deskripsi Menu</h6>
                                    <div class="quill-description quill-input form-control" data-placeholder="...">
                                        {!! old('description') ?? ($menu->description ?? '') !!}
                                    </div>
                                    <input type="hidden" name="description" id="description">
                                </div>
                                <div class="mb-4">
                                    <h6 class="mb-2">Harga</h6>
                                    <div class="input-group">
                                        <span class="input-group-text">IDR</span>
                                        <input type="text" class="form-control" id="price" name="price"
                                            placeholder="default '0'" value="{{ $menu->price }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between pb-2">
                            <div class="">
                                <h5 class="fw-bold">Link stock item</h5>
                            </div>
                            <div class="position-relative">
                                <input class="form-control" type="search" id="table-stock-item-search"
                                    placeholder="Cari...">
                            </div>
                        </div>
                        <div class="product-table">
                            <div class="table-responsive white-space-nowrap">
                                <table class="table align-middle stock-item-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <input class="form-check-input" type="checkbox" id="select-all">
                                            </th>
                                            <th>Stock Item</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sortedItems as $item)
                                            @php
                                                $stockItem = $item['stockItem'];
                                                $menuStockItem = $item['menuStockItem'];
                                                $isChecked = !is_null($menuStockItem);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input name="stock_item_id[]" class="form-check-input row-checkbox"
                                                        type="checkbox" value="{{ $stockItem->id }}"
                                                        {{ $isChecked ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="product-box">
                                                            <img src="{{ $stockItem->image_url }}"
                                                                style="width: 50px; height: 40px; object-fit: cover;"
                                                                class="rounded-3" alt="{{ $stockItem->name }}">
                                                        </div>
                                                        <div class="product-info">
                                                            <a href="javascript:;"
                                                                class="product-title">{{ $stockItem->name }}</a>
                                                            <p class="mb-0 product-category no-export">
                                                                <b>{{ $stockItem['stock'] }}</b>
                                                                {{ $stockItem['unit']->name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control border-0 w-100"
                                                        name="quantity[]" placeholder="..."
                                                        style="background-color: transparent;"
                                                        {{ $isChecked ? '' : 'disabled' }}
                                                        value="{{ $isChecked ? $menuStockItem->pivot->quantity : '' }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

<!-- Modal -->
@push('modals')

@endpush

@push('script')
    <script src="{{ URL::asset('build/plugins/quill/quill.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/cropperjs/js/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/picture-input-costum.js?v=') . md5(time()) }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');

            formatRupiahElement(priceInput);

            priceInput.addEventListener('input', function(e) {
                formatRupiahElement(e.target);
            });

            const options = {
                imageWidth: 400, // Set your desired image width here
                imageHeight: 500, // Set your desired image height here
                cropRatio: 4 / 5, // Set your desired crop ratio here (e.g., '1' for 1:1, '16/9' for 16:9)
            };
            new ImageUploader(options); // Initialize the class with options

            //quill editor
            var quill = new Quill('.quill-description', {
                theme: 'bubble',
                placeholder: $('.quill-description').data('placeholder'),
                modules: {
                    toolbar: [
                        // [{ 'header': [1, 2, false] }],
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
            $('.quill-description .ql-editor').css('min-height', '155px');
            $('form').on('submit', function() {
                $('[name="description"]').val(quill.root.innerHTML);
            });

            // data table
            var table = $('.stock-item-table').DataTable({
                "pageLength": 4,
                "searching": true,
                "bLengthChange": false,
                "bFilter": false,
                "bInfo": false,
                "bAutoWidth": false,
                "ordering": false,
                "pagingType": "simple"
            });

            // Hide default search box
            $('.dataTables_filter').hide();

            $('#table-stock-item-search').on('input', function() {
                table.search($(this).val()).draw();
            });

            // Handle select all checkbox
            $('#select-all').on('change', function() {
                var isChecked = $(this).is(':checked');

                // Set all checkboxes based on select-all checkbox
                table.rows().nodes().to$().find('.row-checkbox').prop('checked', isChecked);

                // Enable or disable quantity inputs based on select-all checkbox
                table.rows().nodes().to$().find('[name="quantity[]"]').each(function() {
                    if (isChecked) {
                        // Enable input and restore saved quantity if exists, otherwise keep current value
                        $(this).prop('disabled', false);
                        var savedQuantity = $(this).data('quantity');
                        if (savedQuantity !== undefined && savedQuantity !== null) {
                            $(this).val(savedQuantity);
                        } else {
                            var currentQuantity = $(this).val() || 1;
                            $(this).val(currentQuantity);
                        }
                    } else {
                        // Disable input and save the current quantity
                        var currentQuantity = $(this).val() || 1;
                        $(this).data('quantity', currentQuantity).val('').prop('disabled', true);
                    }
                });
            });

            // Handle individual row checkbox
            $(document).on('change', '.row-checkbox', function() {
                var isChecked = $(this).is(':checked');
                var quantityInput = $(this).closest('tr').find('[name="quantity[]"]');

                if (isChecked) {
                    // Enable input and restore saved quantity if exists
                    quantityInput.prop('disabled', false);
                    var savedQuantity = quantityInput.data('quantity');
                    if (savedQuantity !== undefined && savedQuantity !== null) {
                        quantityInput.val(savedQuantity);
                    } else {
                        var currentQuantity = quantityInput.val() || 1;
                        quantityInput.val(currentQuantity);
                    }
                } else {
                    // Disable input and save the current quantity
                    var currentQuantity = quantityInput.val() || 1;
                    quantityInput.data('quantity', currentQuantity).val('').prop('disabled', true);
                }

                // If all checkboxes are checked, check the select-all checkbox
                var allCheckboxes = table.rows().nodes().to$().find('.row-checkbox');
                var checkedCheckboxes = table.rows().nodes().to$().find('.row-checkbox:checked');

                if (checkedCheckboxes.length === allCheckboxes.length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });

            $(document).on('input', '[name="quantity[]"]', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                if (value < 1) value = 1;
                $(this).val(value);
            });

        });
    </script>
@endpush
