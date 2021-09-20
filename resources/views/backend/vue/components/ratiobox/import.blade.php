<script type="text/x-template" id="generic-ratiobox-container-tpl">
    <div class="ratiobox" :class="finalRatio" @click="$emit('click')">
        <div class="ratiobox-innerset">
            <slot></slot>
        </div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'Ratiobox', {
            // ------------------------------------------------------------------
            template: '#generic-ratiobox-container-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                finalRatio: function(){ return 'ratio--' +this.ratio }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                ratio: { type: String, default: '4-3' },
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
