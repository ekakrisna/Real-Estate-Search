@extends('frontend._base.vueform')

@section('title', '過去に閲覧した物件' )
@section('description', '')

@section('form-content')
<div class="fav-section">
    <div class="container">

        <template v-if="!isLoading && !( histories && histories.length )">
            <div class="section-notif-no-data">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="content text-center">
                                <img src="{{ asset('frontend/assets/images/icons/bg_fav_history_nodata.png') }}" alt="img-no-data">
                                <span>過去に閲覧した物件はありません</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>

            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="content">
                        <div class="content-header">
                            <h2 class="title title-pages">
                                <img src="{{ asset('frontend/assets/images/icons/icon_history.png') }}" alt="icon_fav" class="icon-title">
                                <span class="text-title">過去に閲覧した物件</span>
                            </h2>
                        </div>
                        <div class="content-body content-icon-fav">
                            <div class="row">

                                <!-- Property card preloader - Start -->
                                <template v-if="isLoading">
                                    <div class="col-12 col-lg-6 mb-3 mb-md-4 d-flex flex-column" v-for="i in 2">
                                        <property-card-preloader></property-card-preloader>
                                    </div>
                                </template>
                                <!-- Property card preloader - End -->

                                <!-- Property card - Start -->
                                <template v-else>
                                    <div class="col-12 col-lg-6 mb-3 mb-md-4 d-flex flex-column" v-for="( history, historyIndex ) in histories" :key="history.id">
                                        <property-card v-if="history && history.property" :property="history.property" :log="history"></property-card>
                                    </div>
                                </template>
                                <!-- Property card - End -->

                            </div>

                            <!-- Pagination - Start -->
                            <div class="text-center mt-3">
                                <pagination v-model="pagination"></pagination>
                            </div>
                            <!-- Pagination - End -->

                        </div>
                    </div>
                </div>
            </div>

        </template>
        
    </div>
</div>
@endsection
@push('vue-scripts')

<!-- Frontend components - Start -->
@include('frontend.vue.pagination.import')

@include('frontend.vue.property-card.import')
@include('frontend.vue.property-card-preloader.import')
<!-- Frontend components - Start -->

<script> @minify    
(function( $, io, document, window, undefined ){        
    // ----------------------------------------------------------------------
    // Vue router
    // ----------------------------------------------------------------------
    router = {
        mode: 'history',
        routes: [{ 
            name: 'index', path: '/history', 
            component: { template: '<div/>' }
        }]
    };
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Vuex store
    // ----------------------------------------------------------------------
    store = {
        // ------------------------------------------------------------------
        // Reactive state
        // ------------------------------------------------------------------
        state: function(){
            var state = {
                // ----------------------------------------------------------
                status: { loading: false },     // Page status
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // APIs in this page
                // ----------------------------------------------------------
                // api: {
                //     toggleDesired: @json( route( 'frontend.property.location.desired' ))
                // },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Server data containers
                // ----------------------------------------------------------
                result: {},                         // Result container
                // ----------------------------------------------------------
            }; 
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return state;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Mutations
        // ------------------------------------------------------------------
        mutations: {
            // --------------------------------------------------------------
            // Set page loading status
            // --------------------------------------------------------------
            setLoading: function( state, loading ){
                if( io.isUndefined( loading )) loading = true;
                Vue.set( state.status, 'loading', loading );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Set main paginated result 
            // --------------------------------------------------------------
            setResult: function( state, result ){
                Vue.set( state, 'result', result );
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
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            state: function(){ return this.$store.state },
            isLoading: function(){ return io.get( this.state, 'status.loading' )},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Paginated histories
            // --------------------------------------------------------------
            pagination: function(){ return io.get( this.state, 'result' )},
            histories: function(){ return io.get( this.state, 'result.data' )},
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Watchers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Route watcher
            // This will run everytine the route changes
            // --------------------------------------------------------------
            $route: {
                immediate: true, handler: function( route ){
                    // ------------------------------------------------------
                    var vm = this, store = this.$store;
                    var url = @json( route( 'frontend.history.filter' ));
                    var query = io.get( route, 'query' );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Route redirection
                    // ------------------------------------------------------
                    var page = io.get( query, 'page' );
                    if( !page ){
                        var data = io.assign({}, query, { page: 1 });
                        vm.$router.push({ name: 'index', query: data }).catch( function(){});
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Perform data request
                    // ------------------------------------------------------
                    var request = axios.post( url, { filter: route.query });
                    store.commit( 'setLoading' ); // Set loading state
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // On success
                    // ------------------------------------------------------
                    request.then( function( response ){
                        // --------------------------------------------------
                        var status = io.get( response, 'status' );
                        var result = io.get( response, 'data.result' );
                        // --------------------------------------------------
                        if( 200 === status ){
                            if( result ){
                                // ------------------------------------------
                                // Find out if current page exceeds the last available page
                                // ------------------------------------------
                                var currentPage = io.get( result, 'current_page' );
                                var lastPage = io.get( result, 'last_page' );
                                // ------------------------------------------
                                // If it does, navigate back to the last available page
                                // ------------------------------------------
                                if( currentPage > lastPage ){
                                    var query = io.assign({}, vm.$route.query, { page: lastPage });
                                    vm.$router.push({ name: 'index', query: query }).catch( function(){});
                                }
                                // ------------------------------------------

                                // ------------------------------------------
                                store.commit( 'setResult', result ); // Commit the paginated data
                                // ------------------------------------------
                            }
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    request.finally( function(){ store.commit( 'setLoading', false )});
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