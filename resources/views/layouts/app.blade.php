<!DOCTYPE html>
<html lang="jp" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Charing</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{asset('ui/custom/images/logo.png')}}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/fonts/flag-icons.css')}}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('ui/assets/css/demo.css')}}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/animate-css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/flatpickr/flatpickr.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/jquery-timepicker/jquery-timepicker.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/pickr/pickr-themes.css')}}" />
    <link rel="stylesheet" href="{{asset('ui/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{ asset('ui/assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('ui/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <script src="{{asset('ui/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('ui/assets/js/config.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('ui/custom/css/custom.css')}}">
    <style>
        @media (min-width: 1200px){
            .layout-horizontal .bg-menu-theme .menu-inner > .menu-item {
                margin: 0.3rem 0;
            }
        }
    </style>
    @yield('style')
</head>

{{--  
    <i class="fas fa-plus me-1"></i> or <span class="ti-xs ti ti-plus me-1"></span>
    <i class="fas fa-search me-1"></i>
    <i class="fas fa-pencil me-1"></i>
    <i class="fas fa-trash me-1"></i> or <i class="ti ti-trash"></i>
    <i class="fas fa-save me-1"></i>
    <i class="fas fa-file me-1"></i>
    <i class="fas fa-upload me-1"></i>
    <i class="fas fa-download me-1"></i>
    <i class="fas fa-calendar me-1"></i>
    <i class="fas fa-message me-1"></i>
    <i class="fas fa-file-import me-1"></i>
--}}

<body>
    @php
        $active_parent_menu = "";
        $active_menu = request()->path();
    @endphp
    @switch(request()->path())
        @case("exhibit")
            @php
                $title = "出品";
            @endphp
            @break
        @case("exhibit_history")
            @php
                $title = "出品履歴";
            @endphp
            @break
        @case("exhibit_history_detail")
            @php
                $title = "出品履歴詳細";
                $active_menu = "exhibit_history";
            @endphp
            @break
        @case("price_history")
            @php
                $title = "価格改定履歴";
            @endphp
            @break
        @case("amazon_info")
            @php
                $title = "Amazon情報取得";
            @endphp
            @break
        @case("black_list")
            @php
                $title = "ブラックリスト";
            @endphp
            @break
        @case("white_list")
            @php
                $title = "ホワイトリスト";
            @endphp
            @break
        @case("setting")
            @php
                $title = "設定";
            @endphp
            @break
        @case("users")
            @php
                $title = "ユーザー管理";
            @endphp
            @break
        @case("/")
        @default
            @php
                $title = "Title";
            @endphp
    @endswitch
    
<!-- Layout wrapper -->
<div class="layout-wrapper layout-horizontal layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="{{url('/exhibit')}}" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img style="width: 85%" src="{{asset('ui/custom/images/logo.png')}}">
                    </span>
                    <span class="app-brand-text demo menu-text fw-bold">Charing</span>
                </a>
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                    <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                    <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                </a>
            </div>
            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Page -->
                {{--                  
                <li class="menu-item @if($active_parent_menu == 'agenda') open active @endif">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-layout-board"></i>
                        <div data-i18n="案件ボード">案件ボード</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item @if($active_menu == 'agenda_new') active @endif">
                            <a href="{{url('/')}}" class="menu-link">
                                <div data-i18n="新規">新規</div>
                            </a>
                        </li>
                        <li class="menu-item @if($active_menu == 'agenda_change') active @endif">
                            <a href="{{url('/agenda_change')}}" class="menu-link">
                                <div data-i18n="更改">更改</div>
                            </a>
                        </li>
                        <li class="menu-item @if($active_menu == 'agenda_customer') active @endif">
                            <a href="{{url('/agenda_customer')}}" class="menu-link">
                                <div data-i18n="顧客の声">顧客の声</div>
                            </a>
                        </li>
                    </ul>
                </li>  
                --}}

                <li class="menu-item @if($active_menu == 'exhibit') active @endif">
                    <a href="{{url('/exhibit')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-description"></i>
                        <div data-i18n="出品">出品</div>
                    </a>
                </li>
                <li class="menu-item @if($active_menu == 'exhibit_history') open active @endif">
                    <a href="{{url('/exhibit_history')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-table"></i>
                        <div data-i18n="出品履歴">出品履歴</div>
                    </a>
                </li>
                <li class="menu-item @if($active_menu == 'price_history') active @endif">
                    <a href="{{url('/price_history')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                        <div data-i18n="価格改定履歴">価格改定履歴</div>
                    </a>
                </li>
                <li class="menu-item  @if($active_menu == 'amazon_info') active @endif">
                    <a href="{{url('/amazon_info')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-brand-amazon"></i>
                        <div data-i18n="Amazon情報取得">Amazon情報取得</div>
                    </a>
                </li>
                <li class="menu-item @if($active_menu == 'black_list') open active @endif">
                    <a href="{{url('/black_list')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-id"></i>
                        <div data-i18n="ブラックリスト">ブラックリスト</div>
                    </a>
                </li>
                <li class="menu-item @if($active_menu == 'white_list') active @endif">
                    <a href="{{url('/white_list')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-id"></i>
                        <div data-i18n="ホワイトリスト">ホワイトリスト</div>
                    </a>
                </li>
                <li class="menu-item @if($active_parent_menu == 'setting') open active @endif">
                    <a href="{{url('/setting')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-settings"></i>
                        <div data-i18n="設定">設定</div>
                    </a>
                </li>
                <li class="menu-item @if($active_menu == 'users') active @endif">
                    <a href="{{url('/users')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-users"></i>
                        <div data-i18n="ユーザー管理">ユーザー管理</div>
                    </a>
                </li>
            </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4 ms-2" href="javascript:void(0)">
                        <i class="ti ti-menu-2 ti-md"></i>
                    </a>
                </div>

                <div class="navbar-nav-right px-3" id="navbar-collapse">
                    <div class="row d-flex align-items-center">
                        <div class="col-7">
                            {{--  <h2 class="m-0">{{$title}}</h2>  --}}
                        </div>
                        <div class="col-5 d-flex">
                            <ul class="navbar-nav ms-auto flex-row justify-sm-content-end">
                                <li class="nav-item d-flex align-items-center">
                                    <a class="" href="javascript:void(0);">〇〇</a>さん
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/')}}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="ログアウト"><i class="ti navbar-icon ti-logout ti-md me-1"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                @yield('content')
                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<div class="print_content"></div>

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
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{asset('ui/assets/vendor/js/dropdown-hover.js')}}"></script>
<script src="{{asset('ui/assets/vendor/js/mega-dropdown.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/bloodhound/bloodhound.js')}}"></script>

<script src="{{asset('ui/assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/flatpickr/ja.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/pickr/pickr.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/sortablejs/sortable.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('ui/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>

<!-- Main JS -->
<script src="{{asset('ui/assets/js/main.js')}}"></script>
<script src="{{asset('ui/assets/js/ui-navbar.js')}}"></script>
<script src="{{asset('ui/assets/js/ui-popover.js')}}"></script>
<script src="{{asset('ui/assets/js/forms-selects.js')}}"></script>

@yield('script')

<!-- Custom JS -->
<script src="{{asset('ui/custom/js/custom.js')}}"></script>
</body>
</html>
