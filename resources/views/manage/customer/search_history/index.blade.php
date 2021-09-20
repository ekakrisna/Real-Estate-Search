@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('manage.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('manage.customer.detail', $id)}}">{{$customer_detail->name}} @lang('label.detail')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
    </ol>
@endsection

@section('content')

    <div class="content-header">
        <div class="container-fluid pb-2 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-6">
                    <p class="m-0 text-dark h1title position-absolute bottom-0" >
                        ■ @lang('label.customer_basic_information')
                        <span class="ml-2"> <i class="fas fa-sort-down" style="font-size: 30px"></i></span>
                    </p>
                </div>
                <div class="col-sm-6">
                    <a href="{{route('manage.customer.edit', $id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>
                </div> 
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- B8-1-1, B8-1-2, B8-1-3, B8-1-4 Section --}}
            <div class="row d-flex align-items-center pt-3 pb-3">
                <div class="col-sm-6">
                    <div class="row ">
                        <div class="col-sm-6">
                            @lang('label.name')
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" name="customer-flag" value="{{$id}}">
                            {{$customer_detail->name}} &nbsp
                            <button type="button" class="btn btn-sm btn-default" @click.prevent="flagHandle(customer)">
                                <i :class="[`${flag}`]"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row ">
                        <div class="col-sm-6">
                            @lang('label.person_charge')
                        </div>
                        <div class="col-sm-6">
                            {{$customer_detail->company_user->name}} ({{$customer_detail->company_user->company->company_name}})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- B8-2 New Table Like Section --}}
    <div class="content-header">
        <div class="container-fluid pb-2 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-12">
                   ■ @lang('label.customer_search_history')
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
                <!-- Result filters - Start -->
                <Filters></Filters>
                <!-- Result filters - End -->

                <div class="tablelike mt-4">

                    <!-- Table header - Start -->
                    <div class="tablelike-header border-top border-right d-none d-xl-block">
                        <div class="row mx-0">
                            <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.search_date_time')</div></div>
                            <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                            <div class="px-0 border-left bg-light col-xl-160px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
                            <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
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
                name: 'index', path: '/manage/customer/{{$id}}/search_history', 
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
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Order options
                        // Reminder:
                        // Ordering based on relation requires Join query in Laravel
                        // Eager loading doesn't support ordering based on relation
                        // ------------------------------------------------------
                        orders: [
                            { id: 'created_at', label: "@lang('label.search_date_time')" },
                            { id: 'location', label: "@lang('label.location')" },
                            { id: 'minimum_price', label: "@lang('label.selling_price')" },
                            { id: 'minimum_land_area', label: "@lang('label.land_area')" }
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
            data: function(){
                return {
                    cust : @json( $customer_detail )
                }
            },
            mounted: function(){
            },
            computed: {
                customer : function() {
                    return this.cust;
                },
                flag: function(){ 
                    if( this.customer.flag  == 1 ) {
                        return 'fas fa-flag text-dark';
                    } else {
                        return 'far fa-flag text-secondary';
                    }
                },

                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
            },
            methods: {
                flagHandle: function(customer){
                    var vm = this;
                    const url =  customer.url.manage_flag;
                    var request = axios.post( url);
                    request.then( function( response ){
                        customer.flag = response.data.result;
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, {
                            type: 'success'
                        });
                    });
                }
            },
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
                        var url = @json( route( 'manage.customer.search_history.filter', $id ));
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



