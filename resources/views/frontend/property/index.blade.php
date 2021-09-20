@extends('frontend._base.vueform')

@section('title', $title)
@section('description', '')

@section('form-content')
<div class="fav-section">
    <div class="container py-5">
        
        <!-- Content - Start -->
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    <div class="content-header mb-4 mb-lg-3" v-if="isLocationValid">
                        <div class="row">
                            <div class="col-12 col-md d-flex align-items-center mb-3 mb-md-0">

                                <!-- Location label - Start -->
                                <h2 class="title title-pages mb-0">
                                    <span class="text-title">@{{ prefecture.name + city.name + town.name }}</span>
                                </h2>
                                <!-- Location label - End -->

                            </div>

                            @if (Auth::guard('user')->user())
                            <div class="col-12 col-md-auto d-flex justify-content-end">
                                <div class="box-fav-area w-auto flex-grow-1">
                                    <div class="row mx-n2 flex-grow-1">
                                        <div class="px-2 col d-flex align-items-center">

                                            <div class="label-text">
                                                <img src="{{ asset('frontend/assets/images/icons/icon_fav_area.png') }}" alt="icon-flag">
                                                <span>お気に入りエリア</span>
                                            </div>

                                        </div>
                                        <div class="px-2 col-auto disabled-cursor-switch">

                                            <!-- Add as Desired Area button - Start -->
                                            <switch-toggle v-model="$store.state.toggleDisabled ? 'true' : state.location.desired" :api="state.api.toggleDesired" 
                                                :data="{ location: state.location.query }" id="add-desired-area" size="lg" :disabled="$store.state.toggleDisabled">
                                            </switch-toggle>
                                            <!-- Add as Desired Area button - End -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <template v-if="$store.state.toggleDisabled">
                                <div class="col-12 my-3 d-block d-md-flex justify-content-end">
                                    <p class="text-left text-danger" style="font-size: 0.82rem">※市区町村で登録されています。<br>変更する際は、
                                    <a href="{{ route('frontend.search_settings') }}" class="ext-primary" style="text-decoration: underline;">My設定</a>から変更をお願いします。</p>
                                </div>
                            </template>
                            @endif

                        </div>
                    </div>
                    <div class="content-body content-icon-fav">

                        <!-- Empty properties placeholder - Start -->
                        <div v-if="!isLoading && !properties.length" class="section-notif-no-data">
                            <div class="container">
                                <div class="content text-center">
                                    <img src="{{ asset('frontend/assets/images/icons/bg_error.png') }}" alt="img-no-data">
                                    <span>該当する物件はありません。</span>
                                </div>
                            </div>
                        </div>
                        <!-- Empty properties placeholder - End -->

                        <template v-else>
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
                                    <div class="col-12 col-lg-6 mb-3 mb-md-4 d-flex flex-column" v-for="( property, propertyIndex ) in properties" :key="property.id">
                                        <property-card :property="property"></property-card>
                                    </div>
                                </template>
                                <!-- Property card - End -->
    
                            </div>

                            <!-- Pagination - Start -->
                            <div class="text-center mt-3">
                                <pagination v-model="pagination"></pagination>
                            </div>
                            <!-- Pagination - End -->

                        </template>

                    </div>
                </div>
            </div>
        </div>
        <!-- Content - End -->

    </div>

    <!-- Dummy router view - Start -->
    <router-view></router-view>
    <!-- Dummy router view - End -->
</div>

@endsection
@push('vue-scripts')

@include('frontend.vue.switch-toggle.import')
@include('frontend.vue.pagination.import')

@include('frontend.vue.property-card.import')
@include('frontend.vue.property-card-preloader.import')

<script> @minify 
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------
    // Vue router
    // ----------------------------------------------------------------------
    router = {
        mode: 'history',
        routes: [{ 
            name: 'index', path: '/property', 
            component: { template: '<div/>' }
        }]
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Vuex store
    // ----------------------------------------------------------------------
    store = {
        // ------------------------------------------------------------------
        // Reactive states
        // ------------------------------------------------------------------
        state: function(){
            // --------------------------------------------------------------
            var state = {
                // ----------------------------------------------------------
                status: { loading: false },     // Page status
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // APIs in this page
                // ----------------------------------------------------------
                api: {
                    toggleDesired: @json( route( 'frontend.property.location.desired'))
                },
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Server data containers
                // ----------------------------------------------------------
                result: {},                         // Result container
                location: @json( $location ),       // Initial location properties data
                toggleDisabled: @json( $location->toggleDisabled),
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

            // --------------------------------------------------------------
            // Set main location properties
            // --------------------------------------------------------------
            setLocation: function( state, location ){
                Vue.set( state, 'location', location );
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
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            state: function(){ return this.$store.state },
            isLoading: function(){ return io.get( this.state, 'status.loading' )},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Location information
            // --------------------------------------------------------------
            location: function(){ return io.get( this.state, 'location' )},
            desired: function(){ return io.get( this.location, 'desired' )},
            // --------------------------------------------------------------
            town: function(){ return io.get( this.location, 'town' )},
            city: function(){ return io.get( this.town, 'city' )},
            prefecture: function(){ return io.get( this.city, 'prefecture' )},
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Location validity where all town, city, prefecture data are present
            // --------------------------------------------------------------
            isLocationValid: function(){ return this.town && this.city && this.prefecture },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Paginated properties
            // --------------------------------------------------------------
            pagination: function(){ return io.get( this.state, 'result' )},
            properties: function(){ return io.get( this.state, 'result.data' ) || []},
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
                    var url = @json( route( 'frontend.property.list.filter'));
                    var query = io.get( route, 'query' );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Route redirection
                    // ------------------------------------------------------
                    var page = io.get( query, ['page']);
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
                    // -----------------------------------------------------

                    // ------------------------------------------------------
                    // On success
                    // ------------------------------------------------------
                    request.then( function( response ){
                        // --------------------------------------------------
                        var status = io.get( response, 'status' );
                        var result = io.get( response, 'data.result' );
                        var location = io.get( response, 'data.location' );
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

                            // ----------------------------------------------
                            // If location data is returned, commit them
                            // ----------------------------------------------
                            if( location ) store.commit( 'setLocation', location );
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
