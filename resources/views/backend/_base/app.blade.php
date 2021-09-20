<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title ?? '' }} | {{ config('app.name') }}</title>

    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset( 'plugins/datatables-bs4/css/dataTables.bootstrap4.min.css' )}}">
    <!-- DataTables Button -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css' )}}">
    {{--multi select--}}
    {{-- <link href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css" rel="stylesheet"> --}}
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset( 'plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css' )}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset( 'plugins/toastr/toastr.min.css' )}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset( 'css/backend/adminlte/adminlte.css' )}}">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset( 'backend/extra/fontawesome/css/all.min.css' )}}">
    
    {{-- css link for datepicker --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />

    <!-- Backend CSS -->
    <link rel="stylesheet" href="@assetv( 'backend/dist/css/app.css' )">
    <link rel="stylesheet" href="@assetv( 'css/backend/backend-custom.css' )">

    @stack('css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-info">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto rightNavbar">
            <li class="nav-item dropdown dropdown-lang">
                <a class="nav-link" data-toggle="dropdown" href="#" style="display: flex;">
                    <?php
                        $lang = 'img/backend/jp.png';
                        if(App::isLocale('en')){
                            $lang = 'img/backend/en.png';
                        }
                    ?>
                    <i class="fa fa-language" style="font-size: 24px; margin-top: -3px; margin-right: 3px"></i>
                    <span class="span-locale">@lang('label.language')</span>
                </a>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                    <a href="{{ route('setlanguage', ['language' => 'en']) }}" class="dropdown-item">
                        <img src="{{ asset("img/backend/en.png") }}" class="img-lang mr-2" id="set-english" /> English
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('setlanguage', ['language' => 'jp']) }}" class="dropdown-item">
                        <img src="{{ asset("img/backend/jp.png") }}" class="img-lang mr-2" id="set-japanese" /> 日本語
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}" target="_blank"><i class="fa fa-home" aria-hidden="true"></i>
                @lang('label.top_page')</a>
            </li>
            <li>
                <?php
                    if(Auth::guard('web')->check()){
                        $logout = 'admin.logout';
                    } else {
                        $logout = 'logout';
                    }
                ?>
                <a class="nav-link" id="admin-logout" href="{{ route($logout) }}"><span><i class="fas fa-sign-out-alt"></i> @lang('label.logout')</span></a>
            </li>
        </ul>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
@include('backend._base.nav_left')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content-wrapper')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Right Sidebar -->
@yield('control-right-sidebar')
<!-- /.control-Right sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer text-sm">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Version 1
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2019 <a href="https://grune.co.jp">Grune</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="@assetv('backend/dist/js/app.js')"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset( 'plugins/bootstrap/js/bootstrap.bundle.min.js' )}}"></script>
<!-- DataTables -->
<script src="{{ asset( 'plugins/datatables/jquery.dataTables.js' )}}"></script>
<script src="{{ asset( 'plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' )}}"></script>
{{--multiselect--}}
{{-- <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script> --}}
<!-- SweetAlert2 -->
<script src="{{ asset( 'plugins/sweetalert2/sweetalert2.min.js' )}}"></script>
<!-- Toastr -->
<script src="{{ asset( 'plugins/toastr/toastr.min.js' )}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset( 'js/backend/adminlte/adminlte.min.js' )}}"></script>
<!-- DATEPICKER JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<!-- custom backend -->
<script src="{{ AssetHelper::version( 'js/backend/backend.js' )}}"></script>

{{--custom js--}}
@stack('scripts')
<script>
    $(function () {
        @if ($message = Session::get('success'))
        toastr.success('{{ $message }}');
        @endif
        @if ($errors->any())
        toastr.error('@foreach ($errors->all() as $error)' +
            '<p>{{ $error }}</p>' +
            '@endforeach');
        @endif
    });
</script>
<script>var mixin = null; var store = null; var router = null; </script>

@stack('vue-scripts')
<script src="@assetv('backend/dist/js/vue.js')"></script>
<script src="{{ asset( 'js/backend/parsley/validators/custom_validators.js' )}}"></script>

</body>
</html>
