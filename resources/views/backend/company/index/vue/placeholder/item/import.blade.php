<script type="text/x-template" id="tablelike-placeholder-item-tpl">
    <div class="tablelike-item border-top border-right mt-2 mt-lg-0 glimmer">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-60px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="row mx-0 my-auto flex-nowrap flex-grow-1">
                        <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                            <div class="w-100 py-1 px-2 glimmer-bar"></div>
                        </div>
                        <div class="px-0 col col-xl-12 overflow-hidden">
                            <div class="w-100 py-1 px-2 glimmer-bar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-xl-none"></div>

            <!-- Company role column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="row mx-0 my-auto flex-nowrap flex-grow-1">
                        <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                            <div class="w-100 py-1 px-2 glimmer-bar"></div>
                        </div>
                        <div class="px-0 col col-xl-12 overflow-hidden">
                            <div class="w-100 py-1 px-2 glimmer-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company role column - End -->

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="row mx-0 my-auto flex-nowrap flex-grow-1">
                        <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                            <div class="w-100 py-1 px-2 glimmer-bar"></div>
                        </div>
                        <div class="px-0 col col-xl-12 overflow-hidden">
                            <div class="w-100 py-1 px-2 glimmer-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->

            <!-- Company status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Company status column - End -->

            <div class="col-12 d-xl-none"></div>

            <!-- Users count column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Users count column - End -->    
            
            <!-- Company edit button - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Company edit button - End -->  

            <!-- Company user button - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-110px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                </div>
            </div>
            <!-- Company user button - End -->

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

