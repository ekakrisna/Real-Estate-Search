@extends('frontend._base.app')
@section('title', 'Real Estate News')
@section('description', '')
@section('page')
<div class="section-notif-no-data">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content text-center">
                    <img src="{{ asset('frontend/assets/images/icons/bg_info_nodata.png') }}" alt="img-no-data">
                    <span>新着情報はありません</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
