<script type="text/x-template" id="page-preloader-tpl">
    <transition name="preloader">
        <div v-if="loading" class="preloader d-flex justify-content-center" :class="{ 'preloader-fullscreen': fullscreen }">
            <div class="folding-cube">
                <div class="cube cube-1"></div>
                <div class="cube cube-2"></div>
                <div class="cube cube-4"></div>
                <div class="cube cube-3"></div>
            </div>
        </div>
    </transition>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PageLoader', {
            // ------------------------------------------------------------------
            template: '#page-preloader-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Properties
            // ------------------------------------------------------------------
            props: {
                loading: { type: Boolean, default: false },
                fullscreen: { type: Boolean, default: true }
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>