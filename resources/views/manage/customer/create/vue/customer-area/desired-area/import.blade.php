<script type="text/x-template" id="desired-area-tpl">
    <div class="row mx-n2 mt-3 justify-content-end">

        @php 
            $button = 'property.create.button.customer';
        @endphp

        <div class="px-2 col-md">
            <select class="form-control form-control-sm" v-model.number="city" required="true">
                <option v-if="city" value="" class="d-none"></option>                
                <optgroup v-for="option in cities" v-if="option.cities.length > 0" :label="option.group_character">
                    <option v-for="option_cities in option.cities" :value="option_cities.id">@{{option_cities.name}}</option>
                </optgroup>
            </select>
        </div>

        <div class="px-2 col-md mt-2 mt-md-0">
            <select class="form-control form-control-sm" v-model.number="town" :disabled="!city">
                <option v-if="city" value="" class="d-none"></option>                
                <optgroup v-for="(data,index) in towns" v-if="data.cities_areas.length > 0" :label="data.group_character">
                    <option v-for="(data_cities,index) in data.cities_areas" :value="data_cities.id">@{{data_cities.display_name}}</option>
                </optgroup>
            </select>
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
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'DesiredArea', {
            // ------------------------------------------------------------------
            template: '#desired-area-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                index: { type: Number, required: true },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Component reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    loading: { town: false },
                    towns: []
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
                // APIs defined in the store
                // --------------------------------------------------------------
                townAPI: function(){ return io.get( this.state, 'preset.api.town' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Preset Cities options
                // --------------------------------------------------------------
                cities: function(){
                    return io.get( this.state, 'preset.city' ) || [];
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // City model
                // --------------------------------------------------------------
                city: {
                    get: function(){  return io.get( this.entry, 'cities_id' )},
                    set: function( value ){ 
                        // ------------------------------------------------------
                        var vm = this;
                        vm.loading.town = true;
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Do xhr request to get the user list
                        // ------------------------------------------------------
                        var request = axios.post( this.townAPI, { city: value });
                        request.then( function( response ){
                            // --------------------------------------------------
                            var towns = io.get( response, 'data' );
                            var status = io.get( response, 'status' );
                            // --------------------------------------------------
                            if( 200 === status ) Vue.set( vm, 'towns', towns );
                            // --------------------------------------------------
                        });
                        // ------------------------------------------------------
                        request.finally( function(){ vm.loading.town = false });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        this.town = null; // Reset the user selection
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Update the model value
                        // ------------------------------------------------------
                        return Vue.set( this.entry, 'cities_id', value );
                        // ------------------------------------------------------
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Town model
                // --------------------------------------------------------------
                town: {
                    get: function(){ return io.get( this.entry, 'cities_area_id' )},
                    set: function( value ){ return Vue.set( this.entry, 'cities_area_id', value )}
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
