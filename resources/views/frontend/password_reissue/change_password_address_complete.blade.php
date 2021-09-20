@extends('frontend._base.app')
@section('title', 'Real Estate AccountSearch')
@section('description', '')
@section('page')
<div class="section-setting-notif">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content text-center">
                    <p class="title">パスワードリセット完了</p>
                    <p class="desc">変更が完了しました。<br/>引き続きトチサーチをご利用ください。</p>
                    <div class="row">
                        <div class="col-11 col-lg-4 mx-auto">
                            <a href="{{ route('frontend.account_settings') }}" class="btn btn-primary-round">
                                <span>ログイン画面へ</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
