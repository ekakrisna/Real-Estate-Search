<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.search_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.ja.created_at }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.name')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.customer.name }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-60px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.flag')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden text-lg-center text-xl-center">
                        <div class="py-2 px-2">

                            <!-- Flag toggle button - Start -->
                            <button-toggle v-model="history.customer.flag" :api="customer.url.manage_flag" 
                                @input="updateHistoryCustomer( history.customer )">
                            </button-toggle>
                            <!-- Flag toggle button - End -->

                        </div>
                    </div>
                </div>
            </div>


            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->


            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.person_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.customer.company_user.name }}</div>
                    </div>
                </div>
            </div>


            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="history.location" class="py-2 px-2">
                            <span>@{{ history.location }}</span>
                        </div>
                        <div v-else class="py-2 px-2">なし</div>
                    </div>
                </div>
            </div>

            <!-- Price column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.selling_price')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="history.minimum_price && history.maximum_price" class="py-2 px-2">
                            <span>@{{ history.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                            <span>~</span>
                            <span>@{{ history.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                        </div>

                        <div v-else-if="history.minimum_price && !history.maximum_price" class="py-2 px-2">
                            <span>@{{ history.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                            <span>~</span>
                            <span> @lang('label.no_upper_limit') </span>
                        </div>

                        <div v-else-if="!history.minimum_price && history.maximum_price" class="py-2 px-2">
                            <span> @lang('label.no_lower_limit') </span>
                            <span>~</span>
                            <span>@{{ history.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                        </div>

                        <div v-else class="py-2 px-2">
                            <span>なし</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Price column - End -->

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.land_area')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="history.minimum_land_area && history.maximum_land_area" class="py-2 px-2">
                            <span>@{{ history.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                            <span>~</span>
                            <span>@{{ history.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                        </div>

                        <div v-else-if="history.minimum_land_area && !history.maximum_land_area" class="py-2 px-2">
                            <span>@{{ history.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                            <span>~</span>
                            <span> @lang('label.no_upper_limit') </span>
                        </div>

                        <div v-else-if="!history.minimum_land_area && history.maximum_land_area" class="py-2 px-2">
                            <span> @lang('label.no_lower_limit') </span>
                            <span>~</span>
                            <span>@{{ history.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                        </div>

                        <div v-else class="py-2 px-2">
                            <span>なし</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End -->

        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ResultItem', {
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            data: function(){
                var data = {
                    customer_id: '',
                };
                return data;
            },
            computed: {
                history: function(){ return this.value },          
                customer: function(){return this.history.customer },
                flag: function(){ return this.history.customer.flag },
                
                resultData: function(){ return io.get( this.$store.state, 'result.data' )}
            },
            methods: {
                // ----------------------------------------------------------
                // Update flag of the same customer from other histories
                // ----------------------------------------------------------
                updateHistoryCustomer: function( customer ){
                    // ------------------------------------------------------
                    // Find histories of the same customer
                    // ------------------------------------------------------
                    var customerHistories = io.filter( this.resultData, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer.id === customerID;
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update all the customer flags
                    // ------------------------------------------------------
                    io.each( customerHistories, function( history ){
                        history.customer.flag = customer.flag;
                    });
                    // ------------------------------------------------------
                },
                // ----------------------------------------------------------
            },
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

