<script type="text/x-template" id="property-building-condition-tpl">
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
            <div class="row px-n2" v-for="name in [ 'property-building-condition' ]">

                <div class="px-2 col-6 col-lg-auto" v-for="( option, index ) in options" :key="option.id">
                    <div class="icheck-cyan" v-for="id in [ name+ '-' +( index +1 ) ]">
                        <input type="radio" :checked="option.id == checkedValue" :value="option.id" :id="id" :name="name" @input="emitCondition" :required="required" />
                        <label :for="id" class="mr-5">@{{ option.label }}</label>
                    </div>
                </div>

            </div>
            <div v-if="true === value" class="row mx-n2 mt-2">
                <div class="px-2 col-lg-9">
                    <input type="text" v-model="descModel" class="form-control" />
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyCondition', {
            // ------------------------------------------------------------------
            template: '#property-building-condition-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                desc: { required: true },
                required: { type: Boolean, default: false },
                label: { type: String, default: '@lang('property.create.label.condition')' },
                options: { type: Array, required: true }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Condition description model
                // --------------------------------------------------------------
                descModel: {
                    get: function(){ return this.desc },
                    set: function( value ){ this.$emit( 'update:desc', value )}
                },
                checkedValue: function() { return this.value ? 1 : 2 },
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // This will trigger the v-model update in parent component
                // --------------------------------------------------------------
                emitCondition: function( $event ){
                    // ----------------------------------------------------------
                    var condition = io.parseInt( $event.target.value );
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
