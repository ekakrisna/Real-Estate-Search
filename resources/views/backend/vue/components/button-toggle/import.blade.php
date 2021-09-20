<script type="text/x-template" id="generic-button-toggle-tpl">
    <button type="button" class="btn btn-sm btn-default fs-14" @click="toggle">
        <i v-if="value" class="fas" :class="iconClass"></i>
        <i v-else class="fal" :class="iconClass"></i>
    </button>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ButtonToggle', {
            // ------------------------------------------------------------------
            template: '#generic-button-toggle-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                api: { type: String, default: null },
                data: { type: Object, default: null },
                icon: { type: String, default: 'flag' },
                result: { type: String, default: 'result' },
                method: { type: String, default: 'post' }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                iconClass: function(){ return 'fa-' +this.icon }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            methods: {
                toggle: function(){
                    // ----------------------------------------------------------
                    var vm = this;
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // If there is NO API endpoint
                    // ----------------------------------------------------------
                    if( !vm.api ){
                        vm.$emit( 'input', !vm.value );
                        return;
                    }
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    else {
                        var request = axios[vm.method]( vm.api, vm.data );
                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var success = 'success' == io.get( response.data, 'status' );                            
                            var result = io.get( response.data, vm.result );
                            // --------------------------------------------------
                            if( 200 === status && success ){
                                if( !io.isUndefined( result )) vm.$emit( 'input', result );
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
