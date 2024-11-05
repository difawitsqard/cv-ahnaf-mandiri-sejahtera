@extends('layouts.app')
@section('title')
    {{ __('Info Perusahaan') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/quill/quill.bubble.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <x-page-title title="Informasi Perusahaan" subtitle="Informasi" />

    @if (session('success'))
        <x-alert-message type="success" :messages="session('success')" />
    @endif

    @if ($errors->any())
        <x-alert-message type="danger" :messages="$errors->all()" />
    @endif

    <form action="{{ route('company-info.create_or_update') }}" id="form-company-info" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Info Perusahaan</h5>
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Nama Perusahaan</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') ?? ($CompanyInfo->name ?? '') }}" placeholder="This company"
                                maxlength="55">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="short_name" class="form-label">Nama Pendek Perusahaan</label>
                            <input type="text" name="short_name" class="form-control" id="short_name"
                                value="{{ old('short_name') ?? ($CompanyInfo->short_name ?? '') }}"
                                placeholder="TCompany ( Optional )" maxlength="16">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="tagline" class="form-label">Tagline Perusahaan</label>
                            <input type="text" name="tagline" class="form-control"
                                value="{{ old('tagline') ?? ($CompanyInfo->tagline ?? '') }}"
                                placeholder="Your Trusted Partner for Success">
                        </div>
                        <p class="mb-2">Tentang Perusahaan</p>
                        <div class="company-about bg-light rounded-2" data-placeholder="...">
                            {!! old('description') ?? ($CompanyInfo->about_us ?? '') !!}
                        </div>
                        <input type="hidden" name="about_us" id="about_us">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Informasi lainnya</h5>
                        <div class="row mb-3">
                            <label for="address" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea type="text" name="address" class="form-control" id="address" rows="3"
                                    placeholder="123 Company St, City, Country">{{ old('address') ?? ($CompanyInfo->address ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email') ?? ($CompanyInfo->email ?? '') }}"
                                    placeholder="info@example.com">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="phone" class="col-sm-2 col-form-label">Telepon</label>
                            <div class="col-sm-10">
                                <input type="text" name="phone" class="form-control" id="phone"
                                    value="{{ old('phone') ?? ($CompanyInfo->phone ?? '') }}" placeholder="0812XXXXXXXX">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="whatsapp" class="col-sm-2 col-form-label">WhatsApp</label>
                            <div class="col-sm-10">
                                <input type="whatsapp" name="whatsapp" class="form-control" id="whatsapp"
                                    value="{{ old('whatsapp') ?? ($CompanyInfo->whatsapp ?? '') }}"
                                    placeholder="0812XXXXXXXX">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="instagram" class="col-sm-2 col-form-label">Instagram</label>
                            <div class="col-sm-10">
                                <input type="instagram" name="instagram" class="form-control" id="instagram"
                                    value="{{ old('instagram') ?? ($CompanyInfo->instagram ?? '') }}"
                                    placeholder="Username instagram">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary ms-2">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    <script src="{{ URL::asset('build/plugins/quill/quill.js') }}"></script>
    <script>
        $(document).ready(function() {
            //quill editor
            var quill = new Quill('.company-about', {
                theme: 'bubble',
                placeholder: $('.company-about').data('placeholder'),
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
            $('.company-about .ql-editor').css('min-height', '155px');
            $('form').on('submit', function() {
                $('[name="about_us"]').val(quill.root.innerHTML);
            });
        });
    </script>
@endpush
