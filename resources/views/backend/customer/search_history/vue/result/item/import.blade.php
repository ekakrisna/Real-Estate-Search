<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-280px col-xl-150px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.search_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.ja.created_at }}</div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->

            <!-- Location column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.location }}</div>
                    </div>
                </div>
            </div>
            <!-- Location column - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- Price column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-280px col-xl-160px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.price')</div>
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
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-140px">
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
        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'ResultItem', {
            // ------------------------------------------------------------------
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {};
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Current history item
                // --------------------------------------------------------------
                history: function(){ return this.value },
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

