@extends('backend._base.content_vueform')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.property')}}">@lang('label.list_of_properties')</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.property.detail',$property_id)}}">物件 ID : {{$property_id}}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')

    <!-- Page preloader - Start -->
    <div v-if="!isMounted" class="preloader preloader-fullscreen d-flex justify-content-center align-items-center">
        <div class="folding-cube">
            <div class="cube cube-1"></div>
            <div class="cube cube-2"></div>
            <div class="cube cube-4"></div>
            <div class="cube cube-3"></div>
        </div>
    </div>
    <!-- Page preloader - End -->

    <!-- Form content - Start -->
    <form class="parsley-minimal" data-parsley>
        <div class="position-relative">

            <!-- Content mask loader - Start -->
            <mask-loader :loading="isLoading"></mask-loader>
            <!-- Content mask loader - End -->

            <!--<favorite-registered-area v-model="propertyDelivery.favorite_registered_area" id="favorite_registered_area" name="favorite_registered_area" label="@lang('label.delivery_favorite')" :required="customer">
            </favorite-registered-area>-->

            <!-- Customer Choice - Start -->
            <property-limited v-model="customer"></property-limited>
            <!-- Customer Choice - End -->

            <div class="row form-group align-items-start pt-0 pb-0">
                <div class="col-md-3 col-lg-2 col-header">
                </div>
                <div class="col-md-9 col-lg-10 col-xl-9 col-content">
                    <div id="er-deliveryTarget"></div>
                </div>
            </div>

             <!-- Delivery Target - Start -->
            <div class="row form-group">
                <div class="col-md-3 col-lg-2 col-header">
                    <span class="bg-success label-required">@lang('label.optional')</span>
                    <strong class="field-title">@lang('label.ignoring_setting')</strong>
                </div>
                <div class="col-md-9 col-lg-10 col-xl-9 col-content">
                    <checkbox v-model="propertyDelivery.exclude_received_over_three" id="exclude_received_over_three" name="exclude_received_over_three"  label="@lang('label.exclude_three_notification')">
                    </checkbox>

                    <checkbox v-model="propertyDelivery.exclude_customers_outside_the_budget" id="exclude_customers_outside_the_budget" name="exclude_customers_outside_the_budget"  label="@lang('label.exclude_outside_budget')">
                    </checkbox>

                    <checkbox v-model="propertyDelivery.exclude_customers_outside_the_desired_land_area" id="exclude_customers_outside_the_desired_land_area" name="exclude_customers_outside_the_desired_land_area"  label="@lang('label.exclude_other_desired_area')">
                    </checkbox>
                </div>
            </div>
             <!-- Delivery Target - End -->

            <!-- Delivery Content - Start -->
            <div class="row form-group">
                <div class="col-md-3 col-lg-2 col-header">
                    <span class="bg-success label-required">@lang('label.optional')</span>
                    <strong class="field-title">@lang('label.delivery_content')</strong>
                </div>
                <div class="col-md-9 col-lg-10 col-xl-9 col-content">
                    <!-- Delivery Text - Start -->
                    <input-subject v-model="propertyDelivery.subject" label="@lang('label.subject')"></input-subject>
                    <!-- Delivery Text - End -->

                    <!-- Delivery Text - Start -->
                    <input-textarea v-model="propertyDelivery.text" label="@lang('label.text')" rows="5"></input-textarea>
                    <!-- Delivery Text - End -->
                </div>
            </div>
             <!-- Delivery Content - End -->

        </div>
        <!-- Form content - End -->
    
        <!-- Form buttons - Start -->
        <div class="row mx-n2 justify-content-center mt-5 mb-5">
            <div class="px-2 col-12 col-md-240px">

                <!-- Submit button - Start -->
                <button type="submit" class="btn btn-block btn-info">
                    <div class="row mx-n1 justify-content-center">
                        <div v-if="isLoading" class="px-1 col-auto">
                            <i class="fal fa-cog fa-spin"></i>
                        </div>
                        <div class="px-1 col-auto">
                            <span>@lang('property.create.button.create')</span>
                        </div>
                    </div>
                </button>
                <!-- Submit button - End -->

            </div>
        </div>
        <!-- Form buttons - End -->
    
    </form>
