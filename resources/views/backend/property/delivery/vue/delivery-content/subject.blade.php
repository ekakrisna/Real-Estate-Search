<script type="text/x-template" id="input-subject-tpl">
    <div class="row form-group border-bottom-0 pb-10 pt-0">
        <div class="col-md-9 col-lg-10 col-content">

            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    <input type="text" :value="this.value" class="form-control" :class="inputSize" 
                        @input="$emit( 'input', $event.target.value )" :name="name" :id="id" :required="required" />
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'InputSubject', {
            // ------------------------------------------------------------------
            template: '#input-subject-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { type: String, default: null },
                name: { type: String, default: null },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' },
                size: { type: String, default: 'md' }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Input size
                // --------------------------------------------------------------
                inputSize: function(){
                    var size = io.toLower( io.trim( this.size ));
                    if( !size || 'md' == size ) return null;
                    return 'form-control-' +size;
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
