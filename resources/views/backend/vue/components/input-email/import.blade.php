<script type="text/x-template" id="generic-input-email-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
            <span v-else class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    <input type="email" :value="value" class="form-control form-control-sm" :name="name" :id="id" :required="required ? true : false"
                        @input="$emit( 'input', $event.target.value )" />
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'InputEmail', {
            // ------------------------------------------------------------------
            template: '#generic-input-email-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { type: String, default: null },
                name: { type: String, default: null },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' }
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