@endsection

@push('vue-scripts')

<!-- General components - Start -->
@include('backend.vue.components.icheck.import')
<!-- General components - End -->

<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
<!-- Preloaders - End -->

<!-- Upload components - Start -->
<!-- Upload components - End -->

<!-- Child components - Start -->
@relativeInclude('vue.favorite-registered-area.import')
@relativeInclude('vue.property-limited.import')
@relativeInclude('vue.delivery-target.checkbox')
@relativeInclude('vue.delivery-content.text')
@relativeInclude('vue.delivery-content.subject')
<!-- Child components - End -->

<script> @minify
(function( io, $, window, document, undefined ){
    // ----------------------------------------------------------------------
    window.queue = {}; // Event queue
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Vuex store
    // ----------------------------------------------------------------------
    store = {
        // ------------------------------------------------------------------
        // Reactive state
        // ------------------------------------------------------------------
        state: function(){
            // --------------------------------------------------------------
            var state = {
                status: { mounted: false, loading: false },
                propertyDelivery: @json( $propertyDelivery ),
                customer: {
                    homeMaker: @json( $customer->homeMaker ),
                    realEstate: @json( $customer->realEstate )
                },
                selection: {
                    homeMaker: false,
                    realEstate: false,
                },
                template: @json( $template ),
                preset: {
                    // -----------------------------------------------------
                    // List company
                    // -----------------------------------------------------
                    company: {
                        homeMaker: @json( $company->homeMaker ),
                        realEstate: @json( $company->realEstate )
                    },
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        user: @json( route( 'api.company.users' )),
                        customer: @json( route( 'api.user.customers' )),
                        store: @json( route( 'admin.property.delivery.store', $property_id ))
                    },
                    // -----------------------------------------------------
                    
                }
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            //console.log( state );
            return state;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Mutations
        // ------------------------------------------------------------------
        mutations: {
            // --------------------------------------------------------------
            refreshParsley: function(){
                setTimeout( function(){
                    var $form = $('form[data-parsley]');
                    $form.parsley().refresh();
                });
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Set loading state
            // --------------------------------------------------------------
            setLoading: function( state, loading ){
                if( io.isUndefined( loading )) loading = true;
                Vue.set( state.status, 'loading', !! loading );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Set mounted state
            // --------------------------------------------------------------
            setMounted: function( state, mounted ){
                if( io.isUndefined( mounted )) mounted = true;
                Vue.set( state.status, 'mounted', !! mounted );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle home-maker selection
            // --------------------------------------------------------------
            toggleHomeMaker: function( state ){
                var selection = state.selection.homeMaker;
                Vue.set( state.selection, 'homeMaker', !selection );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle real-estate selection
            // --------------------------------------------------------------
            toggleRealEstate: function( state ){
                var selection = state.selection.realEstate;
                Vue.set( state.selection, 'realEstate', !selection );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle FavoriteSelection
            // --------------------------------------------------------------
            toggleFavoriteSelection: function( state, check  ){
                Vue.set( state.propertyDelivery, 'favorite_registered_area', check );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create / append new customer
            // --------------------------------------------------------------
            appendCustomer: function( state, type ){
                if( 'realEstate' == type ){
                    var entry = io.clone( state.template.realEstate );
                    state.customer.realEstate.push( entry );
                }
                else if( 'homeMaker' == type ){
                    var entry = io.clone( state.template.homeMaker );
                    state.customer.homeMaker.push( entry );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Remove property customer 
            // --------------------------------------------------------------
            removeCustomer: function( state, { type, index }){
                if( 'realEstate' == type ) state.customer.realEstate.splice( index, 1 );
                else if( 'homeMaker' == type ) state.customer.homeMaker.splice( index, 1 );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Vue mixin / Root component
    // ----------------------------------------------------------------------
    mixin = {
        // ------------------------------------------------------------------
        // On mounted hook
        // ------------------------------------------------------------------
        mounted: function(){
            this.$store.commit( 'setMounted', true );
            $(document).trigger( 'vue-loaded', this );
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Loading and mounted status
            // --------------------------------------------------------------
            isLoading: function(){ return this.$store.state.status.loading },
            isMounted: function(){ return this.$store.state.status.mounted },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reference shortcuts
            // --------------------------------------------------------------
            preset: function(){ return this.$store.state.preset },
            propertyDelivery: function(){ return this.$store.state.propertyDelivery },
            customer: function(){ return this.$store.state.customer },
            // --------------------------------------------------------------

            

            model: {
                get: function(){},
                set: function(){}
            }
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Wacthers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Watch customer data to refresh the validation
            // Since the customer data is dynamic / addable, 
            // we need to refresh the validation each time the data has changed
            // --------------------------------------------------------------
            customer: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            // --------------------------------------------------------------

             // --------------------------------------------------------------
            // Watch the user selection to refresh the validation
            // --------------------------------------------------------------
            selection: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // When vue has been mounted/loaded
    // ----------------------------------------------------------------------
    $(document).on( 'vue-loaded', function( event, vm ){
        // ------------------------------------------------------------------
        // Init parsley form validation
        // ------------------------------------------------------------------
        var $window = $(window);
        var $form = $('form[data-parsley]');
        var form = $form.parsley();
        var queue = window.queue;
        var store = vm.$store;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // --------------------------------------------------------------
            // On form invalid, 
            // navigate/scroll the page to the validation messages
            // --------------------------------------------------------------
            //var validForm = false;
            var validForm = form.isValid();
            if( !validForm ) navigateValidation( validForm );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // On form valid
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                var state = vm.$store.state;
                vm.$store.commit( 'setLoading', true );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Prepare request
                // ----------------------------------------------------------
                var data = {};
                var formData = new FormData();
                var url = io.get( state.preset, 'api.store' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Populate data
                // ----------------------------------------------------------
                data.customer = {};
                data.propertyDelivery = vm.propertyDelivery;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Add customer data based on the selection
                // ----------------------------------------------------------
                var homeMaker = io.get( state, 'selection.homeMaker' );
                if( homeMaker ) data.customer.homeMaker = io.filter( vm.customer.homeMaker, function( item ){
                    return item.companies_id || item.company_users_id || item.customers_id;
                });
                // ----------------------------------------------------------
                var realEstate = io.get( state, 'selection.realEstate' );
                if( realEstate ) data.customer.realEstate = io.filter( vm.customer.realEstate, function( item ){
                    return item.companies_id || item.company_users_id || item.customers_id;
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                //console.log( data ); return; // Debugging
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create multipart request with formData as the post data
                // ----------------------------------------------------------
                var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                queue.save = axios.post( url, formData, options ); // Do the request
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle success response
                // ----------------------------------------------------------
                queue.save.then( function( response ){
                    // ------------------------------------------------------
                    // console.log( response );
                    vm.$store.commit( 'setLoading', true );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( response.data ){
                        // --------------------------------------------------
                        $window.scrollTo( 0, { easing: 'easeOutQuart' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                        vm.$toasted.show( message, { type: 'success' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Redirect to the property page
                        // --------------------------------------------------
                        setTimeout( function(){
                            var propertyPage = io.get( response.data, 'property.url.view' );
                            window.location = propertyPage;
                        }, 1000 );
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle other response
                // ----------------------------------------------------------
                queue.save.catch( function(e){ console.log( e )});
                queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------
        
    });
    // ----------------------------------------------------------------------
}( _, jQuery, window, document ))
@endminify </script>
@endpush