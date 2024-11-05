@extends('layouts.guest')
@section('title')
    Login
@endsection
@section('content')
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="container-fluid my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-bottom-4 rounded-top-0 mb-0 border-top border-4 border-secondary">
                        <div class="card-body p-5">
                            <h3 class="fw-bolder">
                                {{ getCompanyInfo()->name ?? config('app.name') }}</h3>
                            <p class="mb-0">Masukan email dan password anda untuk login</p>
                            <div class="form-body my-5">
                                @if (session('success'))
                                    <x-alert-message type="success" :messages="session('success')" />
                                @endif

                                @if ($errors->any())
                                    <x-alert-message type="danger" :messages="$errors->all()" />
                                @endif
                                <form method="POST" action="{{ route('login') }}" class="row g-3">
                                    @csrf

                                    <div class="col-12">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="Masukan email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group show_hide_password">
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Masukan kata sandi" required>
                                            <button type="button"
                                                class="input-group-text bg-transparent toggle-password"><i
                                                    class="bi bi-eye-slash-fill"></i></button>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="checkbox"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="checkbox">Ingat saya</label>
                                        </div>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <div class="col-md-6 text-end"> <a href="{{ route('password.request') }}">Lupa kata
                                                sandi ?</a>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-dark">Login</button>
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
