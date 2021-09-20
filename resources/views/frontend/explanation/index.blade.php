@extends('frontend._base.app')
@section('title', '無料登録でご利用可能な便利機能')
@section('description', '')
@section('page')
<div class="section-explanation">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    <div class="content-header">
                        <h1 class="title">無料登録でご利用可能な便利機能</h1>
                    </div>
                    <div class="content-body">
                        <div class="row">
                            <div class="col-12 col-lg-6 mx-auto">
                                <div class="content-point-link">
                                    <div class="item-link">
                                        <div class="numbering"><span class="text-point">Point</span><span class="text-numb">01</span></div>
                                        <a href="javascript:void(0)" class="item-link-target target1"><span>新着土地情報が、すぐに通知。</span></a>
                                    </div>
                                    <div class="item-link">
                                        <div class="numbering"><span class="text-point">Point</span><span class="text-numb">02</span></div>
                                        <a href="javascript:void(0)" class="item-link-target target2"><span>お気に入り機能で、簡単に状況<br class="d-block d-md-none">チェック。</span></a>
                                    </div>
                                    <div class="item-link">
                                        <div class="numbering"><span class="text-point">Point</span><span class="text-numb">03</span></div>
                                        <a href="javascript:void(0)" class="item-link-target target3"><span>土地情報の既読機能で、もっと<br class="d-block d-md-none">使いやすく。</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="content-information info1">
                                    <div class="information-header">
                                        <h2 class="title"><span>新着情報の通知</span></h2>
                                        <p class="subtitle text-left text-md-center d-inline-block d-md-block px-3 px-md-0">お気に入り登録したエリアで、新しい土<br class="d-block d-md-none">地情報がでたら、すぐに通知されます。</p>
                                    </div>
                                    <div class="information-body">
                                        <div class="content-img">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_update.png') }}" alt="PC_update" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/SP_update.png') }}" alt="SP_update" class="w-100 lozad d-block d-md-none">
                                        </div>
                                        <p class="text text-left text-md-center d-inline-block d-md-block px-4 px-md-0">随時配信する、お得なおすすめ情報を受け取ることができます。</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="content-information info2">
                                    <div class="information-header">
                                        <h2 class="title"><span>お気に入りの土地</span></h2>
                                        <p class="subtitle text-left text-md-center d-inline-block d-md-block px-3 px-md-0">ご希望の土地をお気に入り登録が行えます。お気に入りした土地は、「お気に入り一覧」からいつでも照会可能です。</p>
                                    </div>
                                    <div class="information-body">
                                        <div class="content-img">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_favorite.png') }}" alt="PC_favorite" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/SP_favorite.png') }}" alt="SP_favorite" class="w-100 lozad d-block d-md-none">
                                        </div>
                                        <div class="content-img">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_favorite-map.png') }}" alt="PC_favorite-map" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/SP_favorite-map.png') }}" alt="SP_favorite-map" class="w-100 lozad d-block d-md-none">
                                        </div>
                                        <p class="text text-left d-inline-block d-md-block px-2 px-md-0">地図上で検索を行った際、お気に入りした土地が含まれている場合は、土地情報に「お気に入り土地のマーク」が追加され、地図上で簡単に見分けが付きます。</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="content-information info3">
                                    <div class="information-header">
                                        <h2 class="title"><span>履歴照会</span></h2>
                                        <p class="subtitle">過去に閲覧した履歴の一覧が照会出来ます。</p>
                                    </div>
                                    <div class="information-body">
                                        <div class="content-img">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_history.png') }}" alt="PC_history" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/SP_history.png') }}" alt="SP_history" class="w-100 lozad d-block d-md-none">
                                        </div>
                                        <div class="content-img">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_history_map.png') }}" alt="PC_history_map" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/SP_history_map.png') }}" alt="PC_history_map" class="w-100 lozad d-block d-md-none">
                                        </div>
                                        <p class="text text-left text-md-center d-inline-block d-md-block px-4 px-md-0">地図上で検索を行った際、過去に閲覧した物件は、灰色で表示され、地図上で簡単に見分けが付きます。</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 px-0 px-md-3">
                                <div class="content-information pb-0">
                                    <div class="information-header">
                                        <h2 class="title mb-4 mb-md-0"><span>My設定</span></h2>
                                        <p class="subtitle d-inline-block d-md-none px-4 px-md-0 text-left pt-3">検索条件の「面積」「価格」を設定する<br/>ことができます。<br/>設定情報は地図上で土地を検索する際、<br/>検索条件として自動で適応されます。</p>
                                    </div>
                                    <div class="information-body">
                                        <div class="content-img pb-0 pb-5">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_mysettings.png') }}" alt="PC_mysettings" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/PC_mysettings2.png') }}" alt="PC_mysettings2" class="w-100 lozad d-none d-md-block">
                                            <img src="{{ asset('frontend/assets/images/explanation/SP_mysetting.png') }}" alt="SP_mysetting" class="w-100 lozad d-block d-md-none">
                                        </div>
                                        <p class="text text-center d-none d-md-block">検索条件の「面積」「価格」を設定することができます。<br/>設定情報は地図上で土地を検索する際、検索条件として自動で適応されます。</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
