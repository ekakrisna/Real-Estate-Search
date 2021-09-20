<script type="text/x-template" id="tablelike-filter-order-tpl">
    <div class="row mt-2">
        <div class="col-3 d-flex align-items-center">@{{ config.label }}</div>
        <div class="col-9">
            <div class="row">
                <div class="px-2 col">

                    <select class="form-control form-control-sm" :value="order" @input="inputOrder">
                        <option value=""></option>
                        <option v-for="option in this.options" :value="option.id" :selected="order == option.id">@{{ option.label }}</option>
                    </select>

                </div>
                <div class="px-2 col">

                    <select class="form-control form-control-sm" :value="direction" @input="inputDirection" :disabled="!value">
                        <option value="asc" :selected="direction == 'asc'">@lang('label.ascending')</option>
                        <option value="desc" :selected="direction == 'desc'">@lang('label.descending')</option>
                    </select>

                </div>
            </div>
    
        </div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'FilterOrder', {
            // ------------------------------------------------------------------
            template: '#tablelike-filter-order-tpl',
            props: {
                value: String,
                options: Array,
                direction: String,
                label: String,
            },
            // ------------------------------------------------------------------
            data: function(){ 
                return {
                    defaults: {
                        label: '@lang('label.order')'
                    }
                }
            },
            // ------------------------------------------------------------------
            computed: {
                order: function(){ return this.value },
                config: function(){ return io.defaults({}, this._props, this.defaults )},
            },
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                inputOrder: function( $event ){ 
                    // ----------------------------------------------------------
                    var value = $event.target.value;
                    this.$emit( 'input', $event.target.value );
                    // ----------------------------------------------------------
                    if( !value ) this.$emit( 'update:direction', null ); // Reset order-direction
                    // ----------------------------------------------------------
                    this.$emit( 'change', $event ); // Trigger on-change event
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------
                inputDirection: function( $event ){ 
                    this.$emit( 'update:direction', $event.target.value );
                    this.$emit( 'change', $event );
                },
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>

