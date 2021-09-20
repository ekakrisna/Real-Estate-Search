<script type="text/x-template" id="frontend-button-action-tpl">
    <button :type="type" class="btn btn-primary-round btn-alternate"
        :disabled="isdisabled"
        :class="{ 'btn-black-border': isButtonSecondary, 'is-loading': value }">

        <span class="innerset">
            <span class="interface">@{{ label }}</span>
            <span class="interface">
                <i class="fal fa-spin" :class="[ iconTypeClass, iconSizeClass ]"></i>
            </span>
        </span>
        
    </button>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ButtonAction', {
            // ------------------------------------------------------------------
            template: '#frontend-button-action-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { default: false },
                type: { type: String, default: 'button' },
                label: { type: String, default: 'Button' },
                icon: { type: String, default: 'cog' },
                iconSize: { type: Number, default: 24 },
                context: { type: String, default: 'primary' },
                isdisabled: {default: false}
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Actual button style/context
                // --------------------------------------------------------------
                isButtonSecondary: function(){
                    var context = io.trim( io.toLower( this.context ));
                    return 'secondary' === context;
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Icon classes
                // --------------------------------------------------------------
                iconTypeClass: function(){ return 'fa-' +this.icon },
                iconSizeClass: function(){ return 'fs-' +this.iconSize },
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
