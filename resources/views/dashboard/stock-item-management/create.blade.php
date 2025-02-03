@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/quill/quill.bubble.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <x-page-title title="Stock" subtitle="Tambah Item" />

    <form action="{{ roleBasedRoute('stock-item.store', ['outlet' => $outlet->slug]) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('POST')

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

        <div class="alert alert-info border-0 bg-grd-info alert-dismissible fade show msg-etalase d-none">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white"><span class="material-icons-outlined fs-2">info</span>
                </div>
                <div class="ms-3">
                    <h5 class="mb-0 text-white fw-bold">Informasi</h5>
                    <div class="text-white">
                        <ul class="mb-1">
                            <li>Item dengan kategori <strong>Etalase</strong> dapat ditautkan ke menu.</li>
                            <li>Item dengan kategori <strong>Etalase</strong> tidak memiliki harga, tentukan harga di menu.
                            </li>
                            <li>Item yang ditautkan ke menu bisa lebih dari satu dan stoknya akan otomatis berkurang saat
                                menu tersebut dibeli.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-lg-3 mb-3">
                                <h6 class="mb-2">Gambar Item</h6>
                                <label class="picture-input-costum" for="image" style="width: 100%; height: 200px;">
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
                                <input type="file" name="image" class="picture__input" id="image">
                            </div>

                            <div class="col-12 col-lg-3">
                                <div class="mb-3">
                                    <h6 class="mb-2">Nama Item <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="..." required>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Deskripsi Item</h6>
                                    <div class="quill-description quill-input form-control" data-placeholder="...">
                                        {!! old('description') ?? ($menu->description ?? '') !!}
                                    </div>
                                    <input type="hidden" name="description" id="description">
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <x-category-item-select :selectedCategory="old('category_id')" />
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6 class="mb-2">Stok Awal</h6>
                                        <input class="form-control" type="number" id="stock" name="stock"
                                            placeholder="Jumlah Stok Awal" value="{{ old('stock') }}">
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <x-unit-select :selectedUnit="old('unit_id')" />
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6 class="mb-2">Minimal Stok
                                            <i class="bi bi-question-circle-fill text-info" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Jumlah minimum stok yang harus tersedia."></i>
                                        </h6>
                                        <input class="form-control text-danger" type="number" id="min_stock"
                                            name="min_stock" placeholder="Default '0'" value="{{ old('min_stock') }}">
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6 class="mb-2">Harga</h6>
                                        <div class="input-group">
                                            <span class="input-group-text">IDR</span>
                                            <input type="text" class="form-control" id="price" name="price"
                                                placeholder="default '0'" value="{{ old('price') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ URL::asset('build/plugins/quill/quill.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/cropperjs/js/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/picture-input-costum.js?v=') . md5(time()) }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');

            // Format saat halaman dimuat
            formatRupiahElement(priceInput);

            // Tambahkan event listener untuk memformat saat input berubah
            priceInput.addEventListener('input', function(e) {
                formatRupiahElement(e.target);
            });

            new ImageUploader({
                imageWidth: 400,
                imageHeight: 500,
                cropRatio: 4 / 5,
            });

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
            $('.quill-description .ql-editor').css('min-height', '118px');
            $('form').on('submit', function() {
                $('[name="description"]').val(quill.root.innerHTML);
            });

            $("select[name='category_id']").on('change', function(e) {
                let category_id = e.target.value;
                let price = $('input[name="price"]');
                let msgboxEtalase = $('.msg-etalase');

                console.log('price', price.val());

                if (category_id == 1) {
                    price.val(0);
                    price.closest('.col-12.col-lg-6').hide();
                    msgboxEtalase.removeClass('d-none');
                } else {
                    price.closest('.col-12.col-lg-6').show();
                    msgboxEtalase.addClass('d-none');
                }
            });

            $("select[name='category_id']").trigger('change');

        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('.select2-single').select2({
                theme: "bootstrap-5",
                width: function() {
                    return $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ?
                        '100%' : 'style';
                },
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                tags: true, // Mengizinkan penambahan data baru
                createTag: function(params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true // Menandai tag baru
                    };
                },
                templateResult: function(data) {
                    var $result = $("<span></span>");
                    $result.text(data.text);
                    if (data.newTag) {
                        $result.append(" <em>(baru)</em>");
                    }
                    return $result;
                }
            }).on('select2:select', function(e) {
                var data = e.params.data;
                if (data.newTag) {
                    $('#add-unit').show();
                } else {
                    $('#add-unit').hide();
                }
            });

            // Event listener untuk tombol tambah
            $('#add-unit').on('click', function() {
                var newUnitName = $('.select2-single').val();
                // Logika untuk menambah unit baru
                $.ajax({
                    type: 'POST',
                    url: '/path/to/your/api/add', // Ganti dengan URL endpoint API Anda
                    data: {
                        _token: '{{ csrf_token() }}', // Token CSRF untuk keamanan
                        name: newUnitName // Nama unit baru yang akan ditambahkan
                    },
                    success: function(response) {
                        // Tambahkan unit baru ke dalam Select2
                        var newOption = new Option(response.name, response.id, false, true);
                        $('.select2-single').append(newOption).trigger('change');
                        $('#add-unit')
                    .hide(); // Sembunyikan tombol tambah setelah unit ditambahkan
                    }
                });
            });

            // Event listener untuk tombol hapus
            $('#remove-unit').on('click', function() {
                var selectedId = $('#unit_id').val();
                if (selectedId) {
                    var $option = $('.select2-single option[value="' + selectedId + '"]');
                    $option.remove();
                    $('.select2-single').trigger('change');

                    // Kirim permintaan penghapusan ke server jika diperlukan
                    $.ajax({
                        type: 'POST',
                        url: '/path/to/your/api/delete', // Ganti dengan URL endpoint API Anda
                        data: {
                            _token: '{{ csrf_token() }}', // Token CSRF untuk keamanan
                            id: selectedId // ID data yang akan dihapus
                        },
                        success: function(response) {
                            console.log('Data berhasil dihapus');
                        }
                    });
                }
            });
        });
    </script> --}}
@endpush
