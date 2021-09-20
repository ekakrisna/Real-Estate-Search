<script type="text/x-template" id="tablelike-placeholder-item-tpl">
    <div class="tablelike-item border-top border-right mt-2 mt-lg-0 glimmer">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-150px">
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
            <div class="px-0 d-flex flex-column border-left ccol-12 col-md-6 col-lg-6 col-xl">
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
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl-60px">
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

            <!-- Company column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-150px">
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
            <!-- Company column - End -->

            <!-- Email column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl-150px">
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
            <!-- Email column - End -->

            <!-- Active status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-100px">
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
            <!-- Active status column - End -->

            <!-- Control column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl-80px">
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
            <!-- Control column - End -->
                        <!-- Control column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl">
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
            <!-- Control column - End -->
            <!-- Control column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-120px">
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
            <!-- Control column - End -->
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

