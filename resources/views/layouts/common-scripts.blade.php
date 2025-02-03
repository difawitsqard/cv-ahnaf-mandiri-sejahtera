<!-- loader-->
{{-- <script>
    paceOptions = {
        ajax: {
            trackMethods: ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
            // trackWebSockets: true
        },
        // document: true,
        // eventLag: false,
        // // elements: false,
        restartOnRequestAfter: 1000,
    };
</script> --}}
<script data-pace-options='{ "ajax": { "trackMethods": ["GET", "POST", "PUT", "DELETE", "PATCH"] } }'
    src="{{ URL::asset('build/js/pace.min.js') }}"></script>

<!--plugins-->
<script src="{{ URL::asset('build/js/jquery.min.js') }}"></script>
<!--bootstrap js-->
<script src="{{ URL::asset('build/js/bootstrap.bundle.min.js') }}"></script>
<!--swetalert2-->
<script src="{{ URL::asset('build/plugins/sweetalert2/js/sweetalert2.min.js') }}"></script>
@if (importOnce('js-flatpickr'))
    <script src="{{ URL::asset('build/plugins/flatpickr/js/flatpickr.js') }}"></script>
@endif

<!--plugins-->
<script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/main.js') }}?v={{ __('v3') }}"></script>

<!--costum js-->
<script src="{{ URL::asset('build/js/costum.js') }}?v={{ __('v5') }}"></script>

<script>
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        @if (request()->routeIs('outlet.index'))
            ($("body").addClass("toggled"),
                $(".sidebar-wrapper").hover(
                    function() {
                        $("body").addClass("sidebar-hovered");
                    },
                    function() {
                        $("body").removeClass("sidebar-hovered");
                    }
                ));
        @endif
    })
</script>

{{-- @if ($errors->any() || session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 3500,
                timerProgressBar: true,
                customClass: {
                    icon: 'ms-2',
                    popup: 'p-2 w-100 pe-3',
                    title: 'mb-0',
                    content: 'mt-0'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Upps!',
                    text: `{{ $errors->first() }}`
                });
            @endif

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: 'Ok!',
                    text: `{{ session('success') }}`
                });
            @endif
        });
    </script>
@endif --}}

@stack('script')
