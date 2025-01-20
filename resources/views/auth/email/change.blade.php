@extends('layouts.guest')
@section('title')
    Ubah Email
@endsection
@section('content')
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0">
                        <div class="card-body p-5">
                            <h4 class="fw-bold">Ubah Email</h4>
                            <p class="mb-0">
                                Jika <strong>{{ auth()->user()->email }}</strong> adalah email anda, verifikasi email anda sekarang.
                            </p>
                            <div class="form-body mt-4">

                                <form method="POST" class="row g-4" action="{{ route('email.change.update') }}">
                                    @csrf
                                    @method('POST')

                                    <div class="col-12">
                                        <label class="form-label" for="email">Email <span
                                                class="text-danger">*</span></label>
                                        <input id="email" type="email"
                                            class="form-control ignore @error('email') is-invalid @enderror  @error('error') is-invalid @enderror" name="email"
                                            placeholder="example@email.com">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        @error('error')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-grd btn-grd-royal">Simpan</button>
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
@endsection
