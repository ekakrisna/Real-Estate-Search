
{{-- Dependency components - Start --}}
@include('frontend.vue.image-placeholder.import')
@include('frontend.vue.property-label.import')
@include('frontend.vue.button-favorite.import')
{{-- Dependency components - End --}}

<script type="text/x-template" id="generic-property-card-container">
    <!-- Clickable card cointainer - Start -->
    <div class="content-list clickable d-flex flex-column flex-grow-1" @click="toPropertyDetail">
        <div class="content-list-header">            
            <!-- History access date - Start -->
            <template v-if="accessDate">
                <p class="date-history">@{{ accessDate }}</p>
            </template>            
            <!-- History access date - End -->

            <!-- Property location - Start -->
            <h3 v-if="location" class="title d-inline ">
                <a :href="destination">@{{ location }}</a>
            </h3>
            <!-- Property location - End -->
            
            <!-- Property browser - Start -->
            <div class="px-1 col-auto d-inline float-right" v-if="browser">
                <label class="bg-badge w-auto d-block m-0 mb-2 badge-gray">
                    <i class="fas fa-history mr-2"></i>
                    閲覧済み
                </label>
            </div> 
            <!-- Property browser - END -->
        </div>
        <div class="content-list-body flex-grow-1 d-flex flex-column">
            <div class="row row-base mx-n2 flex-grow-1">
                <div v-if="photos.length && thumbnailImage" class="px-2 col-5 col-sm-4 col-lg-5">

                    <!-- Ratiobox wrapper - Start -->
                    <div class="ratiobox ratio--1-1 grey-bg rounded">
                        <div class="ratiobox-innerset">

                            <!-- Property thumbnail - Start -->
                            <img :src="thumbnailImage" :alt="thumbnail.file.name" class="rounded">
                            <!-- Property thumbnail - End -->

                            <!-- Thumbnail placeholder - Start -->
                            <!--<image-placeholder v-else class="rounded" alt="No Image"></image-placeholder> -->
                            <!-- Thumbnail placeholder - End -->

                        </div>
                    </div>
                    <!-- Ratiobox wrapper - End -->

                </div>
                <div class="px-2 col d-flex flex-column">
                    <div class="flex-grow-1">

                        <!-- Property labels - Start -->
                        <div class="list-badge h-auto row mx-n1">
                            <div v-if="hasIsReserveLabel" class="px-1 col-auto">
                                <property-label type="is_reserve" :property="property"></property-label>
                            </div>
                            <div v-if="hasNewLabel" class="px-1 col-auto">
                                <property-label type="new" :property="property"></property-label>
                            </div>
                            <div v-if="hasUpdatedLabel" class="px-1 col-auto">
                                <property-label type="updated" :property="property"></property-label>
                            </div>
                            <div v-if="hasNoConditionLabel" class="px-1 col-auto">
                                <property-label type="noCondition" :property="property"></property-label>
                            </div>
                        </div>
                        <!-- Property labels - End -->
    
                        <!-- Property price range - Start -->
                        <p class="price-ranges h-auto">
                            <span v-if="minPrice && maxPrice">@{{ minPriceMan }} ~ @{{ maxPriceMan }}</span>
                            <span v-else-if="minPrice && maxPrice == null">@{{ minPriceMan }}</span>
                            <span v-else-if="minPrice == null && maxPrice">@{{ maxPriceMan }}</span>
                        </p>
                        <!-- Property price range - End -->
                        
                        <!-- Property land-area range - Start -->
                        <div class="row mx-n1 fs-15">
                            <div class="px-1 col-auto">土地</div>
                            <div class="px-1 col">
                                <span v-if="minArea && maxArea">@{{ minAreaMeter }}(@{{ minAreaTsubo }}) ~ @{{ maxAreaMeter }}(@{{ maxAreaTsubo }})</span>
                                <span v-else-if="minArea && maxArea == null">@{{ minAreaMeter }}(@{{ minAreaTsubo }})</span>
                                <span v-else-if="minArea == null && maxArea">@{{ maxAreaMeter }}(@{{ maxAreaTsubo }})</span>
                            </div>
                        </div>
                        <!-- Property land-area range - Start -->

                    </div>
                </div>
                <!-- Favorite button - Start -->
                <div class="w-100 d-flex justify-content-end align-items-end mt-2">
                    @if(Auth::guard('user')->user())
                    <button-favorite v-model="property.favorited" size="26" :property="property"></button-favorite>
                    @endif
                </div>
                <!-- Favorite button - End -->
            </div>
        </div>
                                                
    </div>
    <!-- Clickable card cointainer - End -->
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyCard', {
            // ------------------------------------------------------------------
            template: '#generic-property-card-container',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Props from parent element
            // ------------------------------------------------------------------
            props: {
                property: { type: Object, required: true, default: {}},
                log: { default: null }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            data: function(){ return {
                unit: { price: '万円', area: '坪', meter: 'm²' },
                format: { price: '0,0', area: '0,0' },
                browser: false,
            }},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Log date
                // --------------------------------------------------------------
                accessDate: function(){ return io.get( this.log, 'ja.access_date' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property location
                // --------------------------------------------------------------
                location: function(){ return io.get( this.property, 'location' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property photos
                // --------------------------------------------------------------
                photos: function(){ return io.get( this.property, 'photos' ) || []},
                destination: function(){ return io.get( this.property, 'url.frontend_view' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property thumbnail (Take the first available photo)
                // --------------------------------------------------------------
                thumbnail: function(){ return io.get( this.photos, '[0]' )},
                thumbnailImage: function(){ return io.get( this.thumbnail, 'file.url.image' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property minimum price range
                // --------------------------------------------------------------
                minPrice: function(){ return io.get( this.property, 'minimum_price' )},
                minPriceMan: function(){
                    if( !this.minPrice ) return;
                    var result = Vue.filter('toMan')( this.minPrice );
                    return Vue.filter('numeral')( result, this.format.price ) + this.unit.price;
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property maximum price range
                // --------------------------------------------------------------
                maxPrice: function(){ return io.get( this.property, 'maximum_price' )},
                maxPriceMan: function(){
                    if( !this.maxPrice ) return;
                    var result = Vue.filter('toMan')( this.maxPrice );
                    return Vue.filter('numeral')( result, this.format.price ) + this.unit.price;
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property minimum land-area range
                // --------------------------------------------------------------
                minArea: function(){ return io.get( this.property, 'minimum_land_area' )},
                minAreaMeter: function(){
                    if( !this.minArea ) return;
                    return Vue.filter('numeral')( this.minArea, this.format.area ) + this.unit.meter;
                },
                minAreaTsubo: function(){
                    if( !this.minArea ) return;
                    var result = Vue.filter('toTsubo')( this.minArea );
                    return Vue.filter('numeral')( result, this.format.area ) + this.unit.area;
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Property maximum land-area range
                // --------------------------------------------------------------
                maxArea: function(){ return io.get( this.property, 'maximum_land_area' )},
                maxAreaMeter: function(){
                    if( !this.maxArea ) return;
                    return Vue.filter('numeral')( this.maxArea, this.format.area ) + this.unit.meter;
                },
                maxAreaTsubo: function(){
                    if( !this.maxArea ) return;
                    var result = Vue.filter('toTsubo')( this.maxArea );
                    return Vue.filter('numeral')( result, this.format.area ) + this.unit.area;
                },
                // --------------------------------------------------------------


                // --------------------------------------------------------------
                // Property labels
                // --------------------------------------------------------------
                hasNewLabel: function(){ return io.get( this.property, 'label.new' )},
                hasUpdatedLabel: function(){ return io.get( this.property, 'label.updated' )},
                hasNoConditionLabel: function(){ return io.get( this.property, 'label.noCondition' )},
                hasIsReserveLabel: function(){ return io.get( this.property, 'label.isReserve' )},
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
            // ------------------------------------------------------------------
            // Created
            // ------------------------------------------------------------------
            created(){
                var idProperty = this.property.id;
                this.hasIsBrowsedLabel(idProperty);
            },
            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {                
                hasIsBrowsedLabel: function(value){  
                    var vm = this;
                    var uuid = window.deviceUuid.DeviceUUID().get();
                    const data = {                                
                        uuid: uuid,
                        id_property: value,
                    };
                    var url = @json(route('api.property.check.uuid'));

                    var request = axios.post(url, data);                    
                    request.then( function(response){
                         vm.browser = response.data;
                    });                
                },
                // --------------------------------------------------------------
                // Redirect to property detail on click
                // --------------------------------------------------------------
                toPropertyDetail: function(){
                    var destination = io.get( this.property, 'url.frontend_view' );
                    if( destination ) window.location.href = destination;
                }
                // --------------------------------------------------------------
            },
        });
    }( jQuery, _, document, window ));
@endminify </script>
