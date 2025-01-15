@extends('layouts.app')
@section('title')
    {{ __('Pengaturan') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/quill/quill.bubble.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <x-page-title title="Akun" subtitle="Pengaturan Akun" />

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card rounded-4">
                <div class="card-header p-3">
                    <h5 class="mb-0 fw-bold">Informasi Akun</h5>
                </div>
                <div class="card-body">

                    @if (session('form-name') == 'account-info' && session('success'))
                        <x-alert-message type="success" :messages="session('success')" />
                    @endif

                    @if (old('form-name') == 'account-info' && $errors->any())
                        <x-alert-message type="danger" :messages="$errors->all()" />
                    @endif

                    <form action="{{ route('account-update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="form-name" value="account-info">

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="{{ old('name') ?? ($user->name ?? '') }}" placeholder="Name" maxlength="55">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control border-end-0"
                                            id="email" placeholder="email@example.com"
                                            value="{{ old('email') ?? ($user->email ?? '') }}" required>
                                        <div class="input-group-text bg-transparent">
                                            @if ($user->email_verified_at)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="mobile_phone_number" class="form-label">Nomor Hp</label>
                                    <input type="number" name="mobile_phone_number" class="form-control"
                                        id="mobile_phone_number"
                                        value="{{ old('mobile_phone_number') ?? ($user->mobile_phone_number ?? '') }}"
                                        placeholder="628XX/08XX">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea type="text" name="address" class="form-control" id="address" rows="3" placeholder="...">{{ old('address') ?? ($user->address ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary ms-2">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card rounded-4">
                <div class="card-header p-3">
                    <h5 class="mb-0 fw-bold">Kata Sandi</h5>
                </div>
                <div class="card-body">

                    @if (old('form-name') == 'change-password' && $errors->any())
                        <x-alert-message type="danger" :messages="$errors->all()" />
                    @endif

                    <form action="{{ route('change-password') }}" id="form-password-update" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form-name" value="change-password">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                                    <div class="input-group" id="show_hide_password_current">
                                        <input type="password" name="current_password" class="form-control border-end-0"
                                            id="current_password" placeholder="Kata Sandi Saat Ini" required>
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                class="bi bi-eye-slash-fill"></i></a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                    <div class="input-group" id="show_hide_password_new">
                                        <input type="password" name="new_password" class="form-control border-end-0"
                                            id="new_password" placeholder="Kata Sandi Baru" required>
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                class="bi bi-eye-slash-fill"></i></a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi
                                        Baru</label>
                                    <input type="password" name="new_password_confirmation" class="form-control"
                                        id="new_password_confirmation" placeholder="Konfirmasi Kata Sandi Baru" required>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary">Ubah Kata Sandi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @hasrole('superadmin')
            <div class="col-lg-12">
                <div class="card rounded-4">
                    <div class="card-header p-3">
                        <h5 class="mb-0 fw-bold">Informasi Perusahaan</h5>
                    </div>
                    <div class="card-body">

                        @if (session('form-name') == 'company-info' && session('success'))
                            <x-alert-message type="success" :messages="session('success')" />
                        @endif

                        @if (old('form-name') == 'company-info' && $errors->any())
                            <x-alert-message type="danger" :messages="$errors->all()" />
                        @endif

                        <form action="{{ route('company-info.create-or-update') }}" id="form-company-info" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="form-name" value="company-info">

                            <div class="row">
                                <div class="col-12 col-xl-6">
                                    <div class="col-12 mb-3">
                                        <label for="name" class="form-label">Nama Perusahaan</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            value="{{ old('name') ?? ($companyInfo->name ?? '') }}" placeholder="This company"
                                            maxlength="55">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="short_name" class="form-label">Nama Pendek Perusahaan</label>
                                        <input type="text" name="short_name" class="form-control" id="short_name"
                                            value="{{ old('short_name') ?? ($companyInfo->short_name ?? '') }}"
                                            placeholder="TCompany ( Optional )" maxlength="16">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="tagline" class="form-label">Tagline Perusahaan</label>
                                        <input type="text" name="tagline" class="form-control"
                                            value="{{ old('tagline') ?? ($companyInfo->tagline ?? '') }}"
                                            placeholder="Your Trusted Partner for Success">
                                    </div>
                                </div>
                                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                                    <p class="mb-2">Tentang Perusahaan</p>
                                    <div class="company-about bg-light rounded-2" data-placeholder="..."
                                        style="height: 85%;">
                                        {!! old('description') ?? ($companyInfo->about_us ?? '') !!}
                                    </div>
                                    <input type="hidden" name="about_us" id="about_us">
                                </div>
                                <div class="col-12 d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-secondary ms-2">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endhasrole

    </div>
@endsection

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
            $('.company-about .ql-editor').css('min-height', '50px');
            $('form').on('submit', function() {
                $('[name="about_us"]').val(quill.root.innerHTML);
            });

            $(".input-group-text").on('click', function(event) {
                event.preventDefault();
                var input = $(this).siblings('input');
                var icon = $(this).find('i');
                if (input.attr("type") == "text") {
                    input.attr('type', 'password');
                    icon.addClass("bi-eye-slash-fill");
                    icon.removeClass("bi-eye-fill");
                } else if (input.attr("type") == "password") {
                    input.attr('type', 'text');
                    icon.removeClass("bi-eye-slash-fill");
                    icon.addClass("bi-eye-fill");
                }
            });
        });
    </script>
@endpush
