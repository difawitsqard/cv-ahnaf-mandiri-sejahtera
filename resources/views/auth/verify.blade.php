@extends('layouts.guest')
@section('title')
    Verify Email
@endsection
@section('content')
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0">
                        <div class="card-body p-5">
                            <h4 class="fw-bold">Verifikasi Email</h4>
                            <p class="mb-0">
                                Kami telah mengirimkan email verifikasi ke <strong>{{ auth()->user()->email }}</strong>.
                                Periksa email anda dan klik tautan verifikasi yang kami kirim.
                            </p>


                            @if (session('resent'))
                                <div class="alert alert-success mt-3" role="alert">
                                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                                </div>
                            @endif

                            <div class="form-body mt-3">
                                <div class="d-grid gap-2">
                                    <form method="POST" action="{{ route('verification.resend') }}" class="row g-2">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-grd btn-grd-royal">{{ __('Kirim Ulang Tautan Verifikasi') }}</button>
                                        {{-- <a href="{{ route('login') }}" class="btn btn-grd btn-grd-voilet">Perbarui Email</a> --}}
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3">Bukan email anda ? <a href="{{ route('login') }}">Ubah
                                    email</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
@endsection
