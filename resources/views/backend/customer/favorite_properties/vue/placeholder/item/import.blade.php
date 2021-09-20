<script type="text/x-template" id="tablelike-placeholder-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0 glimmer">
        <div class="row mx-0">

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-150px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div> 
                    </div>
                </div>
            </div>
            <!-- Date column - End -->
            
            <!-- Property ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-90px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div> 
                    </div>
                </div>
            </div>            
            <!-- Property ID column - End -->

            <div class="col-12 d-xl-none"></div>
            
            <!-- Location column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div> 
                    </div>
                </div>
            </div>            
            <!-- Location column - End -->        
            
            <!-- Building column - Start -->
           <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div> 
                    </div>
                </div>
            </div>            
            <!-- Building column - End --> 

            <div class="col-12 d-xl-none"></div>

            <!-- Selling column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div> 
                    </div>
                </div>
            </div>            
            <!-- Selling column - End -->  

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="w-100 py-1 px-2 glimmer-bar"></div> 
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

