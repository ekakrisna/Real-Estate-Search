@extends('backend._base.content_tablelike')
@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('label.history_customer_search')</li>
    </ol>
@endsection
@section('content')

    <!-- Result filters - Start -->
    <Filters></Filters>
    <!-- Result filters - End -->
    <div class="tablelike">

        <!-- Table header - Start -->
        <div class="tablelike-header border-top border-right d-none d-xl-block">
            <div class="row mx-0">
                <div class="px-0 bg-light border-left col-12 col-md col-lg col-xl-150px"><div class="py-2 px-2">@lang('label.used_date_time')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md-6 col-lg-6 col-xl"><div class="py-2 px-2">@lang('label.name_user')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md-6 col-lg-6 col-xl-60px"><div class="py-2 px-2">@lang('label.flag_history')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md col-lg col-xl-150px"><div class="py-2 px-2">@lang('label.corporate_in_charge')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md-6 col-lg-6 col-xl-150px"><div class="py-2 px-2">@lang('label.person_in_charge')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md col-lg col-xl-100px"><div class="py-2 px-2">@lang('label.user_action')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md-6 col-lg-6 col-xl-80px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                <div class="px-0 bg-light border-left col-12 col-md-6 col-lg-6 col-xl"><div class="py-2 px-2">@lang('label.location_user')</div></div>                                
                <div class="px-0 bg-light border-left col-12 col-md col-lg col-xl-120px"><div class="py-2 px-2">@lang('label.building_condition')</div></div>
            </div>
        </div>
        <!-- Table header - End -->        
        <div class="tablelike-content">                
            <Placeholder v-if="isLoading" :count="$store.state.config.placeholder"></Placeholder>            
            <Result v-else v-model="resultData"></Result>            
        </div>        
    </div>

    <div class="mt-3">
        <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
    </div>

    <router-view></router-view>
@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')
@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){        
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/admin/customer_all_use_history/' , 
                component: { template: '<div/>' }
            }]
        };               
        // console.log(router);
        store = {            
            state: function(){
                var state = {                    
                    status: { loading: false },                    
                    config: {
                        placeholder: 3 // Item placeholder count
                    },                    
                    preset: {
                        customercontactus: @json( $CustomerContactUs ),                                            
                        actiontype: @json( $ActionType ),
                        coroprate: @json( $Company ),
                        person: @json( $CompanyUser ),
                        orders: [
                            { id: 'access_time', label: '@lang('label.used_date_time')' },
                            { id: 'name', label: '@lang('label.name_user')' },                            
                            { id: 'company', label: '@lang('label.corporate_in_charge')' },
                            { id: 'customer', label: '@lang('label.person_in_charge')' },
                            { id: 'action', label: '@lang('label.user_action')' },
                            { id: 'property_id', label: '@lang('label.property_id')' },                            
                            { id: 'location', label: '@lang('label.location_user')' },                            
                            { id: 'building', label: '@lang('label.building_condition')' },                            
                        ],
                    },                                        
                    result: null                     
                };                                
                // console.log( state );
                return state;                
            },                                
            // Updating state data will need to go through these mutations            
            mutations: {                
                // Set loading state                
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', loading );
                },                            
                // Set result                
                setResult: function( state, result ){
                    Vue.set( state, 'result', result );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Mutation to set customer flag
                // --------------------------------------------------------------
                setCustomerFlag: function( state, { customer, flag }){
                    var data = io.get( state, 'result.data' );
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Find all histories from the same customer
                    // ----------------------------------------------------------
                    var customerHistories = io.filter( data, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer == customerID;
                    });
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Set all customer flag from all matched customer histories
                    // ----------------------------------------------------------
                    io.each( customerHistories, function( history ){
                        var customer = io.get( history, 'customer' );
                        Vue.set( customer, 'flag', flag );
                    });
                    // ----------------------------------------------------------
                 }
                // --------------------------------------------------------------
            }            
        };            
        // Vue mixin         
        mixin = {            
            // Reactive data            
            data: function(){
                return {}
            },                    
            // On mounted hook            
            mounted: function(){
            },            
            // Computed properties            
            computed: {                
                // Loading state                
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},                            
                // Meta data of the paginated result                
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},                            
                // Data of the paginated result                
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}                
            },                    
            // Methods            
            methods: {},                    
            // Watchers            
            watch: {                
                // Watch the route changes
                // This will run everytime the route is changing
                // It runs immediately after page load                
                $route: {
                    immediate: true, 
                    handler: function( to, from ){                        
                        // Perform data request                        
                        var store = this.$store;
                        var url = @json( route( 'admin.customer_all_use_history.filter' ));
                        var request = axios.post( url, { filter: to.query });                                            
                        store.commit('setLoading'); // Set loading state                                                
                        // On success                        
                        request.then( function( response ){
                            // console.log( response );                            
                            var status = io.get( response, 'status' );
                            var result = io.get( response, 'data.result' );                            
                            if( 200 === status && result ){
                                store.commit( 'setResult', result );
                            }                            
                        });                                                
                        request.finally( function(){ store.commit('setLoading', false )});                        
                    }
                }                
            }            
        };        
        
    }( jQuery, _, document, window ));
@endminify </script>
@endpush