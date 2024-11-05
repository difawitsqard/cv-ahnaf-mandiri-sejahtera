<!-- loader-->
<link href="{{ URL::asset('build/css/pace.min.css') }}" rel="stylesheet">
<script src="{{ URL::asset('build/js/pace.min.js') }}"></script>

<!--plugins-->
<link href="{{ URL::asset('build/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('build/plugins/metismenu/metisMenu.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('build/plugins/metismenu/mm-vertical.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('build/plugins/simplebar/css/simplebar.css') }}">
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<!--bootstrap css-->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/plugins/material-icons/css/material-icons.css') }}" rel="stylesheet">

<!--swetalert2-->
<link href="{{ URL::asset('build/plugins/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet">

@stack('css')

<!--main css-->
<link href="{{ URL::asset('build/css/bootstrap-extended.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/css/main.css') }}?qq={{ md5(time()) }}" rel="stylesheet">
<link href="{{ URL::asset('build/css/dark-theme.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/css/blue-theme.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/css/semi-dark.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/css/bordered-theme.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/css/responsive.css') }}" rel="stylesheet">
