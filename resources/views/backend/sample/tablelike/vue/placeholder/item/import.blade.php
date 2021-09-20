<script type="text/x-template" id="tablelike-placeholder-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0 glimmer">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-40px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <!-- Name column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Name column - End -->

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-150px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- User role column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- User role column - End -->

            <!-- Company column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Company column - End -->

            <!-- Email column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-220px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Email column - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- Active status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Active status column - End -->

            <!-- Control column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Control column - End -->

        </div>
        <div class="border-bottom d-xl-none"></div>
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

