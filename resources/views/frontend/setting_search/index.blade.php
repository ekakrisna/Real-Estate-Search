@extends('frontend._base.vueform')
@section('title', $title)
@section('description', '')
@section('form-content')

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
                                                <a href="javascript:void(0)" class="form-label-flag" @click="addFavorite(index)" v-if="data.default">
                                                    <i class="icon-flag fas fa-flag text-red fs-24"></i>
                                                    <p type="text" class="form-control">@{{data.city.prefecture.name}}@{{data.city.name}}@{{data.city_areas != null ? data.city_areas.display_name :"" }}</p>
                                                </a>
                                                <a href="javascript:void(0)" class="form-label-flag" @click="addFavorite(index)" v-else>
                                                    <i class="icon-flag fas fa-flag text-muted fs-24"></i>
                                                    <p type="text" class="form-control">@{{data.city.prefecture.name}}@{{data.city.name}}@{{data.city_areas != null  ? data.city_areas.display_name :"" }}</p>
                                                </a>                                                                                                
                                                {{-- <a href="javascript:void(0)" class="btn-minus" v-on:click="remove(index)">                                                    
                                                    <i class="icon-flag fas fa-minus-circle fs-24 text-muted" v-bind:class="[{ 'text-red' : data.url.disered }, 'material-icons']"></i>                                                                                                     
                                                </a>  --}}
                                                <a v-if="!data.default" href="javascript:void(0)" class="btn-minus" v-on:click="remove(index)">                                                    
                                                    <i class="icon-flag fas fa-minus-circle fs-24 text-muted"></i>                                                                                                     
                                                </a>        
                                                <a v-else class="btn-minus">            
                                                    <span style="width: 24px" class="d-block"></span>                                                                                                                                          
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
                                                    <form class="form-setting" data-parsley>                                                                                                                                            
                                                        <div class="row">
                                                            <div class="col-12 col-md mb-3 mb-lg-0">            
                                                                <div class="select-required">                                                                                                                           
                                                                    <select name="prefectures" class="form-control" v-model="valuePrefecture" required data-parsley-required-message="未選択の項目があります。" disabled>                                                                                                                                                
                                                                        <option value="" :selected="valuePrefecture">宮城県</option>                                                                        
                                                                        <option v-for="(data,index) in prefectures" :key="data.id" :value="data.id" >@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md mb-3 mb-lg-0">
                                                                <div class="select-required">
                                                                    <select name="prefecture_area" class="form-control" v-model="value.valuePrefectureArea" required data-parsley-required-message="未選択の項目があります。" v-if="!isLoadingPrefectureArea">
                                                                        <option value="" class="d-none">選択してください</option>
                                                                        <option v-for="(data,index) in prefecture_area" :key="data.id" :value="data.id">@{{data.display_name}}</option>
                                                                    </select>   
                                                                    <p class="form-control text-center" style="padding: 0.75rem 1rem;height: auto;" v-if="isLoadingPrefectureArea">
                                                                        <span class="innerset">
                                                                            <span class="interface">
                                                                                <i class="fal fa-spin fa-cog fs-24"></i>
                                                                            </span>
                                                                        </span>
                                                                    </p>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                                <div class="mb-lg-4"></div>
                                                            </div>
                                                            
                                                            <div class="col-12 col-md mb-3 mb-lg-0">
                                                                <div class="select-required">
                                                                    <select name="city" class="form-control" v-model="value.valueCity" :disabled="!value.valuePrefectureArea" required data-parsley-required-message="未選択の項目があります。" v-if="!isLoadingCity">
                                                                        <option v-if="value.valuePrefectureArea" value="" class="d-none">選択してください</option>
                                                                        <optgroup v-for="(data,index) in city" v-if="data.cities.length > 0" :label="data.group_character">
                                                                            <option v-for="(data_city,index) in data.cities" :value="data_city.id">@{{data_city.name}}</option>
                                                                        </optgroup>
                                                                    </select>
                                                                    <p class="form-control text-center" style="padding: 0.75rem 1rem;height: auto;" v-if="isLoadingCity">
                                                                        <span class="innerset">                                                                        
                                                                            <span class="interface">
                                                                                <i class="fal fa-spin fa-cog fs-24"></i>
                                                                            </span>
                                                                        </span>
                                                                    </p>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                                <div class="mb-lg-4"></div>
                                                            </div>
                                                            <div class="col-12 col-md mb-5 mb-lg-0">
                                                                <div class="select-required">                                                                    
                                                                    <select name="town" class="form-control" v-model="value.valueCitiesArea" :disabled="!value.valueCity || !value.valuePrefectureArea" v-if="!isLoadingCitiesArea"  data-parsley-required-message="未選択の項目があります。">
                                                                        <option v-if="value.valuePrefectureArea" value="" class="d-none">選択してください</option>
                                                                        <optgroup v-for="(data,index) in town" v-if="data.cities_areas.length > 0" :label="data.group_character">
                                                                            <option v-for="(data_cities,index) in data.cities_areas" :value="data_cities.id">@{{data_cities.display_name}}</option>
                                                                        </optgroup>
                                                                    </select>
                                                                    <p class="form-control text-center" style="padding: 0.75rem 1rem;height: auto;" v-if="isLoadingCitiesArea">
                                                                        <span class="innerset">                                                                        
                                                                            <span class="interface">
                                                                                <i class="fal fa-spin fa-cog fs-24"></i>
                                                                            </span>
                                                                        </span>
                                                                    </p>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 col-lg-4 mx-auto px-lg-0 pt-3 pt-lg-4">                                                                                                                                   
                                                                <button-action v-model="isLoading" type="submit" label="追加する"></button-action>                                                                                                                                    
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="form-setting-customer" data-parsley-customer>                                        
                                        <div class="row mt-4">                                        
                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地金額</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col"> 
                                                                <div class="select-required">                                                              
                                                                    <select name="minimum_price" id="minimum_price" v-model="customers.minimum_price" class="form-control" 
                                                                        data-parsley-le="#maximum_price" data-parsley-trigger="input change" @input="trigger('#maximum_price')"> 
                                                                        <option :value="0" :selected="!customers.minimum_price">下限なし</option>
                                                                        <option v-for="( data, index ) in list_consider_amount" :key="data.value" :value="data.value" 
                                                                            :selected="customers.minimum_price == data.value">@{{ data.value | toManDisplay }}</option>
                                                                    </select>                                                                    
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">                                                            
                                                                <div class="select-required">                                                              
                                                                    <select name="maximum_price" id="maximum_price" v-model="customers.maximum_price" class="form-control" 
                                                                        data-parsley-ge="#minimum_price" data-parsley-trigger="input change" @input="trigger('#minimum_price')">
                                                                        <option :value="0" :selected="!customers.maximum_price">上限なし</option>
                                                                        <option v-for="( data, index ) in list_consider_amount" :key="data.id" :value="data.value" 
                                                                            :selected="customers.maximum_price == data.value">@{{ data.value | toManDisplay }}</option>
                                                                    </select>                                                                    
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地+建物金額</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col">
                                                                <select name="minimum_price_land_area" id="minimum_price_land_area" v-model="customers.minimum_price_land_area" class="form-control" 
                                                                    data-parsley-le="#maximum_price_land_area" data-parsley-trigger="input change" @input="trigger('#maximum_price_land_area')">
                                                                    <option :value="0" :selected="!customers.minimum_price_land_area">下限なし</option>
                                                                    <option v-for="( data, index ) in list_consider_amount" :key="data.id" :value="data.value" 
                                                                        :selected="customers.minimum_price_land_area == data.value">@{{ data.value | toManDisplay }}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_price_land_area" id="maximum_price_land_area" v-model="customers.maximum_price_land_area" class="form-control" 
                                                                    data-parsley-ge="#minimum_price_land_area" data-parsley-trigger="input change" @input="trigger('#minimum_price_land_area')">
                                                                    <option :value="0" :selected="!customers.maximum_price_land_area">上限なし</option>    
                                                                    <option v-for="( data, index ) in list_consider_amount" :key="data.id" :value="data.value" 
                                                                        :selected="customers.maximum_price_land_area == data.value">@{{ data.value | toManDisplay }}</option>
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
                                                                <select name="minimum_land_area" id="minimum_land_area" v-model="customers.minimum_land_area" class="form-control" 
                                                                    data-parsley-le="#maximum_land_area" data-parsley-trigger="input change" @input="trigger('#maximum_land_area')">
                                                                    <option :value="0" :selected="!customers.minimum_land_area">下限なし</option>                                                             
                                                                    <option v-for="( data, index ) in list_land_area" :key="data.id" :value="data.value" 
                                                                        :selected="customers.minimum_land_area == data.value">@{{ data.value | toTsubo | numeral('0,0') }}坪</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_land_area" id="maximum_land_area" v-model="customers.maximum_land_area" class="form-control" 
                                                                    data-parsley-ge="#minimum_land_area" data-parsley-trigger="input change" @input="trigger('#minimum_land_area')">
                                                                    <option :value="0" :selected="!customers.maximum_land_area">上限なし</option>                                                                
                                                                    <option v-for="( data, index ) in list_land_area" :key="data.id" :value="data.value" 
                                                                        :selected="customers.maximum_land_area == data.value">@{{ data.value | toTsubo | numeral('0,0') }}坪</option>    
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
                                                <button-action v-model="isLoadingCustomer" type="submit" label="上記の内容で保存する"></button-action>                                                
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

