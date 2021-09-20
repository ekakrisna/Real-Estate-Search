@extends('frontend._base.app')
@section('title', 'トチサーチとは？')
@section('description', '')
@section('page')
<div class="section-overview">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    <div class="content-header">
                        <h2 class="title"><span>トチサーチとは？</span></h2>
                        <p class="subtitle">トチサーチは地図上で<br class="d-block d-md-none text-left">土地の検索が無料で誰でも<br class="d-block d-md-none">簡単に行えるアプリです！</p>
                    </div>
                    <div class="content-body">
                        <div class="content-img">
                            <img src="{{ asset('frontend/assets/images/overview/AppleProDisplay-1.png') }}" alt="AppleProDisplay" class="w-100 lozad d-none d-md-block">
                            <img src="{{ asset('frontend/assets/images/overview/AppleiPhone.png') }}" alt="AppleProDisplay" class="w-100 lozad d-block d-md-none">
                        </div>
                        <p class="text">土地はGoogle Map上に表示されるので、<br class="d-block d-md-none">住まいのイメージ合わせた理想の土地を<br class="d-block d-md-none text-left">探すことができます。</p>
                        <div class="content-img">
                            <img src="{{ asset('frontend/assets/images/overview/AppleProDisplay-1.png') }}" alt="AppleProDisplay" class="w-100 lozad d-none d-md-block">
                            <img src="{{ asset('frontend/assets/images/overview/AppleiPhone2.png') }}" alt="AppleProDisplay" class="w-100 lozad d-block d-md-none">
                        </div>
                        <p class="text">ご希望に沿った「面積」「価格」の<br class="d-block d-md-none">条件で検索が可能です。</p>
                    </div>
                    <div class="content-footer">
                        <p class="text">無料の会員登録することで様々便利機能を<br class="d-block d-md-none">ご用意しております！</p>
                        <a href="{{ route('explanation') }}" class="btn btn-primary-round">
                            <span>便利機能の詳細はこちら</span>
                        </a>
                        <p class="text">あなたの理想の土地選びを<br class="d-block d-md-none">サポートします。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
