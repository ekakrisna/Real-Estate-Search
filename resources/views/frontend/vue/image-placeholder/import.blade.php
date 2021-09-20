<script type="text/x-template" id="generic-empty-image-placeholder-tpl">
    <img :src="source" />
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ImagePlaceholder', {
            // ------------------------------------------------------------------
            template: '#generic-empty-image-placeholder-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                source: { type: String, default: @json( asset( config( 'const.image_placeholder' )))},
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
