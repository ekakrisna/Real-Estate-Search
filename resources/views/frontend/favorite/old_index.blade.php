@extends('frontend._base.app')
@section('title', 'Real Estate Favorite')
@section('description', '')
@section('page')

@if($query->count() < 1)
<div class="section-notif-no-data">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content text-center">
                    <img src="{{ asset('frontend/assets/images/icons/bg_fav_property_nodata.png') }}" alt="img-no-data">
                    <span>新着情報はありません</span>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="fav-section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    <div class="content-header">
                        <h2 class="title title-pages">
                            <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_on.png') }}" alt="icon_fav" class="icon-title">
                            <span class="text-title">お気に入り物件</span>
                        </h2>
                    </div>
                    <div class="content-body content-icon-fav">
                        <div class="row">
                            @foreach ($query as $data)
                                <div class="col-12 col-lg-6 mb-30">
                                    <div class="content-list">
                                        <div class="content-list-header">
                                            <h3 class="title">{{ $data->location }}</h3>
                                        </div>
                                        <div class="content-list-body">
                                            <div class="row row-base">
                                                <div class="col-4 col-lg-6">
                                                    <div class="img-content">
                                                        <img src="{{ asset('seeder_image/'. $data->name.'.'.$data->extension) }}" alt="" class="w-100">
                                                    </div>
                                                </div>
                                                <div class="col-8 col-lg-6">
                                                    <div class="list-badge">
                                                        @php
                                                            $nowDate = date('m-Y');
                                                            $createDate = date('m-Y', strtotime($data->created_at));
                                                            $updateDate = date('m-Y', strtotime($data->updated_date));
                                                        @endphp
                                                        @if($data->building_conditions == 0)
                                                            <label class="bg-badge badge-green">建築条件なし</label>
                                                        @elseif($data->building_conditions == 0 && $createDate == $nowDate)
                                                            <label class="bg-badge badge-red">新着</label>
                                                            <label class="bg-badge badge-green">建築条件なし</label>
                                                        @elseif($data->building_conditions == 0 && $createDate !== $updateDate)
                                                            <label class="bg-badge badge-blue">更新</label>
                                                            <label class="bg-badge badge-green">建築条件なし</label>
                                                        @elseif($data->building_conditions == 1 && $createDate == $nowDate)
                                                            <label class="bg-badge badge-red">新着</label>
                                                        @elseif($data->building_conditions == 1 && $createDate !== $updateDate)
                                                            <label class="bg-badge badge-blue">更新</label>
                                                        @endif
                                                    </div>
                                                    <p class="price-ranges">
                                                        @if(!empty($data->minimum_price) && empty($data->maximum_price))
                                                            {{ number_format($data->minimum_price) }}万円
                                                        @elseif(empty($data->minimum_price) && !empty($data->maximum_price))
                                                            {{ number_format($data->maximum_price) }}万円
                                                        @else
                                                            {{ number_format($data->minimum_price) }}万円 ~  {{ number_format($data->maximum_price) }}万円
                                                        @endif
                                                    </p>
                                                    <div class="list-desc">
                                                        <span class="text-label">土地</span>
                                                        <p class="text-value">
                                                            @if(!empty($data->minimum_land_area) && empty($data->maximum_land_area))
                                                                {{ $data->minimum_land_area }}㎡
                                                            @elseif(empty($data->minimum_land_area) && !empty($data->maximum_land_area))
                                                                {{ $data->maximum_land_area }}㎡
                                                            @else
                                                                {{ $data->minimum_land_area }}㎡ ~ {{ $data->maximum_land_area }}㎡
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="favIcon" class="w-100 d-flex justify-content-end" @submit.prevent="updateFavorite" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" id="fav_id" name="fav.id" :value="{{ $data->id }}">
                                            <button class="btn-love" type="submit">
                                                <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_off.png') }}" class="icon-fav icon-fav-off d-none">
                                                <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_on.png') }}" class="icon-fav icon-fav-on">
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@push('vue-scripts')
<script> @minify    
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue router
        // ----------------------------------------------------------------------
        router = {
            mode: 'history',
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vuex store - Centralized data
        // ----------------------------------------------------------------------
        store = {
            // ------------------------------------------------------------------
            // Reactive central data
            // ------------------------------------------------------------------
            state: function(){
                var state = {
                    // ----------------------------------------------------------
                    // Status flags
                    // ----------------------------------------------------------
                    status: { loading: false },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Configs
                    // ----------------------------------------------------------
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Preset data
                    // ----------------------------------------------------------
                    preset: {},
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Request result will go here
                    // ----------------------------------------------------------
                    result: null 
                    // ----------------------------------------------------------
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return state;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Updating state data will need to go through these mutations
            // ------------------------------------------------------------------
            mutations: {}
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue mixin 
        // ----------------------------------------------------------------------
        mixin = {
            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted hook
            // ------------------------------------------------------------------
            mounted: function(){
                                
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed
            // ------------------------------------------------------------------
            computed: {

            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                updateFavorite() {
                    let url = '/api/fav-list/${this.$route.params.id}';
                    this.axios.post(url, this.post).then((response) => {
                        this.$route.push({ name:'fav' });
                    })
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
@endsection
