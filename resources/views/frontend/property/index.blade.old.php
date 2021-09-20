@extends('frontend._base.app')
@section('title', 'Real Estate Property')
@section('description', '')
@section('page')
<div class="fav-section">
    <div class="container py-5" v-if="property && area">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    <div class="content-header mb-4 mb-lg-3 ">
                        <div class="row">
                            <div class="col-12 col-lg-6 d-flex align-items-center pb-19">
                                <h2 class="title title-pages mb-0">
                                    <span class="text-title">@{{area.prefecture}}@{{area.city}}@{{area.town}}</span>
                                </h2>
                            </div>
                            <div class="col-12 col-lg-6 d-flex justify-content-end">
                                <div class="box-fav-area">
                                    <div class="label-text">
                                        <img src="{{ asset('frontend/assets/images/icons/icon_fav_area.png') }}" alt="icon-flag">
                                        <span>お気に入りエリア</span>
                                    </div>
                                    <label class="toggle-switch" for="fav_toggle" data-size="lg" data-text="false" data-style="rounded">
                                        <input type="checkbox" v-model="favoriteButton" :value="toggle_area" id="fav_toggle" @change="saveHandle()" />
                                        <span class="toggle">
                                            <span class="switch"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-body content-icon-fav">
                        <div class="row" v-if="property.length > 0">
                            <div class="col-12 col-lg-6 mb-30 mb-16-sp" v-for="(data,index) in property">
                                <div class="content-list">
                                    <div class="content-list-header">
                                        <a :href="data.url.frontend_view"><h3 class="title">@{{data.location}}</h3></a>                                        
                                    </div>
                                    <div class="content-list-body">                                        
                                        <div class="row row-base">
                                            <div class="col-4 col-lg-6">
                                                <div class="img-content" v-if="data.property_photos[0]">                                                                                                        
                                                    <img :src="data.property_photos[0].file.url.image" v-bind:alt="data.property_photos[0].file.name" class="w-100">                                                    
                                                </div>
                                                <div class="img-content" v-else>                                                    
                                                </div>
                                            </div>
                                            <div class="col-8 col-lg-6">
                                                <div class="list-badge">                                                    
                                                    <label class="bg-badge badge-red" v-if="data.ja.lessMonth">新着</label>
                                                    <label class="bg-badge badge-blue" v-if="data.ja.withUpdate">更新</label>
                                                    <label class="bg-badge badge-green" v-if="building_conditions(index)">建築条件なし</label>
                                                </div>
                                                <p class="price-ranges" v-if="data.minimum_price && data.maximum_price">
                                                    @{{ data.minimum_price | toManDisplay }}  ~ @{{ data.maximum_price | toManDisplay }} 
                                                </p>
                                                <p class="price-ranges" v-else-if="data.minimum_price && data.maximum_price == null">
                                                    @{{ data.minimum_price | toManDisplay }}
                                                </p>
                                                <p class="price-ranges" v-else-if="data.minimum_price == null && data.maximum_price">
                                                    @{{ data.maximum_price | toManDisplay }}
                                                </p>                                                
                                                <div class="list-desc">
                                                    <span class="text-label">土地</span>
                                                    <p class="text-value" v-if="data.minimum_land_area && data.maximum_land_area">
                                                        @{{ data.minimum_land_area | toTsubo }} ~ @{{ data.maximum_land_area | toTsubo}}
                                                    </p>
                                                    <p class="text-value" v-else-if="data.minimum_land_area && data.maximum_land_area == null">
                                                        @{{ data.minimum_land_area | toTsubo }}
                                                    </p>
                                                    <p class="text-value" v-else-if="data.minimum_land_area == null && data.maximum_land_area">
                                                        @{{ data.maximum_land_area | toTsubo}}
                                                    </p>                                                         
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 d-flex justify-content-end" v-if="data.customer_favorite_properties.length > 0">                                        
                                        <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_on.png') }}" class="icon-fav icon-fav-on" @click="flagHandle(index)">                                                                            
                                    </div>
                                    <div class="w-100 d-flex justify-content-end" v-else>                                        
                                        <img src="{{ asset('frontend/assets/images/icons/icon_fav_property_off.png') }}" class="icon-fav icon-fav-off" @click="flagHandle(index)">
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
            </div>
        </div>
    </div>
    <div class="container py-5" v-else>
        <div class="section-notif-no-data"e>
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
@endsection
@push('vue-scripts')
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
                    favoriteButton  : @json($togle_area),
                    properties      : @json($properties),
                    desired_area    : @json($desired_area),
                    customer        : @json($customer),
                    togle_area      : @json($togle_area),
                }
            },            
            mounted: function(){
                // console.log( this.properties );
            },            
            computed: {
                property : function() {
                    return this.properties;
                },
                area : function() {
                    return this.desired_area;
                },        
                customers : function() {
                    return this.customer;
                },    
                toggle_area: function(){
                    if (this.togle_area == 1) {
                        return this.favoriteButton = true;
                    }else{
                        return this.favoriteButton = false;
                    }
                }
            },            
            methods: {
                building_conditions : function(index) {                    
                    return this.property[index].building_conditions;
                },   
                flagHandle: function(index){
                    var vm              = this; 
                    var id              = vm.property[index].id;
                    console.log(id);
                    const url           = @json(route('frontend.property.addfavorite', ':id' ));
                    const urlReplace    = url.replace(":id", id);                                         
                    axios.post(urlReplace, {
                        customers_id: @json($idCustomers),                            
                    }).then( function( response ){                                                
                        vm.property[index].customer_favorite_properties = response.data;                        
                    });  
                },  
                saveHandle: function(){
                    var vm      = this; 
                    var id      = vm.area.id;                    
                    var Arr     = id.split(',');
                    var cities  = Arr[1];
                    var towns   = Arr[2];
                    var properties = vm.property;
                    // console.log(properties);
                    const url   = @json(route('frontend.property.adddesired'));                               
                    axios.post(url, {
                        customers_id: @json($idCustomers),                            
                        cities_id: cities,
                        towns_id: towns,
                        properties: properties,
                    }).then( function( response ){});  
                },
            },            
            watch: {}            
        };        
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
