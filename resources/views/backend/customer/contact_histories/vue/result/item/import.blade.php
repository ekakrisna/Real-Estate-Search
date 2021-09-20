<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <!--<input type="hidden" v-bind:value="inquiry.customer.id" ref="customer_id">-->
        <div class="row mx-0">            
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-90px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.favorite_flag')</div>
                    </div>
                    <div class="text-center px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1 justify-content-lg-center">                                
                                <div class="px-1 col-auto">                                    
                                <!-- Star toggle button - Start -->
                                <button-toggle v-model="inquiry.flag" :api="inquiry.url.change_star" :icon="'star'" @input="toggleFavoriteFlag(inquiry)">
                                </button-toggle>
                                <!-- Star toggle button - End -->
                                </div>
                            </div>
                        </div>             
                    </div>
                </div>
            </div>            
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-120px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-lg-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.contact_us_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1">                                
                                <div class="px-1 col-auto">
                                    <a class="py-2 px-2" :href="inquiry.url.detail"  target="_blank" rel="noopener noreferrer">
                                        @{{ inquiry.id }}
                                    </a>  
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>            
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.contact_us_date_and_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ inquiry.ja.created_at }}</div>
                    </div>
                </div>
            </div>
            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.dashboard_status')</div>
                    </div>
                    <div class="text-lg-center px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ inquiry.is_finish ? '@lang('label.active')' : '@lang('label.not_active')' }}</div>                        
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-lg-120px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.name_user')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ inquiry.customer == null ? inquiry.name : inquiry.customer.name }}</div>                        
                    </div>
                </div>
            </div>                    

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-180px col-xl-60px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.flag_history')</div>
                    </div>
                    <div class="text-center px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1 justify-content-lg-center">                                
                                <div class="px-1 col-auto">
                                    <!-- Star toggle button - Start -->
                                    <template v-if="inquiry.customer">
                                        <button-toggle v-model="inquiry.customer.flag" :api="inquiry.customer.url.flag"
                                        @input="toggleCustomerFlag(inquiry.customer)">
                                        </button-toggle>
                                    </template>
                                    <template v-else>
                                        ー
                                    </template>
                                    <!-- Star toggle button - End -->
                                </div>
                            </div>
                        </div>             
                    </div>
                </div>
            </div>

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.corporate_in_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2" v-if="inquiry.customer">@{{ !inquiry.customer.company_user ? 'ー' : inquiry.customer.company_user.company.company_name }} </div>

                        <div class="py-2 px-2" v-else>ー</div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.person_in_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2" v-if="inquiry.customer">@{{ !inquiry.customer.company_user ? 'ー' : inquiry.customer.company_user.name }} </div>

                        <div class="py-2 px-2" v-else>ー</div>
                    </div>
                </div>
            </div>            
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-180px col-xl-60px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.dashboard_detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1 justify-content-lg-center">                                
                                <div class="px-1 col-auto">
                                    <a class="btn btn-sm btn-info fs-12 text-white" :href="inquiry.url.detail"  target="_blank" rel="noopener noreferrer">
                                        @lang('label.detail')
                                    </a>
                                </div>
                            </div>
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
                var data = {
                    customer_id: '',
                };
                return data;
            },
            computed: {
                inquiry: function(){ return this.value },
                customer: function(){return this.inquiry.customer },           
                star: function(){return this.inquiry.flag },   
                flag: function(){ return this.inquiry.customer.flag },

                resultData: function(){ return io.get( this.$store.state, 'result.data' )},
            },
            mounted: function(){                
            },
            methods: {
                toggleFavoriteFlag: function( inquiry ){   
                    var vm = this;                               
                    var customerHistories = io.filter( this.resultData, function( inquiry ){
                        var contactID = io.get( inquiry, 'id' );                        
                        return inquiry.id === contactID;
                    });                    
                    io.each( customerHistories, function( inquiry ){
                        inquiry.flag = inquiry.flag;
                    });                    
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    vm.$toasted.show( message, {
                        type: 'success'
                    }); 
                },
                toggleCustomerFlag: function( customer ){ 
                    var vm = this;                                                                                
                    var customerHistories = io.filter( this.resultData, function( inquiry ){
                        var customerID = io.get( inquiry, 'customer.id' );                        
                        return customer.id === customerID;
                    });                    
                    io.each( customerHistories, function( inquiry ){
                        inquiry.customer.flag = customer.flag;
                    });                    
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    vm.$toasted.show( message, {
                        type: 'success'
                    });
                },
            },            
            watch: {}            
        });        
    }( jQuery, _, document, window ));
@endminify </script>

