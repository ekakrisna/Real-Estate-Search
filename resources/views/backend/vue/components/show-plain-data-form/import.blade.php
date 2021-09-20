<script type="text/x-template" id="generic-show-plain-data-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Field label - Start -->
            <strong class="field-title">@{{label}}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    @{{value}} @{{append}}
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ShowPlainDataForm', {
            // ------------------------------------------------------------------
            template: '#generic-show-plain-data-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: {default: null},
                label: { type: String, default: 'Label' },
                append: { type: String, default: null },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {}
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>