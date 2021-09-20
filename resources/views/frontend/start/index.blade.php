@extends('frontend._base.vueform')
@section('title', $title)
@section('description', '')
@section('form-content')
    <div class="section-form-start">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="content">
                        {{-- <form action="{{route('frontend.map')}}" action="GET" class="form form-start" data-parsley> --}}
                        <form class="form form-start" data-parsley>
                            <div class="form-group mb-50">
                                <label class="label-input">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_fav_area.png') }}" alt="icon_fav_area" class="img-icon-label">
                                    <span>お気に入りエリア</span>      
                                </label>
                                <div class="row" v-if="customer_area.length > 0">                                                                        
                                    <div class="col-12 col-md-6 col-lg-4 mb-2" v-for="(data,index) in customer_area">
                                        <label class="radio-custom">
                                            @{{data.city.prefecture.name}}@{{data.city.name}}@{{data.city_areas != null ? data.city_areas.display_name :"" }}
                                            <input type="radio" name="customer_desired_areas" id="customer_desired_areas" @click="getValue(index)" :value="data.id" required　
                                            data-parsley-errors-container="#favarit_area_list" data-parsley-error-message="エリアを選択してください。" :checked="data.default">
                                            <span class="label-radio"></span>
                                        </label>
                                    </div>  
                                    <div class="row col-12">
                                        <div id="favarit_area_list" class="col-12"></div>
                                    </div>  
                                </div>
                                <div class="row" v-else>
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <label>お気に入り登録しているエリアはありません。</label>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group mb-50">
                                <label class="label-input">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_budget.png') }}" alt="icon_budget" class="img-icon-label">
                                    <span>検討可能な予算</span>
                                </label>
                                <div class="row">                                    
                                    <div class="col-12 col-md-6 col-lg-6 pb-5 pb-md-0">
                                        <label class="input-form">検討可能な土地金額</label>
                                        <div class="row align-items-center">
                                            <div class="col"> 
                                                <div class="select-required">                                                              
                                                    <select name="minimum_price" id="minimum_price" v-model="customers.minimum_price" class="form-control" 
                                                        data-parsley-le="#maximum_price" data-parsley-trigger="input change" @input="trigger('#maximum_price')"> 
                                                        <option :value="0" :selected="!customers.minimum_price">下限なし</option>
                                                        <option v-for="( data, index ) in list_consider_amount" :key="data.value" :value="data.value" 
                                                            :selected="customers.minimum_price === data.value">@{{ data.value | toManDisplay }}</option>
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
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <label class="input-form">検討可能な土地+建物金額</label>
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <select name="minimum_price_land_area" id="minimum_price_land_area" v-model="customers.minimum_price_land_area" 
                                                    class="form-control" data-parsley-le="#maximum_price_land_area" data-parsley-trigger="input change" 
                                                    @input="trigger('#maximum_price_land_area')">
                                                    <option :value="0" :selected="!customers.minimum_price_land_area">下限なし</option>
                                                    <option v-for="( data, index ) in list_consider_amount" :key="data.id" :value="data.value" 
                                                        :selected="customers.minimum_price_land_area == data.value">@{{ data.value | toManDisplay }}</option>
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                            <span>～</span>
                                            <div class="col">
                                                <select name="maximum_price_land_area" id="maximum_price_land_area" v-model="customers.maximum_price_land_area" 
                                                    class="form-control" data-parsley-ge="#minimum_price_land_area" data-parsley-trigger="input change"
                                                    @input="trigger('#minimum_price_land_area')">    
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
                            <div class="form-group mb-5">
                                <label class="label-input">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_prooerty_size.png') }}" alt="icon_prooerty_size" class="img-icon-label">
                                    <span>土地面積</span>
                                </label>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <label class="input-form">希望の土地面積</label>
                                        <div class="row align-items-center">
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
                            <div class="row">
                                <div class="col-12">                                    
                                    <button type="submit" class="btn btn-primary-round btn-max-320">                                        
                                        <span>物件を表示する</span>                                        
                                    </button>
                                </div>
                                <div class="col-12 d-flex justify-content-center pt-2">
                                    <p class="text-red">※選択した検索条件は、My設定に反映されます。</p>
                                </div>
                                <div class="col-12 d-flex justify-content-center">
                                    <a href="{{ route('frontend.search_settings') }}" class="link-with-icon-play">
                                        <i class="fa fa-caret-right"></i>
                                        <span>MY設定を変更する</span>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('vue-scripts')
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
                    status: { mounted: false, loading: false },
                    cancellation: { loading: false },                                                   
                    preset: {
                        api: {                            
                            map: @json( route( 'frontend.map' )),
                            store: @json( route( 'frontend.map.store' )),
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
            }            
        };                        
        mixin = {                        
            data: function(){
                return {
                    customer_desired_areas : @json( $desired_area ),                    
                    consider_amount        : @json( $consider_amount ),   
                    land_area              : @json( $land_area ),                        
                    customer               : @json( $customer_detail ),
                    customer_desired       : [],
                }
            },                                     
            created: function(){
                var vm = this;
                this.customer_area.forEach(function (key) {
                    if (key.default == true) {
                        vm.customer_desired = key.id;                                                                             
                    }
                });
            },
            mounted: function(){                     
                this.$store.commit( 'setMounted', true );                
                $(document).trigger( 'vue-loaded', this );                        
            },            
            computed: {
                customers               : function(){ return this.customer },     
                customer_area           : function(){ return this.customer_desired_areas },       
                list_consider_amount    : function(){ return this.consider_amount },       
                list_land_area          : function(){ return this.land_area },                 
                isLoading               : function(){ return this.$store.state.status.loading },
                isMounted               : function(){ return this.$store.state.status.mounted },                
            },                                
            methods: {
                getValue: function(index){
                    var vm = this;                    
                    vm.customer_desired = vm.customer_area[index].id;                    
                },

                // Trigger input-event on the target element
                trigger: function( target ){
                    $(target).trigger('input');
                }
            },                                    
            watch: {}
        };   

        $(document).on( 'vue-loaded', function( event, vm ){            
            var $window = $(window);
            var $form = $('form[data-parsley]');            
            var form = $form.parsley();
            var queue = window.queue;               
            var store = vm.$store;                                                                    

            form.on( 'form:validated', function(){                
                var validForm = form.isValid();
                if( validForm == false ) navigateValidation( validForm );                
                else {                    
                    var state = vm.$store.state;
                    vm.$store.commit( 'setLoading', true );
                    var url = io.get( state.preset, 'api.store' );
                    var urlMap = io.get( state.preset, 'api.map' );
                    const data = {
                        customer_desired_areas: vm.customer_desired,
                        minimum_price: vm.customers.minimum_price,
                        maximum_price: vm.customers.maximum_price,
                        minimum_price_land_area: vm.customers.minimum_price_land_area,
                        maximum_price_land_area: vm.customers.maximum_price_land_area,
                        minimum_land_area: vm.customers.minimum_land_area,
                        maximum_land_area: vm.customers.maximum_land_area,                        
                    };                    
                    var request = axios.post( url, data );
                    request.then( function( response ){
                        var customer = response.data.customer;
                        var lat = response.data.lat;
                        var lng = response.data.lng;
                        var noLimitMinPrice = customer.minimum_price === 0 ? Number(Vue.filter('toMan')(response.data.lowest_price_filter)) - @json(config('const.man_price_step')) : Vue.filter('numeral')( Number(Vue.filter('toMan')(customer.minimum_price)), 0,0 );
                        var noLimitMaxPrice = customer.maximum_price === 0 ? Number(Vue.filter('toMan')(response.data.highest_price_filter)) + @json(config('const.man_price_step')) : Vue.filter('numeral')( Number(Vue.filter('toMan')(customer.maximum_price)), 0,0 );
                        var noLimitMinLandArea = customer.minimum_land_area === 0 ? Number(Vue.filter('toTsubo')(response.data.lowest_land_area_filter)) - @json(config('const.tsubo_area_step')) : Vue.filter('numeral')( Number(Vue.filter('toTsubo')(customer.minimum_land_area)), 0,0 );                                
                        var noLimitMaxLandArea = customer.maximum_land_area === 0 ? Number(Vue.filter('toTsubo')(response.data.highest_land_area_filter)) + @json(config('const.tsubo_area_step')) : Vue.filter('numeral')( Number(Vue.filter('toTsubo')(customer.maximum_land_area)), 0,0 );
                        if (response.data.status == 'success') {
                            // var paramURL = urlMap+'?lat='+lat+'&lng='+lng+'&minimum_price='+noLimitMinPrice
                            //     +'&maximum_price='+noLimitMaxPrice+'&minimum_land_area='+noLimitMinLandArea
                            //     +'&maximum_land_area='+noLimitMaxLandArea;
                            window.location = urlMap;
                        }else{
                            var message = '@lang('label.FAILED_UPDATE_MESSAGE')';
                            vm.$toasted.show( message, { type: 'error' });
                        }
                    });                                        
                    // Handle other response                    
                    request.catch( function(e){ console.log( e )});
                    request.finally( function(){ vm.$store.commit( 'setLoading', false ) });                                 
                }                
            }).on('form:submit', function(){ return false });  
        });                     
    }( jQuery, _, document, window ));
@endminify </script>
@endpush


@push('css')
<style>
#parsley-id-multiple-customer_desired_areas > li {
    font-size: 15px;
    color: red;
}
</style>
@endpush