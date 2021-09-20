<script type="text/x-template" id="tablelike-filter-company-tpl">
    <div class="row mt-2">
        <div class="col-3 d-flex align-items-center">@{{ config.label }}</div>
        <div class="col-9">
    
            <select class="form-control form-control-sm" :value="company" @input="inputCompany" :disabled="!options.length">
                <option value=""></option>
                <option v-for="option in options" :key="option.id" :value="option.id" :selected="company == option.id">@{{ option.company_name }}</option>
            </select>
    
        </div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'FilterCompany', {
            // ------------------------------------------------------------------
            template: '#tablelike-filter-company-tpl',
            props: [ 'value', 'person', 'source', 'label' ],
            // ------------------------------------------------------------------
            data: function(){ 
                return {
                    options: [],
                    defaults: {
                        label: '@lang('label.corporate_charge')'
                    }
                }
            },
            // ------------------------------------------------------------------
            created: function(){
                if( this.source ){
                    // ----------------------------------------------------------
                    // Fetch all options
                    // ----------------------------------------------------------
                    var vm = this;
                    var request = axios.post( this.source );
                    // ----------------------------------------------------------
                    request.then( function( response ){
                        // ------------------------------------------------------
                        var status = io.get( response, 'status' );
                        var options = io.get( response, 'data' );
                        // ------------------------------------------------------
                        if( 200 === status && options.length ){
                            vm.options.length = 0;
                            io.each( options, function( option ){
                                vm.options.push( option );
                            })
                        }
                        // ------------------------------------------------------
                    });
                    // ----------------------------------------------------------
                }
            },
            // ------------------------------------------------------------------
            computed: {
                company: function(){ return this.value },
                config: function(){ return io.defaults({}, this._props, this.defaults )},
            },
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                inputCompany: function( $event ){ 
                    // ----------------------------------------------------------
                    this.$emit( 'input', $event.target.value ), // Trigger v-model update
                    this.$emit( 'update:person', null ); // Reset person in charge
                    // ----------------------------------------------------------
                    this.$emit( 'change', $event ); // Trigger parent on-change event
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            }
        });
    }( jQuery, _, document, window ));
@endminify </script>

