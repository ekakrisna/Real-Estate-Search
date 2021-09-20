<script type="text/x-template" id="generic-property-card-preloader">
    <!-- Clickable card cointainer - Start -->
    <div class="d-flex flex-column glimmer">
        <div class="glimmer-bar"></div>
        <div class="content-list-body flex-grow-1 d-flex flex-column">
            <div class="row row-base mx-0 flex-grow-1">
                <div class="px-0 col-5 col-sm-4 col-lg-5 d-flex flex-column">

                    <!-- Ratiobox wrapper - Start -->
                    <div class="ratiobox ratio--1-1">
                        <div class="ratiobox-innerset glimmer-bar"></div>
                    </div>
                    <!-- Ratiobox wrapper - End -->

                    <!-- Space filler - Start -->
                    <div class="bg-white flex-grow-1"></div>
                    <!-- Space filler - End -->

                </div>
                <div class="px-0 col-7 col-sm-8 col-lg-7 d-flex flex-column">
                    <div class="flex-grow-1 d-flex flex-column">

                        <!-- Glimmer bar - Start -->
                        <div class="glimmer-bar double"></div>
                        <!-- Glimmer bar - End -->

                        <!-- Space filler - Start -->
                        <div class="bg-white flex-grow-1"></div>
                        <!-- Space filler - End -->

                    </div>
                </div>
            </div>
        </div>
                                                
    </div>
    <!-- Clickable card cointainer - End -->
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyCardPreloader', {
            template: '#generic-property-card-preloader',
        });
    }( jQuery, _, document, window ));
@endminify </script>
