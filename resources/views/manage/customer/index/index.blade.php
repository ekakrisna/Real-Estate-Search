@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('manage.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
    </ol>
@endsection

@section('content')

    <!-- Result filters - Start -->
    <Filters></Filters>
    <!-- Result filters - End -->

    <div class="tablelike mt-4">

        <!-- Table header - Start -->
        <div class="tablelike-header border-top border-right d-none d-xl-block">
            <div class="row mx-0">
                <div class="px-0 border-left bg-light col-12 col-xl-80px"><div class="py-2 px-2">@lang('label.flag')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.name')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.in_charge_staff')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.registration_date_and_time')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.last_use_date')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-90px"><div class="py-2 px-2">@lang('label.favorite_property_number')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-90px"><div class="py-2 px-2">@lang('label.property_number_checked_recent_2_weeks')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-90px"><div class="py-2 px-2">@lang('label.license')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-100px"><div class="py-2 px-2">@lang('label.edit')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-100px"><div class="py-2 px-2">@lang('label.detail')</div></div>
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
@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')
@relativeInclude('vue.pagination.import')

@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue router
        // ----------------------------------------------------------------------
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path:'/manage/customer/', 
                component: { template: '<div/>' }
            }]
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vuex store - Centralized data
        // ----------------------------------------------------------------------
        store = {
            // ------------------------------------------------------------------
            // Reactive central data
            // ------------------------------------------------------------------
            state: function(){
                var state = {
                    // ----------------------------------------------------------
                    // Status flags
                    // ----------------------------------------------------------
                    status: { loading: false },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Configs
                    // ----------------------------------------------------------
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Preset data
                    // ----------------------------------------------------------
                    preset: {
                        company_users: @json( $company_users ),
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Order options
                        // Reminder:
                        // Ordering based on relation requires Join query in Laravel
                        // Eager loading doesn't support ordering based on relation
                        // ------------------------------------------------------
                        orders: [
                            { id: 'name', label: '@lang('label.name')' },
                            { id: 'created_at', label: '@lang('label.registration_date_and_time')' },
                            { id: 'favorite_properties', label: '@lang('label.favorite_property_number')' },
                            { id: 'properties_checked', label: '@lang('label.property_number_checked_recent_2_weeks')' },
                            { id: 'license', label: '@lang('label.license')' },
                            { id: 'flag', label: '@lang('label.flag')' },
                            { id: 'company_users_id ', label: '@lang('label.in_charge_staff')' },
                            { id: 'customer_last_activity', label: '@lang('label.last_use_date')' },
                            
                            // { id: 'role', label: 'User Role' },
                            // { id: 'company', label: 'Company Name' },
                        ]
                        // ------------------------------------------------------
                    },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Request result will go here
                    // ----------------------------------------------------------
                    result: null 
                    // ----------------------------------------------------------
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                console.log( state );
                return state;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Updating state data will need to go through these mutations
            // ------------------------------------------------------------------
            mutations: {
                // --------------------------------------------------------------
                // Set loading state
                // --------------------------------------------------------------
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', loading );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Set result
                // --------------------------------------------------------------
                setResult: function( state, result ){
                    Vue.set( state, 'result', result );
                },
                setCompanyUser (state, newCompanyUser) {
                    Vue.set( state.preset.company_users = newCompanyUser );
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue mixin 
        // ----------------------------------------------------------------------
        mixin = {
            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {}
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted hook
            // ------------------------------------------------------------------
            mounted: function(){
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Loading state
                // --------------------------------------------------------------
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Meta data of the paginated result
                // --------------------------------------------------------------
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Data of the paginated result
                // --------------------------------------------------------------
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // Watch the route changes
                // This will run everytime the route is changing
                // It runs immediately after page load
                // --------------------------------------------------------------
                $route: {
                    immediate: true, 
                    handler: function( to, from ){
                        // ------------------------------------------------------
                        // Perform data request
                        // ------------------------------------------------------
                        var store = this.$store;
                        var url = @json( route( 'manage.customer.filter' ));
                        var request = axios.post( url, { filter: to.query });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        store.commit('setLoading'); // Set loading state
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // On success
                        // ------------------------------------------------------
                        request.then( function( response ){
                            // console.log( response );
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var result = io.get( response, 'data.result' );
                            // --------------------------------------------------
                            if( 200 === status && result ){
                                store.commit( 'setResult', result );
                            }
                            // --------------------------------------------------
                        });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        request.finally( function(){ store.commit('setLoading', false )});
                        // ------------------------------------------------------
                    }
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush