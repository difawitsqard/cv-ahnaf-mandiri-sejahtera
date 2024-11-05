<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="semi-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ getCompanyInfo()->short_name ?? getCompanyInfo()->name ?? config('app.name')}} - @yield('title')</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ URL::asset('build/images/favicon.png') }}" rel="icon" />
    <link href="{{ URL::asset('build/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

    @include('layouts.head-css')
</head>

<body>

    @include('layouts.topbar')

    @include('layouts.sidebar')
    {{-- @include('layouts.sidebar-demo') --}}
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            @yield('content')
        </div>
    </main>

    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    @stack('modals')


    <!-- Modal for logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">Konfirmasi !</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <div class="modal-body">
                    <h4>Hei <b>{{ auth()->user()->name }}</b></h4>
                    <p>Apakah anda yakin ingin keluar ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary d-flex" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="event.preventDefault();this.closest('form').submit();">Ya, keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for cropping image -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="img-container"
                        style="border: 1px #111; width: 100%; height: 0; padding-bottom: 100%;  position: relative; overflow: hidden;">
                        <img class="modal-crop-canvas" src=""
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                            alt="Picture">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary d-flex" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary d-flex" id="rotateImageModal"><span
                            class="material-icons-outlined">refresh</span></button>
                    <button type="button" class="btn btn-primary d-flex" id="cropImageModal"><span
                            class="material-icons-outlined">crop</span></button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.extra')

    @include('layouts.common-scripts')
</body>

</html>
