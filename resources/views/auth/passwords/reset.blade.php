@extends('layouts.guest')
@section('title')
    Atur Ulang Kata Sandi
@endsection
@section('content')
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0">
                        <div class="card-body p-5">
                            <h4 class="fw-bold">Buat Kata Sandi Baru</h4>
                            <p class="mb-0">Kami telah menerima permintaan pengaturan ulang kata sandi Anda. Silakan masukkan kata sandi baru Anda!</p>
                            <div class="form-body mt-4">
                                <form method="POST" class="row g-4"
                                    action="{{ route('password.update') }}">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="col-12">
                                        <label class="form-label" for="email">Email <span
                                                class="text-danger">*</span></label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ $email ?? old('email') }}" readonly>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="password">Kata Sandi</label>
                                        <div class="input-group show_hide_password">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required placeholder="Masukan kata sandi" autofocus>
                                            <button type="button" class="input-group-text bg-transparent toggle-password"><i
                                                    class="bi bi-eye-slash-fill"></i></button>

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="password_confirmation">Kata Sandi</label>
                                        <div class="input-group show_hide_password">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Masukan kata sandi" required>
                                            <button type="button"
                                                class="input-group-text bg-transparent toggle-password"><i
                                                    class="bi bi-eye-slash-fill"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-grd btn-grd-royal">Ubah Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
    <!--authentication-->
@endsection
@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/js/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".show_hide_password .toggle-password").on('click', function(event) {
                event.preventDefault();

                var input = $(this).closest('.input-group').find('input');
                var icon = $(this).find('i');

                if (input.attr("type") == "text") {
                    input.attr('type', 'password');
                    icon.addClass("bi-eye-slash-fill");
                    icon.removeClass("bi-eye-fill");
                } else {
                    input.attr('type', 'text');
                    icon.removeClass("bi-eye-slash-fill");
                    icon.addClass("bi-eye-fill");
                }
            });
        });
    </script>
@endpush

