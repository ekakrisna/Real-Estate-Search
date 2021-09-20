<!DOCTYPE html>
<html class="d-flex flex-column" lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="@yield('description')">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/images/icons/favicon.ico') }}">
        <!-- CSRF Token -->
        
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') | {{ config('app.name') }}</title>

        <!-- Components -->
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/components/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/fonts/fontawesome-pro/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/components/swiper/package/css/swiper.min.css') }}">
        <!-- Components -->
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset( 'plugins/toastr/toastr.min.css' )}}">

        <!-- Base stylesheet -->
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/base.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/elements.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/header.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/banner.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/contents.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/footer.css') }}">
        <!-- Base stylesheet -->

        <!-- Scripts -->
        @stack('css')
        @stack('js-top')

    </head>
    <body class="flex-grow-1 d-flex flex-column">
        <div class="wrapper flex-grow-1 d-flex flex-column justify-content-between" id="app">
            @yield('page')
        </div>
        
        <!-- Polyfills -->
        <script src="{{ asset('frontend/components/intersection-observer/intersection-observer.js') }}"></script>
        <!-- Polyfills -->

        <!-- Libraries -->
        <script src="{{ asset('frontend/components/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('frontend/components/animejs/lib/anime.min.js') }}"></script><!-- Toastr -->
        <script src="{{ asset( 'plugins/toastr/toastr.min.js' )}}"></script>
        <!-- Libraries -->

        <!-- Components -->
        <script src="{{ asset('frontend/components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('frontend/components/lozad/dist/lozad.min.js') }}"></script>
        <script src="{{ asset('frontend/components/parsleyjs/dist/parsley.min.js') }}"></script>
        <script src="{{ asset('frontend/components/geocomplete/jquery.geocomplete.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/noUiSlider.js') }}"></script>
        <script src="{{ asset('frontend/components/swiper/package/js/swiper.min.js') }}"></script>
        <!-- Components -->

        <!-- Main script -->
        <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
        <!-- Main script -->

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="{{ asset( 'backend/dist/js/parsley.js' )}}"></script>
        <script src="{{ asset( 'plugins/parsley/i18n/ja.js' )}}"></script>

        @stack('js')
        
        <script src="@assetv('backend/dist/js/app.js')"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        
        <script>var mixin = null; var store = null; var router = null; </script>

        @stack('scripts')
        @stack('vue-scripts')        
        <script src="@assetv('backend/dist/js/vue.js')"></script>
        <script>
        /* For handling the back button 
        * This should be put after vue instance creation
        */
            let exceptionPaths = [
                "change_email",
                "property-detail"
            ]
            let goToMap = function(){
                window.location.href = `/map?${localStorage.getItem('mapQueryParams')}`;
            }
            let goBack = function(){
                history.back();
            }
            document.getElementById('back-button')?.addEventListener('click', () => {
                //let isInExceptionPath = exceptionPaths.some(path => );
                let isInExceptionPath = exceptionPaths.includes(window.location.pathname.split('/')[1]);
                if(isInExceptionPath){
                    goBack();
                } 
                else{
                    goToMap();
                }
            });
        </script>

        <script src="{{ asset( 'js/backend/parsley/validators/custom_validators.js' )}}"></script>
    </body>
</html>
