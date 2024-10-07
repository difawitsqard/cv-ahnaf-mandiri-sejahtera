@extends('layouts.app')
@section('title')
    Basic Cards
@endsection
@section('content')
    <x-page-title title="Component" subtitle="Cards" />

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
        <div class="col">
            <div class="card">
                <img src="{{ URL::asset('build/images/gallery/01.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Stay at home</h5>
                    <p class="card-text">Nam libero tempore, cum soluta nobis est
                        eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas
                        assumenda est, omnis dolor repellendus Temporibus autem
                        quibusdam et aut officiis debitis aut rerum necessitatibus saepe.</p>

                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="{{ URL::asset('build/images/gallery/02.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Stay at home</h5>
                    <p class="card-text">Nam libero tempore, cum soluta nobis est
                        eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas
                        assumenda est, omnis dolor repellendus Temporibus autem
                        quibusdam et aut officiis debitis aut rerum necessitatibus saepe.</p>

                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="{{ URL::asset('build/images/gallery/03.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Stay at home</h5>
                    <p class="card-text">Nam libero tempore, cum soluta nobis est
                        eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas
                        assumenda est, omnis dolor repellendus Temporibus autem
                        quibusdam et aut officiis debitis aut rerum necessitatibus saepe.</p>

                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="{{ URL::asset('build/images/gallery/04.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Stay at home</h5>
                    <p class="card-text">Nam libero tempore, cum soluta nobis est
                        eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas
                        assumenda est, omnis dolor repellendus Temporibus autem
                        quibusdam et aut officiis debitis aut rerum necessitatibus saepe.</p>

                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/gallery/05.png') }}" class="w-100 mb-4 rounded" alt="...">
                    <h5 class="card-title mb-4">Why do we use it?</h5>
                    <p class="card-text mb-4">Many desktop publishing packages and web page editors now use Lorem Ipsum.</p>
                    <button class="btn btn-primary w-100 raised">Add Payment</button>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/gallery/06.png') }}" class="w-100 mb-4 rounded" alt="...">
                    <h5 class="card-title mb-4">Why do we use it?</h5>
                    <p class="card-text mb-4">Many desktop publishing packages and web page editors now use Lorem Ipsum.</p>
                    <button class="btn btn-danger w-100 raised">Add Payment</button>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/gallery/07.png') }}" class="w-100 mb-4 rounded" alt="...">
                    <h5 class="card-title mb-4">Why do we use it?</h5>
                    <p class="card-text mb-4">Many desktop publishing packages and web page editors now use Lorem Ipsum.</p>
                    <button class="btn btn-success w-100 raised">Add Payment</button>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/gallery/08.png') }}" class="w-100 mb-4 rounded" alt="...">
                    <h5 class="card-title mb-4">Why do we use it?</h5>
                    <p class="card-text mb-4">Many desktop publishing packages and web page editors now use Lorem Ipsum.</p>
                    <button class="btn btn-warning w-100 raised">Add Payment</button>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
