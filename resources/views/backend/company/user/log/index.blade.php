@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item">@lang('label.usage_history_home_maker')</li>
    </ol>
@endsection

@section('top_buttons')
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid row mb-3">
            <div class="col-sm-12">

                <!-- Result filters - Start -->
                <Filters></Filters>
                <!-- Result filters - End -->

                <hr class="my-4" />

                <div class="tablelike">

                    <!-- Table header - Start -->
                    <div class="tablelike-header border-top border-right d-none d-xl-block">
                        <div class="row mx-0">
                            <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
                            <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.company_name')</div></div>
                            <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.user_name_company')</div></div>
                            <div class="px-0 border-left bg-light col-xl-220px"><div class="py-2 px-2">@lang('label.e-mail')</div></div>
                            <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.users_authority')</div></div>
                            <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.executed_action')</div></div>
                            <div class="px-0 border-left bg-light col-xl-160px"><div class="py-2 px-2">@lang('label.user_detail')</div></div>
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
        </div>
    </section>


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
                name: 'index', path: '/admin/homemaker/use_history',
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
                        roles: @json( $roles ),
                        actionType: @json( $actions ),
                        orders: [
                            { id: 'access_time', label: '@lang('label.datetime')' },
                            { id: 'company_name', label: '@lang('label.company_name')' },
                            { id: 'user_name', label: '@lang('label.user_name_company')' },
                            { id: 'email', label: '@lang('label.e-mail')' },
                            { id: 'authority', label: '@lang('label.authority')' },
                            { id: 'activity', label: '@lang('label.action')' }
                        ]
                    },
                    result: null
                };
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
                        var url = @json( route( 'admin.company.user_log.filter' ));
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
