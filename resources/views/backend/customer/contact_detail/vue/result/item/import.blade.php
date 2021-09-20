<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-2 mt-lg-0">
        <div class="row mx-0">


            <!-- Email column - Start -->
            <div class="px-0 border-left col-lg-160px">
                <div class="border-top d-md-none d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">@lang('label.last_update')</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ data.ja.created_time }}</div>
                    </div>
                </div>
            </div>
            <!-- Email column - End -->

            <div class="px-0 border-left col-md-6 col-lg-160px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">@lang('label.update_type')</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ data.property_scraping_type.label }}</div>
                    </div>
                </div>
            </div>

            <!-- Active status column - Start -->
            <div class="px-0 border-left col-md-6 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">@lang('label.before_update')</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ data.before_update_text }}</div>
                    </div>
                </div>
            </div>
            <!-- Active status column - End -->

            <!-- Active status column - Start -->
            <div class="px-0 border-left col-md-6 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">@lang('label.after_update')</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ data.after_update_text }}</div>
                    </div>
                </div>
            </div>
            <!-- Active status column - End -->

        </div>
        <div class="border-bottom d-lg-none"></div>
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
                // Current user item
                // --------------------------------------------------------------
                data: function(){ return this.value },
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

