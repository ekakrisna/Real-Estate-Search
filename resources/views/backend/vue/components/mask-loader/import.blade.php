<script type="text/x-template" id="content-mask-loader-tpl">
    <transition name="fade-in">
        <div v-if="loading" class="mask-loader">
            <div class="spinner bouncing-dots">
                <div class="bouncer bounce-1"></div>
                <div class="bouncer bounce-2"></div>
                <div class="bouncer bounce-3"></div>
            </div>
        </div>
    </transition>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'MaskLoader', {
            // ------------------------------------------------------------------
            template: '#content-mask-loader-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Properties
            // ------------------------------------------------------------------
            props: {
                loading: { type: Boolean, default: false }
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>