@relativeInclude('property-customer.import')
@relativeInclude('button-append.import')

<script type="text/x-template" id="property-status-limited-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">
             <!-- Required / Optional labels - Start -->
             <span class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@lang('label.delivery_target')</strong>
            <!-- Field label - End -->
        </div>
        <div class="col-md-9 col-lg-10 col-xl-9 col-content">
            <div class="row px-n2">

                <div class="px-2 col-lg-auto">
                    <div class="icheck-cyan">
                        <input type="checkbox" v-model="favorite_registered_area" id="favorite_registered_area" name="favorite_registered_area"
                        :required="!homeMaker && !realEstate" data-parsley-required-message="配信対象は少なくとも一つは選択してください。" data-parsley-errors-container="#er-deliveryTarget" />
                        <label for="favorite_registered_area" class="mr-5">@lang('label.delivery_favorite')</label>
                    </div>
                </div>

            </div>

            <!-- Home maker customers / publishings - Start -->
            <div class="home-maker-customers">

                <icheck v-model="homeMaker" name="home-maker-customers" label="@lang('property.create.label.customer.homeMaker')"
                :required="favorite_registered_area && !realEstate" data-parsley-required-message="配信対象は少なくとも一つは選択してください。" data-parsley-errors-container="#er-deliveryTarget">
                </icheck>

                <div v-show="homeMaker">

                    <!-- Empty placeholder - Start -->
                    <empty-placeholder v-if="!value.homeMaker.length"></empty-placeholder>
                    <!-- Empty placeholder - End -->

                    <!-- Home Maker customer - Start -->
                    <property-customer v-for="( customer, index ) in value.homeMaker" v-model="value.homeMaker[index]" 
                        :index="index" type="homeMaker" :required="homeMaker" :count="value.homeMaker.length"  @remove="removeCustomer" @create="appendCustomer('homeMaker')">
                    </property-customer>
                    <!-- Home Maker customer - End -->
                    
                    <!-- Create customer button - Start -->
                    <div class="row px-n2 justify-content-end mt-3">
                        <div class="px-2 col-auto">

                            <!-- Button append desktop - Start -->
                            <button-append class="btn-info d-none d-md-block" @click="appendCustomer('homeMaker')"></button-append>
                            <!-- Button append desktop - End -->

                        </div>
                        <div class="px-2 col-50px"></div>
                    </div>
                    <!-- Create customer button - End -->

                    <!-- Create customer button when list is empty - Start -->
                    <button-append v-if="!value.homeMaker.length" class="btn-info d-md-none" 
                        @click ="appendCustomer('homeMaker')">
                    </button-append>
                    <!-- Create customer button when list is empty - End -->

                </div>
            </div>
            <!-- Home maker customers / publishings - End -->

            <!-- Real estate customers / publishings - Start -->
            <div class="real-estate-customers mt-3">

                <icheck v-model="realEstate" name="real-estate-customers" label="@lang('property.create.label.customer.realEstate')"
                :required="favorite_registered_area && !homeMaker" data-parsley-required-message="配信対象は少なくとも一つは選択してください。" data-parsley-errors-container="#er-deliveryTarget">
                </icheck>

                <div v-show="realEstate">

                    <!-- Empty placeholder - Start -->
                    <empty-placeholder v-if="!value.realEstate.length"></empty-placeholder>
                    <!-- Empty placeholder - End -->
                    
                    <!-- Real Estate customer - Start -->
                    <property-customer v-for="( customer, index ) in value.realEstate" v-model="value.realEstate[index]"
                        :index="index" type="realEstate" :required="realEstate" :count="value.realEstate.length" @remove="removeCustomer" @create="appendCustomer('realEstate')">
                    </property-customer>
                    <!-- Real Estate customer - End -->

                    <!-- Create customer button - Start -->
                    <div class="row px-n2 justify-content-end mt-3">
                        <div class="px-2 col-auto">

                            <!-- Button append desktop - Start -->
                            <button-append class="btn-info d-none d-md-block" @click="appendCustomer('realEstate')"></button-append>
                            <!-- Button append desktop - End -->

                        </div>
                        <div class="px-2 col-50px"></div>
                    </div>
                    <!-- Create customer button - End -->

                    <!-- Create customer button when list is empty - Start -->
                    <button-append v-if="!value.realEstate.length" class="btn-info d-md-none" 
                        @click="appendCustomer('realEstate')">
                    </button-append>
                    <!-- Create customer button when list is empty - End -->

                </div>
            </div>
            <!-- Real estate customers / publishings - Start -->

            <div class="row px-n2">
                <div class="px-2 col-6 col-lg-auto">
                    <div id="er-deliveryTarget"></div>
                </div>
            </div>

        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyLimited', {
            // ------------------------------------------------------------------
            template: '#property-status-limited-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { tyep: Array, required: true },
                required: { type: Boolean, default: false },
                label: { type: String, default: '@lang('property.create.label.disclosure')' }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                favorite_registered_area: {
                    get: function(){ return io.get( this.propertyDelivery, 'favorite_registered_area' )},
                    set: function(val){ 
                        var stat = null; // default false
                        if(val == true){
                            stat = 1; // default true
                        }
                        // ----------------------------------------------------------
                        var condition = io.parseInt( stat );
                        // ----------------------------------------------------------
                        this.$store.commit( 'toggleFavoriteSelection', condition );
                        // ----------------------------------------------------------}
                    }
                },
                selection: function(){ return io.get( this.$store.state, 'selection' )},
                homeMaker: {
                    get: function(){ return io.get( this.selection, 'homeMaker' )},
                    set: function(){ this.$store.commit( 'toggleHomeMaker' )}
                },
                realEstate: {
                    get: function(){ return io.get( this.selection, 'realEstate' )},
                    set: function(){ this.$store.commit( 'toggleRealEstate' )}
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Append new customer
                // --------------------------------------------------------------
                appendCustomer: function( type ){
                    this.$store.commit( 'appendCustomer', type );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Remove property customer
                // --------------------------------------------------------------
                removeCustomer: function( data ){
                    this.$store.commit( 'removeCustomer', data );
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
