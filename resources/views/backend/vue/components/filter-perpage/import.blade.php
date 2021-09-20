<script type="text/x-template" id="tablelike-filter-perpage-tpl">
    <div class="row mt-2">
        <div class="col-3 d-flex align-items-center">@{{ config.label }}</div>
        <div class="col-9">
    
            <select class="form-control form-control-sm" :value="perpage" @input="inputPerpage">
                <option v-for="option in options" :key="option" :value="option" :selected="perpage == option">@{{ option }}</option>
            </select>
    
        </div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'FilterPerpage', {
            // ------------------------------------------------------------------
            template: '#tablelike-filter-perpage-tpl',
            props: [ 'value', 'label' ],
            // ------------------------------------------------------------------
            data: function(){ 
                return {
                    options: [ 1, 2, 5, 10, 20, 50 ],
                    defaults: {
                        label: '@lang('label.perpage')'
                    }
                }
            },
            // ------------------------------------------------------------------
            computed: {
                perpage: function(){ return io.parseInt( this.value )},
                config: function(){ return io.defaults({}, this._props, this.defaults )},
            },
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                inputPerpage: function( $event ){ 
                    this.$emit( 'input', io.parseInt( $event.target.value )),
                    this.$emit( 'change', $event );
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>

