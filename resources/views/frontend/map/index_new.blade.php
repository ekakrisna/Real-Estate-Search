@extends('frontend._base.app')
@section('title', $title)
@section('description', '')
@section('page')
<div class="map-page">
    @if(Auth::guard('user')->user())
        <map-filter v-if="isMapFilterShown" ref="mapfilter" v-model="logged_in_customer" :filters="filters" @get-filters="updateFilters"></map-filter>
    @else
        <map-filter v-if="isMapFilterShown" ref="mapfilter" :filters="filters" @get-filters="updateFilters"></map-filter>
    @endif
    {{-- <div id="map" ref="myMap" class="map-canvas"></div> --}}
    <google-map
        ref="gmap"
        class="map-canvas {{ Auth::guard('user')->user() == null ? "map-canvas-not-login" : "" }}"
        :options="map_options"
        :center="{lat: lat, lng:lng}"
        :zoom="map_zoom"
        @zoom_changed="zoomChangeHandle($event)"
        @bounds_changed="boundsChangeHandle($event)"
        @idle="idle()"
        @center_changed="updateLatLng($event)"
        @StreetViewPanorama.visible_changed="streetView()"
    >
        <template v-if="marked_areas.length > 0">
            <google-marker
                v-for="(m, index) in marked_areas"
                :key="index"
                :position = "{lat: m.lat, lng: m.lng }"
                :icon = "m.icon"
                :label = "m.label"
                :clickable="true"
                :draggable="false"
                @click="markerClick(m)"
            >
            </google-marker>
        </template>
    </google-map>

    <transition name="fade" mode="out-in">
        <template v-if="isTooFarToastShowed">
            <div class="map-toast">
                <div class="content-toast">
                    物件は縮尺が500m以下で表示されます。
                </div>
            </div>
        </template>
    </transition>

</div>
@push('vue-scripts')

