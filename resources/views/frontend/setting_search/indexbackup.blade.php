@extends('frontend._base.app')
@section('title', 'Real Estate SettingSearch')
@section('description', '')
@section('page')
<div class="section-setting">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    <div class="content-header">
                        <h2 class="title title-pages">
                            <img src="{{ asset('frontend/assets/images/icons/icon_my_setting.png') }}" alt="icon_my_setting" class="icon-title">
                            <span class="text-title">My設定</span>
                        </h2>
                    </div>
                    <div class="content-body">
                        <div class="form-setting">                            
                            <div class="row">
                                <div class="col-12">
                                    <p class="label-form">お気に入りエリア</p>
                                    <div class="row">
                                        <div class="col-12 col-lg-6 input-flag" v-for="(data,index) in customer_area">
                                            <div class="form-group">
                                                <div class="form-label-flag">
                                                    <img src="{{ asset('frontend/assets/images/icons/icon_fav_area.png') }}" alt="icon-fav-area" class="icon-flag">
                                                    <input type="text" class="form-control" :value="data.city.prefecture.name + data.city.name + data.town.name">
                                                </div>
                                                <a href="javascript:void(0)" class="btn-minus" @click="remove(index)">
                                                    <img src="{{ asset('frontend/assets/images/icons/icon_remove_fav_area.png') }}" alt="icon-remove">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="content-area-fav">
                                                <div class="label-with-icon">
                                                    <img src="{{ asset('frontend/assets/images/icons/icon_add_fav_area.png') }}" alt="icon-plus">
                                                    <p class="text-label">お気に入りエリアを追加する</p>
                                                </div>
                                                <div class="form-area-fav">
                                                    <form action="{{route('frontend.search_settings.store')}}" method="POST" class="form-setting" data-parsley-validate>
                                                        @csrf
                                                        {{-- <div class="row mb-4" v-for="(item, index) in addLocation">
                                                            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                                                <div class="select-required">                                                                
                                                                    <select name="prefectures" class="form-control" v-model="item.valuePrefecture" @change="cities(index)">
                                                                        <option v-for="(data,index) in item.prefectureData" :key="data.id" :value="data.id">@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                                                <div class="select-required">
                                                                    <select name="city" class="form-control" v-model="item.valueCity" required data-parsley-required-message="未選択の項目があります。" @change="towns(index)">                                                                    
                                                                        <option v-for="(data,index) in item.cityData" :key="data.id" :value="data.id">@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                                <div class="mb-28"></div>
                                                            </div>
                                                            <div class="col-12 col-lg-4">
                                                                <div class="select-required">
                                                                    <select name="town" v-model="item.valueTown" class="form-control">
                                                                        <option v-for="(data,index) in item.townData" :key="data.id" :value="data.id">@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        <div class="row">
                                                            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                                                <div class="select-required">                                                                
                                                                    <select name="prefectures" class="form-control" v-model="addLocation.valuePrefecture" required data-parsley-required-message="未選択の項目があります。"  @change="cities()">
                                                                        <option v-for="(data,index) in prefecture" :key="data.id" :value="data.id">@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                                                <div class="select-required">
                                                                    <select name="city" class="form-control" v-model="addLocation.valueCity" required data-parsley-required-message="未選択の項目があります。" @change="towns()"> {{--:disabled="!addLocation.valuePrefecture" --}}
                                                                        <option v-for="(data,index) in addLocation[0].cityData" :key="data.id" :value="data.id">@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                                <div class="mb-28"></div>
                                                            </div>
                                                            <div class="col-12 col-lg-4">
                                                                <div class="select-required">
                                                                    <select name="town" v-model="addLocation.valueTown" class="form-control" required data-parsley-required-message="未選択の項目があります。" > {{-- :disabled="!addLocation.valueCity"  --}}
                                                                        <option v-for="(data,index) in addLocation[0].townData" :key="data.id" :value="data.id">@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="row">
                                                            <div class="col-6 col-lg-4 mx-auto px-lg-0 pt-3 pt-lg-4">
                                                                <button class="btn btn-primary-round" @click="addRowLocation()">
                                                                    <span>追加する</span>
                                                                </button>
                                                            </div>
                                                        </div> --}}
                                                        <div class="row">
                                                            <div class="col-6 col-lg-4 mx-auto px-lg-0 pt-3 pt-lg-4">
                                                                <button class="btn btn-primary-round" type="submit">
                                                                    <span>追加する</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{route('frontend.search_settings.customer')}}" method="POST" data-parsley-validate>
                                        @csrf
                                        <div class="row mt-4">                                        
                                            <div class="col-12 col-lg-6 mb-4">
                                                <div class="content-land">
                                                    <p class="label-form">お気に入りエリア</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col">
                                                                <select name="minimum_price" class="form-control" required>                                                                    
                                                                    <option v-for="(data,index) in list_consider_amount" :value="data.value">@{{data.value}}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_price" class="form-control" required>
                                                                    <option v-for="(data,index) in list_consider_amount" :value="data.value">@{{data.value}}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 mb-4">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地+建物金額</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col">
                                                                <select name="minimum_price_land_area" class="form-control">
                                                                    <option v-for="(data,index) in list_consider_amount" :value="data.value">@{{data.value}}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_price_land_area" class="form-control">
                                                                    <option v-for="(data,index) in list_consider_amount" :value="data.value">@{{data.value}}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="content-land">
                                                    <p class="label-form">希望の土地面積</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col">
                                                                <select name="minimum_land_area" class="form-control">
                                                                    <option v-for="(data,index) in list_land_area" :value="data.value">@{{data.value}}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_land_area" class="form-control">
                                                                    <option v-for="(data,index) in list_land_area" :value="data.value">@{{data.value}}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                        <div class="row">
                                            <div class="col-11 col-lg-4 mx-auto mt-48 px-lg-0">
                                                <button type="submit" class="btn btn-primary-round">
                                                    <span>上記の内容で保存する</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
