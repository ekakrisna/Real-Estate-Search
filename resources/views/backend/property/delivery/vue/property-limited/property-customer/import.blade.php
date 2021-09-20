<script type="text/x-template" id="property-limited-customer-tpl">
    <div class="row mx-n2 mt-3 justify-content-end">

        @php 
            $select = 'property.create.label.select';
            $button = 'property.create.button.customer';
        @endphp

        <div class="px-2 col-md mt-2 mt-md-0">
            <div class="row mx-n1">
                <div class="px-1 col-70px">
                    <div class="py-2">@lang( "{$select}.company" ):</div>
                </div>
                <div class="px-1 col">
                    <select class="form-control" v-model.number="company" :required="required && !company && !user" 
                    data-parsley-required-message="いずれかを選択してください。">
                        <option value=""></option>
                        <option v-for="option in companies" :value="option.id">@{{ option.company_name }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="px-2 col-md mt-2 mt-md-0">
            <div class="row mx-n1">
                <div class="px-1 col-70px">
                    <div class="py-2">@lang( "{$select}.user" ):</div>
                </div>
                <div class="px-1 col">
                    <select class="form-control" v-model.number="user" :disabled="!company">
                        <option value=""></option>
                        <option v-for="option in users" :value="option.id">@{{ option.name }}</option>
                    </select>
                    <div class="form-result"></div>
                </div>
            </div>
        </div>

        <div class="px-2 col-md mt-2 mt-md-0">
            <div class="row mx-n1">
                <div class="px-1 col-70px">
                    <div class="py-2">@lang( "{$select}.customer" ):</div>
                </div>
                <div class="px-1 col">
                    <select class="form-control" v-model.number="customer" :disabled="!user">
                        <option value=""></option>
                        <option v-for="option in customers" :value="option.id">@{{ option.name }}</option>
                    </select>
                    <div class="form-result"></div>
                </div>
            </div>
        </div>

        <div class="col-12 d-md-none"></div>

        <!-- Control buttons - Start -->
        <div class="px-2 col-12 col-md-60px mt-2 mt-md-0">
            <div class="row mx-n1 justify-content-end">
                <div class="px-1 col-auto d-md-none">
                    
                    <!-- Add button - Start -->
                    <button class="btn btn-block btn-sm btn-primary" type="button" @click="create">
                        <div class="row mx-n1 justify-content-center">
                            <div class="px-1 col-auto">
                                <i class="fs-14 far fa-plus-circle"></i>
                            </div>
                            <div class="px-1 col-auto d-md-none">
                                <span>@lang( "{$button}.append" )</span>
                            </div>
                        </div>
                    </button>
                    <!-- Add button - End -->

                </div>
                <div class="px-1 col-100px col-md-50px text-center">
                    
                    <!-- Remove button - Start -->
                    <button class="btn btn-block btn-sm btn-danger" type="button" @click="remove" :disabled="count == 1">
                        <div class="row mx-n1 justify-content-center">
                            <div class="px-1 col-auto">
                                <i class="fs-14 far fa-minus-circle"></i>
                            </div>
                            <div class="px-1 col-auto d-md-none">
                                <span>@lang( "{$button}.remove" )</span>
                            </div>
                        </div>
                    </button>
                    <!-- Remove button - End -->

                </div>
            </div>
        </div>
        <!-- Control buttons - End -->

    </div>
        
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyCustomer', {
            // ------------------------------------------------------------------
            template: '#property-limited-customer-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                type: { type: String, required: true },
                index: { type: Number, required: true },
                required: { type: Boolean, default: false },
                count : { type: Number, required: true}
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Component reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    loading: { user: false, customer: false },
                    users: [], customers: []
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                entry: function(){ return this.value },
                state: function(){ return this.$store.state },
                // --------------------------------------------------------------

                
                isFormActive: function(){ return io.get( this.state.selection, this.type )},
                isRequired: function(){ return this.isFormActive },

                // --------------------------------------------------------------
                // APIs defined in the store
                // --------------------------------------------------------------
                userAPI: function(){ return io.get( this.state, 'preset.api.user' )},
                customerAPI: function(){ return io.get( this.state, 'preset.api.customer' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Preset company options
                // --------------------------------------------------------------
                companies: function(){
                    if( 'realEstate' == this.type ) return io.get( this.state, 'preset.company.realEstate' ) || [];
                    else if( 'homeMaker' == this.type ) return io.get( this.state, 'preset.company.homeMaker' ) || [];
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Company model
                // --------------------------------------------------------------
                company: {
                    get: function(){ return io.get( this.entry, 'companies_id' )},
                    set: function( value ){ 
                        // ------------------------------------------------------
                        var vm = this;
                        vm.loading.user = true;
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Do xhr request to get the user list
                        // ------------------------------------------------------
                        var request = axios.post( this.userAPI, { company: value });
                        request.then( function( response ){
                            // --------------------------------------------------
                            var users = io.get( response, 'data' );
                            var status = io.get( response, 'status' );
                            // --------------------------------------------------
                            if( 200 === status ) Vue.set( vm, 'users', users );
                            // --------------------------------------------------
                        });
                        // ------------------------------------------------------
                        request.finally( function(){ vm.loading.user = false });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        this.user = null; // Reset the user selection
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Update the model value
                        // ------------------------------------------------------
                        return Vue.set( this.entry, 'companies_id', value );
                        // ------------------------------------------------------
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // User model
                // --------------------------------------------------------------
                user: {
                    get: function(){ return io.get( this.entry, 'company_users_id' )},
                    set: function( value ){ 
                        // ------------------------------------------------------
                        var vm = this;
                        vm.loading.customer = true;
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Do xhr request to get the customer list
                        // ------------------------------------------------------
                        var request = axios.post( this.customerAPI, { user: value });
                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var customers = io.get( response, 'data' );
                            // --------------------------------------------------
                            if( 200 === status ) Vue.set( vm, 'customers', customers );
                            // --------------------------------------------------
                        });
                        // ------------------------------------------------------
                        request.finally( function(){ vm.loading.customer = false });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        this.customer = null; // Reset the customer selection
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Update the model value
                        // ------------------------------------------------------
                        return Vue.set( this.entry, 'company_users_id', value );
                        // ------------------------------------------------------
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Customer model
                // --------------------------------------------------------------
                customer: {
                    get: function(){ return io.get( this.entry, 'customers_id' )},
                    set: function( value ){ return Vue.set( this.entry, 'customers_id', value )}
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
                create: function(){ this.$emit( 'create', this.type )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Confirm removal
                // --------------------------------------------------------------
                remove: function(){
                    // ----------------------------------------------------------
                    var confirmed = true;
                    if( this.company || this.user || this.customer ){
                        var confirmed = confirm( @json( __( 'label.CONFIRM_DELETE_MESSAGE' )));
                    }
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // If confirmed, emit remove event to the parent component
                    // Send the type ( "realEstate" or "homeMaker" ) and loop index data
                    // ----------------------------------------------------------
                    if( confirmed ) this.$emit( 'remove', {
                        type: this.type, index: this.index
                    });
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
