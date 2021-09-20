<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-150px">
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.used_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ history.ja.access_time }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->

            <!-- Property ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.name_user')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a :href="history.customer.url.view" target="_blank" rel="noopener noreferrer">@{{ history.customer.name }}</a>  
                        </div>  
                    </div>
                </div>
            </div>            
            <!-- Property ID column - End -->
            
            <!-- Location column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl-60px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.flag_history')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">                            
                            <div class="row mx-n1 justify-content-xl-center">                                
                                <div class="px-1 col-auto">                                    
                                    <button-toggle v-model="history.customer.flag" :api="history.customer.url.flag"
                                    @input="toggleFlag(history.customer)">
                                    </button-toggle>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <!-- Location column - End --> 
            

            <!-- Selling column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.corporate_in_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ !history.customer.company_user ? 'ー' : history.customer.company_user.company.company_name }} </div>
                    </div>
                </div>
            </div>            
            <!-- Selling column - End -->

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.person_in_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ !history.customer.company_user ? 'ー' : history.customer.company_user.name }} </div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End --> 

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.user_action')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.action_type.label }}</div>                        
                    </div>
                </div>
            </div>
            <!-- Land Area column - End --> 

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.property_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a v-if="history.property"  target="_blank" rel="noopener noreferrer" :href="history.url.view_property">@{{ propertyId }}</a>
                            <span v-else>@{{ propertyId }}</span>                         
                        </div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End -->         

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md-6 col-lg-6 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">                        
                        <div class="py-2 px-2">@{{ propertyLocation }}</div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End -->                
            
            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.building_condition')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ propertyBuildingConditionDesc }}</div>                        
                    </div>
                </div>
            </div>
            <!-- Land Area column - End -->                        
        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>
    <!-- <router-view></router-view>-->
</script>

<script> @minify
    (function( $, io, document, window, undefined ){       
        // Result item        
        Vue.component( 'ResultItem', {            
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',            
            data: function(){                
                var data = {};                                
                return data;                                
            },                    
            computed: {                         
                history: function(){return this.value},                       
                propertyId: function(){
                    return this.history.properties_id ? this.history.properties_id : 'ー'; 
                },
                propertyLocation : function(){
                    return this.history.properties_id ? this.history.property.location : 'ー'; 
                },
                propertyBuildingConditionDesc : function(){
                    if (!this.history.properties_id) {
                        return 'ー';
                    } else if(this.history.properties_id) {
                        return this.history.property.building_conditions_desc; 
                    } else {
                        '@lang('label.none')';
                    } 
                },                
                resultData: function(){ return io.get( this.$store.state, 'result.data' )},
            }, 
            mounted: function(){                
                // console.log(this.customers);
            },                       
            methods: {
                toggleFlag: function( customer ){                                                             
                    var customerHistories = io.filter( this.resultData, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer.id === customerID;
                    });                    
                    console.log(customerHistories);
                    io.each( customerHistories, function( history ){
                        history.customer.flag = customer.flag;
                    });                    
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                },
            },            
            watch: {}            
        });        
    }( jQuery, _, document, window ));
@endminify </script>

