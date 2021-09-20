<script type="text/x-template" id="property-publish-tpl">
    <div class="row mx-n2 mt-3 justify-content-end">

        @php 
            $button = 'property.create.button.customer';
        @endphp

        <div class="px-2 col-md mt-2 mt-md-0">
            <input class="form-control" type="text" v-model="destination">              
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
        Vue.component( 'PropertyPublish', {
            // ------------------------------------------------------------------
            template: '#property-publish-tpl',
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
                // Destination model
                // --------------------------------------------------------------
                destination: {
                    get: function(){
                        return io.get( this.entry, 'publication_destination' )
                    },
                    set: function( value ){ 
                        return Vue.set( this.entry, 'publication_destination', value );                        
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Town model
                // --------------------------------------------------------------
                // town: {
                //     get: function(){ return io.get( this.entry, 'towns_id' )},
                //     set: function( value ){ return Vue.set( this.entry, 'towns_id', value )}
                // },
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
                    if( this.destination ){
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
