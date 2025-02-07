<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="bodered-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ getCompanyInfo()->short_name ?? (getCompanyInfo()->name ?? config('app.name')) }} - @yield('title')</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ URL::asset('build/images/favicon.png') }}" rel="icon" />
    <link href="{{ URL::asset('build/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

    @include('layouts.head-css')
</head>

<body data-pace="true">
    <div class="preloader"></div>
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
                        <button type="submit" class="btn btn-danger">Ya, keluar</button>
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

    @isset($outlet)
        <div class="modal fade" id="exportReportModal" tabindex="-1" aria-labelledby="exportReportModal"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 py-2">
                        <h5 class="modal-title">{{ old('title') ?? '' }}</h5>
                        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal" >
                            <i class="material-icons-outlined">close</i>
                        </a>
                    </div>
                    <div class="modal-body">
                        <div class="card rounded-4 mb-3">
                            <div class="card-body">
                                <div
                                    class="d-flex flex-lg-row flex-column align-items-start align-items-lg-center justify-content-between gap-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="detail-icon fs-2">
                                            <i class="bi bi-house-door-fill"></i>
                                        </div>
                                        <div class="detail-info">
                                            <h4 class="fw-bold mb-1">{{ $outlet->name }}</h4>
                                            <p class="mb-0">{{ $outlet->address }}</p>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (session('report-error'))
                            <x-alert-message type="danger" :messages="session('report-error')" />
                        @endif
                        <form action="{{ old('action') ?? '#' }}" method="POST" download="true">
                            @csrf
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <h6 class="mb-2">Mulai<span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control w-100 ignore" id="start_date"
                                        name="start_date" value="{{ old('start_date') ?? date('01 M Y') }}" required
                                        readonly>
                                </div>
                                <div class="col-6 mb-3">
                                    <h6 class="mb-2">Akhir<span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control w-100 ignore" id="end_date"
                                        name="end_date" value="{{ old('end_date') ?? date('t M Y') }}" required readonly>
                                </div>

                                <div class="col-12 mt-2 text-end border-top pt-3 pb-0">
                                    <button type="submit" class="btn btn-light me-2" name="export_as" value="excel">
                                        <i class="bi bi-file-earmark-excel"></i>
                                        Simpan Excel
                                    </button>

                                    <button type="submit" class="btn btn-light" name="export_as" value="pdf">
                                        <i class="bi bi-filetype-pdf"></i>
                                        Simpan PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                        @push('script')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const exportReportModal = document.getElementById('exportReportModal');
                                    const modalTitle = exportReportModal.querySelector('.modal-title');
                                    const modalBody = exportReportModal.querySelector('.modal-body');
                                    const form = exportReportModal.querySelector('form');

                                    const start_date = exportReportModal.querySelector('#start_date');
                                    const end_date = exportReportModal.querySelector('#end_date');

                                    const modalInstance = new bootstrap.Modal(exportReportModal);

                                    @if (session('report-error'))
                                        modalInstance.show();
                                    @endif

                                    // listen click data-bs-target="#exportReportModal"
                                    exportReportModal.addEventListener('show.bs.modal', function(event) {
                                        const button = event.relatedTarget;

                                        exportReportModal.querySelector('.alert-danger')?.remove();

                                        modalTitle.textContent = button.getAttribute('data-bs-title');
                                        form.action = button.getAttribute('data-bs-action');
                                    });

                                    form.addEventListener('submit', function(event) {
                                        ['title', 'action', 'export_as'].forEach(name => {
                                            const input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = name;
                                            input.value = name === 'title' ? modalTitle.textContent.trim() : (name ===
                                                'action' ? form.action : event.submitter.value);
                                            form.appendChild(input);
                                        });

                                        modalInstance.hide();
                                    });

                                    var startDatePicker = start_date.flatpickr({
                                        static: true,
                                        dateFormat: "d M Y",
                                        allowInput: false,
                                        onChange: function(selectedDates, dateStr, instance) {
                                            var startDate = selectedDates[0]; // Tanggal yang dipilih di startDate
                                            var endDate = endDatePicker.selectedDates[0]; // Tanggal yang dipilih di endDate

                                            // Atur batas minimum untuk endDate
                                            endDatePicker.set('minDate', dateStr);

                                            if (!endDate || startDate > endDate) {
                                                endDatePicker.setDate(startDate);
                                                end_date.value = instance.formatDate(startDate,
                                                    "d M Y"); // Update value input end_date
                                            }
                                        }
                                    });

                                    var endDatePicker = end_date.flatpickr({
                                        static: true,
                                        dateFormat: "d M Y",
                                        allowInput: false,
                                        minDate: new Date(),
                                    });

                                });
                            </script>
                        @endpush
                    </div>
                </div>
            </div>
        </div>
    @endisset

    @include('layouts.common-scripts')
</body>

</html>
