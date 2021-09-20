@relativeInclude('property-publish.import')
@relativeInclude('button-append.import')

<script type="text/x-template" id="customer-property-publish-tpl">
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

            <!-- Start -->
            <div class="home-maker-customers">

                <!-- Empty placeholder - Start -->
                <empty-placeholder v-if="!value.propertyPublish.length"></empty-placeholder>
                <!-- Empty placeholder - End -->

                <!-- Start -->
                <property-publish v-for="( data, index ) in value.propertyPublish" :key="value.propertyPublish[index].id" v-model="value.propertyPublish[index]" 
                    :index="index" type="propertyPublish" @remove="removeData" @create="appendData('propertyPublish')">
                </property-publish>
                <!-- End -->
                
                <!-- Create button - Start -->
                <div class="row px-n2 justify-content-end mt-3">
                    <div class="px-2 col-auto">
                        <!-- Button append desktop - Start -->
                        <button-append class="btn-primary d-none d-md-block" @click="appendData('propertyPublish')"></button-append>
                        <!-- Button append desktop - End -->
                    </div>                    
                </div>
                <!-- Create button - End -->

                <!-- Create button when list is empty - Start -->
                <button-append v-if="!value.propertyPublish.length" class="btn-primary d-md-none" 
                    @click="appendData('propertyPublish')">
                </button-append>
                <!-- Create button when list is empty - End -->

            </div>            
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyArea', {
            // ------------------------------------------------------------------
            template: '#customer-property-publish-tpl',
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
