@extends('backend._base.content_vueform')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li> 
        <li class="breadcrumb-item"><a href="{{route('admin.approval')}}">@lang('label.approval')</a></li>        
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

    @include('backend.approval.edit.includes.modal')

    <!-- Form content - Start -->
    <form class="parsley-minimal" data-parsley>
        <div class="position-relative">
            <!-- Content mask loader - Start -->
            {{-- <mask-loader :loading="isLoading"></mask-loader> --}}
            <!-- Content mask loader - End -->

            <!-- Property id - Start -->
            <show-plain-data-form v-model="approvalProperty.id" label="@lang( "label.property_id" )" ></show-plain-data-form>
            <!-- Property id - End -->

            <!-- Property location - Start -->
            <input-text v-model="approvalProperty.location" label="@lang( "label.location" )" :filed-color="locationColor"></input-text>
            <!-- Property location - End -->            

            <!-- Property Price - Start -->
            <input-range :min.sync="landprice.minimum_price" :max.sync="landprice.maximum_price" :filed-color="priceColor"
                :precision="{min:0, max:4}" label="@lang( "label.b27_selling_price_approval" )" append="万円" :names="[ 'minimum_price', 'maximum_price' ]">
            </input-range>
            <!-- Property Price - End -->

            <!-- Property Land Area - Start -->
            <input-range :min.sync="landprice.minimum_land_area" :max.sync="landprice.maximum_land_area" :filed-color="LandAreaColor"
                :precision="{min:0, max:4}" label="@lang( "label.b27_land_area_approval" )" append="㎡" :names="[ 'minimum_land_area', 'maximum_land_area' ]">
            </input-range>
            <!-- Property Land Area - End -->

            <!-- Property id - Start -->
            <show-plain-data-form :value="buildingCondition" label="@lang( "label.status_approval" )" ></show-plain-data-form>
            <!-- Property id - End -->

            <div class="row form-group">
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.publication_medium')</strong>        
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10" >
                    <div class="row">
                        <div v-for="tmp in area.propertyPublish" class="col-12">
                            <a :href="tmp.url">
                                @{{tmp.publication_destination}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Form content - End -->

        <div>
            <p class="text-red">@lang('label.need_to_fix_point')</p>
        </div>
    
        <!-- Form buttons - Start -->
        <div class="row mx-n2 justify-content-center mt-5 mb-5">
            <div class="px-2 col-12 col-md-240px">

                <!-- Submit button - Start -->
                <button type="submit" class="btn btn-block btn-info">
                    <div class="row mx-n1 justify-content-center">
                        {{-- <div v-if="isLoading" class="px-1 col-auto">
                            <i class="fal fa-cog fa-spin"></i>
                        </div> --}}
                        <div class="px-1 col-auto">
                            <span>@lang('label.fix_button')</span>
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

<!-- General components - Start -->)
@include('backend.vue.components.show-plain-data-form.import')
@include('backend.vue.components.empty-placeholder.import')
@include('backend.vue.components.input-text.import')
@include('backend.vue.components.input-range.import')
<!-- General components - End -->

<!-- Preloaders - Start -->
{{-- @include('backend.vue.components.page-loader.import') --}}
{{-- @include('backend.vue.components.mask-loader.import') --}}
<!-- Preloaders - End -->

<!-- Child components - Start -->
@relativeInclude('vue.property-area.import')
@relativeInclude('vue.property-condition.import')
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
                status      : { mounted: false, loading: false },
                property    : @json( $property ), 
                priceland   : @json( $priceland ),
                publish     : {
                    propertyPublish: @json( $publish->propertyPublish )
                },
                template    : @json( $template ),
                preset: {
                    // -----------------------------------------------------
                    // Building condition options
                    // -----------------------------------------------------
                    condition: [
                        { id: 1, name: 'available', label: @json( $available )},
                        { id: 2, name: 'none', label: @json( $none )},
                    ],
                    api: {
                        store: @json( route( 'admin.approval.update',$id )),
                    },
                    // -----------------------------------------------------
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
            // Change building condition 
            // --------------------------------------------------------------
            setBuildingCondtion: function( state, condition ){
                var building_condition = condition == 1 ? true : false;
                Vue.set( state.property, 'building_conditions', building_condition );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create / append new DATA
            // --------------------------------------------------------------
            appendData: function( state, type ){
                var entry = io.clone( state.template.propertyPublish );
                state.publish.propertyPublish.push( entry );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Remove property Data 
            // --------------------------------------------------------------
            removeData: function( state, { index }){
                state.publish.propertyPublish.splice( index, 1 );
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
            approvalProperty: function(){ return this.$store.state.property },            
            // -------------------------------------------------------------              
            buildingCondition: {
                get: function(){ return this.$store.state.property.building_conditions === true ? "あり" : "なし" },
                set: function( value ){ this.$store.commit( 'setBuildingCondtion', value )}
            },                      
            conditionOptions: function(){ return io.get(  this.$store.state.preset, 'condition' )},
            area            : function(){ return this.$store.state.publish },
            landprice       : function(){ return this.$store.state.priceland },
            locationColor: function(){ return this.$store.state.property.property_convert_status_id == 100 ? 'text-red' : '' },
            priceColor   : function(){ return this.$store.state.property.property_convert_status_id == 200 ? 'text-red' : '' },
            LandAreaColor: function(){ return this.$store.state.property.property_convert_status_id == 300 ? 'text-red' : '' }
        },

        methods: {
            submit: function(){
                var vm          = this;                          
                var link        = vm.$store.state.preset.api.store;                                                  
                axios.post(link, {
                    location            : vm.approvalProperty.location,
                    minimum_price       : vm.landprice.minimum_price,
                    maximum_price       : vm.landprice.maximum_price,
                    minimum_land_area   : vm.landprice.minimum_land_area,
                    maximum_land_area   : vm.landprice.maximum_land_area,
                    building_conditions : vm.approvalProperty.building_conditions,
                    building_conditions_desc   : vm.approvalProperty.building_conditions_desc,
                    propertyPublish     : vm.area.propertyPublish,
                }).then(function (response) {   
                    console.log(response);
                    if (response.data.status == "success") {                                                                                                                           
                        var message = "@lang('label.SUCCESS_UPDATE_MESSAGE')";
                        vm.$toasted.show(message, {
                            type: 'success',
                        });
                        setTimeout( function(){
                            var redirectPage = @json( route( 'admin.approval' ));
                            window.location = redirectPage;
                        }, 1000 );
                    }
                    else if(response.data.status == "wrong_location"){
                        var message = "@lang('label.FAILED_LOCATION_MESSAGE')";
                            vm.$toasted.show(message, {
                                    type: 'error',
                            });
                    }                     
                })              
                    
            }
        },

        // ------------------------------------------------------------------
        // Wacthers
        // ------------------------------------------------------------------
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
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){            
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
                let element = vm.$refs.modalContent;                        
                $(element).modal('show');
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