@push('css')
    @if (importOnce('css-select2'))
        <link href="{{ URL::asset('build/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ URL::asset('build/plugins/select2/css/select2-bootstrap-5.min.css') }}" rel="stylesheet" />
    @endif
@endpush


<div class="unit-select">
    <h6 class="mb-2">Satuan / Unit
        <i class="bi bi-question-circle-fill text-info" data-bs-toggle="tooltip" data-bs-placement="top"
            title="Akan ditempatkan setelah jumlah stok ( cth:1 ekor )"></i>
        <span class="text-danger">*</span>
    </h6>
    <select class="form-select select2-single select2-unit" id="unit_id" name="unit_id" required>
        <option disabled selected>Pilih Satuan</option>
        @foreach ($units as $unit)
            <option value="{{ $unit->id }}" {{ $selectedUnit == $unit->id ? 'selected' : '' }}>
                {{ $unit->name }}
            </option>
        @endforeach
    </select>
</div>

@push('script')
    @if (importOnce('js-select2'))
        <script src="{{ URL::asset('build/plugins/select2/js/select2.min.js') }}"></script>
    @endif

    <script>
        $(document).ready(function() {
            $('.select2-unit').select2({
                theme: "bootstrap-5",
                // width: function() {
                //     return $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ?
                //         '100%' : 'style';
                // },
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
                },
                templateSelection: function(data) {
                    if (data.newTag) {
                        return $('<span>' + data.text +
                            ' <em class="text-muted">(draft)</em></span>');
                    } else {
                        return $('<span>' + data.text + '</span>');
                    }
                }
            });
        });
    </script>
@endpush
