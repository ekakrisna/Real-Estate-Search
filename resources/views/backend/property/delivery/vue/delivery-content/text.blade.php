<script type="text/x-template" id="generic-input-textarea-tpl">
    <div class="row form-group  border-bottom-0 pt-0">
        <div class="col-md-9 col-lg-10 col-content">
            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    <textarea :value="value" :rows="rows" class="form-control form-control-sm" :name="name" :id="id"
                        @input="$emit( 'input', $event.target.value )" :required="required">
                    </textarea>
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'InputTextarea', {
            // ------------------------------------------------------------------
            template: '#generic-input-textarea-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { type: String, default: null },
                name: { type: String, default: null },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' },
                rows: { default: 3 }
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
