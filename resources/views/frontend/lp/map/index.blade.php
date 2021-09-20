@extends('frontend._base.old_app')
@section('title', 'Real Estate Map')
@section('description', '')
@section('page')
<div class="map-page">
    <a href="javascript:void(0)" class="btn-filter">
        <img src="{{ asset('frontend/assets/images/icons/icon_map_filter.png')}}" alt="icon-filter" class="img-filter">
        <span>条件で絞り込み</span>
    </a>
    <div class="sidebar-left-map d-none">
        <div class="sidebar-overlay"></div>
        <div class="content-sidebar">
            <a href="javascript:void(0)" class="btn-icon-close">
                <img src="{{ asset('frontend/assets/images/icons/icon_close.png') }}" alt="icon-close">
            </a>
            <form class="form-filter" action="#">
                <div class="form-group mb-5">
                    <label class="label-input">
                        <img src="{{ asset('frontend/assets/images/icons/icon_budget.png') }}" alt="icon_budget" class="img-icon-label">
                        <span>検討可能な予算</span>
                    </label>
                    <div class="row align-items-center">
                        <div class="col">
                            <p id="slider-range-value1" class="form-control form-control-red"></p>
                        </div>
                        <span>～</span>
                        <div class="col">
                            <p id="slider-range-value2" class="form-control form-control-red"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="rangeslider rangeslider1">
                                <div id="slider-range"></div>
                                <input type="hidden" id="slider-min-price-value" value="">
                                <input type="hidden" id="slider-max-price-value" value="">
                                <input type="hidden" id="tmp-slider-min-price-value" value="">
                                <input type="hidden" id="tmp-slider-max-price-value" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-5">
                    <label class="label-input">
                        <img src="{{ asset('frontend/assets/images/icons/icon_prooerty_size.png') }}" alt="icon_prooerty_size" class="img-icon-label">
                        <span>土地面積</span>
                    </label>
                    <div class="row align-items-center">
                        <div class="col">
                            <p id="slider-range-value3" class="form-control form-control-red"></p> 
                        </div>
                        <span>～</span>
                        <div class="col">
                            <p id="slider-range-value4" class="form-control form-control-red"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="rangeslider rangeslider2">
                                <div id="slider-range2"></div>
                                <input type="hidden" id="slider-min-land_area-value" value="">
                                <input type="hidden" id="slider-max-land_area-value" value="">
                                <input type="hidden" id="tmp-slider-min-land_area-value" value="">
                                <input type="hidden" id="tmp-slider-max-land_area-value" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button id="mapSearchButton" class="btn btn-primary-round btn-max-320" type="button">
                            <span>検索する</span>
                        </button>
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        <a href="javascript:void(0)" class="link-with-icon-times">
                            <img src="{{ asset('frontend/assets/images/icons/icon_reset.png') }}" alt="icon-reset">
                            <span>絞り込み条件をリセット</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="map" class="map-canvas"></div>
</div>

<input id="loggedInCustomerID" type="hidden" value="{{Auth::guard('user')->user()->id}}">
<input id="townLat" type="hidden" value="{{$town->lat}}">
<input id="townLng" type="hidden" value="{{$town->lng}}">

@push('js')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIautqM0oUnD3MUiGP002uDC5xA63I3Es&libraries=places&region=JP&language=ja"></script>
@endpush
@endsection
