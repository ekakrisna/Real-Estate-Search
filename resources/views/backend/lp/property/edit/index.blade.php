@extends('backend._base.content_vueform')

@php
    $label       = 'property.create.label';
    $status      = 'property.create.status';
@endphp

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.lp.property')}}">@lang('label.lp_list_property')</a></li>
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

            <!-- Property status - Start -->
            <property-status v-model="propertyStatus" :options="statusOptions" :required="true"></property-status>            
            <!-- Property status - End -->

            <!-- Property id - Start -->
            <show-plain-data-form v-model="property.ja.contracted_years" label="@lang( "label.contracted_years" )" ></show-plain-data-form>
            <!-- Property id - End -->

            <!-- Property id - Start -->
            <show-plain-data-form v-model="property.id" label="@lang( "label.property_id" )" ></show-plain-data-form>
            <!-- Property id - End -->
        
            <!-- Property location - Start -->
            <input-text v-model="property.location" label="@lang( "{$label}.location" )"></input-text>
            <!-- Property location - End -->
        
            <!-- Property price - Start -->
            <show-price-form :min.sync="property.minimum_price" :max.sync="property.maximum_price" label="@lang( "label.selling_price_lp" )" append="万円"></show-price-form>
            <!-- Property price - End -->

            <!-- Property land area - Start -->
            <show-land-area-form :min.sync="property.minimum_land_area" :max.sync="property.maximum_land_area" label="@lang( "label.land_area_lp" )"  append="坪"></show-land-area-form>
            <!-- Property land area - End -->

            <!-- Property Building Area - Start -->
            <show-land-area-form :min.sync="property.building_area" label="@lang( "label.buliding_area_lp" )"  append="坪"></show-land-area-form>
            <!-- Property Building Area - End -->

            <!-- Property Building Age - Start -->
            <show-plain-data-form v-model="property.building_age" label="@lang( "label.buliding_age_lp" )" append="年"></show-plain-data-form>
            <!-- Property Building Age - End -->

            <!-- Property Building House Layout - Start -->
            <show-plain-data-form v-model="property.house_layout" label="@lang( "label.house_layout" )" ></show-plain-data-form>
            <!-- Property Building House Layout - End -->
            
            <!-- Property Building House connecting_road - Start -->
            <show-plain-data-form v-model="property.connecting_road" label="@lang( "label.connecting_road" )" ></show-plain-data-form>
            <!-- Property Building House connecting_road - End -->
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
                            <span>@lang('label.save')</span>
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
@include('backend.vue.components.input-textarea.import')
@include('backend.vue.components.input-range.import')
@include('backend.vue.components.empty-placeholder.import')
@include('backend.vue.components.show-price-form.import')
@include('backend.vue.components.show-land-area-form.import')
@include('backend.vue.components.show-plain-data-form.import')
@include('backend.vue.components.show-anchor-data-form.import')
<!-- General components - End -->

<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
<!-- Preloaders - End -->

<!-- Upload components - Start -->
@include('backend.vue.components.ratiobox.import')
<!-- Upload components - End -->

<!-- Shared property components - Start -->
@include('backend.property.vue.property-status.import')
<!-- Shared property components - End -->

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
                property: @json( $property ),
                preset: {
                    // -----------------------------------------------------
                    // Property status options
                    // -----------------------------------------------------
                    status: [
                        { id: 1, name: 'approval_pending', label: @json( __( "label.approval_pending" ) )},
                        { id: 2, name: 'published', label: @json( __( "label.published" ))},
                        { id: 3, name: 'not_post', label: @json( __( "label.not_publish" ))},
                        { id: 4, name: 'not_publish', label: @json( __( "property.create.status.notpublish" ) )},                        
                    ],
                    // -----------------------------------------------------                    
                    api: {
                        store : @json(route('admin.lp.property.update',$id));
                    },                                            
                }
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( state );
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
            // Assign property properties
            // --------------------------------------------------------------
            assignProperty: function( state, dataset ){
                io.assign( state.property, dataset );
            },
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
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            return {
                
            }
        },
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
            property: function(){ return this.$store.state.property },            
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Property status model
            // --------------------------------------------------------------
            propertyStatus: {
                get: function(){ return this.$store.state.property.lp_property_status_id },
                set: function( value ){ this.$store.commit( 'assignProperty', { lp_property_status_id: value })}
            },
            // --------------------------------------------------------------
                        
            // --------------------------------------------------------------
            statusOptions: function(){ return io.get( this.preset, 'status' )},            
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        methods: {},
        watch: {}
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
                data.property = vm.property;
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
                    console.log(response);
                    // ------------------------------------------------------
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
                            var propertyPage = @json(route('admin.lp.property'));
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