@push('css')
    <link rel="stylesheet" href="{{ asset( 'plugins/icheck-bootstrap/icheck-bootstrap.min.css' )}}">
@endpush

@push('vue-scripts')
<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
<!-- Preloaders - End -->

<!-- Frontend components - Start -->
@include('frontend.vue.button-action.import')
<!-- Frontend components - End -->
<script> @minify    
    (function( $, io, document, window, undefined ){    
        window.queue = {};            
        router = {
            mode: 'history',
        };                    
        store = {                        
            state: function(){
                var state = {                                        
                    status: { mounted: false, loading: false, mountedCustomer: false, loadingCustomer: false, },
                    cancellation: { loading: false },                                                   
                    preset: {
                        api: {                            
                            prefecture: @json( route( 'api.location.prefecture' )),
                            prefecture_areas: @json( route( 'api.location.prefecture_areas' )),
                            prefecture_area: @json( route( 'api.location.prefecture_area' )),
                            city: @json( route( 'api.location.city' )),                            
                            town: @json( route( 'api.location.town' )),
                            cities_areas: @json( route('api.location.cities_areas')),
                            store: @json( route( 'frontend.search_settings.store')),                            
                            customer: @json( route( 'frontend.search_settings.customer')),                            
                        },
                    },                                                                    
                    result: null                     
                };                         
                return state;                
            },                                    
            mutations: {
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', !! loading );
                },                
                setMounted: function( state, mounted ){
                    if( io.isUndefined( mounted )) mounted = true;
                    Vue.set( state.status, 'mounted', !! mounted );
                },              
                setLoadingCustomer: function( state, loadingCustomer ){
                    if( io.isUndefined( loadingCustomer )) loadingCustomer = true;
                    Vue.set( state.status, 'loadingCustomer', !! loadingCustomer );
                },                
                setMountedCustomer: function( state, mountedCustomer ){
                    if( io.isUndefined( mountedCustomer )) mountedCustomer = true;
                    Vue.set( state.status, 'mountedCustomer', !! mountedCustomer );
                },  
            }            
        };                        
        mixin = {                        
            data: function(){
                return {
                    customer_desired_areas : @json( $customer_desired_areas ),                    
                    consider_amount        : @json( $consider_amount ),   
                    land_area              : @json( $land_area ),                        
                    customer               : @json( $customer ),                    
                    isLoadingCity          : false,
                    isLoadingCitiesArea    : false,
                    isLoadingPrefectureArea : false,                    
                    prefectures            : [],
                    prefecture_area        : [],
                    is_all_show            : [],
                    valuePrefecture        : 4,
                    city                   : [],                    
                    town                   : [],    
                    value                  : {
                        valuePrefectureArea: '',
                        valueCity: '',
                        valueCitiesArea: ''
                    },               
                    idDesired              : [],
                }
            },                                     
            created: function(){                                
                if( this.$store.state.preset.api.prefecture ){
                    var vm = this;                    
                    var request = axios.post( this.$store.state.preset.api.prefecture );                    
                    request.then( function( response ){                        
                        var status = io.get( response, 'status' );
                        var prefectures = io.get( response, 'data' );                           
                        if( 200 === status && prefectures.length ){
                            vm.prefectures.length = 0;
                            io.each( prefectures, function( prefecture ){
                                vm.prefectures.push( prefecture );                                
                            })
                        }                        
                    });                    
                }
            },            
            mounted: function(){                     
                this.$store.commit( 'setMounted', true );
                this.$store.commit( 'setMountedCustomer', true );
                $(document).trigger( 'vue-loaded', this );        
                this.loadCity(4);
            },            
            computed: {
                customers               : function() { return this.customer },     
                customer_area           : function() { return this.customer_desired_areas },       
                list_consider_amount    : function() { return this.consider_amount },       
                list_land_area          : function() { return this.land_area },                 
                isLoading               : function(){ return this.$store.state.status.loading },
                isMounted               : function(){ return this.$store.state.status.mounted },
                isLoadingCustomer       : function(){ return this.$store.state.status.loadingCustomer },
                isMountedCustomer       : function(){ return this.$store.state.status.mountedCustomer },                
            },                                
            methods: {  
                addFavorite:function(index){
                if( this.customer_desired_areas[index].default){
                    return;
                }

                for(var i = 0 ; i < this.customer_desired_areas.length ; i++){
                    this.customer_desired_areas[i].default = false;
                    if(i == index){
                        this.customer_desired_areas[i].default = true;
                    }
                }
                },  
                remove:function(index){
                    vm = this;                                            
                    if(vm.customer_area[index].default){return;}
                    if(!confirm("お気に入りエリアを削除しますか？")){
                        return;          
                    }

                    var arr = vm.customer_desired_areas;
                    const filteredItems = arr.slice(0, index).concat(arr.slice(index + 1, arr.length));
                    vm.idDesired.push(vm.customer_area[index].id);
                    vm.customer_desired_areas = filteredItems;                   
                },
                loadCity:function(prefecture_id){
                    var vm = this;                            
                    if (!prefecture_id) {
                        vm.value.valueCity = '';
                        vm.value.valueCitiesArea = ''; 
                        vm.isLoadingCity = false;                       
                        vm.isLoadingCitiesArea = false;
                    }else{
                        if( this.$store.state.preset.api.prefecture && prefecture_id ){                            
                            vm.isLoadingPrefectureArea = true;
                            var request = axios.post( this.$store.state.preset.api.prefecture_areas, { id: prefecture_id });
                            request.then( function( response ){                            
                                var status = io.get( response, 'status' );
                                var options = io.get( response, 'data' );                            
                                if( 200 === status && options.length ){ 
                                    vm.prefecture_area = options;
                                }                            
                            });
                            request.finally(function(){
                                vm.isLoadingPrefectureArea = false;                                        
                            });
                        } 
                    }                            
                },

                // Trigger input-event on the target element
                trigger: function( target ){
                    $(target).trigger('input');
                }
            },                                    
            watch: {       
                'valuePrefecture': function(prefecture_id) {     
                    this.loadCity(prefecture_id);
                },
                'value.valuePrefectureArea': function( areaID ){
                    var vm = this;
                    vm.value.valueCity = '';

                    if( this.$store.state.preset.api.prefecture_area && areaID ){
                        vm.isLoadingCity = true;
                        var request = axios.post( this.$store.state.preset.api.prefecture_area, { id: areaID });
                        request.then( function( response ){                            
                            var status = io.get( response, 'status' );
                            var options = io.get( response, 'data' );                                
                            if( 200 === status){                                      
                                vm.is_all_show = options;                                
                                vm.value.valueCitiesArea = '';
                            }
                        });                                                                                                                                            
                    }                          
                },
                'is_all_show':function(all_show){
                    vm = this;
                    const prefecture_id = vm.valuePrefecture;
                    const prefecture_area_id = vm.value.valuePrefectureArea;

                    var request = axios.post( this.$store.state.preset.api.city, { prefecture_id: prefecture_id ,prefecture_areas_id: prefecture_area_id });
                    request.then( function( response ){                            
                        var status = io.get( response, 'status' );
                        var options = io.get( response, 'data' );                            
                        if( 200 === status && options.length ){
                            vm.city = options;
                        }                            
                    });
                    request.finally(function(){
                        vm.isLoadingCity = false;
                    });
                },
                'value.valueCity': function( cityID ) {
                    var vm = this;
                    vm.value.valueCitiesArea = '';

                    if( this.$store.state.preset.api.prefecture && cityID ){                        
                        vm.isLoadingCitiesArea = true;                               
                        var request = axios.post( this.$store.state.preset.api.cities_areas, { id: cityID });                        
                        request.then( function( response ){                            
                            var status = io.get( response, 'status' );
                            var options = io.get( response, 'data' );                            
                            if( 200 === status && options.length ){
                                vm.town = options;
                            }else{
                                vm.town = null;
                            }
                        });
                        request.finally(function(){                            
                            vm.isLoadingCitiesArea = false;
                        });
                    }                                                                                                                                                                                                                                                                                                              
                },   
            }     
        };   

        $(document).on( 'vue-loaded', function( event, vm ){            
            var $window = $(window);
            var $form = $('form[data-parsley]');
            var form = $form.parsley();
            var queue = window.queue;               
            var store = vm.$store;                                                        

            // ------------------------------------------------------------------
            // Form Customer
            var $formcustomer = $('form[data-parsley-customer]');
            var formcustomer = $formcustomer.parsley();
            // ------------------------------------------------------------------

            form.on( 'form:validated', function(){                
                
                var validForm = form.isValid();
                if( validForm == false ) navigateValidation( validForm );                
                else {                    
                    var state = vm.$store.state;
                    vm.$store.commit( 'setLoading', true );                                                                      
                    var data = {};
                    var formData = new FormData();
                    var url = io.get( state.preset, 'api.store' );                                                        
                    data.cities_id = vm.value.valueCity;                                                            
                    data.cities_area_id = vm.value.valueCitiesArea;                                      
                    formData.append( 'dataset', JSON.stringify( data ));                                        
                    // console.log( data ); return;
                    var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                    queue.save = axios.post( url, formData, options ); // Do the request                                                    
                    queue.save.then( function( response ){                                                
                        vm.$store.commit( 'setLoading', true );
                        if( response.data ){
                            if(response.data.status == "success"){
                                var message = '変更を保存する場合は「上記の内容で保存する」ボタンを押してください。';
                                vm.$toasted.show( message, { type: 'success' });
                                var response = response.data.desired;
                                
                                vm.customer_desired_areas.push({
                                     cities_id :  response.city.id,
                                     city :  response.city,
                                     default : false,
                                     id : -1,
                                     location : null,
                                     cities_area_id : response.city_areas != null ? response.city_areas.id : null,
                                     city_areas : response.city_areas,
                                });

                                vm.value.valueCity = '';
                                vm.value.valueCitiesArea = '';
                                vm.value.valuePrefectureArea = '';
                            }else{                                
                                var message = '@lang('label.FAILED_CREATE_MESSAGE')';
                                vm.$toasted.show( message, { type: 'error' });                                
                            }                            
                        }                        
                    });                                        
                    // Handle other response                    
                    queue.save.catch( function(e){ console.log( e )});
                    queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });                                        
                    return false;       
                }
                    
            }).on('form:submit', function(){ return false });  

            // ------------------------------------------------------------------
            // On form submit Customer
            // ------------------------------------------------------------------
            formcustomer.on( 'form:validated', function(){  
                var validForm = formcustomer.isValid();
                vm.style = validForm;
                if( validForm == false ) navigateValidation( validForm );                
                else {                 
                    var state = vm.$store.state;
                    vm.$store.commit( 'setLoadingCustomer', true );                
                    var data = {};
                    var formData = new FormData();
                    var url = io.get( state.preset, 'api.customer' ); 
                    data.minimum_price = vm.customer.minimum_price;                                                            
                    data.maximum_price = vm.customer.maximum_price;                  
                    data.minimum_price_land_area = vm.customer.minimum_price_land_area;                
                    data.maximum_price_land_area = vm.customer.maximum_price_land_area;                                                            
                    data.minimum_land_area = vm.customer.minimum_land_area;                  
                    data.maximum_land_area = vm.customer.maximum_land_area; 
                    data.customer_desired_areas = vm.customer_desired_areas;     
                    formData.append( 'dataset', JSON.stringify( data ));
                    // console.log(data); return;
                    var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                    queue.save = axios.post( url, formData, options ); // Do the request                
                    queue.save.then( function( response ){
                        vm.$store.commit( 'setLoadingCustomer', true );
                        if( response.data ){
                            if(response.data.status == "success"){
                                vm.customer = response.data.customer;                                
                                var customer = response.data.customer;
                                var lat = response.data.lat;
                                var lng = response.data.lng;
                                var noLimitMinPrice = customer.minimum_price === 0 ? Number(Vue.filter('toMan')(response.data.lowest_price_filter)) - @json(config('const.man_price_step')) : Vue.filter('numeral')( Number(Vue.filter('toMan')(customer.minimum_price)), 0,0 );
                                var noLimitMaxPrice = customer.maximum_price === 0 ? Number(Vue.filter('toMan')(response.data.highest_price_filter)) + @json(config('const.man_price_step')) : Vue.filter('numeral')( Number(Vue.filter('toMan')(customer.maximum_price)), 0,0 );
                                var noLimitMinLandArea = customer.minimum_land_area === 0 ? Number(Vue.filter('toTsubo')(response.data.lowest_land_area_filter)) - @json(config('const.tsubo_area_step')) : Vue.filter('numeral')( Number(Vue.filter('toTsubo')(customer.minimum_land_area)), 0,0 );                                
                                var noLimitMaxLandArea = customer.maximum_land_area === 0 ? Number(Vue.filter('toTsubo')(response.data.highest_land_area_filter)) + @json(config('const.tsubo_area_step')) : Vue.filter('numeral')( Number(Vue.filter('toTsubo')(customer.maximum_land_area)), 0,0 );

                                var redirectPage = @json(route('frontend.map'));                                
                                window.location = redirectPage;                                
                            }else{                            
                                var message = '@lang('label.FAILED_UPDATE_MESSAGE')';
                                vm.$toasted.show( message, { type: 'error' });                        
                            }                       
                        }
                    });                                
                    // Handle other response                
                    queue.save.catch( function(e){ console.log( e )});
                    queue.save.finally( function(){ vm.$store.commit( 'setLoadingCustomer', false ) });                                
                    return false;   
                }    
            }).on('form:submit', function(){ return false });            
        });                     
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
