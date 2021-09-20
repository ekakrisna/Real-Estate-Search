<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-150px">
                <div class="row mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.registration_date_and_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ inquiry . ja . created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->
            
            <!-- Property ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-90px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.property_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a v-if="inquiry.property" target="_blank" :href="inquiry.property.url.view">@{{ propertyId }}</a>
                            <span v-else>@{{ propertyId }}</span>   
                        </div>  
                    </div>
                </div>
            </div>            
            <!-- Property ID column - End -->

            <div class="col-12 d-xl-none"></div>
            
            <!-- Location column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">                            
                            <div v-if="inquiry.property">
                                @{{ location }}
                            </div>
                            <div v-else>@{{ location }}</div>
                        </div>
                    </div>
                </div>
            </div>            
            <!-- Location column - End -->        
            
            <!-- Building column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.building_condition')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div v-if="inquiry.property">
                                @{{ building_desc }}
                            </div>
                            <div v-else>@{{ building_desc }}</div> 
                        </div>
                    </div>
                </div>
            </div>            
            <!-- Building column - End --> 

            <div class="col-12 d-xl-none"></div>

            <!-- Selling column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.selling_price_yuan')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <span v-if="inquiry.property.minimum_price && inquiry.property.maximum_price">
                                @{{ inquiry.property.minimum_price | toMan | numeral('0,0.[00]') }} ~ @{{  inquiry.property.maximum_price | toMan | numeral('0,0.[00]')}}
                            </span>
                            <span v-else-if="inquiry.property.minimum_price && inquiry.property.maximum_price == null">
                                @{{ inquiry.property.minimum_price | toMan | numeral('0,0.[00]') }}
                            </span>
                            <span v-else-if="inquiry.property.minimum_price == null && inquiry.property.maximum_price">
                                @{{ inquiry.property.maximum_price | toMan | numeral('0,0.[00]') }}
                            </span>
                            <span v-else>-</span>

                        </div>

                    </div>
                </div>
            </div>            
            <!-- Selling column - End -->  

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-6 col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.land_area_m2')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">     
                            <span v-if="inquiry.property.minimum_land_area && inquiry.property.maximum_land_area">
                                @{{ inquiry.property.minimum_land_area | toTsubo | numeral('0,0')}} ~ @{{ inquiry.property.maximum_land_area | toTsubo | numeral('0,0')}}
                            </span>
                            <span v-else-if="inquiry.property.minimum_land_area && inquiry.property.maximum_land_area == null">
                                @{{ inquiry.property.minimum_land_area | toTsubo | numeral('0,0')}}
                            </span>
                            <span v-else-if="inquiry.property.minimum_land_area == null && inquiry.property.maximum_land_area">
                                @{{ inquiry.property.maximum_land_area | toTsubo | numeral('0,0')}}
                            </span>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End --> 
      
        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>    
</script>

<script>
    @minify
        (function($, io, document, window, undefined) {
            // Result item        
            Vue.component('ResultItem', {
                props: ['value', 'index'],
                template: '#tablelike-result-item-tpl',
                data: function() {
                    var data = {};
                    return data;
                },
                computed: {
                    inquiry: function() {
                        return this.value
                    },
                    propertyId: function() {
                        return this.inquiry.property ? this.inquiry.properties_id : 'ー';
                    },
                    location: function() {
                        return this.inquiry.property ? this.inquiry.property.location : '@lang('label.none')';
                    },
                    // building: function(){
                    //     return this.inquiry.property ? this.inquiry.property.building_conditions_desc : '@lang('label.none')'; 
                    // },
                    building_desc: function() {
                        return this.inquiry.property ? this.inquiry.property.building_conditions_desc : '@lang('label.none')';
                    },
                },
                methods: {},
                watch: {}
            });
        }(jQuery, _, document, window));
    @endminify

</script>
