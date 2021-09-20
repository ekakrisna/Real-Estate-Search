@extends('backend._base.content_tablelike')

@section('content')        
    <Filters></Filters>    
    <div class="tablelike text-break">        
        <div class="tablelike-header border-top border-right d-none d-xl-block">
            <div class="row mx-0">
                <div class="px-0 border-left bg-light col-lg-6 col-xl-180px"><div class="py-2 px-2">@lang('label.team_leader')</div></div>
                <div class="px-0 border-left bg-light col-lg col-xl"><div class="py-2 px-2">Email (@lang('label.leader'))</div></div>
                <div class="px-0 border-left bg-light col-lg-6 col-xl-180px"><div class="py-2 px-2">@lang('label.browsing_target')</div></div>
                <div class="px-0 border-left bg-light col-lg-6 col-xl"><div class="py-2 px-2">Email (@lang('label.member'))</div></div>
                <div class="px-0 border-left bg-light col-lg col-xl-130px"><div class="py-2 px-2"></div></div>                                
            </div>
        </div>        
        <div class="tablelike-content">                
            <Placeholder v-if="isLoading" :count="$store.state.config.placeholder"></Placeholder>            
            <Result v-else v-model="resultData"></Result>            
        </div>            
    </div>
    <div class="mt-3">
        <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
    </div>    
@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')

<script> @minify
    (function( $, io, document, window, undefined ){        
        router = {
            mode: 'history',
            routes: [{                 
                name: 'index', path: '/admin/company/{{$company_id}}/user/permission/',                
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
                        orders: [
                            { id: 'team_leader', label: '@lang('label.team_leader')' },
                            { id: 'email_leader', label: 'Email @lang('label.leader')' },                                   
                        ],
                    },                                        
                    result: null                     
                };                                                
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
                        var url = @json( route( 'admin.company.user.detail.filter', $company_id));
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