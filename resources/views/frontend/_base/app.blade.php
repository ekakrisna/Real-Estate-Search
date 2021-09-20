<!DOCTYPE html>
<html class="d-flex flex-column" lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="@yield('description')">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta http-equiv="Cache-Control" content="no-cache">
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
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
        @stack('css')
        @stack('js-top')

        @php
            $isStartPage = 'frontend.start' === Route::currentRouteName();
        @endphp
    </head>
    <body class="flex-grow-1 d-flex flex-column">
    <div class="wrapper d-flex flex-column justify-content-between" id="app">

        @if((!Request::is('login')) && ( !$isStartPage ) && (!Request::is('frontend/change_mail_address_complete')) && (!Request::is('frontend/error_page')))
        <div class="menu-navbar-top">
            <div class="container-full">
                <div class="row row-base">
                    <div class="col-6">
                        @if(Request::is('map'))
                        <div class="content-search">
                            <form action="" onSubmit="return false;" class="form-search" method="post">
                                <div class="form-group mb-0">
                                    <div class="inline-form">
                                        <i class="fa fa-search"></i>
                                        <gmap-autocomplete
                                            @input="search_filter = $event.target.value"
                                            class="form-control form-control-search"
                                            placeholder="住所・駅名などで検索"
                                            @place_changed="setPlace"
                                            :options="{fields: ['geometry', 'formatted_address', 'address_components']}"
                                            :select-first-on-enter="true">
                                        </gmap-autocomplete>
                                        {{-- <input type="text" v-model="search_filter" name="search" id="place" class="form-control form-control-search" placeholder="住所・駅名などで検索" autocomplete="off"> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                        @else
                        <a href="javascript:void(0)" class="btn-back" id="back-button">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                        @endif
                    </div>

                    @if((!Request::is('password_reissue')) && (!Request::is('password_reissue/reset')))
                    <div class="col-6 pl-0">
                        <div class="content-menu">
                            <ul class="menu-top">
                                @if (Auth::guard('user')->user() != null)
                                    <li>
                                        <a href="{{ route('frontend.map') }}" >
                                        <img src="{{ asset('frontend/assets/images/icons/icon_map.png') }}" alt="icon-map" >
                                    </a>
                                    </li>
                                    <li class="mr-4"><a href="{{ route('frontend.news') }}">
                                        <img src="{{ asset('frontend/assets/images/icons/btn_menu_info.png') }}" alt="icon-notif">
                                        <span class="badge-notif">{{ $news_count > 0 ? $news_count : '0'}}</span>
                                        </a>
                                    </li>
                                @endif
                                @if( Request::is('login') != true && Request::is('signup') != true && Request::is('disabled_token') != true)
                                    @if (Auth::guard('user')->user() == null)
                                        <li class="d-none d-lg-block mr-4">
                                            <a href="{{ route('signup') }}" class="btn btn-red-round">
                                                <span>会員登録（無料）</span>
                                            </a>
                                        </li>
                                        <li class="d-none d-lg-block mr-4">
                                            <a href="{{route('customer-login')}}" class="btn btn-white-round">
                                                <span>ログイン</span>
                                            </a>
                                        </li>
                                        <li class="mr-4">
                                            <a href="{{ route('frontend.map') }}" >
                                            <img src="{{ asset('frontend/assets/images/icons/icon_map.png') }}" alt="icon-map" >
                                        </a>
                                    @endif
                                @endif
                                <li class="mr-0">
                                    <a href="javascript:void(0)" class="active btn-menu-sideright navbar">
                                        <img src="{{ asset('frontend/assets/images/icons/btn_menu_open.png') }}" alt="btn-open" class="icon-open">
                                        <img src="{{ asset('frontend/assets/images/icons/btn_menu_close.png') }}" alt="btn-close" class="icon-close d-none">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="sideright-menu d-none">
            <div class="sideright-overlay"></div>
            <div class="content-sideright">
                <div class="sidetop-sideright">
                    @if (!Auth::guard('user')->user())
                        <ul class="list-menu">
                            <li>
                                <a href="{{ route('frontend.map') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_map.png') }}" alt="icon-map">
                                    <span>マップ</span>
                                </a>
                            </li>
                            {{-- <li>
                                <div class="list">
                                    <a href="{{ route('signup') }}" class="btn btn-red-round">
                                        <span>会員登録（無料）はこちら</span>
                                    </a>
                                </div>
                            </li> --}}
                            <li class="text-center my-2">
                                <a href="{{ route('explanation' )}}">
                                    <span>会員登録をするとできること</span>
                                    <i class="far fa-lg fa-question-circle"></i>
                                </a>
                            </li>
                            {{-- <li class="mb-3">
                                <div class="list">
                                    <a href="{{route('customer-login')}}" class="btn btn-white-round">
                                        <span>ログイン</span>
                                    </a>
                                </div>
                            </li>                             --}}
                        </ul>
                        <ul class="list-menu-icon">
                            <li>
                                <a href="#">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_on.png') }}" alt="icon-fav">
                                    <span class="text-menu">お気に入り物件</span>
                                    <i class="fas fa-lock-alt text-red"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_history.png') }}" alt="icon-histoy">
                                    <span class="text-menu">過去に閲覧した物件</span>
                                    <i class="fas fa-lock-alt text-red"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_my_setting.png') }}" alt="icon-my-setting">
                                    <span class="text-menu">MY設定</span>
                                    <i class="fas fa-lock-alt text-red"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}" alt="icon-account">
                                    <span class="text-menu">アカウント設定</span>
                                    <i class="fas fa-lock-alt text-red"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="list-arrow">
                            <li>
                                <a href="{{ route('overview') }}">
                                    <span>トチサーチとは</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('terms') }}">
                                    <span>利用規約</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('privacy_policy') }}">
                                    <span>プライバシーポリシー</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">
                                    <span>お問い合わせ</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company_information') }}">
                                    <span>運営会社</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                        </ul>
                    @else
                        <ul class="list-menu-icon">
                            <li>
                                <a href="{{ route('frontend.map') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_map.png') }}" alt="icon-map">
                                    <span>マップ</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.fav_list') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_on.png') }}" alt="icon-fav">
                                    <span class="text-menu">お気に入り物件</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.history') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_history.png') }}" alt="icon-histoy">
                                    <span class="text-menu">過去に閲覧した物件</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.search_settings') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_my_setting.png') }}" alt="icon-my-setting">
                                    <span class="text-menu">MY設定</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.account_settings') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}" alt="icon-account">
                                    <span class="text-menu">アカウント設定</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="list-arrow">
                            <li>
                                <a href="{{ route('overview') }}">
                                    <span>トチサーチとは</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('terms') }}">
                                    <span>利用規約</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('privacy_policy') }}">
                                    <span>プライバシーポリシー</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">
                                    <span>お問い合わせ</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company_information') }}">
                                    <span>運営会社</span>
                                    <i class="fa fa-caret-right"></i>
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
                <div class="sidebot-sideright {{ Auth::guard('user')->user()== null ? "pb-login" : "" }}">
                    <div class="qr-content d-none d-lg-block">
                        <img src="{{ asset('frontend/assets/images/home_qr.png') }}" alt="qr">
                    </div>
                    <a href="{{ route('frontend.map') }}" class="logo-footer-sideright d-block">
                        <img src="{{ asset('frontend/assets/images/icons/logo_footer.png') }}" alt="logo-footer">
                    </a>
                    <div class="copyright-sideright">
                        <span>© {{ date('Y') }} Kaibi inc.</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @yield('page')

        @if( Request::is('map') != true && Request::is('company_information') != true && Request::is('lp/map') != true)
        <footer class="footer {{ $isStartPage ? 'footer-start' : '' }}">
            <div class="container">
                @if( $isStartPage || Request::is('frontend/password_reissue') || Request::is('signup'))
                <div class="row">
                    <div class="col-12">
                        <div class="content text-center">
                            <img src="{{ asset('frontend/assets/images/logo_main.png')}}" alt="logo-footer" class="w-100 img-logo-footer">
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="content">
                            <ul class="menu-footer">                                
                                <li><a href="{{ route('overview') }}">トチサーチとは <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('terms') }}">利用規約 <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('privacy_policy') }}">プライバシーポリシー <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('contact') }}">お問い合わせ <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('company_information') }}">運営会社 <i class="fa fa-caret-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::guard('user')->user() == null)
            <div class="footer-bottom pb-3 pb-lg-0">
                <div class="container pb-4 pb-lg-0">
                    <div class="row pb-5 pb-lg-3">
                        <div class="col-12">
                            <p class="text">© {{ date('Y') }} Kaibi inc.</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <p class="text">© {{ date('Y') }} Kaibi inc.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </footer>
        @endif
        
        @if( Request::is('login') != true && Request::is('signup') != true && Request::is('disabled_token') != true)
            @if (Auth::guard('user')->user() == null)
            <div class="menu-navbar-bottom d-lg-none"
            style=
            "position: fixed;
            bottom: 0;
            width: 100%;
            height: 70px;">
                <div class="container-full">
                    <div class="row row-base">
                        <div class="col-6">
                            <a href="{{ route('signup') }}" class="btn btn-red-round">
                                <span>会員登録（無料）</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{route('customer-login')}}" class="btn btn-white-round">
                                <span>ログイン</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
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

            @if( Request::is('signup') != true )
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
            @endif

            // --vhというカスタムプロパティを作成
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
            // window resize
            window.addEventListener('resize', () => {
                vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            });
        </script>

        <script src="{{ asset( 'js/backend/parsley/validators/custom_validators.js' )}}"></script>
    </body>
</html>
