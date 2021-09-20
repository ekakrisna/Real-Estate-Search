@extends('frontend._base.app')
@section('title', 'Real Estate History')
@section('description', '')
@section('page')
<div class="section-notif-no-data">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content text-center">
                    <img src="{{ asset('frontend/assets/images/icons/bg_fav_history_nodata.png') }}" alt="img-no-data">
                    <span>過去に閲覧した物件はありません</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
