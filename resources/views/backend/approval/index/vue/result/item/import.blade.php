<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <div class="px-0 d-flex flex-column border-left col-12 col-xl-130px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.update_date_and_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.ja.created_at }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl">                
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden" :class="location">
                        <div v-if="history.location" class="py-2 px-2">
                            <span>@{{ history.location }}</span>
                        </div>
                        <div v-else class="py-2 px-2">なし</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-xl-130px">                
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.selling_price_approval')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden" :class="price">                        
                        <div v-if="history.minimum_price && history.maximum_price" class="py-2 px-2">
                            <span>@{{ history.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                            <span>~</span>
                            <span>@{{ history.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                        </div>

                        <div v-else-if="history.minimum_price && !history.maximum_price" class="py-2 px-2">
                            <span>@{{ history.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                        </div>

                        <div v-else-if="!history.minimum_price && history.maximum_price" class="py-2 px-2">
                            <span>@{{ history.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                        </div>

                        <div v-else class="py-2 px-2">
                            <span>なし</span>
                        </div>                                                    
                    </div>
                </div>
            </div>
            

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-xl-130px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.land_area_approval')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden" :class="land">
                        <div v-if="history.minimum_land_area && history.maximum_land_area" class="py-2 px-2">
                            <span>@{{ history.minimum_land_area  | numeral('0,0') }}</span>
                            <span>~</span>
                            <span>@{{ history.maximum_land_area  | numeral('0,0') }}</span>
                        </div>

                        <div v-else-if="history.minimum_land_area && !history.maximum_land_area" class="py-2 px-2">
                            <span>@{{ history.minimum_land_area  | numeral('0,0') }}</span>
                        </div>

                        <div v-else-if="!history.minimum_land_area && history.maximum_land_area" class="py-2 px-2">
                            <span>@{{ history.maximum_land_area  | numeral('0,0') }}</span>
                        </div>

                        <div v-else class="py-2 px-2">
                            <span>なし</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.status_approval')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            @{{ history.building_conditions_desc ? history.building_conditions_desc : '@lang('label.none')'}}
                        </div>
                    </div>
                </div>
            </div>


            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.publish_site')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">                        
                        <div class="py-2 px-2" v-if="history.property_publish.length != 0">                            
                            <div v-for="publish in history.property_publish">                            
                                <div v-if="publish.length != 0">
                                    <a :href="publish.url">@{{ publish.publication_destination }}</a>
                                </div>                                                            
                            </div>                                                
                        </div>                            
                        <div class="py-2 px-2"  v-else>                                
                            @lang('label.none')
                        </div>                       
                    </div>
                </div>
            </div>

            <!-- Price column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-50px">                
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1">
                                <div class="px-1 col-auto">
                                    <a target="_blank" class="btn btn-sm btn-info fs-12" :href="history.url.approval">
                                        <i class="far fa-external-link-square"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Price column - End -->

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
                var data = {
                    customer_id: '',
                };
                return data;
            },
            computed: {
                history : function(){ return this.value },
                location: function(){ return this.history.property_convert_status_id == 100 ? 'text-red' : '' },
                price   : function(){ return this.history.property_convert_status_id == 200 ? 'text-red' : '' },
                land    : function(){ return this.history.property_convert_status_id == 300 ? 'text-red' : '' },
            },
            methods: {

            },
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

