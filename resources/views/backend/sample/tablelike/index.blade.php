@extends('backend._base.content_tablelike')

@section('content')

    <!-- Result filters - Start -->
    <Filters></Filters>
    <!-- Result filters - End -->

    <hr class="my-4" />

    <div class="mb-3">
        <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
    </div>

    <div class="tablelike">

        <!-- Table header - Start -->
        <div class="tablelike-header border-top border-right d-none d-xl-block">
            <div class="row mx-0">
                <div class="px-0 border-left bg-light col-xl-40px"><div class="py-2 px-2 text-center">ID</div></div>
                <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">Name</div></div>
                <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">Date</div></div>
                <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">User Role</div></div>
                <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">Company</div></div>
                <div class="px-0 border-left bg-light col-xl-220px"><div class="py-2 px-2">Email</div></div>
                <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">Active Status</div></div>
                <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">Option</div></div>
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
                name: 'index', path: '/sample/tablelike', 
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
                        companies: @json( $company ),
                        roles: @json( $roles ),
                        // ------------------------------------------------------

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
                            { id: 'id', label: 'User ID' },
                            { id: 'name', label: 'User Name' },
                            { id: 'email', label: 'User Email' },
                            { id: 'status', label: 'Active Status' },
                            { id: 'role', label: 'User Role' },
                            { id: 'company', label: 'Company Name' }
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
                        var url = @json( route( 'sample.tablelike.filter' ));
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