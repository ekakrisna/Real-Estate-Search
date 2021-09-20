@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('manage.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
    </ol>
@endsection

@section('content')

{{-- table like section --}}
<section class="content">
    <div class="container-fluid">
        <!-- Result filters - Start -->
        <Filters></Filters>

        <div class="tablelike mt-4">

            <!-- Table header - Start -->
            <div class="tablelike-header border-top border-right d-none d-xl-block">
                <div class="row mx-0">
                    <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.search_date_time')</div></div>
                    <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.name')</div></div>
                    <div class="px-0 border-left bg-light col-12 col-xl-60px"><div class="py-2 px-2">@lang('label.flag')</div></div> 
                    <div class="px-0 border-left bg-light col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_staff')</div></div>
                    <div class="px-0 border-left bg-light col-xl-180px"><div class="py-2 px-2">@lang('label.action_name')</div></div>
                    <div class="px-0 border-left bg-light col-xl-60px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                    <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                    <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.building_condition')</div></div>
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
            

        </div>

        <div class="mt-3">
            <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
        </div>

        <router-view></router-view>
    </div>   
</section>
{{-- table like section end --}}

@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')
@relativeInclude('vue.pagination.import')

@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/manage/customer_all_use_history', 
                component: { template: '<div/>' }
            }]
        };
        store = {
            state: function(){
                var state = {
                    status: { loading: false },
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    preset: {
                        actions: @json($actions),
                        company_users: @json($company_users),
                        orders: [
                            { id: 'access_time', label: '@lang('label.search_date_time')' },
                            { id: 'name', label: '@lang('label.name')' },
                            { id: 'in_charge_staff', label: '@lang('label.in_charge_staff')' },
                            { id: 'action_types.label', label: '@lang('label.action')' },
                            { id: 'properties_id', label: '@lang('label.property_id')' },
                            { id: 'properties.location', label: '@lang('label.location')' },
                            { id: 'properties.building_conditions_desc', label: '@lang('label.building_condition')' },
                        ]
                    },
                    result: null 
                };
                console.log( state );
                return state;
            },
            mutations: {
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', loading );
                },
                setResult: function( state, result ){
                    Vue.set( state, 'result', result );
                }
            }
        };

        mixin = {
            data: function(){
                return {
                }
            },

            mounted: function(){
            },

            computed: {
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
            },

            methods: {
                
            },

            watch: {
                $route: {
                    immediate: true, 
                    handler: function( to, from ){

                        var store = this.$store;
                        var url = @json( route( 'manage.customer.customer_all_use_history.filter' ));
                        var request = axios.post( url, { filter: to.query });

                        store.commit('setLoading'); // Set loading state

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
                    }
                }
            }
        };
    }( jQuery, _, document, window ));
@endminify </script>
@endpush