<script type="text/x-template" id="generic-show-anchor-data-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Field label - Start -->
            <strong class="field-title">@{{label}}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    <a :href="link">@{{ value }}</a>
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ShowAnchorDataForm', {
            // ------------------------------------------------------------------
            template: '#generic-show-anchor-data-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { default: null },
                link : { default: null },
                label: { type: String, default: 'Label' },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {}
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>