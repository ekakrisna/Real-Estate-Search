<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.search_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.ja.created_at }}</div>
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
                        <div class="py-2 px-2"><a :href="history.customer.url.view"  target="_blank" rel="noopener noreferrer">@{{ history.customer.name }}</a></div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-240px col-xl-80px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.flag')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden text-lg-center text-xl-center">
                        <div class="py-2 px-2"> 

                            <!-- Customer flag button - Start -->
                            <button type="button" class="btn btn-sm btn-default fs-14" @click="toggleCustomerFlag">
                                <i v-if="customerFlag" class="fas fa-flag"></i>                                            
                                <i v-else class="fal fa-flag"></i>
                            </button>
                            <!-- Customer flag button - End -->

                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.corporate_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ !history.customer.company_user ? 'ー' : history.customer.company_user.company.company_name }} </div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.person_charge')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ !history.customer.company_user ? 'ー' : history.customer.company_user.name }} </div>
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
                        <div class="py-2 px-2">@lang('label.location')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="history.location" class="py-2 px-2">
                            <span>@{{ history.location }}</span>
                        </div>
                        <div v-else class="py-2 px-2">なし</div>
                    </div>
                </div>
            </div>

            <!-- Price column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.selling_price')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                        
                            <div v-if="history.minimum_price && history.maximum_price" class="py-2 px-2">
                                <span>@{{ history.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                                <span>~</span>
                                <span>@{{ history.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                            </div>

                            <div v-else-if="history.minimum_price && !history.maximum_price" class="py-2 px-2">
                                <span>@{{ history.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                                <span>~</span>
                                <span> @lang('label.no_upper_limit') </span>
                            </div>

                            <div v-else-if="!history.minimum_price && history.maximum_price" class="py-2 px-2">
                                <span> @lang('label.no_lower_limit') </span>
                                <span>~</span>
                                <span>@{{ history.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                            </div>

                            <div v-else class="py-2 px-2">
                                <span>なし</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Price column - End -->

            <!-- Land Area column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-240px col-xl-140px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.land_area')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            
                            <div v-if="history.minimum_land_area && history.maximum_land_area" class="py-2 px-2">
                                <span>@{{ history.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                                <span>~</span>
                                <span>@{{ history.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                            </div>

                            <div v-else-if="history.minimum_land_area && !history.maximum_land_area" class="py-2 px-2">
                                <span>@{{ history.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                                <span>~</span>
                                <span> @lang('label.no_upper_limit') </span>
                            </div>

                            <div v-else-if="!history.minimum_land_area && history.maximum_land_area" class="py-2 px-2">
                                <span> @lang('label.no_lower_limit') </span>
                                <span>~</span>
                                <span>@{{ history.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                            </div>

                            <div v-else class="py-2 px-2">
                                <span>なし</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Land Area column - End -->

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
                history: function(){ return this.value },
                customer: function(){ return io.get( this.history, 'customer' )},
                flagURL: function(){ return io.get( this.history, 'url.change_flag' )},
                customerFlag: function(){ return io.get( this.history, 'customer.flag' )},
            },
            methods: {
                // --------------------------------------------------------------
                // Toggle customer flag
                // --------------------------------------------------------------
                toggleCustomerFlag: function(){
                    // ----------------------------------------------------------
                    var vm = this;
                    var request = axios.get( this.flagURL );
                    var customerID = io.get( this.customer, 'id' );
                    // ----------------------------------------------------------
                    // Do server request
                    // ----------------------------------------------------------
                    request.then( function( response ){
                        // ------------------------------------------------------
                        if( 200 !== io.get( response, 'data.status')) return;
                        var flag = io.get( response, 'data.flag' );
                        // ------------------------------------------------------
                        // Commit the mutation
                        // ------------------------------------------------------
                        vm.$store.commit( 'setCustomerFlag', {
                            flag: flag, customer: customerID
                        });
                        // ------------------------------------------------------
                        // Display toast message
                        // ------------------------------------------------------
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, { type: 'success' });
                        // ------------------------------------------------------
                    });
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Old code
                // --------------------------------------------------------------
                flagHandle(customer_id){               
                    var vm = this;                    
                    var history = this.history;                       
                    const button = document.querySelectorAll('#flag');                    
                    const url =  history.url.change_flag;                    
                    var request = axios.get( url);                    
                    request.then( function( response ){                        
                        vm.history.customer.flag = response.data.flag;                                         
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, {
                            type: 'success'
                        });
                        button.forEach(function(entry) {
                        if(entry.dataset.id == customer_id.id) {
                            var flag = entry.children[0];                        
                            if(vm.history.customer.flag == 1) {
                                flag.classList.remove('fal');
                                flag.classList.add('fas');
                            } else if(vm.history.customer.flag == 0) {
                                flag.classList.remove('fas');
                                flag.classList.add('fal');
                            }
                        }
                        });
                    });
                }
                // --------------------------------------------------------------
            },
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

