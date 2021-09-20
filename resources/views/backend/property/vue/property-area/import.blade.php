<script type="text/x-template" id="property-land-area-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
            <span v-else class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">

                <div class="px-2 col-lg-9">
                    <div class="row mx-n1">
                        
                        <div class="px-1 col-12 col-sm">
                            <div class="input-group input-group-sm">
                                <template>
                                    <currency-input v-model.number="value.minimum_land_area" class="form-control form-control-sm" :currency="null" 
                                        :precision="{min:0, max:4}" :allow-negative="false">
                                    </currency-input>
                                </template>
                                <div class="input-group-append">
                                    <span class="input-group-text fs-12">坪</span>
                                </div>
                            </div>
                        </div>
        
                        <div class="px-1 col-12 col-sm-auto d-none d-sm-flex align-items-center">
                            <span class="fs-13">-</span>
                        </div>
        
                        <div class="px-1 col-12 col-sm mt-2 mt-sm-0">
                            <div class="input-group input-group-sm">
                                <template>
                                    <currency-input v-model.number="value.maximum_land_area" class="form-control form-control-sm" :currency="null" 
                                        :precision="{min:0, max:4}" :allow-negative="false">
                                    </currency-input>
                                </template>
                                <div class="input-group-append">
                                    <span class="input-group-text fs-12">坪</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyArea', {
            // ------------------------------------------------------------------
            template: '#property-land-area-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                required: { type: Boolean, default: false },
                label: { type: String, default: '@lang('property.create.label.area')' },
            },
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
