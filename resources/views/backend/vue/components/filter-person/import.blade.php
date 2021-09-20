<script type="text/x-template" id="tablelike-filter-person-incharge-tpl">
    <div class="row mt-2">
        <div class="col-3 d-flex align-items-center">@{{ config.label }}</div>
        <div class="col-9">
    
            <select class="form-control form-control-sm" :value="person" @input="inputPerson" :disabled="!company">
                <option value=""></option>
                <option v-for="option in options" :key="option.id" :value="option.id" :selected="person == option.id">@{{ option.name }}</option>
            </select>
    
        </div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'FilterPerson', {
            // ------------------------------------------------------------------
            template: '#tablelike-filter-person-incharge-tpl',
            props: [ 'value', 'source', 'company', 'label' ],
            // ------------------------------------------------------------------
            data: function(){ 
                return {
                    options: [],
                    defaults: {
                        label: '@lang('label.person_charge')'
                    }
                }
            },
            // ------------------------------------------------------------------
            computed: {
                person: function(){ return this.value },
                config: function(){ return io.defaults({}, this._props, this.defaults )},
            },
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                inputPerson: function( $event ){ 
                    this.$emit( 'input', $event.target.value ),
                    this.$emit( 'change', $event );
                }
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // Watch the company property
                // Request data when it is changed
                // --------------------------------------------------------------
                company: { immediate: true, handler: function( companyID ){
                    // ----------------------------------------------------------
                    var vm = this;
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Fetch all options
                    // ----------------------------------------------------------
                    if( this.source && companyID ){
                        // ------------------------------------------------------
                        // ------------------------------------------------------
                        var request = axios.post( this.source, { id: companyID });
                        // ------------------------------------------------------
                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var options = io.get( response, 'data' );
                            // --------------------------------------------------
                            if( 200 === status && options.length ){
                                vm.options.length = 0;
                                io.each( options, function( option ){
                                    vm.options.push( option );
                                });
                            }
                            // --------------------------------------------------
                        });
                        // ------------------------------------------------------
                    }
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Reset when company is empty
                    // ----------------------------------------------------------
                    else {
                        vm.options.length = 0;
                        vm.$emit( 'input', null ),
                        vm.$emit( 'change' );
                    }
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            }}
        });
    }( jQuery, _, document, window ));
@endminify </script>

