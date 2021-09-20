<script type="text/x-template" id="tablelike-placeholder-item-tpl">
    <div class="tablelike-item border-top border-right mt-2 mt-lg-0 glimmer">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 border-left col-md-12 col-lg-160px">
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <!-- ID column - Start -->
            <div class="px-0 border-left col-md-12 col-lg-160px">
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->


            <!-- Name column - Start -->
            <div class="px-0 border-left col-md-6 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Name column - End -->

            <!-- User role column - Start -->
            <div class="px-0 border-left col-md-6 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- User role column - End -->

        </div>
        <div class="border-bottom d-lg-none"></div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Result placeholder
        // ----------------------------------------------------------------------
        Vue.component( 'PlaceholderItem', {
            // ------------------------------------------------------------------
            props: [ 'value', 'index' ],
            template: '#tablelike-placeholder-item-tpl',
            // ------------------------------------------------------------------
            data: function(){ return {}},
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

