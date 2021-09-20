@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('label.approval')</li>
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
                <div class="px-0 border-left bg-light col-12 col-xl-130px"><div class="py-2 px-2">@lang('label.update_date_and_time')</div></div>
                <div class="px-0 border-left bg-light col-12 col-lg col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-130px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-130px"><div class="py-2 px-2">@lang('label.land_area_approval')</div></div>
                <div class="px-0 border-left bg-light col-12 col-lg col-xl"><div class="py-2 px-2">@lang('label.status_approval')</div></div>                
                <div class="px-0 border-left bg-light col-12 col-lg col-xl"><div class="py-2 px-2">@lang('label.publish_site')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-50px"><div class="py-2 px-2">@lang('label.detail')</div></div>                
            </div>
        </div>
        <!-- Table header - End -->


        <!-- Table content - Start -->
        <div class="tablelike-content">
            
            <!-- Loading placeholder - Start -->
            <Placeholder v-if="isLoading" :count="$store.state.config.placeholder"></Placeholder>
            <!-- Loading placeholder - End -->

            <!-- Result items - Start -->
            <Result v-else v-model="resultData"></Result>
            <!-- Result items - End -->

        </div>
        <!-- Table content - End -->
        <div class="mt-3">
            <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
        </div>

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
                name: 'index',path:'/admin/approval', 
                component: { template: '<div/>' }
            }]
        };
        store = {
            // ------------------------------------------------------------------
            // Reactive central data
            // ------------------------------------------------------------------
            state: function(){
                var state = {
                    status: { loading: false },
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    preset: {
                        orders: [
                            { id: 'created_at', label: "@lang('label.update_date_and_time')" },
                            { id: 'location', label: "@lang('label.location')" },                            
                            { id: 'building_conditions_desc', label: "@lang('label.status_approval')" },
                            { id: 'property_convert_status_id', label: "@lang('label.publish_site')" },                            
                        ]
                    },
                    result: null 
                };
                // console.log( state );
                return state;
            },
            mutations: {
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', loading );
                },
                setResult: function( state, result ){
                    Vue.set( state, 'result', result );
                },
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };

        // ----------------------------------------------------------------------
        // Vue mixin 
        // ----------------------------------------------------------------------
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
                        // ------------------------------------------------------
                        // Perform data request
                        // ------------------------------------------------------
                        var store = this.$store;
                        var url = @json( route( 'admin.approval.filter' ));
                        var request = axios.post( url, { filter: to.query });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        store.commit('setLoading'); // Set loading state
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // On success
                        // ------------------------------------------------------
                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var result = io.get( response, 'data.result' );
                            // --------------------------------------------------
                            if( 200 === status && result ){
                                store.commit( 'setResult', result );
                            }
                            // --------------------------------------------------
                        });
                        request.finally( function(){ store.commit('setLoading', false )});
                        // ------------------------------------------------------
                    }
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
