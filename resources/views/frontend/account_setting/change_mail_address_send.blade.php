@extends('frontend._base.app')
@section('title', 'Real Estate AccountSearch')
@section('description', '')
@section('page')
<div class="section-setting-notif">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content text-center">
                    <p class="title">メールを送信しました。</p>
                    <p class="desc">メールアドレス変更用のメールを送信いたしました。<br/>送信されたメールにしたがって操作をしてください。</p>
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
