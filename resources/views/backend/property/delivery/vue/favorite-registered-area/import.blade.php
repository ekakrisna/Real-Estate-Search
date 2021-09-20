<script type="text/x-template" id="favorite-registered-area-tpl">
    <div class="row form-group border-bottom-0 pb-0">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@lang('label.delivery_target')</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row px-n2">

                <div class="px-2 col-6 col-lg-auto">
                    <div class="icheck-cyan">
                        <input type="checkbox" :value="id" :id="id" :name="name" @input="emitCondition" :required="required" />
                        <label :for="id" class="mr-5">@{{ label }}</label>
                    </div>
                </div>

            </div>
        </div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'FavoriteRegisteredArea', {
            // ------------------------------------------------------------------
            template: '#favorite-registered-area-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { required: true },
                name: { required: true },
                required: { type: Boolean, default: false },
                label: { type: String, required: true }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // This will trigger the v-model update in parent component
                // --------------------------------------------------------------
                emitCondition: function( $event ){
                    var stat = 0; // default false
                    if($event.target.checked == true){
                        stat = 1; // default true
                    }
                    // ----------------------------------------------------------
                    var condition = io.parseInt( stat );
                    // ----------------------------------------------------------
                    this.$emit( 'input', condition );
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
