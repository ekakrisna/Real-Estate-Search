@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('label.contact_contact_history')</li>
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
                <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.favorite_flag')</div></div>
                <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.contact_us_id')</div></div>
                <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.contact_us_date_and_time')</div></div>
                <div class="px-0 border-left bg-light col-xl-80px text-lg-center"><div class="py-2 px-2">@lang('label.dashboard_status')</div></div>
                <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.name_user')</div></div>
                <div class="px-0 border-left bg-light col-xl-60px"><div class="py-2 px-2">@lang('label.flag_history')</div></div>
                <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.corporate_in_charge')</div></div>
                <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.person_in_charge')</div></div>
                <div class="px-0 border-left bg-light col-xl-60px"><div class="py-2 px-2">@lang('label.dashboard_detail')</div></div>
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
                name: 'index', path: '/admin/customer_all_contact', 
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
                        coroprate: @json( $Coroprate ),
                        person: @json( $Customer ),
                        orders: [
                            { id: 'flag', label: '@lang('label.favorite_flag')' },
                            { id: 'id', label: '@lang('label.contact_us_id')' },
                            { id: 'created_at', label: '@lang('label.datetime')' },
                            { id: 'is_finish', label: '@lang('label.dashboard_status')' },
                            { id: 'name', label: '@lang('label.name_user')' },
                            { id: 'flag_favorite', label: '@lang('label.flag_history')' },
                            { id: 'company_users_id', label: '@lang('label.corporate_in_charge')' },
                            { id: 'company', label: '@lang('label.person_in_charge')' },                            
                        ]                        
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
            }            
        };            
        // Vue mixin         
        mixin = {                        
            data: function(){
                return {}
            },                                
            mounted: function(){
            },                        
            computed: {                                
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},                                            
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},                                                      
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}                
            },                                     
            methods: {},                                
            watch: {                                
                $route: {
                    immediate: true, 
                    handler: function( to, from ){                        
                        // Perform data request                        
                        var store = this.$store;
                        var url = @json( route( 'admin.customer_all_contact.filter' ));
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