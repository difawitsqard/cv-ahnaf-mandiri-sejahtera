@extends('layouts.guest')
@section('title')
    Lupa Kata Sandi
@endsection
@section('content')
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0">
                        <div class="card-body p-5">
                            <h4 class="fw-bold">Lupa Kata Sandi ?</h4>
                            <p class="mb-3">Masukkan email Anda untuk mengatur ulang kata sandi.</p>

                            @if (session('status'))
                                <div class="alert alert-success mt-3" role="alert">
                                    {{ __('Tautan untuk mengatur ulang kata sandi telah dikirim ke email Anda.') }}
                                </div>
                            @endif

                            {{-- @if (session('success') || session('status') || session('info') || session('warning') || $errors->any())
                               
                            @php
                                $alertType = session('success') ? 'success' : (session('info') || session('status') ? 'info' : (session('warning') ? 'warning' : 'danger'));
                                $message = session('success') ?? session('status') ?? session('info') ?? session('warning') ?? $errors->first();
                            @endphp
                            <div class="alert alert-{{ $alertType }} alert-dismissible bg-{{ $alertType }} border-0 mb-2 fade show">
                                <div class="text-white">{{ $message }}</div>
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
                            </div>
                               
                            @endif --}}

                            <div class="form-body">
                                <form method="POST" class="row g-4" action="{{ route('password.email') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="col-12">
                                        <label class="form-label" for="email">Email <span
                                                class="text-danger">*</span></label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus
                                            placeholder="Masukan email anda.">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-grd btn-grd-royal">Kirim</button>
                                            <a href="{{ route('login') }}" class="btn btn-grd btn-grd-voilet">Kembali ke
                                                login</a>
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
