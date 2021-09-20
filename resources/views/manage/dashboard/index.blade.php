@extends('backend._base.content_tablelike')

@section('content')
<!-- customer log activity section - Start -->

<!-- customer log activity header - Start -->
<div class="row border-bottom py-2 mb-3">
    <div class="col-sm-12">
        <span class="m-0 text-dark h1title">
            ■ @lang('dashboard.most_recently_used_customers')
        </span>
        ( <a href="{{route('manage.customer.index')}}">@lang('label.view_user_list')</a>)
    </div>
</div>
<!-- customer log activity header - End -->

<!-- customer log activity Table - Start -->
<div class="tablelike">
    <!-- Table header - Start -->
    <div class="tablelike-header border-top border-right d-none d-xl-block">
        <div class="row mx-0">
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.active_date_time')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.name')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-60px"><div class="py-2 px-2">@lang('label.flag')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_staff')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.action_name')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-160px"><div class="py-2 px-2">@lang('label.favorite_property_number')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-160px"><div class="py-2 px-2">@lang('label.property_number_checked_recent_2_weeks')</div></div>
        </div>
    </div>
    <!-- Table header - End -->
    <div class="tablelike-content">
        <div class="tablelike-result">
            
            <div v-if="customerLogActivities.length == 0" class="text-center py-2 px-2 border">
                <span>対象のレコードはありません。</span>
            </div>
            
            <template v-else>
                <div v-for="customerLogActivity in customerLogActivities" :key="customerLogActivity.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">
                    <div class="row mx-0">
                        <!-- active_date_time column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg-300px col-xl-150px">
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.active_date_time')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.ja.access_time }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- active_date_time column - End -->

                        <!-- Name column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                            <div class="border-top d-lg-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        <a :href="customerLogActivity.customer.url.manage_view">@{{ customerLogActivity.customer.name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Name column - End -->

                        <div class="col-12 d-xl-none"></div>

                        <!-- Flag column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg-300px col-xl-60px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.flag')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                                        <div class="px-2 py-2">

                                            <!-- Flag toggle button - Start -->
                                            <button-toggle v-model="customerLogActivity.customer.flag" :api="customerLogActivity.customer.url.manage_flag" 
                                                @input="flagHandle( customerLogActivity )">
                                            </button-toggle>
                                            <!-- Flag toggle button - End -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Flag column - End -->
                        
                        <!-- In charge staff column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.in_charge_staff')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.customer.company_user.name }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge staff  column - End -->

                        <div class="col-12 d-xl-none"></div>

                        <!-- In charge staff column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.action_name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.action_type.label }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge staff  column - End -->

                        <div class="col-12 d-xl-none"></div>

                        <!-- Favorite property number column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg-300px col-xl-160px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.favorite_property_number')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.customer.favorite_properties.length }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Favorite property number  column - End -->

                        <!-- Property number checked recent 2 weeks column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-160px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.property_number_checked_recent_2_weeks')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.customer.properties_checked.length }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Property number checked recent 2 weeks column - End -->
                    </div>
                    <div class="border-bottom d-xl-none"></div>
                </div>
            </template>

            <div class="border-bottom d-none d-xl-block"></div>
        </div>
    </div>
</div>
<!-- customer log activity Table - End -->

<!-- customer log activity section - End -->

<!-- customer search history section - Start -->

<!-- customer search history header - Start -->
<div class="row border-bottom py-2 mb-3 mt-4">
    <div class="col-sm-12">
        <span class="m-0 text-dark h1title">
            ■ @lang('dashboard.recent_customer_search_history')
        </span>
        ( <a href="{{route('manage.customer_all_search_history')}}">@lang('label.view_search_history')</a> )
    </div>
</div>
<!-- customer search history header - End -->

