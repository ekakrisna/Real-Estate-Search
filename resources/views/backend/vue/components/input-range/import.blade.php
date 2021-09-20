<script type="text/x-template" id="generic-input-range-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
            <span v-else class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title" :class="filedColor">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">

                <div class="px-2 col-lg-9">
                    <div class="row mx-n1">
                        
                        <div class="px-1 col-12 col-sm">
                            <div class="input-group" :class="controlSize">
                                <div v-if="prepend" class="input-group-prepend">
                                    <span class="input-group-text fs-12">@{{ prepend }}</span>
                                </div>

                                <template>
                                    <currency-input :value="min" class="form-control" :currency="null"
                                        :required="required" :name="minName" :id="minName"
                                        :precision="precision" :allow-negative="negative" @input="onMinInput">
                                    </currency-input>
                                </template>

                                <div v-if="append" class="input-group-append">
                                    <span class="input-group-text fs-12">@{{ append }}</span>
                                </div>
                            </div>
                        </div>
        
                        <div class="px-1 col-12 col-sm-auto d-none d-sm-flex align-items-center">
                            <span class="fs-13">-</span>
                        </div>
        
                        <div class="px-1 col-12 col-sm mt-2 mt-sm-0">
                            <div class="input-group" :class="controlSize">
                                <div v-if="prepend" class="input-group-prepend">
                                    <span class="input-group-text fs-12">@{{ prepend }}</span>
                                </div>

                                <template v-if="validation">
                                    <currency-input :value="max" class="form-control" :currency="null" ref="maxInput"
                                        :required="required" :name="maxName" :id="maxName" data-parsley-trigger="keyup focusout"
                                        :precision="precision" :allow-negative="negative" @input="$emit( 'update:max', $event )"
                                        :data-parsley-errors-container="'#'+maxParsleyContainer" :data-parsley-greater-than="'#'+minName">
                                    </currency-input>
                                </template>

                                <template v-else>
                                    <currency-input :value="max" class="form-control" :currency="null"
                                        :required="required" :name="maxName" :id="maxName"
                                        :precision="precision" :allow-negative="negative" @input="$emit( 'update:max', $event )">
                                    </currency-input>
                                </template>

                                <div v-if="append" class="input-group-append">
                                    <span class="input-group-text fs-12">@{{ append }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="parsley-error-container mt-2" :id="maxParsleyContainer"></div>
                </div>

            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'InputRange', {
            // ------------------------------------------------------------------
            template: '#generic-input-range-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                min: { required: true },
                max: { required: true },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' },
                precision: { type: Object, default: { min: 0, max: 4 }},
                prepend: { type: String, default: null },
                append: { type: String, default: null },
                negative: { type: Boolean, default: false },
                size: { type: String, default: 'md' },
                names: { type: Array, default: [ 'input-range-min', 'input-range-max' ]},
                validation: { default: true },
                filedColor:{type:String , default:'text-black'}
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                controlSize: function(){
                    var size = io.toLower( io.trim( this.size ));
                    if( !size || 'md' == size ) return null;
                    return 'input-group-' +size;
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Input names
                // --------------------------------------------------------------
                minName: function(){ return io.get( this, 'names[0]' )},
                maxName: function(){ return io.get( this, 'names[1]' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Parsley error containers
                // --------------------------------------------------------------
                maxParsleyContainer: function(){ return 'parsley-error-' +this.maxName }
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            methods: {
                onMinInput: function(e){
                    this.$emit( 'update:min', e );
                    $( this.$refs.maxInput.$el ).trigger('input');
                }
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
