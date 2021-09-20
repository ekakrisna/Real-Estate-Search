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
                        <div class="py-2 px-2">@lang('label.name')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a :href="activity.customer.url.manage_view">@{{ activity.customer.name }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-210px col-xl-60px">
                <div class="border-top d-lg-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.flag')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="px-2 py-2">

                                <!-- Flag toggle button - Start -->
                                <button-toggle v-model="activity.customer.flag" :api="activity.customer.url.manage_flag" 
                                    @input="updateHistoryCustomer( activity.customer )">
                                </button-toggle>
                                <!-- Flag toggle button - End -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-xl-none"></div>

            <!-- In charge staff column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.in_charge_staff')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.customer.company_user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- In charge staff  column - End -->

            <div class="col-12 d-xl-none"></div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.action_name')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.action_type.label }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-210px col-xl-60px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.property_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a v-if="activity.property" :href="activity.property.url.manage_view">@{{ propertyId }}</a>
                            <span v-else>@{{ propertyId }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-xl-none"></div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl">
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

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-210px col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.building_condition')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ propertyBuildingConditionDesc }} </div>
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
                flag: function(){ 
                    if(this.activity.customer.flag == 1 ) {
                        return 'fas';
                    } else {
                        return 'far';
                    }
                },
                favorite_property: function() {
                    return this.activity.customer_favorite_property.length >= 1 ? 'fal fa-circle' : 'fal fa-times';
                },
                propertyId : function(){
                    return this.activity.properties_id ? this.activity.properties_id : '-'; 
                },
                propertyLocation : function(){
                    return this.activity.properties_id ? this.activity.property.location : '-'; 
                },
                propertyBuildingConditionDesc : function(){
                    if (!this.activity.properties_id) {
                        return '-';
                    } else if(this.activity.property.building_conditions_desc) {
                        return this.activity.property.building_conditions_desc; 
                    } else {
                        return '@lang('label.none')';
                    } 
                },
                resultData: function(){ return io.get( this.$store.state, 'result.data' )},
                // --------------------------------------------------------------
            },
            methods: {
                // ----------------------------------------------------------
                // Update flag of the same customer from other histories
                // ----------------------------------------------------------
                updateHistoryCustomer: function( customer ){
                    // ------------------------------------------------------
                    // Find histories of the same customer
                    // ------------------------------------------------------
                    var customerHistories = io.filter( this.resultData, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer.id === customerID;
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update all the customer flags
                    // ------------------------------------------------------
                    io.each( customerHistories, function( history ){
                        history.customer.flag = customer.flag;
                    });
                    // ------------------------------------------------------
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });

                },
                // ----------------------------------------------------------
            },
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

