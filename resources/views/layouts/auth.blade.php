<!DOCTYPE html>

<html lang="ja" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>ログイン | Charing</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('ui/custom/images/logo.png')}}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/fonts/flag-icons.css')}}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('ui/assets/css/demo.css')}}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/css/pages/page-auth.css')}}" />
    @yield('style')
    <!-- Helpers -->
    <script src="{{asset('ui/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('ui/assets/js/config.js')}}"></script>
</head>

<body>
<!-- Content -->
@yield('content')
<!-- / Content -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{asset('ui/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('ui/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/node-waves/node-waves.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('ui/assets/vendor/js/menu.js')}}"></script>
<!-- Vendors JS -->
<script src="{{asset('ui/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>

<!-- Page JS -->
<script src="{{asset('ui/assets/js/pages-auth.js')}}"></script>
@yield('script')
</body>
</html>
