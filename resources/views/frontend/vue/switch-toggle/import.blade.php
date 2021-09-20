<script type="text/x-template" id="frontend-switch-toggle-tpl">
    <label class="toggle-switch" :class="[ switchSize ]" :for="id">
        <input type="checkbox" :id="id" :checked="value" @input="toggle" :disabled="disabled" />
        <span class="toggle">
            <span class="switch"></span>
        </span>
    </label>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'SwitchToggle', {
            // ------------------------------------------------------------------
            template: '#frontend-switch-toggle-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { type: String, default: 'switch-toggle' },
                size: { type: String, default: null },
                api: { type: String, default: null },
                method: { type: String, default: 'post' },
                result: { type: String, default: 'result' },
                data: { default: null },
                notification: { type: Boolean, default: true },
                disabled: { default: false },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Cleaned switch size
                // --------------------------------------------------------------
                cleanSize: function(){ return this.size ? io.trim( io.camelCase( this.size )) : null },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Actual switch size class
                // --------------------------------------------------------------
                switchSize: function(){ return this.size ? 'toggle-switch-' +this.cleanSize : null },
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            methods: {
                toggle: function( event ){
                    // ----------------------------------------------------------
                    var vm = this;
                    var checked = io.get( event, 'target.checked' );
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Without an asynchronous request
                    // ----------------------------------------------------------
                    if( !vm.api ){
                        // ------------------------------------------------------
                        vm.$emit( 'input', result );    // Emit changes to parent
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Show notification
                        // ------------------------------------------------------
                        if( vm.notification ){
                            var message = @json( __( 'label.SUCCESS_UPDATE_MESSAGE' ));
                            vm.$toasted.show( message );
                        }
                        // ------------------------------------------------------
                    }
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // With request
                    // ----------------------------------------------------------
                    else {
                        var request = axios[vm.method]( this.api, vm.data );
                        request.then( function( response ){
                            // --------------------------------------------------
                            var code = io.get( response, 'status' );
                            var status = io.get( response, 'data.status' );
                            var result = io.get( response, `data.${vm.result}` );
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Response success
                            // --------------------------------------------------
                            if( 200 === code && 'success' === status ){
                                // ----------------------------------------------
                                vm.$emit( 'input', result );    // Emit changes to parent
                                // ----------------------------------------------

                                // ----------------------------------------------
                                // Show notification
                                // ----------------------------------------------
                                if( vm.notification ){
                                    var message = @json( __( 'label.SUCCESS_UPDATE_MESSAGE' ));
                                    vm.$toasted.show( message );
                                }
                            }
                            // --------------------------------------------------
                        });
                    }
                    // ----------------------------------------------------------
                }
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
