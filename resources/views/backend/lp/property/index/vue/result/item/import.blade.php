<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- updated_at column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.update_date_and_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ property.ja.updated_at }}</div>
                    </div>
                </div>
            </div>
            <!-- updated_at column - End -->

            <!-- Building condition column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.contracted_years')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            @{{ property.ja.contracted_years }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Building condition column - End -->

            <!-- property id column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.property_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div v-for="publisher in publishers" :key="publisher.id">
                                <p>@{{ publisher.property_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- property id column - End -->

            <!-- Location column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class=" py-0 px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ property.location }}</div>
                    </div>
                </div>
            </div>
            <!-- Location column - End -->

            <!-- Separator on medium layout - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Separator on medium layout - End -->

            <!-- Selling Price column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.selling_price_lp')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <span v-if="property.minimum_price && property.maximum_price">
                                @{{ property.minimum_price | toMan | numeral(0,0)}} ~ @{{  property.maximum_price | toMan | numeral(0,0)}}
                            </span>
                            <span v-else-if="property.minimum_price && property.maximum_price == null">
                                @{{ property.minimum_price | toMan | numeral(0,0)}}
                            </span>
                            <span v-else-if="property.minimum_price == null && property.maximum_price">
                                @{{ property.maximum_price | toMan | numeral(0,0)}}
                            </span>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Selling Price column - End -->

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.land_area_lp')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <span v-if="property.minimum_land_area && property.maximum_land_area">
                                @{{ property.minimum_land_area | toTsubo | numeral('0,0')}} ~ @{{ property.maximum_land_area | toTsubo | numeral('0,0')}}
                            </span>
                            <span v-else-if="property.minimum_land_area && property.maximum_land_area == null">
                                @{{ property.minimum_land_area | toTsubo | numeral('0,0')}}
                            </span>
                            <span v-else-if="property.minimum_land_area == null && property.maximum_land_area">
                                @{{ property.maximum_land_area | toTsubo | numeral('0,0')}}
                            </span>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End -->

            <!-- Separator on medium layout - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Separator on medium layout - End -->

            <!-- Building condition column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.buliding_area_lp')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            @{{ property.building_area | toTsubo | numeral('0,0')}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Building condition column - End -->

            <!-- Property Status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.status')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div>@{{ property.property_status.label }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Property Status column - End -->

            <!-- Property Building Age column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-12 col-xl-70px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.buliding_age_lp')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            @{{ property.building_age }} 年
                        </div>                         
                    </div>
                </div>
            </div>
            <!-- Property Photo column - Start -->

            <!-- Property Flyer column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-12 col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.house_layout')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            @{{ property.house_layout }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Property Flyer column - Start -->

            <!-- Property Flyer column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-12 col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.connecting_road')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            @{{ property.connecting_road }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Property Flyer column - Start -->

            <!-- Control column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-12 col-xl-50px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2"></div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center justify-content-center">

                        <div class="py-2 px-2">
                            <a target="_blank" class="btn btn-sm btn-info fs-12" :href="editPage">
                                <i class="far fa-edit"></i>
                            </a>                            
                        </div>

                    </div>
                </div>
            </div>
            <!-- Control column - End -->

        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'ResultItem', {
            // ------------------------------------------------------------------
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {
                    isOpened: {
                        photo: false,
                        flyer: false,
                    }
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Current property item
                // --------------------------------------------------------------
                property: function(){ return this.value },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // If property has photo
                // --------------------------------------------------------------
                hasPhoto : function(){ return this.property.property_photos.length >= 1 },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // If property has flyer
                // --------------------------------------------------------------
                hasFlyer : function(){ return this.property.property_flyers.length >= 1 },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // User properties
                // --------------------------------------------------------------
                role: function(){ 
                    var roleName = io.get( this.user, 'role.name' );
                    return io.startCase( io.lowerCase( roleName ));
                },
                company: function(){ return io.get( this.user, 'company.company_name' )},
                status: function(){ return io.parseInt( io.get( this.user, 'is_active' ))},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Publisher
                // --------------------------------------------------------------
                publishers: function(){ return io.get( this.property, 'property_publish' ) || []},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Page URLs
                // Comes from laravel model accessor
                // --------------------------------------------------------------
                editPage: function(){ return io.get( this.value, 'url.edit' )},                
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                // ======= FROM DEV_MAIN
                showPhotoModal(){
                    var vm = this;

                    var url = @json(route('api.property.publish.company'));
                    var request = axios.post(url, {
                        id: vm.property.id,                        
                    });
                    // ------------------------------------------------------
                    // On success
                    // ------------------------------------------------------
                    request.then(function(response) {                        
                        vm.$store.state.selection = response.data.publishingSelection;
                        vm.$store.state.property = response.data.property;
                        vm.$store.state.publishing.options = response.data.publishingOptions;
                        vm.$store.state.publishing.customer = response.data.publishingCustomer;
                        vm.$store.state.template = response.data.template;
                        
                        vm.$store.state.preset.company.homeMaker = response.data.company.homeMaker;
                        vm.$store.state.preset.company.realEstate = response.data.company.realEstate;

                        var element = vm.$refs.photoModal.$el;
                        vm.isOpened.photo = true;
                        $(element).modal('show').on( 'hidden.bs.modal', function(){
                            vm.isOpened.photo = false;
                        });
                    });
                },

                showFlyerModal(){
                    var vm = this;

                    var url = @json(route('api.property.publish.company'));
                    var request = axios.post(url, {
                        id: vm.property.id,                        
                    });
                    // ------------------------------------------------------
                    // On success
                    // ------------------------------------------------------
                    request.then(function(response) {    
                        vm.$store.state.selection = response.data.publishingSelection;
                        vm.$store.state.property = response.data.property;
                        vm.$store.state.publishing.options = response.data.publishingOptions;
                        vm.$store.state.publishing.customer = response.data.publishingCustomer;
                        vm.$store.state.template = response.data.template;
                        
                        vm.$store.state.preset.company.homeMaker = response.data.company.homeMaker;
                        vm.$store.state.preset.company.realEstate = response.data.company.realEstate;
                                                                      
                        var element = vm.$refs.flyerModal.$el;
                    
                        vm.isOpened.flyer = true;
                        $(element).modal('show').on( 'hidden.bs.modal', function(){
                            vm.isOpened.flyer = false;
                        });
                    });                    
                    // ======= END FROM DEV_MAIN
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

