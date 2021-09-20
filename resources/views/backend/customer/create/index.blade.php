@extends('backend._base.content_vueform')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
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

            <input-text v-model="customer.name" label="@lang('label.name')" required name="name"></input-text>
            <input-email-unique v-model="customer.email" label="@lang('label.email')" required name="email"></input-email-unique>
            <input-phone v-model="customer.phone" label="@lang('label.phone')" name="phone"></input-phone>

            <!-- Desired Area- Start -->
            <customer-area v-model="area" ></customer-area>
            <!-- Desired Area - End -->
            
            <range-price name1="minimum_price" name2="maximum_price" :min.sync="customer.minimum_price" :max.sync="customer.maximum_price" required
                label="@lang('label.min_consideration')" append="万円">
            </range-price>

            <range-price name1="minimum_price_land_area" name2="maximum_price_land_area" :min.sync="customer.minimum_price_land_area" :max.sync="customer.maximum_price_land_area" required 
                label="@lang('label.min_land')" append="万円">
            </range-price>

            <range-land name1="minimum_land_area" name2="maximum_land_area" :min.sync="customer.minimum_land_area" :max.sync="customer.maximum_land_area" required
                label="@lang('label.min_desired_land')" append="坪">
            </range-land>

            <input-password v-model="customer.password" label="@lang('label.password')" required name="password"></input-password>

            
            <corporate-charge v-model="customer" required></corporate-charge>

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
@include('backend.vue.components.input-text.import')
@include('backend.vue.components.input-password.import')
@include('backend.vue.components.input-email-unique.import')
@include('backend.vue.components.input-phone.import')
@include('backend.vue.components.empty-placeholder.import')
<!-- General components - End -->

<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
<!-- Preloaders - End -->

<!-- Child components - Start -->
@relativeInclude('vue.customer-area.import')
@relativeInclude('vue.corporate-charge.import')
@relativeInclude('vue.range-price.import')
@relativeInclude('vue.range-land.import')
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
                customer: @json( $customer ),
                area: {
                    desiredArea: @json( $area->desiredArea )
                },
                template: @json( $template ),
                preset: {
                    // -----------------------------------------------------
                    // List city & Company
                    // -----------------------------------------------------
                    city: @json( $city ),
                    company: @json( $company ),
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // List consider_amount
                    // -----------------------------------------------------
                    consider_amount: @json( $consider_amount ),
                    consider_land: @json( $consider_land ),
                    // -----------------------------------------------------


                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        cities_areas: @json( route( 'api.company.cityAreaList' )),
                        user: @json( route( 'api.company.persons' )),
                        store: @json( route( 'admin.customer.create.store' ))
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
            // Create / append new DATA
            // --------------------------------------------------------------
            appendData: function( state, type ){
                var entry = io.clone( state.template.desiredArea );
                state.area.desiredArea.push( entry );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Remove property Data 
            // --------------------------------------------------------------
            removeData: function( state, { index }){
                state.area.desiredArea.splice( index, 1 );
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

            // -------------------------------------------------------------
            // Reference shortcuts
            // -------------------------------------------------------------
            preset: function(){ return this.$store.state.preset },
            customer: function(){ return this.$store.state.customer },
            area: function(){ return this.$store.state.area },
            // -------------------------------------------------------------

            model: {
                get: function(){},
                set: function(){}
            }
        },

        // ------------------------------------------------------------------
        // Wacthers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Watch customer data to refresh the validation
            // Since the customer data is dynamic / addable, 
            // we need to refresh the validation each time the data has changed
            // --------------------------------------------------------------
            area: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            customer: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            }
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
            //console.log(form.isValid());
            // --------------------------------------------------------------
            // On form invalid, 
            // navigate/scroll the page to the validation messages
            // --------------------------------------------------------------
            var validForm = form.isValid();
            if( validForm==false ) navigateValidation( validForm );
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
                data.area = {};
                data.customer = vm.customer;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Add customer data based on the selection
                // ----------------------------------------------------------
                data.area.desiredArea = io.filter( vm.area.desiredArea, function( item ){
                    return item.cities_id || item.cities_area_id ;
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // console.log( data ); return; // Debugging
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
                            var redirectPage = io.get( response.data, 'customer.url.view' );
                            window.location = redirectPage;
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