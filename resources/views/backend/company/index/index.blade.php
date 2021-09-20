@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
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
                <div class="px-0 bg-light border-left col-xl-60px"><div class="py-2 px-2">ID</div></div>
                <div class="px-0 bg-light border-left col-xl"><div class="py-2 px-2">@lang('label.company_named')</div></div>
                <div class="px-0 bg-light border-left col-xl-120px"><div class="py-2 px-2">@lang('label.type')</div></div>
                <div class="px-0 bg-light border-left col-xl-150px"><div class="py-2 px-2">@lang('label.register_date')</div></div>
                <div class="px-0 bg-light border-left col-xl-100px"><div class="py-2 px-2">@lang('label.status')</div></div>
                <div class="px-0 bg-light border-left col-xl-100px"><div class="py-2 px-2">@lang('label.user_count')</div></div>
                <div class="px-0 bg-light border-left col-xl-100px"><div class="py-2 px-2">@lang('label.dashboard_detail')</div></div>                
                <div class="px-0 bg-light border-left col-xl-110px"><div class="py-2 px-2">@lang('label.dashboard_detail')</div></div>                
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
@relativeInclude('vue.pagination.import')

<script> @minify
    (function( $, io, document, window, undefined ){        
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/admin/company', 
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
                        company: @json( $Company ),                                                    
                        companyRole: @json( $CompanyRole ), 
                        orders: [
                            { id: 'id', label: 'ID' },
                            { id: 'company_name', label: '@lang('label.company_named')' },
                            { id: 'label', label: '@lang('label.type')' },
                            { id: 'created_at', label: '@lang('label.register_date')' },
                            { id: 'is_active', label: '@lang('label.status')' },                                                            
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
                }                
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
                        var url = @json( route( 'admin.company.list.filter' ));
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