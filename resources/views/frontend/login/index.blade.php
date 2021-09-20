@extends('frontend._base.app')
@section('title', $title)
@section('description', '')
@section('page')
    <div class="header-logo">
        <div class="container">
            <div class="row">
                <div class="col-10 col-md-6 col-lg-4 mx-auto">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{ asset('frontend/assets/images/logo_main.png') }}" alt="logo" class="w-100">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="section-login">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="content">
                        <div class="row">
                            <div class="col-12 col-lg-5">
                                <div class="form-login">
                                    <form class="form form-login-validate" action="{{ route('customer-login-action') }}" method="post" data-parsley-validate>
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-md-8 col-lg-12 mx-auto">
                                                <div class="form-group form-with-icon">
                                                    <i class="fa fa-envelope"></i>
                                                    <input type="email" name="email" class="form-control" placeholder="登録メールアドレス" required>
                                                </div>
                                                <div class="form-group form-with-icon mb-0">
                                                    <i class="fa fa-lock"></i>
                                                    <input type="password" name="password" class="form-control" placeholder="パスワード" required>
                                                </div>
                                                <div class="error-notif error-user-pass d-none">
                                                    <p class="text-error">メールアドレス、パスワードを正しく入力してください。</p>
                                                </div>
                                                @error('errorlogin')
                                                <span class="error-notif" role="alert"> <p class="text-error">{{ $message }}</p> </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-agreement agreement">
                                            <label class="label-agreement checkmark-custom">
                                                利用規約<i class="fa fa-external-link"></i><a href="{{ route('terms') }}"><span class="text-black"> に同意してログイン</span></a>
                                                <input type="checkbox" name="agreement" class="checkbox" required>
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="error-notif error-agreement d-none">
                                                <p class="text-error">利用規約への同意は必須です。</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-11 col-md-8 col-lg-12 mx-auto">
                                                <button type="submit" class="btn btn-primary-round">
                                                    <span>ログイン</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-agreement">
                                            <label class="label-stay-login checkmark-custom">
                                                ログイン状態を維持する
                                                <input type="checkbox" name="stay_login" class="checkbox" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-11 col-md-8 col-lg-12 mx-auto">
                                            <a href="{{ route('signup') }}" class="btn btn-with-icon-play">
                                                <i class="fa fa-caret-right"></i>
                                                <span>新規登録はこちら</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-11 col-md-8 col-lg-12 mx-auto">
                                            <a href="{{ route('password_reissue') }}" class="text-red d-flex justify-content-end">
                                                <u>ログインできない場合はこちら</u>
                                            </a>
                                        </div>
                                    </div>                                       
                                </div>                                
                            </div>
                            <div class="col-12 col-lg-2 align-items-center justify-content-center">
                                <div class="border-login">&nbsp;</div>
                            </div>
                            <div class="col-11 col-md-8 col-lg-5 mx-auto">
                                <div class="form-login-social">
                                    <p class="label-social">外部アカウントを利用してログイン</p>
                                    <a href="{{ route('login-social', 'google') }}" class="btn btn-social-login">
                                        <img src="{{ asset('frontend/assets/images/icons/google.png') }}" alt="icon-google">
                                        <span>Google IDでログイン</span>
                                    </a>
                                    <a href="{{ route('login-social', 'facebook') }}" class="btn btn-social-login">
                                        <img src="{{ asset('frontend/assets/images/icons/fb.png') }}" alt="icon-fb">
                                        <span>Facebookでログイン</span>
                                    </a>
                                    <a href="{{ route('login-social', 'line') }}" class="btn btn-social-login mb-0">
                                        <img src="{{ asset('frontend/assets/images/icons/line.png') }}" alt="icon-line">
                                        <span>LINEでログイン</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
