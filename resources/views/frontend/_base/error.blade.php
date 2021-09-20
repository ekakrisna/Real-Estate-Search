@extends('frontend._base.app')
@section('title', 'Real Estate Error')
@section('description', '')
@section('page')
<div class="notification-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content text-center">
                    <div class="row">
                        <div class="col-12 col-lg-10 mx-auto">
                            <img src="{{ asset('frontend/assets/images/icons/bg_error.png') }}" alt="img_accept" class="icon_error">
                            <h2 class="title">エラーが発生しました</h2>
                            <p class="desc">エラーメッセージが入ります。エラーメッセージが入ります。エラーメッセージが入ります。
                                エラーメッセージが<br class="d-none d-lg-block">入ります。<br class="d-block d-lg-none">エラーメッセージが入ります。</p>
                        </div>
                        <div class="col-11 col-lg-4 mx-auto">
                            <a href="" class="btn btn-primary-round">
                                <span>マップ画面へ戻る</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
