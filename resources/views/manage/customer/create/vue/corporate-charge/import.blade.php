<script type="text/x-template" id="customer-corporate-charge-tpl">
    <div class=" align-items-start">

        <div class="row form-group">
            <div class="col-md-3 col-lg-2 col-header">

                <!-- Required / Optional labels - Start -->
                <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
                <span v-else class="bg-success label-required">@lang('label.optional')</span>
                <!-- Required / Optional labels - End -->

                <!-- Field label - Start -->
                <strong class="field-title">@lang('label.person_charge')</strong>
                <!-- Field label - End -->

            </div>
            <div class="col-md-9 col-lg-10 col-content">
                <div class="row mx-n2 mt-2 mt-md-0">
                    <div class="px-2 col-lg-9">
                        <select class="form-control form-control-sm" v-model.number="user" name="company_users_id" :required="required">
                            <option value=""></option>
                            <option v-for="option in companyuser" :value="option.id">@{{ option.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>

                
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'CorporateCharge', {
            // ------------------------------------------------------------------
            template: '#customer-corporate-charge-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { tyep: Array, required: true },
                required: { type: Boolean, default: false }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Component reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    loading: { user: false },
                    users: []
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                entry: function(){ return this.value },
                state: function(){ return this.$store.state },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Preset companyuser options
                // --------------------------------------------------------------
                companyuser: function(){
                    return io.get( this.state, 'preset.companyuser' ) || [];
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // User model
                // --------------------------------------------------------------
                user: {
                    get: function(){  return io.get( this.entry, 'company_users_id' )},
                    set: function( value ){ return Vue.set( this.entry, 'company_users_id', value )}
                },
                // --------------------------------------------------------------

                
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Append new entry
                // --------------------------------------------------------------
                create: function(){ this.$emit( 'create' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Confirm removal
                // --------------------------------------------------------------
                remove: function(){
                    // ----------------------------------------------------------
                    var confirmed = true;
                    if( this.city ){
                        var confirmed = confirm( @json( __( 'label.CONFIRM_DELETE_MESSAGE' )));
                    }
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // If confirmed, emit remove event to the parent component
                    // Send the type ( "realEstate" or "homeMaker" ) and loop index data
                    // ----------------------------------------------------------
                    if( confirmed ) this.$emit( 'remove', {
                        index: this.index
                    });
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
