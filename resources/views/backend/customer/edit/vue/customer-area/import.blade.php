@relativeInclude('desired-area.import')
@relativeInclude('button-append.import')

<script type="text/x-template" id="customer-desired-area-tpl">
    <div class="row form-group align-items-start">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
            <span v-else class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-xl-9 col-content">

            <!-- Home maker customers / publishings - Start -->
            <div class="home-maker-customers">

                <!-- Empty placeholder - Start -->
                <empty-placeholder v-if="!value.desiredArea.length"></empty-placeholder>
                <!-- Empty placeholder - End -->

                <!-- Home Maker customer - Start -->
                <desired-area v-for="( area, index ) in value.desiredArea" v-bind:key="area.id" v-model="value.desiredArea[index]" 
                    :index="index" type="desiredArea" @remove="removeData" @create="appendData('desiredArea')">
                </desired-area>
                <!-- Home Maker customer - End -->
                
                <!-- Create customer button - Start -->
                <div class="row px-n2 justify-content-end mt-3">
                    <div class="px-2 col-auto">

                        <!-- Button append desktop - Start -->
                        <button-append class="btn-primary d-none d-md-block" @click="appendData('desiredArea')"></button-append>
                        <!-- Button append desktop - End -->

                    </div>
                    <div class="px-2 col-50px"></div>
                </div>
                <!-- Create customer button - End -->

                <!-- Create customer button when list is empty - Start -->
                <button-append v-if="!value.desiredArea.length" class="btn-primary d-md-none" 
                    @click="appendData('desiredArea')">
                </button-append>
                <!-- Create customer button when list is empty - End -->

            </div>
            <!-- Home maker customers / publishings - End -->

            

        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'CustomerArea', {
            // ------------------------------------------------------------------
            template: '#customer-desired-area-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { tyep: Array, required: true },
                required: { type: Boolean, default: false },
                label: { type: String, default: '@lang('label.desired_area')' }
            },
            // ------------------------------------------------------------------


            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Append new Data
                // --------------------------------------------------------------
                appendData: function( type ){
                    this.$store.commit( 'appendData', type );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Remove Data
                // --------------------------------------------------------------
                removeData: function( data ){
                    this.$store.commit( 'removeData', data );
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
