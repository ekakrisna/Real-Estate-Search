<script type="text/x-template" id="property-limited-customer-tpl">
    <div class="py-2 px-3 border-bottom" :class="{ 'bg-light': isEven, 'border-top': !index }">
        <div class="row mx-n2 justify-content-end">

            @php 
                $select = 'property.create.label.select';
                $button = 'property.create.button.customer';
            @endphp

            <div class="px-2 col-md mt-2 mt-md-0">
                <div class="row mx-n1">
                    <div class="px-1 col-70px col-md-auto">
                        <div class="py-2">@lang( "{$select}.company" ):</div>
                    </div>
                    <div class="px-1 col">
                        <select class="form-control" v-model.number="company">
                            <option value=""></option>
                            <option v-for="option in companies" :value="option.id">@{{ option.company_name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-2 col-md mt-2 mt-md-0">
                <div class="row mx-n1">
                    <div class="px-1 col-70px col-md-auto">
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
                    <div class="px-1 col-70px col-md-auto">
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
                <div class="row mx-n1 h-100 justify-content-end justify-content-md-center align-items-center">
                    <div class="px-1 col-auto d-md-none">
                        
                        <!-- Add button - Start -->
                        <button class="btn btn-block btn-sm btn-info" type="button" @click="create">
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
                        <button class="btn btn-block btn-sm btn-danger" type="button" @click="remove">
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
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Component reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    loading: { user: false, customer: false },
                    users: [], customers: [],
                    // count: 0
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
                // Current property
                // --------------------------------------------------------------
                property: function(){ return this.state.property },
                propertyStatus: function(){ return io.parseInt( io.get( this.property, 'property_statuses_id' ))},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Form required state
                // Form is active/selected & property status is 3 (Limited)
                // --------------------------------------------------------------
                isFormActive: function(){ return io.get( this.state.selection, this.type )},
                isPropertyLimited: function(){ return 3 === this.propertyStatus },
                isRequired: function(){ return this.isPropertyLimited && this.isFormActive },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Row index identifiers
                // --------------------------------------------------------------
                isEven: function(){ return 0 === Number( this.index ) % 2 },
                isOdd: function(){ return !this.isEven },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // APIs defined in the store
                // --------------------------------------------------------------
                userAPI: function(){ return io.get( this.state, 'preset.api.user' )},
                customerAPI: function(){ return io.get( this.state, 'preset.api.customer' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Publishing options
                // --------------------------------------------------------------
                options: function(){ return io.get( this.state, 'publishing.options' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Entity IDs
                // --------------------------------------------------------------
                companyID: function(){ return Number( io.get( this.entry, 'companies_id' )) || null },
                userID: function(){ return Number( io.get( this.entry, 'company_users_id' )) || null },
                customerID: function(){ return Number( io.get( this.entry, 'customers_id' )) || null },
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
                    get: function(){ return this.companyID },
                    set: function( value ){
                        this.user = null; // Reset the user selection
                        return Vue.set( this.entry, 'companies_id', value );
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // User model
                // --------------------------------------------------------------
                user: {
                    get: function(){ return this.userID },
                    set: function( value ){
                        this.customer = null; // Reset the customer selection
                        return Vue.set( this.entry, 'company_users_id', value );
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
                    // this.count = 1; 
                    if( confirmed ) this.$emit( 'remove', {
                        type: this.type, index: this.index
                    });
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // Watch company changes to request or update the user list
                // --------------------------------------------------------------
                company: { immediate: true, handler: function( companyID ){
                    // ----------------------------------------------------------
                    var vm = this;
                    if( !companyID ){ vm.users = []; return };
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Get user list from the store publishing options
                    // ----------------------------------------------------------
                    var company = io.find( vm.options, { id: Number( companyID )});
                    var users = io.get( company, 'users' );
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    if( io.isArray( users )) vm.users = users;
                    else {
                        // ------------------------------------------------------
                        // Do xhr request to get the user list
                        // ------------------------------------------------------
                        vm.loading.user = true;
                        var request = axios.post( vm.userAPI, { company: companyID });
                        // ------------------------------------------------------
                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var users = io.get( response, 'data' ) || [];
                            // --------------------------------------------------
                            if( 200 === status ){
                                vm.users = users; // Update the local user options
                                Vue.set( company, 'users', users ); // Update the store publishing options
                            }
                            // --------------------------------------------------
                        }).finally( function(){ vm.loading.user = false });
                        // ------------------------------------------------------
                    }
                    // ----------------------------------------------------------
                }},
                // --------------------------------------------------------------


                // --------------------------------------------------------------
                // Watch company-user changes to request or update the customer list
                // --------------------------------------------------------------
                user: { immediate: true, handler: function( userID ){
                    // ----------------------------------------------------------
                    var vm = this;
                    if( !vm.userID ){ vm.customers = []; return };
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Get the customer list from the publishing options
                    // ----------------------------------------------------------
                    var company = io.find( vm.options, { id: vm.companyID });
                    var users = io.get( company, 'users' );
                    var user = io.find( users, { id: userID });
                    var customers = io.get( user, 'customers' );
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    if( io.isArray( customers )) vm.customers = customers;
                    else {
                        // ------------------------------------------------------
                        // Do xhr request to get the customer list
                        // ------------------------------------------------------
                        vm.loading.customer = true;
                        var request = axios.post( vm.customerAPI, { user: userID });
                        // ------------------------------------------------------
                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var customers = io.get( response, 'data' ) || [];
                            // --------------------------------------------------
                            if( 200 === status ){
                                vm.customers = customers; // Update the local customer options
                                Vue.set( user, 'customers', customers ); // Update the store publishing options
                            }
                            // --------------------------------------------------
                        }).finally( function(){ vm.loading.customer = false });
                        // ------------------------------------------------------
                    }
                    // ----------------------------------------------------------
                }},
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
