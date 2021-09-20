<script type="text/x-template" id="checkbox-tpl">
    
    <div class="row px-n2">

        <div class="px-2 col-lg-auto">
            <div class="icheck-cyan">
                <input type="checkbox" :value="id" :id="id" :name="name" @input="emitCondition" :required="required" />
                <label :for="id" class="mr-5">@{{label}}</label>
            </div>
        </div>

    </div>
        
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'Checkbox', {
            // ------------------------------------------------------------------
            template: '#checkbox-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { required: true },
                name: { required: true },
                required: { type: Boolean, default: false },
                label: {required: true}
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
