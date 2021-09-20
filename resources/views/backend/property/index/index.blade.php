@extends('backend._base.content_tablelike')

@php    
    $status      = 'property.create.status';

    $pending     = __( "{$status}.pending" );
    $published   = __( "{$status}.published" );
    $limited     = __( "{$status}.limited" );
    $unpublished = __( "{$status}.unpublished" );
    $notpublish  = __( "{$status}.notpublish" );
    
@endphp

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
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
                <div class="px-0 border-left bg-light col-xl-60px"><div class="py-2 px-2">@lang('label.report_bug')</div></div>
                <div class="px-0 border-left bg-light col-xl-160px"><div class="py-2 px-2">@lang('label.update_date_and_time')</div></div>
                <div class="px-0 border-left bg-light col-xl-160px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
                <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
                <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.status')</div></div>
                <div class="px-0 border-left bg-light col-xl-80px"><div class="py-2 px-2">@lang('label.building_condition')</div></div>
                <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.photo')</div></div>
                <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.flyer')</div></div>
                <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2"></div></div>
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
@relativeInclude('vue.result.property-limited.import')

<!-- Upload components - Start -->
@include('backend.vue.components.icheck.import')
@include('backend.vue.components.ratiobox.import')
<!-- Upload components - End -->

<!-- Shared property components - Start -->
@include('backend.property.vue.property-status.import')
@include('backend.vue.components.empty-placeholder.import')
<!-- Shared property components - End -->

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
                name: 'index', path: '/admin/property', 
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
                    status: { mounted: false, loading: false },
                    // ----------------------------------------------------------
                    property: '',
                    selection: '',
                    publishing: {
                        options: '',
                        customer: ''
                    },
                    template: '',
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
                        // -----------------------------------------------------
                        // List company
                        // -----------------------------------------------------
                        company: {
                            homeMaker: '',
                            realEstate: ''
                        },
                        
                        // -----------------------------------------------------
                        // API endpoints
                        // -----------------------------------------------------
                        api: {
                            user: @json( route( 'api.company.users' )),
                            customer: @json( route( 'api.user.customers' )),                            
                        },
                        // -----------------------------------------------------
                        // -----------------------------------------------------
                        // ------------------------------------------------------
                        property_statuses: @json( $property_statuses ),
                        // ------------------------------------------------------
                        // Order options
                        // ------------------------------------------------------
                        // Reminder:
                        // Ordering based on relation requires Join query or Subquery ordering in Laravel
                        // Eager loading doesn't support ordering based on relation
                        // ------------------------------------------------------
                        // Issue: http://bit.ly/3hd7InN
                        // Solutions: http://bit.ly/3aFV4Mz
                        // ------------------------------------------------------
                        orders: [
                            { id: 'updated_at', label: '@lang('label.update_date_and_time')' },
                            { id: 'id', label: '@lang('label.property_id')' },
                            { id: 'location', label: '@lang('label.location')' },
                            { id: 'minimum_price', label: '@lang('label.selling_price')' },
                            { id: 'minimum_land_area', label: '@lang('label.land_area')' },
                            { id: 'property_status', label: '@lang('label.status')' },
                            { id: 'building_conditions_desc', label: '@lang('label.building_condition')' },
                            { id: 'photo', label: '@lang('label.photo')' },
                            { id: 'flyer', label: '@lang('label.flyer')' },
                        ],

                        status: [
                            { id: 1, name: 'pending', label: @json( $pending )},
                            { id: 2, name: 'published', label: @json( $published )},
                            { id: 3, name: 'limited', label: @json( $limited )},
                            { id: 4, name: 'unpublished', label: @json( $unpublished )},
                            { id: 5, name: 'notpublish', label: @json( $notpublish )}
                        ],
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
                // console.log( state );
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
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Set mounted state
                // --------------------------------------------------------------
                setMounted: function( state, mounted ){
                    if( io.isUndefined( mounted )) mounted = true;
                    Vue.set( state.status, 'mounted', !! mounted );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Toggle home-maker selection
                // --------------------------------------------------------------
                toggleHomeMaker: function( state ){
                    var homeMaker = io.get( state.selection, 'homeMaker' );
                    Vue.set( state.selection, 'homeMaker', !homeMaker );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Toggle real-estate selection
                // --------------------------------------------------------------
                toggleRealEstate: function( state ){
                    var realEstate = io.get( state.selection, 'realEstate' );
                    Vue.set( state.selection, 'realEstate', !realEstate );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Create / append new customer
                // --------------------------------------------------------------
                appendCustomer: function( state, type ){
                    if( 'realEstate' == type ){
                        var entry = io.clone( state.template.realEstate );
                        state.publishing.customer.realEstate.push( entry );
                    }
                    else if( 'homeMaker' == type ){
                        var entry = io.clone( state.template.homeMaker );
                        state.publishing.customer.homeMaker.push( entry );
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Remove property customer 
                // --------------------------------------------------------------
                removeCustomer: function( state, { type, index }){
                    if( 'realEstate' == type ) state.publishing.customer.realEstate.splice( index, 1 );
                    else if( 'homeMaker' == type ) state.publishing.customer.homeMaker.splice( index, 1 );
                },
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
                // Loading and mounted status
                // --------------------------------------------------------------
                isLoading: function(){ return this.$store.state.status.loading },
                isMounted: function(){ return this.$store.state.status.mounted },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Meta data of the paginated result
                // --------------------------------------------------------------
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Data of the paginated result
                // --------------------------------------------------------------
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Reference shortcuts
                // --------------------------------------------------------------
                preset: function(){ return this.$store.state.preset },
                property: function(){ return this.$store.state.property },
                customer: function(){ return this.$store.state.publishing.customer },
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
                        var url = @json( route( 'admin.property.filter' ));
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