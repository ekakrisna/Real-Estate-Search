@extends('frontend._base.vueform')
@section('title', 'Real Estate Property Detail')
@section('description', '')
@section('form-content')
<div class="fav-section">
    <div class="container" v-if="property">
        <div class="row" v-if="property.length > 0">
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
                            <div class="col-12 col-lg-6 mb-30 mb-16-sp" v-for="(data,index) in property">
                                {{-- Property card component --}}
                                <property-card :property="data.property"></property-card>
                                {{-- Property card component --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-notif-no-data" v-else>
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
    </div>
</div>
{{-- @endif --}}
@endsection
@push('vue-scripts')

<!-- Frontend components - Start -->
@include('frontend.vue.property-card.import')
@include('frontend.vue.property-label.import')
@include('frontend.vue.button-favorite.import')
<!-- Frontend components - End -->

<script> @minify    
    (function( $, io, document, window, undefined ){        
        router = {
            mode: 'history',
        };
        store = {            
            state: function(){
                var state = {                    
                    status: { loading: false },                  
                    config: {
                        placeholder: 3 // Item placeholder count
                    },                    
                    preset: {},                    
                    result: null                     
                };
                return state;
            },                    
            // Updating state data will need to go through these mutations            
            mutations: {}            
        };                
        // Vue mixin         
        mixin = {            
            data: function(){
                return {                    
                    properties      : @json($propertyList),
                }
            },            
            mounted: function(){},            
            computed: {
                property : function() {
                    return this.properties ? this.properties : "";
                },
            },            
            methods: {},            
            watch: {}            
        };        
    }( jQuery, _, document, window ));
@endminify </script>
@endpush