@if(Auth::guard('user')->user())
    @relativeInclude('filter')
    <script> @minify
        // ----------------------------------------------------------------------
        // If access by login user
        // ----------------------------------------------------------------------
        // ----------------------------------------------------------------------
        // Google maps vue references
        // https://www.npmjs.com/package/vue2-google-maps
        // https://github.com/xkjyeah/vue-google-maps/tree/8d6bbac96b0797cf1e5b9537d58920c23ba75bd2/examples
        // send fucntion to child component
        // https://michaelnthiessen.com/pass-function-as-prop/
        // ----------------------------------------------------------------------
        (function( $, io, document, window, undefined ){
            // ----------------------------------------------------------------------
            // Vue router
            // ----------------------------------------------------------------------
            router = {
                mode: 'history',
                routes: [{
                    name: 'index', path:'/map' ,
                    component: { template: '<div/>' }
                }]
            };
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // Vuex store - Centralized data
            // ----------------------------------------------------------------------
            store = {
                // ------------------------------------------------------------------
                // Reactive central data
                // ------------------------------------------------------------------
                state: function(){},
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Updating state data will need to go through these mutations
                // ------------------------------------------------------------------
                mutations: {}
                // ------------------------------------------------------------------
            };
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // Vue mixin
            // ----------------------------------------------------------------------
            mixin = {
                // ------------------------------------------------------------------
                // Reactive data
                // ------------------------------------------------------------------
                data: function(){
                    return {
                        // ------------------------------------------------------------------
                        // create reactive data for map option, lat, long, and logged in customer
                        // ------------------------------------------------------------------
                        map_options : {
                            zoomControl: true,
                            mapTypeControl: true,
                            scaleControl: true,
                            clickableIcons: false,
                            streetViewControl: false
                        },
                        lat: this.$route.query.lat ? Number(this.$route.query.lat) : (@json($lat) !== 0 ? Number(@json($lat)) : @json(config('const.map_default.lat')) ),
                        lng: this.$route.query.lng ? Number(this.$route.query.lng) : (@json($lng) !== 0 ? Number(@json($lng)) : @json(config('const.map_default.lng')) ),
                        map_bounds: null,
                        logged_in_customer: @json($customer),
                        first_getMarker  : true,
                        is_serach_push  : false,
                        serach_text:"",
                        // ------------------------------------------------------------------
                        // create reactive data for list area showed on map
                        // ------------------------------------------------------------------
                        marked_areas: [],
                        // ------------------------------------------------------------------
                        // create reactive data for data in map search box
                        // ------------------------------------------------------------------
                        searchBox: null,
                        // ------------------------------------------------------------------
                        // create object for filter
                        // ------------------------------------------------------------------
                        isMapFilterShown: true,
                        filters: {
                            // ------------------------------------------------------------------
                            // -> check if in route query has value
                            // -> check if from controller has value (from desired area town)
                            // -> default value
                            // ------------------------------------------------------------------
                            lat: this.$route.query.lat ? Number(this.$route.query.lat) : (@json($lat) !== 0 ? Number(@json($lat)) : @json(config('const.map_default.lat'))),
                            lng: this.$route.query.lng ? Number(this.$route.query.lng) : (@json($lng) !== 0 ? Number(@json($lng)) : @json(config('const.map_default.lng'))),
                            // ------------------------------------------------------------------
                            // -> check if in route query has value
                            // -> check if from controller has value (from logged in customer)
                            // ------------------------------------------------------------------
                            minimum_price: this.$route.query.minimum_price ? Number(this.$route.query.minimum_price) : Number(@json(toMan($customer->minimum_price))),
                            maximum_price: this.$route.query.maximum_price ? Number(this.$route.query.maximum_price) : Number(@json(toMan($customer->maximum_price))),
                            minimum_land_area: this.$route.query.minimum_land_area ? Number(this.$route.query.minimum_land_area) : Number(@json(toTsubo($customer->minimum_land_area))),
                            maximum_land_area: this.$route.query.maximum_land_area ? Number(this.$route.query.maximum_land_area) : Number(@json(toTsubo($customer->maximum_land_area))),
                            // ------------------------------------------------------------------
                            // -> check if in route query has value
                            // -> default value
                            // ------------------------------------------------------------------
                            north: this.$route.query.north ? this.$route.query.north : 0,
                            east: this.$route.query.east ? this.$route.query.east : 0,
                            south: this.$route.query.south ? this.$route.query.south : 0,
                            west: this.$route.query.west ? this.$route.query.west : 0,

                            zoom_point: this.$route.query.zoom_point ? Number(this.$route.query.zoom_point): Number(14)
                        },
                        // ------------------------------------------------------------------
                        // get search location value
                        // ------------------------------------------------------------------
                        search_filter : '',
                        // ------------------------------------------------------------------
                        // add map zoom reactive data
                        // ------------------------------------------------------------------
                        map_zoom: this.$route.query.zoom_point ?  Number(this.$route.query.zoom_point): Number(14),
                        // time:null,
                        isTooFarToastShowed: this.$route.query.zoom_point == undefined || Number(this.$route.query.zoom_point) >= 14 ? false : true,
                    }
                },
                // ------------------------------------------------------------------
                // Methods
                // ------------------------------------------------------------------
                methods: {
                    streetView: function(){
                    alert("aaa");
                    },
                    // ------------------------------------------------------------------
                    // when mouse dragged, update it's lat and lng
                    // ------------------------------------------------------------------
                    updateLatLng: function(event){
                        this.filters.lat = event.lat();
                        this.filters.lng = event.lng();
                    },
                    // ------------------------------------------------------------------
                    // if page is idle, update router, it will trigger watcher and execute
                    // get marker
                    // ------------------------------------------------------------------
                    // idle: function(){
                    //     this.time = setTimeout( () => this.$router.push({ name: 'index', query: this.filters }).catch(function(){}), 500);
                    // },
                    idle: io.debounce( function(){
                        this.$router.replace({ name: 'index', query: this.filters }).catch(function(){});
                    }, 500 ),
                    // ------------------------------------------------------------------
                    // zoom handle, update zoom reactive data
                    // ------------------------------------------------------------------
                    zoomChangeHandle: function(event){
                        this.map_zoom = event;
                        this.filters.zoom_point = event;
                        // ----------------------------------------------------------
                        // show too far toast message when zoom is < 14
                        // ----------------------------------------------------------
                        if( event < 14 ){
                            this.isTooFarToastShowed = true;
                            this.showTooFarToast();
                        } else {
                            this.isTooFarToastShowed = false;
                        }
                        // ----------------------------------------------------------
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // too far toast handle
                    // ------------------------------------------------------------------
                    showTooFarToast: function(){
                        if( this.isTooFarToastShowed ) {
                            // ---------------------------------------------------------
                            // empty the marker immediately
                            // ---------------------------------------------------------
                            if( !this.$route.query.zoom_point == undefined || this.$route.query.zoom_point == undefined < 14 ){
                                this.marked_areas = [];
                            }
                            // ---------------------------------------------------------

                            // ----------------------------------------------------------
                            // give time to get rid the toast
                            // ----------------------------------------------------------
                            //setTimeout( () => this.isTooFarToastShowed = false, 5000);
                            // ----------------------------------------------------------
                        }
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // bounds handle, update map bounds reactive data
                    // ------------------------------------------------------------------
                    boundsChangeHandle: function(event){
                        // clearTimeout(this.time);
                        this.map_bounds = event;
                        // ------------------------------------------------------------------
                        // also update filters north, east, south, and west (direction) data
                        // ------------------------------------------------------------------
                        if(this.map_bounds){
                            this.filters.north = this.map_bounds.getNorthEast().lat();
                            this.filters.east = this.map_bounds.getNorthEast().lng();
                            this.filters.south = this.map_bounds.getSouthWest().lat();
                            this.filters.west = this.map_bounds.getSouthWest().lng();
                        }

                    },
                    // ------------------------------------------------------------------
                    // change place on search, update lat, lng
                    // ------------------------------------------------------------------
                    setPlace(place) {
                        this.searchBox = place;
                        this.serach_text = place.formatted_address;

                        this.lat = this.searchBox.geometry.location.lat();
                        this.lng = this.searchBox.geometry.location.lng();
                        // ------------------------------------------------------------------
                        // also update filters lat and lng data, directions are upadated
                        // automatically from @bounds_change listener
                        // ------------------------------------------------------------------
                        this.filters.lat = this.lat;
                        this.filters.lng = this.lng;
                        this.map_zoom = 15;
                        this.is_serach_push = true;
                    },
                    // ------------------------------------------------------------------
                    // get list area from API then render each data as a marker with throttling by 100ms
                    // ------------------------------------------------------------------
                    getMarker: function(){
                        this.lat = this.filters.lat;
                        this.lng = this.filters.lng;
                        // ------------------------------------------------------------------
                        // if zoom point >= 14 get the area request
                        // ------------------------------------------------------------------
                        if(this.map_zoom >= 14){

                            const url =  @json(route('api.propertylist'));

                            const data = {
                                cust_id: this.logged_in_customer.id,
                                north: this.filters.north,
                                east: this.filters.east,
                                south: this.filters.south,
                                west: this.filters.west,
                                is_serach_push :this.is_serach_push,
                                serachText : this.serach_text,
                                uuid : window.deviceUuid.DeviceUUID().get(),

                                // search condition
                                min_landArea : Number(this.filters.minimum_land_area),
                                max_landArea : Number(this.filters.maximum_land_area),
                                min_price : Number(this.filters.minimum_price),
                                max_price : Number(this.filters.maximum_price),
                            };

                            const self = this;
                            var request = axios.post(url,data);
                            this.is_serach_push = false;
                            request.then( function(response){
                                // ------------------------------------------------------------------
                                // call function to create maker for each response data
                                // ------------------------------------------------------------------
                                self.createMarker(response.data);

                            });
                        } else {
                            this.marked_areas = [];
                        }
                    },
                    // ------------------------------------------------------------------
                    // create marker for each response data
                    // ------------------------------------------------------------------
                    createMarker: function(markerData){
                        // ------------------------------------------------------------------
                        // check if there is data for marker ( from server response )
                        // ------------------------------------------------------------------
                        if(markerData) {

                            // ------------------------------------------------------------------
                            // create new array for marker that going to be pushed to current marker list
                            // ------------------------------------------------------------------
                            let new_marker = [];
                            // ------------------------------------------------------------------

                            // ------------------------------------------------------------------
                            // check if there is marked area displyaed
                            // ------------------------------------------------------------------
                            if(this.marked_areas.length > 0){

                                // ------------------------------------------------------------------
                                // get town id form each displayed marked area and area from server
                                // ------------------------------------------------------------------
                                let markerData_id = markerData.map( area => area.town.id);
                                let marked_areas_id = this.marked_areas.map( area => area.town.id);
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // array id for marker that going to be deleted from marker list
                                // ------------------------------------------------------------------
                                const deleted_marker_ids = io.difference( marked_areas_id, markerData_id);
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // delete item on current displayed area for each id that match with deleted_marker_ids
                                // ------------------------------------------------------------------
                                deleted_marker_ids.map((item) => {
                                    this.marked_areas.map((area) => {
                                        if(area.town.id === item){
                                            this.marked_areas.splice(this.marked_areas.indexOf(area), 1);
                                        };
                                    });
                                });
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // check duplicate id from displayed area and area from server
                                // ------------------------------------------------------------------
                                this.marked_areas.map( area => {
                                    markerData.map( item => {
                                        if( area.town.id === item.town.id ){
                                            // ------------------------------------------------------------------
                                            // check the mathced area has different label
                                            // case : default filter show '2' on label (from property count)
                                            // then do the filtering again, if there is any different property count
                                            // the label should be changed as well
                                            // ------------------------------------------------------------------
                                            if( area.label.text !=  item.label ){
                                                // ------------------------------------------------------------------
                                                // delete item from array, then push the new one from server
                                                // ------------------------------------------------------------------
                                                this.marked_areas.splice(this.marked_areas.indexOf(area), 1);
                                                new_marker.push(item);
                                                // ------------------------------------------------------------------
                                            }
                                            // ------------------------------------------------------------------
                                        }
                                    });
                                });
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // array id for marker that going to be pushed to marker list
                                // ------------------------------------------------------------------
                                new_marker_ids = io.difference( markerData_id, marked_areas_id );
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // if any, push each item that matched with array id of new_marker_ids
                                // ------------------------------------------------------------------
                                if(new_marker_ids.length > 0){
                                    new_marker_ids.map( id => {
                                        markerData.map( item => {
                                            if( item.town.id === id){
                                                new_marker.push(item);
                                            };
                                        })
                                    });
                                }
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // clear the id array, for next usage
                                // ------------------------------------------------------------------
                                marked_areas_id = [];
                                markerData_id = [];
                                // ------------------------------------------------------------------
                            }
                            // ------------------------------------------------------------------
                            // if there isn't any displayed area, change the new marker value with area from server
                            // ------------------------------------------------------------------
                            else{ new_marker = markerData; }
                            // ------------------------------------------------------------------

                            // ------------------------------------------------------------------
                            // if there is new area, create the marker
                            // ------------------------------------------------------------------
                            if(new_marker){
                                new_marker.map(item => {
                                    // ------------------------------------------------------------------
                                    // update item lat and lng format to number, because marker element
                                    // only accept number
                                    // ------------------------------------------------------------------
                                    item.lat = Number(item.lat);
                                    item.lng = Number(item.lng);
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // create marker label
                                    // ------------------------------------------------------------------
                                    if(item.browsed){
                                        var markerLabel = {
                                            color: "#676767",
                                            fontFamily: "Arial",
                                            fontSize: "20px",
                                            fontWeight: "bold",
                                            text: String(item.label),
                                        };
                                    }
                                    else{
                                        var markerLabel = {
                                            color: "#FF5A50", // "#da380c",
                                            fontFamily: "Arial",
                                            fontSize: "20px",
                                            fontWeight: "bold",
                                            text: String(item.label),
                                        };
                                    }
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // condition for marker image
                                    // There are 8 type image by all conditions 2(new) * 2(fav) * 2(area)
                                    // ------------------------------------------------------------------
                                    // if customer already browsed all property of that area.
                                    if (item.new && item.fav == false && item.area == false) {

                                        var imageUrl = "frontend/assets/images/bg_plot_new.png";
                                        if(item.browsed){
                                            imageUrl = "frontend/assets/images/bg_browsed_plot_new.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(66, 40),
                                            labelOrigin: new google.maps.Point(47,20)
                                        };
                                    }
                                    // if marker data is fav area only
                                    else if (item.area && item.new == false && item.fav == false) {

                                        var imageUrl = "frontend/assets/images/bg_plot_fav_area.png";
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed_plot_fav_area.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(46, 40),
                                            labelOrigin: new google.maps.Point(18,20)
                                        };
                                    }
                                    // if marker data is fav only
                                    else if (item.fav && item.new == false && item.area == false) {

                                        var imageUrl = "frontend/assets/images/bg_plot_fav_property.png";
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed_plot_fav_property.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(46, 40),
                                            labelOrigin: new google.maps.Point(18,20)
                                        };
                                    }
                                    // if marker data is fav and new
                                    else if (item.fav && item.new && item.area == false) {

                                        var imageUrl = "frontend/assets/images/bg_plot_fav_property_new.png";
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed_plot_fav_property_new.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(72, 42),
                                            labelOrigin: new google.maps.Point(44,22)
                                        };
                                    }
                                    // if marker data is area and new
                                    else if (item.area && item.new && item.fav == false) {

                                        var imageUrl = "frontend/assets/images/bg_plot_fav_area_new.png";
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed_plot_fav_area_new.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(70, 40),
                                            labelOrigin: new google.maps.Point(42,20)
                                        };
                                    }
                                    // if marker data is area and fav
                                    else if (item.area && item.new == false && item.fav) {

                                        var imageUrl = "frontend/assets/images/bg_plot_full.png";
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed_plot_full.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(50, 40),
                                            labelOrigin: new google.maps.Point(19,20)
                                        };
                                    }
                                    // if marker data is area, fav, and new
                                    else if (item.area && item.new && item.fav) {

                                        var imageUrl = "frontend/assets/images/bg_plot_full_new.png";
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed_plot_full_new.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(70, 40),
                                            labelOrigin: new google.maps.Point(42,20)
                                        };
                                    }
                                    // if marker data are all false
                                    else {

                                        var imageUrl = 'frontend/assets/images/bg_plot.png';
                                        if(item.browsed){
                                            imageUrl =  "frontend/assets/images/bg_browsed.png";
                                        }

                                        var image = {
                                            url: imageUrl,
                                            scaledSize: new google.maps.Size(37, 35),
                                            labelOrigin: new google.maps.Point(18,18)
                                        };
                                    }
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // add data to item object
                                    // ------------------------------------------------------------------
                                    item.icon = image;
                                    item.label = markerLabel;
                                    item.section_id = String(item.section_id);
                                    item.title =  String(item.name);
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // then push to array (then it will be rendered as a marker)
                                    // ------------------------------------------------------------------
                                    this.marked_areas.push(item);
                                    // ------------------------------------------------------------------
                                });
                            }
                        }
                        // ------------------------------------------------------------------
                        // if there is no data from server, empty array of the displayed area
                        // ------------------------------------------------------------------
                        else { this.marked_areas = []; }
                        // ------------------------------------------------------------------
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // update filters
                    // ------------------------------------------------------------------
                    updateFilters: function(){

                        this.getMarker();

                        const data = {
                            customers_id : this.logged_in_customer.id,
                            //location : this.search_filter,
                            location : this.serach_text,
                            minimum_land_area : Number(this.filters.minimum_land_area),
                            maximum_land_area : Number(this.filters.maximum_land_area),
                            minimum_price : Number(this.filters.minimum_price),
                            maximum_price : Number(this.filters.maximum_price),
                        };

                        const url = @json(route('api.customer_search_history.post'));
                        if(this.logged_in_customer.not_leave_record == false){
                            var request = axios.post(url,data);
                            request.then( function(response){});
                        }
                    },
                    // ------------------------------------------------------------------
                    // marker on click
                    // ------------------------------------------------------------------
                    markerClick: function(marker){
                        // --------------------------------------------------------------
                        // property list URL
                        // --------------------------------------------------------------
                        const propertyUrl = @json(route('frontend.property.list'));
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // marker location prefecture + city + town
                        // --------------------------------------------------------------
                        const location = `?location=${marker.town.city.prefecture.name}${marker.town.city.name}${marker.town.name}`;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // Search Condition
                        // --------------------------------------------------------------
                        const minPrice = this.filters.minimum_price < @json( toMan($lowest_price_filter) ) ? null : this.filters.minimum_price;
                        const maxPrice = this.filters.maximum_price > @json( toMan($highest_price_filter) ) ? null : this.filters.maximum_price;
                        const minLandArea = this.filters.minimum_land_area < @json( toTsubo($lowest_land_area_filter) ) ? null : this.filters.minimum_land_area;
                        const maxLandArea = this.filters.maximum_land_area > @json( toTsubo($highest_land_area_filter) ) ? null : this.filters.maximum_land_area;
                        const searchCondition = `&minimum_price=${minPrice}&maximum_price=${maxPrice}&minimum_land_area=${minLandArea}&maximum_land_area=${maxLandArea}`;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // construct URL with location and search condition as query string
                        // --------------------------------------------------------------
                        const url = `${propertyUrl}${location}${searchCondition}`;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        window.location.href = url;
                        // --------------------------------------------------------------
                    },
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Watchers
                // ------------------------------------------------------------------
                watch: {
                    // --------------------------------------------------------------
                    // Watch the route changes
                    // This will run everytime the route is changing
                    // It runs immediately after page load
                    // --------------------------------------------------------------
                    $route: {
                        immediate: true,
                        handler: function( to, from ){
                            // ------------------------------------------------------------------
                            // update filters with route query filter
                            // ------------------------------------------------------------------
                            this.filters.lat = this.$route.query.lat ? Number(this.$route.query.lat) : (@json($lat) !== 0 ? Number(@json($lat)) : @json(config('const.map_default.lat')));
                            this.filters.lng = this.$route.query.lng ? Number(this.$route.query.lng) : (@json($lng) !== 0 ? Number(@json($lng)) : @json(config('const.map_default.lng')));

                            this.filters.minimum_price = this.$route.query.minimum_price ? Number(this.$route.query.minimum_price) : ( Number(@json(toMan($customer->minimum_price))) == 0 ? Number(@json(toMan($lowest_price_filter))) - @json(config('const.man_price_step')) : Number(@json(toMan($customer->minimum_price))));
                            this.filters.maximum_price = this.$route.query.maximum_price ? Number(this.$route.query.maximum_price) : ( Number(@json(toMan($customer->maximum_price))) == 0 ? Number(@json(toMan($highest_price_filter))) + @json(config('const.man_price_step')) : Number(@json(toMan($customer->maximum_price))));
                            this.filters.minimum_land_area = this.$route.query.minimum_land_area ? Number(this.$route.query.minimum_land_area) : ( Number(@json(toTsubo($customer->minimum_land_area))) == 0 ? Number(@json(toTsubo($lowest_land_area_filter))) - @json(config('const.tsubo_area_step')) : Number(@json(toTsubo($customer->minimum_land_area))));
                            this.filters.maximum_land_area = this.$route.query.maximum_land_area ? Number(this.$route.query.maximum_land_area) : ( Number(@json(toTsubo($customer->maximum_land_area))) == 0 ? Number(@json(toTsubo($highest_land_area_filter))) + @json(config('const.tsubo_area_step')) : Number(@json(toTsubo($customer->maximum_land_area))));

                            this.filters.north = Number(this.$route.query.north) ? this.$route.query.north : 0 ;
                            this.filters.east = Number(this.$route.query.east) ? this.$route.query.east : 0 ;
                            this.filters.south = Number(this.$route.query.south) ? this.$route.query.south : 0 ;
                            this.filters.west = Number(this.$route.query.west) ? this.$route.query.west : 0 ;

                            this.filters.zoom_point = Number(this.$route.query.zoom_point) ? Number(this.$route.query.zoom_point) : Number(14);
                            // ------------------------------------------------------------------
                            // update lat and lng with route query filter (for render map)
                            // ------------------------------------------------------------------
                            this.lat = Number(this.$route.query.lat);
                            this.lng = Number(this.$route.query.lng);
                            // ------------------------------------------------------------------
                            // call update filters method, to update customer newest search history
                            // ------------------------------------------------------------------
                            this.updateFilters();
                            localStorage.setItem('mapQueryParams', new URLSearchParams(this.$route.query).toString());

                        }
                    }
                },
                created(){
                    //this.updateFilters();
                },
                // ------------------------------------------------------------------
                mounted(){
                    this.$refs.gmap.$mapPromise.then((map) => {
                        let thePanorama = map.getStreetView();
                        google.maps.event.addListener(thePanorama, 'visible_changed',
                            () => {
                                if (thePanorama.getVisible()) {
                                    this.isMapFilterShown = false;
                                } else {
                                    this.isMapFilterShown = true;
                                }
                            }
                        );
                    });
                }
            };
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
        }( jQuery, _, document, window ));
    @endminify </script>
@else
    @relativeInclude('filter')
        <script> @minify
        // ----------------------------------------------------------------------
        // If access by users not logged in (anonymous user)
        // ----------------------------------------------------------------------
        // ----------------------------------------------------------------------
        // Google maps vue references
        // https://www.npmjs.com/package/vue2-google-maps
        // https://github.com/xkjyeah/vue-google-maps/tree/8d6bbac96b0797cf1e5b9537d58920c23ba75bd2/examples
        // send fucntion to child component
        // https://michaelnthiessen.com/pass-function-as-prop/
        // ----------------------------------------------------------------------
        (function( $, io, document, window, undefined ){
            // ----------------------------------------------------------------------
            // Vue router
            // ----------------------------------------------------------------------
            router = {
                mode: 'history',
                routes: [{
                    name: 'index', path:'/map' ,
                    component: { template: '<div/>' }
                }]
            };
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // Vuex store - Centralized data
            // ----------------------------------------------------------------------
            store = {
                // ------------------------------------------------------------------
                // Reactive central data
                // ------------------------------------------------------------------
                state: function(){},
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Updating state data will need to go through these mutations
                // ------------------------------------------------------------------
                mutations: {}
                // ------------------------------------------------------------------
            };
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
            // Vue mixin
            // ----------------------------------------------------------------------
            mixin = {
                // ------------------------------------------------------------------
                // Reactive data
                // ------------------------------------------------------------------
                data: function(){
                    return {
                        // ------------------------------------------------------------------
                        // create reactive data for map option, lat, long, and logged in customer
                        // ------------------------------------------------------------------
                        map_options : {
                            zoomControl: true,
                            mapTypeControl: true,
                            scaleControl: true,
                            clickableIcons: false,
                            streetViewControl: false
                        },
                        lat: this.$route.query.lat ? Number(this.$route.query.lat) : (@json($lat) !== 0 ? Number(@json($lat)) : @json(config('const.map_default.lat')) ),
                        lng: this.$route.query.lng ? Number(this.$route.query.lng) : (@json($lng) !== 0 ? Number(@json($lng)) : @json(config('const.map_default.lng')) ),
                        map_bounds: null,
                        first_getMarker  : true,
                        is_serach_push  : false,
                        serach_text:"",
                        // ------------------------------------------------------------------
                        // create reactive data for list area showed on map
                        // ------------------------------------------------------------------
                        marked_areas: [],
                        // ------------------------------------------------------------------
                        // create reactive data for data in map search box
                        // ------------------------------------------------------------------
                        searchBox: null,
                        // ------------------------------------------------------------------
                        // create object for filter
                        // ------------------------------------------------------------------
                        isMapFilterShown: true,
                        filters: {
                            // ------------------------------------------------------------------
                            // -> check if in route query has value
                            // -> check if from controller has value (from desired area town)
                            // -> default value
                            // ------------------------------------------------------------------
                            lat: this.$route.query.lat ? Number(this.$route.query.lat) : (@json($lat) !== 0 ? Number(@json($lat)) : @json(config('const.map_default.lat'))),
                            lng: this.$route.query.lng ? Number(this.$route.query.lng) : (@json($lng) !== 0 ? Number(@json($lng)) : @json(config('const.map_default.lng'))),
                            // ------------------------------------------------------------------
                            // -> check if in route query has value
                            // -> check if from controller has value (from logged in customer)
                            // ------------------------------------------------------------------
                            minimum_price:  Number(this.$route.query.minimum_price),
                            maximum_price:  Number(this.$route.query.maximum_price),
                            minimum_land_area:  Number(this.$route.query.minimum_land_area),
                            maximum_land_area:  Number(this.$route.query.maximum_land_area),
                            // ------------------------------------------------------------------
                            // -> check if in route query has value
                            // -> default value
                            // ------------------------------------------------------------------
                            north: this.$route.query.north ? this.$route.query.north : 0,
                            east: this.$route.query.east ? this.$route.query.east : 0,
                            south: this.$route.query.south ? this.$route.query.south : 0,
                            west: this.$route.query.west ? this.$route.query.west : 0,

                            zoom_point: this.$route.query.zoom_point ? Number(this.$route.query.zoom_point): Number(14)
                        },
                        // ------------------------------------------------------------------
                        // get search location value
                        // ------------------------------------------------------------------
                        search_filter : '',
                        // ------------------------------------------------------------------
                        // add map zoom reactive data
                        // ------------------------------------------------------------------
                        map_zoom: this.$route.query.zoom_point ?  Number(this.$route.query.zoom_point): Number(14),
                        // time:null,
                        isTooFarToastShowed: this.$route.query.zoom_point == undefined || Number(this.$route.query.zoom_point) >= 14 ? false : true,
                    }
                },
                // ------------------------------------------------------------------
                // Methods
                // ------------------------------------------------------------------
                methods: {
                    streetView: function(){
                    alert("aaa");
                    },
                    // ------------------------------------------------------------------
                    // when mouse dragged, update it's lat and lng
                    // ------------------------------------------------------------------
                    updateLatLng: function(event){
                        this.filters.lat = event.lat();
                        this.filters.lng = event.lng();
                    },
                    // ------------------------------------------------------------------
                    // if page is idle, update router, it will trigger watcher and execute
                    // get marker
                    // ------------------------------------------------------------------
                    // idle: function(){
                    //     this.time = setTimeout( () => this.$router.push({ name: 'index', query: this.filters }).catch(function(){}), 500);
                    // },
                    idle: io.debounce( function(){
                        this.$router.replace({ name: 'index', query: this.filters }).catch(function(){});
                    }, 500 ),
                    // ------------------------------------------------------------------
                    // zoom handle, update zoom reactive data
                    // ------------------------------------------------------------------
                    zoomChangeHandle: function(event){
                        this.map_zoom = event;
                        this.filters.zoom_point = event;
                        // ----------------------------------------------------------
                        // show too far toast message when zoom is < 14
                        // ----------------------------------------------------------
                        if( event < 14 ){
                            this.isTooFarToastShowed = true;
                            this.showTooFarToast();
                        } else {
                            this.isTooFarToastShowed = false;
                        }
                        // ----------------------------------------------------------
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // too far toast handle
                    // ------------------------------------------------------------------
                    showTooFarToast: function(){
                        if( this.isTooFarToastShowed ) {
                            // ---------------------------------------------------------
                            // empty the marker immediately
                            // ---------------------------------------------------------
                            if( !this.$route.query.zoom_point == undefined || this.$route.query.zoom_point == undefined < 14 ){
                                this.marked_areas = [];
                            }
                            // ---------------------------------------------------------

                            // ----------------------------------------------------------
                            // give time to get rid the toast
                            // ----------------------------------------------------------
                            //setTimeout( () => this.isTooFarToastShowed = false, 5000);
                            // ----------------------------------------------------------
                        }
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // bounds handle, update map bounds reactive data
                    // ------------------------------------------------------------------
                    boundsChangeHandle: function(event){
                        // clearTimeout(this.time);
                        this.map_bounds = event;
                        // ------------------------------------------------------------------
                        // also update filters north, east, south, and west (direction) data
                        // ------------------------------------------------------------------
                        if(this.map_bounds){
                            this.filters.north = this.map_bounds.getNorthEast().lat();
                            this.filters.east = this.map_bounds.getNorthEast().lng();
                            this.filters.south = this.map_bounds.getSouthWest().lat();
                            this.filters.west = this.map_bounds.getSouthWest().lng();
                        }

                    },
                    // ------------------------------------------------------------------
                    // change place on search, update lat, lng
                    // ------------------------------------------------------------------
                    setPlace(place) {
                        this.searchBox = place;
                        this.serach_text = place.formatted_address;

                        this.lat = this.searchBox.geometry.location.lat();
                        this.lng = this.searchBox.geometry.location.lng();
                        // ------------------------------------------------------------------
                        // also update filters lat and lng data, directions are upadated
                        // automatically from @bounds_change listener
                        // ------------------------------------------------------------------
                        this.filters.lat = this.lat;
                        this.filters.lng = this.lng;
                        this.map_zoom = 15;
                        this.is_serach_push = true;
                    },
                    // ------------------------------------------------------------------
                    // get list area from API then render each data as a marker with throttling by 100ms
                    // ------------------------------------------------------------------
                    getMarker: function(){
                        this.lat = this.filters.lat;
                        this.lng = this.filters.lng;

                        // ------------------------------------------------------------------
                        // if zoom point >= 14 get the area request
                        // ------------------------------------------------------------------
                        if(this.map_zoom >= 14){

                            const url =  @json(route('api.propertylist'));

                            const data = {
                                // cust_id: this.logged_in_customer.id,
                                north: this.filters.north,
                                east: this.filters.east,
                                south: this.filters.south,
                                west: this.filters.west,
                                is_serach_push :this.is_serach_push,
                                serachText : this.serach_text,
                                uuid : window.deviceUuid.DeviceUUID().get(),

                                // search condition
                                min_landArea : Number(this.filters.minimum_land_area),
                                max_landArea : Number(this.filters.maximum_land_area),
                                min_price : Number(this.filters.minimum_price),
                                max_price : Number(this.filters.maximum_price),
                            };

                            const self = this;
                            var request = axios.post(url,data);
                            this.is_serach_push = false;
                            request.then( function(response){
                                // ------------------------------------------------------------------
                                // call function to create maker for each response data
                                // ------------------------------------------------------------------
                                self.createMarker(response.data);

                            });
                        } else {
                            this.marked_areas = [];
                        }
                    },
                    // ------------------------------------------------------------------
                    // create marker for each response data
                    // ------------------------------------------------------------------
                    createMarker: function(markerData){
                        // ------------------------------------------------------------------
                        // check if there is data for marker ( from server response )
                        // ------------------------------------------------------------------
                        if(markerData) {

                            // ------------------------------------------------------------------
                            // create new array for marker that going to be pushed to current marker list
                            // ------------------------------------------------------------------
                            let new_marker = [];
                            // ------------------------------------------------------------------

                            // ------------------------------------------------------------------
                            // check if there is marked area displyaed
                            // ------------------------------------------------------------------
                            if(this.marked_areas.length > 0){

                                // ------------------------------------------------------------------
                                // get town id form each displayed marked area and area from server
                                // ------------------------------------------------------------------
                                let markerData_id = markerData.map( area => area.town.id);
                                let marked_areas_id = this.marked_areas.map( area => area.town.id);
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // array id for marker that going to be deleted from marker list
                                // ------------------------------------------------------------------
                                const deleted_marker_ids = io.difference( marked_areas_id, markerData_id);
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // delete item on current displayed area for each id that match with deleted_marker_ids
                                // ------------------------------------------------------------------
                                deleted_marker_ids.map((item) => {
                                    this.marked_areas.map((area) => {
                                        if(area.town.id === item){
                                            this.marked_areas.splice(this.marked_areas.indexOf(area), 1);
                                        };
                                    });
                                });
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // check duplicate id from displayed area and area from server
                                // ------------------------------------------------------------------
                                this.marked_areas.map( area => {
                                    markerData.map( item => {
                                        if( area.town.id === item.town.id ){
                                            // ------------------------------------------------------------------
                                            // check the mathced area has different label
                                            // case : default filter show '2' on label (from property count)
                                            // then do the filtering again, if there is any different property count
                                            // the label should be changed as well
                                            // ------------------------------------------------------------------
                                            if( area.label.text !=  item.label ){
                                                // ------------------------------------------------------------------
                                                // delete item from array, then push the new one from server
                                                // ------------------------------------------------------------------
                                                this.marked_areas.splice(this.marked_areas.indexOf(area), 1);
                                                new_marker.push(item);
                                                // ------------------------------------------------------------------
                                            }
                                            // ------------------------------------------------------------------
                                        }
                                    });
                                });
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // array id for marker that going to be pushed to marker list
                                // ------------------------------------------------------------------
                                new_marker_ids = io.difference( markerData_id, marked_areas_id );
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // if any, push each item that matched with array id of new_marker_ids
                                // ------------------------------------------------------------------
                                if(new_marker_ids.length > 0){
                                    new_marker_ids.map( id => {
                                        markerData.map( item => {
                                            if( item.town.id === id){
                                                new_marker.push(item);
                                            };
                                        })
                                    });
                                }
                                // ------------------------------------------------------------------

                                // ------------------------------------------------------------------
                                // clear the id array, for next usage
                                // ------------------------------------------------------------------
                                marked_areas_id = [];
                                markerData_id = [];
                                // ------------------------------------------------------------------
                            }
                            // ------------------------------------------------------------------
                            // if there isn't any displayed area, change the new marker value with area from server
                            // ------------------------------------------------------------------
                            else{ new_marker = markerData; }
                            // ------------------------------------------------------------------

                            // ------------------------------------------------------------------
                            // if there is new area, create the marker
                            // ------------------------------------------------------------------
                            if(new_marker){
                                new_marker.map(item => {
                                    // ------------------------------------------------------------------
                                    // update item lat and lng format to number, because marker element
                                    // only accept number
                                    // ------------------------------------------------------------------
                                    item.lat = Number(item.lat);
                                    item.lng = Number(item.lng);
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // create marker label
                                    // ------------------------------------------------------------------
                                    var markerLabel = {
                                        color: "#FF5A50",
                                        fontFamily: "Arial",
                                        fontSize: "20px",
                                        fontWeight: "bold",
                                        text: String(item.label),
                                    };
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // condition for marker image
                                    // ------------------------------------------------------------------
                                    // if customer already browsed all property of that area.
                                    if (item.browsed) {
                                        var markerLabel = {
                                            color: "#676767",
                                            fontFamily: "Arial",
                                            fontSize: "20px",
                                            fontWeight: "bold",
                                            text: String(item.label),
                                        };
                                        var image = {
                                            url: 'frontend/assets/images/bg_browsed.png',
                                            scaledSize: new google.maps.Size(37, 35),
                                        };
                                    }
                                    // if property new
                                    else if (item.new) {
                                        var image = {
                                            url: 'frontend/assets/images/bg_plot_new.png',
                                            scaledSize: new google.maps.Size(66, 40),
                                            labelOrigin: new google.maps.Point(46,20)
                                        };
                                    }
                                    else {
                                        var image = {
                                            url: 'frontend/assets/images/bg_plot.png',
                                            scaledSize: new google.maps.Size(37, 35),
                                        };
                                    }
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // add data to item object
                                    // ------------------------------------------------------------------
                                    item.icon = image;
                                    item.label = markerLabel;
                                    item.section_id = String(item.section_id);
                                    item.title =  String(item.name);
                                    // ------------------------------------------------------------------

                                    // ------------------------------------------------------------------
                                    // then push to array (then it will be rendered as a marker)
                                    // ------------------------------------------------------------------
                                    this.marked_areas.push(item);
                                    // ------------------------------------------------------------------
                                });
                            }
                        }
                        // ------------------------------------------------------------------
                        // if there is no data from server, empty array of the displayed area
                        // ------------------------------------------------------------------
                        else { this.marked_areas = []; }
                        // ------------------------------------------------------------------
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // update filters
                    // ------------------------------------------------------------------
                    updateFilters: function(){
                        this.getMarker();
                    },
                    // ------------------------------------------------------------------
                    // marker on click
                    // ------------------------------------------------------------------
                    markerClick: function(marker){
                        // console.log( marker );
                        // --------------------------------------------------------------
                        // property list URL
                        // --------------------------------------------------------------
                        const propertyUrl = @json(route('frontend.property.list'));
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // marker location prefecture + city + town
                        // --------------------------------------------------------------
                        const location = `?location=${marker.town.city.prefecture.name}${marker.town.city.name}${marker.town.name}`;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // Search Condition
                        // --------------------------------------------------------------
                        const minPrice = this.filters.minimum_price < @json( toMan($lowest_price_filter) ) ? null : this.filters.minimum_price;
                        const maxPrice = this.filters.maximum_price > @json( toMan($highest_price_filter) ) ? null : this.filters.maximum_price;
                        const minLandArea = this.filters.minimum_land_area < @json( toTsubo($lowest_land_area_filter) ) ? null : this.filters.minimum_land_area;
                        const maxLandArea = this.filters.maximum_land_area > @json( toTsubo($highest_land_area_filter) ) ? null : this.filters.maximum_land_area;
                        const searchCondition = `&minimum_price=${minPrice}&maximum_price=${maxPrice}&minimum_land_area=${minLandArea}&maximum_land_area=${maxLandArea}`;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // construct URL with location and search condition as query string
                        // --------------------------------------------------------------
                        const url = `${propertyUrl}${location}${searchCondition}`;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        window.location.href = url;
                        // --------------------------------------------------------------
                    },
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Watchers
                // ------------------------------------------------------------------
                watch: {
                    // --------------------------------------------------------------
                    // Watch the route changes
                    // This will run everytime the route is changing
                    // It runs immediately after page load
                    // --------------------------------------------------------------
                    $route: {
                        immediate: true,
                        handler: function( to, from ){
                            // ------------------------------------------------------------------
                            // update filters with route query filter
                            // ------------------------------------------------------------------
                            this.filters.lat = this.$route.query.lat ? Number(this.$route.query.lat) : (@json($lat) !== 0 ? Number(@json($lat)) : @json(config('const.map_default.lat')));
                            this.filters.lng = this.$route.query.lng ? Number(this.$route.query.lng) : (@json($lng) !== 0 ? Number(@json($lng)) : @json(config('const.map_default.lng')));

                            this.filters.minimum_price = this.$route.query.minimum_price ? Number(this.$route.query.minimum_price) : Number(@json(toMan($lowest_price_filter))) - @json(config('const.man_price_step'));
                            this.filters.maximum_price = this.$route.query.maximum_price ? Number(this.$route.query.maximum_price) : Number(@json(toMan($highest_price_filter))) + @json(config('const.man_price_step'));
                            this.filters.minimum_land_area = this.$route.query.minimum_land_area ? Number(this.$route.query.minimum_land_area) : Number(@json(toTsubo($lowest_land_area_filter))) - @json(config('const.tsubo_area_step'));
                            this.filters.maximum_land_area = this.$route.query.maximum_land_area ? Number(this.$route.query.maximum_land_area) : Number(@json(toTsubo($highest_land_area_filter))) + @json(config('const.tsubo_area_step'));

                            this.filters.north = Number(this.$route.query.north) ? this.$route.query.north : 0 ;
                            this.filters.east = Number(this.$route.query.east) ? this.$route.query.east : 0 ;
                            this.filters.south = Number(this.$route.query.south) ? this.$route.query.south : 0 ;
                            this.filters.west = Number(this.$route.query.west) ? this.$route.query.west : 0 ;

                            this.filters.zoom_point = Number(this.$route.query.zoom_point) ? Number(this.$route.query.zoom_point) : Number(14);
                            // ------------------------------------------------------------------
                            // update lat and lng with route query filter (for render map)
                            // ------------------------------------------------------------------
                            this.lat = Number(this.$route.query.lat);
                            this.lng = Number(this.$route.query.lng);
                            // ------------------------------------------------------------------
                            // call update filters method, to update customer newest search history
                            // ------------------------------------------------------------------
                            this.updateFilters();
                            localStorage.setItem('mapQueryParams', new URLSearchParams(this.$route.query).toString());

                        }
                    }
                },
                created(){
                    //this.updateFilters();
                },
                // ------------------------------------------------------------------
                mounted(){
                    this.$refs.gmap.$mapPromise.then((map) => {
                        let thePanorama = map.getStreetView();
                        google.maps.event.addListener(thePanorama, 'visible_changed',
                            () => {
                                if (thePanorama.getVisible()) {
                                    this.isMapFilterShown = false;
                                } else {
                                    this.isMapFilterShown = true;
                                }
                            }
                        );
                    });
                }
            };
            // ----------------------------------------------------------------------

            // ----------------------------------------------------------------------
        }( jQuery, _, document, window ));
    @endminify </script>
@endif
@endpush
@endsection