@push('vue-scripts')
<script> @minify    
    (function( $, io, document, window, undefined ){        
        // Vue root component                
        // Vue router        
        router = {
            mode: 'history',
        };            
        // Vuex store - Centralized data        
        store = {            
            // Reactive central data            
            state: function(){
                var state = {                    
                    // Status flags                    
                    status: { loading: false },                                    
                    // Configs                    
                    config: {
                        placeholder: 3 // Item placeholder count
                    },                                        
                    // Preset data                    
                    preset: {
                        api: {                            
                            city: @json( route( 'api.location.city' )),
                            town: @json( route( 'api.location.town' ))
                        },
                    },                                        
                    // Request result will go here                    
                    result: null                     
                };                         
                return state;                
            },                        
            // Updating state data will need to go through these mutations            
            mutations: {}            
        };                
        // Vue mixin         
        mixin = {            
            // Reactive data            
            data: function(){
                return {
                    customer_desired_areas : @json( $customer_desired_areas ),                    
                    consider_amount        : @json( $consider_amount ),   
                    land_area              : @json( $land_area ),
                    prefecture             : @json($prefecture),
                    addLocation            : [{                                                    
                            cityData     : [],
                            townData     : [],
                    }],                                                            
                }
            },                                     
            // On mounted hook            
            mounted: function(){      
                // console.log(this);
                // this.addRowLocation();
            },               
            // Computed            
            computed: {
                customer_area : function() {
                    return this.customer_desired_areas;
                },       
                list_consider_amount : function() {
                    return this.consider_amount;
                },       
                list_land_area : function() {
                    return this.land_area;
                },          
            },                    
            // Methods            
            methods: {
                remove:function(index){
                    vm = this;
                    var getDesired = vm.customer_area[index].id;
                    var url = @json(route('frontend.search_settings.remove'));
                    var request = axios.post(url,{
                        id : getDesired,
                    });                    
                    request.then(function(response){                                    
                        this.customer_desired_areas = response.data.customer_desired_areas;
                    }.bind(this));      
                },

                // submit : function(){
                //     vm = this;                    
                //     var city = this.addLocation.valueCity;
                //     var town = this.addLocation.valueTown;
                //     var url = @json(route('frontend.search_settings.store'));
                //     var request = axios.post(url,{
                //         cities_id : city,
                //         towns_id : town,
                //     });                    
                //     request.then(function(response){                                    
                //         console.log(response);
                //         // this.customer_desired_areas = response.data.customer_desired_areas;
                //     }.bind(this));     
                // },
                // addRowLocation : function() {                                                      
                //     this.addLocation.push({                            
                //             prefectureData    : @json($prefecture),
                //             cityData     : [],
                //             townData     : [],
                //         },
                //     );                    
                // },    
                // cities : function(index){   
                //     vm = this;                                     
                //     var request = axios.post( this.$store.state.preset.api.city,{
                //         id : this.addLocation[index].valuePrefecture; 
                //     });                    
                //     request.then(function(response){                                            
                //         this.addLocation[index].cityData = response.data;
                //     }.bind(this));                                    
                // },
                // towns : function(index){   
                //     vm = this;                                     
                //     var request = axios.post( this.$store.state.preset.api.town,{
                //         id : this.addLocation[index].valuePrefecture; 
                //     });                    
                //     request.then(function(response){                                            
                //         this.addLocation[index].townData = response.data;
                //     }.bind(this));                                    
                // },
                cities : function(){   
                    vm = this;                                     
                    var request = axios.post( this.$store.state.preset.api.city,{
                        id : this.addLocation.valuePrefecture; 
                    });                    
                    request.then(function(response){                                            
                        this.addLocation[0].cityData = response.data;
                    }.bind(this));                                    
                },
                towns : function(){   
                    vm = this;                                     
                    var request = axios.post( this.$store.state.preset.api.town,{
                        id : this.addLocation.valuePrefecture; 
                    });                    
                    request.then(function(response){                 
                        this.addLocation[0].townData = response.data;                                                   
                    }.bind(this));                                    
                },
            },            
            
            // Watchers            
            watch: {}            
        };        
        
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
