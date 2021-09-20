@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('content')
    {{-- A9-1 Property  Search--}}
    <div class="row border-bottom py-2">
        <div class="col-sm-12">
            ■ @lang('label.property_search')
        </div>
    </div>

    <div class="row form-group">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
            <strong class="field-title">@lang('label.property_id')</strong>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
            <input type="text" v-model="currentPropertyID"  v-on:keyup.enter="searchProperty" class="form-control" />
        </div>
    </div>

    <template v-if="loadingProperty">
        <div class="row justify-content-center py-3">
            <div class="spinner-border text-info" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </template>

    <template v-else>
        <div v-if="propertyNotFound" class="row justify-content-center py-3">
            <h4>@lang('label.property_not_found')</h4>
        </div>
    
        <div v-else>
            {{-- A9-2 Property  Detail--}}
            <div class="row border-bottom py-2 mt-3">
                <div class="col-sm-12">
                    ■ @lang('label.property_information')
                </div>
            </div>
    
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.property_id')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.id }}
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.location')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.location }}
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.selling_price')</strong>
                </div>
                <div v-if="property.minimum_price && property.maximum_price" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.minimum_price | toMan | numeral(0,0) }} ~ @{{ property.maximum_price | toMan | numeral(0,0)}}
                </div>
                <div v-else-if="property.minimum_price && property.maximum_price == null" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.minimum_price | toMan | numeral(0,0) }}
                </div>
                <div v-else-if="property.minimum_price == null && property.maximum_price" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.maximum_land_area | toMan | numeral(0,0) }}
                </div>
                <div v-else class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @lang('label.none')
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.land_area')</strong>
                </div>
                <div v-if="property.minimum_land_area && property.maximum_land_area" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.minimum_land_area | toTsubo | numeral('0,0') }} ~ @{{ property.maximum_land_area | toTsubo | numeral('0,0') }}
                </div>
                <div v-else-if="property.minimum_land_area && property.maximum_land_area == null" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.minimum_land_area | toTsubo | numeral('0,0') }}
                </div>
                <div v-else-if="property.minimum_land_area == null && property.maximum_land_area" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.maximum_land_area | toTsubo | numeral('0,0') }}
                </div>
                <div v-else class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @lang('label.none')
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.building_condition')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.building_conditions_desc ? property.building_conditions_desc : '@lang('label.none')' }}
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.property_contact_us')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.contact_us }}
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.publication_date_and_time')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.ja.publish_date }}
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.last_update_date_and_time')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    @{{ property.ja.updated_date }}
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.publication_medium')</strong>
                </div>
                <div class="col-xs-12 col-content">
                    <div class="row">
                        <a v-for="publish in property.property_publish" target="_blank" :href="publish.url" class="ml-2 col-12">
                            @{{ publish.publication_destination }}
                        </a>
                    </div>
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.photo')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    <div class="row mx-n2 mt-n3">
                        <template v-if="propertyPhotos === undefined || propertyPhotos.length > 0">
                            <div v-for="photo in propertyPhotos" :key="photo.id" class="px-2 col-lg-6 col-xl-4 mt-3">
                                <div class="ratiobox ratio--4-3">
                                    <div class="ratiobox-innerset">
                                        <img class="img-thumbnail" :src="photo.file.url.image" :alt="photo.file.name">
                                    </div>
                                </div>  
                            </div>
                        </template>
                        <div class="px-2 col-lg-6 col-xl-4 mt-3" v-else>
                            <img src="{{asset('img/backend/default.jpg')}}" class="img-thumbnail" alt="default">
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row form-group">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang('label.flyer')</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    <div class="row mx-n2 mt-n3">
                        <template v-if="propertyFlyers === undefined || propertyFlyers.length > 0">
                            <div v-for="photo in propertyFlyers" :key="photo.id" class="px-2 col-lg-6 col-xl-4 mt-3">
                                <div class="ratiobox ratio--4-3">
                                    <div class="ratiobox-innerset">
                                        <img class="img-thumbnail" :src="photo.file.url.image" :alt="photo.file.name">
                                    </div>
                                </div>  
                            </div>
                        </template>
                        <div class="px-2 col-lg-6 col-xl-4 mt-3" v-else>
                            <img src="{{asset('img/backend/default.jpg')}}" class="img-thumbnail" alt="default">
                        </div>
                    </div>
                </div>
            </div>
        
            @component('backend._components.section_header', [ 'label' => __('label.change_log')]) @endcomponent
        
            <div class="tablelike mt-2">
        
                <!-- Table header - Start -->
                <div class="tablelike-header border-top border-right d-none d-lg-block">
                    <div class="row mx-0">
                        <div class="px-0 border-left bg-light col-lg-160px"><div class="py-2 px-2">@lang('label.last_update')</div></div>
                        <div class="px-0 border-left bg-light col-lg-160px"><div class="py-2 px-2">@lang('label.update_type')</div></div>
                        <div class="px-0 border-left bg-light col-lg"><div class="py-2 px-2">@lang('label.before_update')</div></div>
                        <div class="px-0 border-left bg-light col-lg"><div class="py-2 px-2">@lang('label.after_update')</div></div>
                    </div>
                </div>
                <!-- Table header - End -->
        
        
                <!-- Table content - Start -->
                <div class="tablelike-content">
                    <div class="tablelike-result">
                        <div v-if="property.property_log_activities.length == 0" class="text-center py-2 px-2 border">
                            <span>対象のレコードはありません。</span>
                        </div>
                        <template v-else >
    
                            <div v-for="logActivity in propertyLogActivities" :key="logActivity.id" class="tablelike-item border-top border-right mt-3 mt-lg-0">
                                <div class="row mx-0">
                        
                                    <!-- last_update column - Start -->
                                    <div class="px-0 border-left col-lg-160px">
                                        <div class="row mx-0 flex-nowrap">
                                            <div class="px-0 col-100px border-right d-lg-none bg-light">
                                                <div class="py-2 px-2">@lang('label.last_update')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden text-lg-center">
                                                <div class="py-2 px-2">@{{ logActivity.ja.created_time }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email column - End -->

                                    <!-- Active status column - Start -->
                                    <div class="px-0 border-left col-md-6 col-lg-160px">
                                        <div class="border-top d-lg-none"></div>
                                        <div class="row mx-0 flex-nowrap">
                                            <div class="px-0 col-100px border-right d-lg-none bg-light">
                                                <div class="py-2 px-2">@lang('label.update_type')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ logActivity.property_scraping_type.label }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Active status column - End -->
                        
                                    <!-- Active status column - Start -->
                                    <div class="px-0 border-left col-md-6 col-lg">
                                        <div class="border-top d-lg-none"></div>
                                        <div class="row mx-0 flex-nowrap">
                                            <div class="px-0 col-100px border-right d-lg-none bg-light">
                                                <div class="py-2 px-2">@lang('label.before_update')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ logActivity.before_update_text }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Active status column - End -->
                        
                                    <!-- Active status column - Start -->
                                    <div class="px-0 border-left col-md-6 col-lg">
                                        <div class="border-top d-lg-none"></div>
                                        <div class="row mx-0 flex-nowrap">
                                            <div class="px-0 col-100px border-right d-lg-none bg-light">
                                                <div class="py-2 px-2">@lang('label.after_update')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ logActivity.after_update_text }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Active status column - End -->
                        
                                </div>
                                <div class="border-bottom d-lg-none"></div>
                            </div>
                            
                            <div class="border-bottom d-none d-lg-block"></div>
                
                        </template>
                    </div>
                </div>
                <!-- Table content - End -->
                
        
            </div>
        </div>
    </template>
