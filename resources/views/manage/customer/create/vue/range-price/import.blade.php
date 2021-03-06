<script type="text/x-template" id="range-price-tpl">
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
                                <div v-if="prepend" class="input-group-prepend">
                                    <span class="input-group-text fs-12">@{{ prepend }}</span>
                                </div>
                                <select class="form-control form-control-sm" :value="min" :id="name1" :required="required && !max"
                                v-bind:data-parsley-errors-container="'#er'+name1" @input="$emit( 'update:min', $event.target.value )" >
                                    <option value="0">δΈιη‘γ</option>
                                    <option v-for="option in consider_amount" :value="option.value">@{{ option.value | toMan | numeral('0,0.[00]') }}</option>
                                </select>
                                <div v-if="append" class="input-group-append">
                                    <span class="input-group-text fs-12">@{{ append }}</span>
                                </div>
                            </div>
                        </div>
        
                        <div class="px-1 col-12 col-sm-auto d-none d-sm-flex align-items-center">
                            <span class="fs-13">-</span>
                        </div>
        
                        <div class="px-1 col-12 col-sm mt-2 mt-sm-0">
                            <div class="input-group input-group-sm">
                                <div v-if="prepend" class="input-group-prepend">
                                    <span class="input-group-text fs-12">@{{ prepend }}</span>
                                </div>
                                <select class="form-control form-control-sm" :value="max" :id="name2" :required="required && !min"
                                v-bind:data-parsley-errors-container="'#er'+name2" v-bind:data-parsley-ge="'#'+name1" @input="$emit( 'update:max', $event.target.value )" >
                                    <option value="0">δΈιη‘γ</option>
                                    <option v-for="option in consider_amount" :value="option.value">@{{ option.value | toMan | numeral('0,0.[00]') }}</option>
                                </select>
                                <div v-if="append" class="input-group-append">
                                    <span class="input-group-text fs-12">@{{ append }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="px-2 col-lg-9">
                    <div class="row mx-n1">
                        <div class="px-1 col-12 col-sm">
                            <div :id="'er'+name1"></div>
                        </div>
        
                        <div class="px-1 col-12 col-sm-auto d-none d-sm-flex align-items-center">
                            <span class="fs-13"></span>
                        </div>
        
                        <div class="px-1 col-12 col-sm mt-2 mt-sm-0">
                            <div :id="'er'+name2"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        



    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'RangePrice', {
            // ------------------------------------------------------------------
            template: '#range-price-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                name1: { type: String },
                name2: { type: String },
                min: { required: true },
                max: { required: true },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' },
                prepend: { type: String, default: null },
                append: { type: String, default: null },
                negative: { type: Boolean, default: false }
            },
            // ------------------------------------------------------------------

            computed: {
                // --------------------------------------------------------------
                entry: function(){ return this.value },
                state: function(){ return this.$store.state },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Preset options
                // --------------------------------------------------------------
                consider_amount: function(){
                    return io.get( this.state, 'preset.consider_amount' ) || [];
                },
                // --------------------------------------------------------------

                
            }

        });
    }( jQuery, _, document, window ));
@endminify </script>
