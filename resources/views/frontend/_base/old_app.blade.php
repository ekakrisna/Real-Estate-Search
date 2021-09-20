<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="@yield('description')">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') | {{ config('app.name') }}</title>

        <!-- Components -->
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/components/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/fonts/fontawesome-pro/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/components/swiper/package/css/swiper.min.css') }}">
        <!-- Components -->

        <!-- Base stylesheet -->
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/base.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/elements.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/header.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/banner.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/contents.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/footer.css') }}">
        <!-- Base stylesheet -->
        @stack('css')
        @stack('js-top')

        @php
            $isStartPage = 'frontend.start' === Route::currentRouteName();
        @endphp

    </head>
    <body>
        @if((!Request::is('login')) && ( !$isStartPage ) && (!Request::is('frontend/change_mail_address_complete')) && (!Request::is('frontend/error_page')))
        <div class="menu-navbar-top">
            <div class="container-full">
                <div class="row row-base">
                    <div class="col-7">
                        @if(Request::is('mip'))
                        <div class="content-search">
                            <form action="" onSubmit="return false;" class="form-search" method="post">
                                <div class="form-group mb-0">
                                    <div class="inline-form">
                                        <i class="fa fa-search"></i>
                                        <input type="text" name="search" id="place" class="form-control form-control-search" placeholder="住所・駅名などで検索" autocomplete="off">
                                    </div>
                                </div>
                            </form>
                        </div>
                        @else
                        <a href="{{ url()->previous() }}" class="btn-back">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                        @endif
                    </div>
                    <div class="col-5 pl-0">
                        <div class="content-menu">
                            <ul class="menu-top">
                                <li><a href="{{ url('frontend/news') }}">
                                    <img src="{{ asset('frontend/assets/images/icons/btn_menu_info.png') }}" alt="icon-notif">
                                    <span class="badge-notif">3</span>
                                </a></li>
                                <li>
                                    <a href="javascript:void(0)" class="active btn-menu-sideright">
                                        <img src="{{ asset('frontend/assets/images/icons/btn_menu_open.png') }}" alt="btn-open" class="icon-open">
                                        <img src="{{ asset('frontend/assets/images/icons/btn_menu_close.png') }}" alt="btn-close" class="icon-close d-none">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sideright-menu d-none">
            <div class="content-sideright">
                <div class="sidetop-sideright">
                    <ul class="list-menu-icon">
                        <li>
                            <a href="{{ url('frontend/fav-list') }}">
                                <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_on.png') }}" alt="icon-fav">
                                <span class="text-menu">お気に入り物件</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('frontend/history') }}">
                                <img src="{{ asset('frontend/assets/images/icons/icon_history.png') }}" alt="icon-histoy">
                                <span class="text-menu">過去に閲覧した物件</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('frontend/search_settings') }}">
                                <img src="{{ asset('frontend/assets/images/icons/icon_my_setting.png') }}" alt="icon-my-setting">
                                <span class="text-menu">MY設定</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('frontend/account_settings') }}">
                                <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}" alt="icon-account">
                                <span class="text-menu">アカウント設定</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="list-arrow">
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
                            <a href="{{ route('frontend.contact') }}">
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
                </div>
                <div class="sidebot-sideright">
                    <div class="qr-content">
                        <img src="{{ asset('frontend/assets/images/qr.jpg') }}" alt="qr">
                    </div>
                    <div class="logo-footer-sideright">
                        <img src="{{ asset('frontend/assets/images/icons/logo_footer.png') }}" alt="logo-footer">
                    </div>
                    <div class="copyright-sideright">
                        <span>© {{ date('Y') }} Kaibi inc.</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @yield('page')

        @if( Request::is('login') || $isStartPage || Request::is( 'frontend/password_reissue' ))
        <footer class="footer {{ $isStartPage ? 'footer-start' : '' }}">
            <div class="container">
                @if( $isStartPage || Request::is('frontend/password_reissue'))
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
                                <li><a href="{{ route('terms') }}">利用規約 <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('privacy_policy') }}">プライバシーポリシー <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('contact') }}">お問い合わせ <i class="fa fa-caret-right"></i></a></li>
                                <li><a href="{{ route('company_information') }}">運営会社 <i class="fa fa-caret-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <p class="text">© {{ date('Y') }} Kaibi inc.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @endif

        <!-- Polyfills -->
        <script type="text/javascript" src="{{ asset('frontend/components/intersection-observer/intersection-observer.js') }}"></script>
        <!-- Polyfills -->

        <!-- Libraries -->
        <script type="text/javascript" src="{{ asset('frontend/components/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/components/animejs/lib/anime.min.js') }}"></script>
        <!-- Libraries -->

        <!-- Components -->
        <script type="text/javascript" src="{{ asset('frontend/components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/components/lozad/dist/lozad.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/components/parsleyjs/dist/parsley.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/components/geocomplete/jquery.geocomplete.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/assets/js/noUiSlider.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/components/swiper/package/js/swiper.min.js') }}"></script>
        <!-- Components -->

        <!-- Main script -->
        <script type="text/javascript" src="{{ asset('frontend/assets/js/old_main.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/assets/js/map-custom.js') }}"></script>
        <!-- Main script -->
        @stack('js')
    </body>
</html>