<!-- customer search history table - Start -->
<div class="tablelike">
    <!-- Table header - Start -->
    <div class="tablelike-header border-top border-right d-none d-xl-block">
        <div class="row mx-0">
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.name')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-60px"><div class="py-2 px-2">@lang('label.flag')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_staff')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-160px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-160px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
        </div>
    </div>

    <div class="tablelike-content">
        <div class="tablelike-result">

            <div v-if="customerSearchHistories.length == 0" class="text-center py-2 px-2 border">
                <span>対象のレコードはありません。</span>
            </div>

            <template v-else>
                <div v-for="customerSearchHistory in customerSearchHistories" :key="customerSearchHistory.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">
                    <div class="row mx-0">

                        <!-- active_date_time column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg-300px col-xl-150px">
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.datetime')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerSearchHistory.ja.created_at }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- active_date_time column - End -->

                        <!-- Name column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                            <div class="border-top d-lg-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        <a :href="customerSearchHistory.customer.url.manage_view">@{{ customerSearchHistory.customer.name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Name column - End -->

                        <div class="col-12 d-xl-none"></div>

                        <!-- Flag column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg-300px col-xl-60px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.flag')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                                        <div class="px-2 py-2">

                                            <!-- Flag toggle button - Start -->
                                            <button-toggle v-model="customerSearchHistory.customer.flag" :api="customerSearchHistory.customer.url.manage_flag" 
                                                @input="flagHandle( customerSearchHistory )">
                                            </button-toggle>
                                            <!-- Flag toggle button - End -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Flag column - End -->

                        <!-- In charge staff column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.in_charge_staff')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerSearchHistory.customer.company_user.name }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge staff  column - End -->

                        <div class="col-12 d-xl-none"></div>

                        <!-- address column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.address')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerSearchHistory.location }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- address  column - End -->

                        <div class="col-12 d-xl-none"></div>

                        <!-- price column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg-300px col-xl-160px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.selling_price')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerSearchHistory.minimum_price && customerSearchHistory.maximum_price" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_price | toMan | numeral(0,0) }} ~
                                        @{{ customerSearchHistory.maximum_price | toMan | numeral(0,0) }}
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_price && customerSearchHistory.maximum_price == null" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_price | toMan | numeral(0,0) }} ~ @lang('label.no_upper_limit')
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_price == null && customerSearchHistory.maximum_price" class="py-2 px-2">
                                        @lang('label.no_lower_limit') ~ @{{ customerSearchHistory.maximum_price | toMan | numeral(0,0) }}
                                    </div>
                                    <div v-else class="py-2 px-2">
                                        @lang('label.none')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- price  column - End -->

                        <!-- land_area column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-160px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.land_area')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerSearchHistory.minimum_land_area && customerSearchHistory.maximum_land_area" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_land_area | toTsubo | numeral('0,0') }} ~
                                        @{{ customerSearchHistory.maximum_land_area | toTsubo | numeral('0,0') }}
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_land_area && customerSearchHistory.maximum_land_area == null" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_land_area | toTsubo | numeral('0,0') }} ~ @lang('label.no_upper_limit')
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_land_area == null && customerSearchHistory.maximum_land_area" class="py-2 px-2">
                                        @lang('label.no_lower_limit') ~ @{{ customerSearchHistory.maximum_land_area | toTsubo | numeral('0,0') }}
                                    </div>
                                    <div v-else class="py-2 px-2">
                                        @lang('label.none')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- land_area  column - End -->

                    </div>
                    <div class="border-bottom d-xl-none"></div>
                </div>
            </template>
            
            <div class="border-bottom d-none d-xl-block"></div>
        </div>
    </div>
</div>
<!-- customer search history table - End -->

<!-- customer search history section - End -->

@endsection

@push('vue-scripts')

@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue router
        // ----------------------------------------------------------------------
        router = {};
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vuex store - Centralized data
        // ----------------------------------------------------------------------
        store = {};
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue mixin 
        // ----------------------------------------------------------------------
        mixin = {
            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    customerLogActivities : @json($customer_log_activites),
                    customerSearchHistories : @json($customer_search_histories),
                }
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
            computed: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                // ------------------------------------------------------------------
                // function to change customer flag value
                // object parameter can be either customerLogActivity, customerContactUs,
                // or customerSearchHistory
                // ------------------------------------------------------------------
                flagHandle: function (object) {
                    // ------------------------------------------------------------------
                    // initialize variables
                    // ------------------------------------------------------------------
                    customer_flag = object.customer.flag;
                    customer_id = object.customer.id;
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // filter customer log activitites array 
                    // ------------------------------------------------------------------
                    var customerLogs = io.filter( this.customerLogActivities, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer_id === customerID;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // each data filtered, update it's flag property
                    // ------------------------------------------------------------------
                    io.each( customerLogs, function( history ){
                        history.customer.flag = customer_flag;
                    });
                    // ------------------------------------------------------------------
                    
                    // ------------------------------------------------------------------
                    // filter customer search history array 
                    // ------------------------------------------------------------------
                    var customerSearchs = io.filter( this.customerSearchHistories, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer_id === customerID;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // each data filtered, update it's flag property
                    // ------------------------------------------------------------------
                    io.each( customerSearchs, function( history ){
                        history.customer.flag = customer_flag;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                    // ------------------------------------------------------------------
                },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush