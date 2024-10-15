@extends('layouts.app')
@section('title')
    {{ __('Stock') }}
@endsection

@push('css')

@endpush

@section('content')
    <x-page-title title="Stock" subtitle="Manajemen Stock" />

    <form action="{{ route('outlet.stock-item.store', ['outlet' => $outlet->slug]) }}" method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="card mb-3">
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
                    <div class="overflow-auto">
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

            <div class="col-12 col-lg-3">

                <div class="card">
                    <img src="{{ asset('build/images/placeholder-image.webp') }}"
                        style="width: 100%; height: 179px; object-fit: cover;" class="card-img-top" id="image-preview"
                        alt="">
                    <div class="card-body">
                        <h6 class="mb-3">Gambar</h6>
                        <input type="file" class="form-control form-control-sm" id="image" name="image"
                            accept="image/*">
                    </div>
                    <script>
                        document.getElementById('image').addEventListener('change', function(e) {
                            var file = e.target.files[0];
                            var reader = new FileReader();
                            reader.onloadend = function() {
                                document.getElementById('image-preview').src = reader.result;
                            }
                            reader.readAsDataURL(file);
                        });
                    </script>
                </div>

            </div>

            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="mb-3">
                                    <h6 class="mb-2">Nama Item <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="..." required>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2">Deskripsi Item</h6>
                                    <textarea class="form-control" id="description" name="description" cols="4" rows="3" placeholder="...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-lg-8 mb-3">

                                <div class="row g-3">
                                    <div class="col-12 col-lg-6">
                                        <h6 class="mb-2">Stok Awal</h6>
                                        <input class="form-control" type="number" id="stock" name="stock"
                                            placeholder="Jumlah Stok Awal" value="{{ old('stock') }}" required>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <x-unit-select :selectedUnit="old('unit_id')" />
                                        {{-- <div class="input-group">
                                            <select class="form-select select2-single" id="unit_id" name="unit_id"
                                                required>
                                                <option disabled selected>Pilih Satuan</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-outline-secondary" type="button" id="add-unit"
                                                style="display: none;">Tambah</button>
                                            <button class="btn btn-outline-danger" type="button"
                                                id="remove-unit">Hapus</button>
                                        </div> --}}
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6 class="mb-2">Minimal Stok
                                            <i class="bi bi-question-circle-fill text-info" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Jumlah minimum stok yang harus tersedia."></i>
                                        </h6>
                                        <div class="input-group">
                                            <span class="input-group-text text-danger"><i
                                                    class="material-icons-outlined fs-5">priority_high</i></span>
                                            <input type="number" class="form-control" id="min_stock" name="min_stock"
                                                placeholder="Default '0'" value="{{ old('min_stock') }}">
                                        </div>
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

<!-- Modal -->
@push('modals')
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');

            // Format saat halaman dimuat
            formatRupiahElement(priceInput);

            // Tambahkan event listener untuk memformat saat input berubah
            priceInput.addEventListener('input', function(e) {
                formatRupiahElement(e.target);
            });
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
