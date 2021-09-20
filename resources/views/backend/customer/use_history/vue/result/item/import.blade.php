<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.search_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.ja.access_time }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.action_name')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.label }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-240px col-xl-90px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.property_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a v-if="activity.property" :href="activity.property.url.view" target="_blank" rel="noopener noreferrer">@{{ propertyId }}</a>
                            <span v-else>@{{ propertyId }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-xl-none"></div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ propertyLocation }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-240px col-xl-140px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.selling_price')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="activity.minimum_price && activity.maximum_price" class="py-2 px-2">
                            @{{  activity.minimum_price | toMan | numeral(0,0) }} ~ @{{  activity.maximum_price | toMan | numeral(0,0) }}  
                        </div>
                        <div v-else-if="activity.minimum_price && activity.maximum_price == null" class="py-2 px-2">
                            @{{  activity.minimum_price | toMan | numeral(0,0) }}
                        </div>
                        <div v-else-if="activity.minimum_price == null && activity.maximum_price" class="py-2 px-2">
                            @{{  activity.maximum_price | toMan | numeral(0,0) }}  
                        </div>
                        <div v-else class="py-2 px-2">-</div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-xl-none"></div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-140px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.land_area')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="activity.minimum_land_area && activity.maximum_land_area" class="py-2 px-2">
                            @{{  activity.minimum_land_area | toTsubo | numeral('0,0') }} ~ @{{  activity.maximum_land_area | toTsubo | numeral('0,0') }}  
                        </div>
                        <div v-else-if="activity.minimum_land_area && activity.maximum_land_area == null" class="py-2 px-2">
                            @{{  activity.minimum_land_area | toTsubo | numeral('0,0') }}
                        </div>
                        <div v-else-if="activity.minimum_land_area == null && activity.maximum_land_area" class="py-2 px-2">
                            @{{  activity.maximum_land_area | toTsubo | numeral('0,0') }}  
                        </div>
                        <div v-else class="py-2 px-2">-</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.building_condition')</div>
                    </div>
                    <div  v-if="propertyBuildingConditionDesc" class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ propertyBuildingConditionDesc }} </div>
                    </div>
                    <div v-else class="py-2 px-2">-</div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-240px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.favorite')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="px-2 h-100 d-flex align-items-center justify-content-xl-center">
                            <span v-if="!this.activity.properties_id"> - </span>
                            <i v-else :class="favorite_property"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ResultItem', {
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            data: function(){
                var data = {};
                return data;
            },
            computed: {
                activity: function(){ return this.value },
                favorite_property: function() {
                    return this.activity.customer_favorite_property.length >= 1 ? 'fal fa-circle' : 'fal fa-times';
                },
                propertyId : function(){
                    return this.activity.properties_id ? this.activity.properties_id : '-'; 
                },
                propertyLocation : function(){
                    return this.activity.location ? this.activity.location : '-'; 
                },
                propertyBuildingConditionDesc : function(){
                    if (!this.activity.properties_id) {
                        return '-';
                    } else if(this.activity.building_conditions_desc) {
                        return this.activity.building_conditions_desc; 
                    } else {
                        '@lang('label.none')';
                    } 
                },
                // --------------------------------------------------------------
            },
            methods: {},
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