@endsection

@push('vue-scripts')

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/manage/property/' + @json($id), 
                component: { template: '<div/>' }
            }]
        };
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
                    property: @json( $property ) ? @json( $property ) : null,
                    currentPropertyID: @json($id),
                    propertyNotFound: @json( $property ) ? false : true,
                    loadingProperty: false,
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted hook
            // ------------------------------------------------------------------
            mounted: function(){},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Property Photos
                // --------------------------------------------------------------
                propertyLogActivities: function() {
                    return this.property.property_log_activities;
                },
                // --------------------------------------------------------------
                // --------------------------------------------------------------
                // Property Photos
                // --------------------------------------------------------------
                propertyPhotos: function() {
                    return this.property.property_photos;
                },
                // --------------------------------------------------------------
                // --------------------------------------------------------------
                // Property Flyers
                // --------------------------------------------------------------
                propertyFlyers: function() {
                    return this.property.property_flyers;
                },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Search Property
                // --------------------------------------------------------------
                searchProperty: function(){
                    this.loadingProperty = true;
                    const data = { id: this.currentPropertyID };
                    const url = @json($search_property_url);
                    const request = axios.post(url, data);
                    const self = this;
                    request.then( function( response ){
                        // --------------------------------------------------------------
                        // create new path based on inputted property id
                        // --------------------------------------------------------------
                        const newPath = `/manage/property/${self.currentPropertyID}`;
                        // --------------------------------------------------------------
                        // check if current path is deifferent with the new created one
                        // if true ovverride the path
                        // --------------------------------------------------------------
                        if(self.$route.path != newPath){
                            self.$router.push({ path : newPath});
                        }
                        self.loadingProperty = false;
                        if(response.data.property != null) {
                            self.property = response.data.property;
                            self.propertyNotFound = false;
                        } else {
                            self.propertyNotFound = true;
                        }
                    });
                },
                // --------------------------------------------------------------
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
                        // get current router path, then get the property id
                        // ex: manage/property/2 -> get the 2 char
                        // ------------------------------------------------------------------
                        const path = this.$route.path;
                        const n = path.lastIndexOf('/');
                        const property_id = path.substring(n + 1);
                        // ------------------------------------------------------------------
                        // update currentPropertyID reactive data
                        // needed for request data on searchProperty method
                        // ------------------------------------------------------------------
                        this.currentPropertyID = property_id;
                        this.searchProperty();
                    }
                }
            },
            mounted: function(){
                if(this.currentPropertyID =="search"){
                    this.currentPropertyID = "";
                }
            }
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush