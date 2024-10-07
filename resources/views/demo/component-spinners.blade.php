@extends('layouts.app')
@section('title')
    Spinners
@endsection
@section('content')

    <x-page-title title="Components" subtitle="Spinners" />

    <div class="row row-cols-auto row-cols-1 row-cols-lg-2">
        <div class="col">
            <h5 class="mb-0">Border spinner</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h5 class="mb-0">Border Color spinner</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-secondary" role="status"> <span
                            class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-success" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-danger" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-warning" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-info" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-light" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-dark" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h5 class="mb-0">Growing spinner</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="spinner-grow" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h5 class="mb-0">Border Color spinner</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-danger" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-warning" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-info" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-light" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-dark" role="status"> <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h5 class="mb-0">Growing Size Spinner</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="spinner-border spinner-border-sm" role="status"> <span
                            class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow spinner-grow-sm" role="status"> <span
                            class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h5 class="mb-0">Border Color spinner</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"> <span
                            class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"> <span
                            class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <h5 class="mb-0">Spinners With Buttons</h5>
            <hr>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary" type="button" disabled> <span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-primary" type="button" disabled> <span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <button class="btn btn-primary" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-primary" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <hr>
                    <button class="btn btn-danger" type="button" disabled> <span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-danger" type="button" disabled> <span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <button class="btn btn-danger" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-danger" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <hr>
                    <button class="btn btn-success" type="button" disabled> <span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-success" type="button" disabled> <span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <button class="btn btn-success" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-success" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <hr>
                    <button class="btn btn-dark" type="button" disabled> <span class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-dark" type="button" disabled> <span class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true"></span>
                        Loading...</button>
                    <button class="btn btn-dark" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </button>
                    <button class="btn btn-dark" type="button" disabled> <span class="spinner-grow spinner-grow-sm"
                            role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

@endsection
@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
