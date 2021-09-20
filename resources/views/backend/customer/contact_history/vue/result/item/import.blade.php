<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0 w-100">
        <div class="row mx-0">

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-160px col-xl-90px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right bg-light d-flex align-items-center d-xl-none ">
                        <div class="py-2 px-2">@lang('label.favorite')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2 d-flex justify-content-lg-center"> 
                            <button type="button" class="btn btn-sm btn-default fs-13" id="flag" @click.prevent="starHandle(history)">
                                <i :class="[`${star}`]"></i>
                            </button> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-120px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-lg-120px border-right d-flex d-xl-none bg-light align-items-center">
                        <div class="py-2 px-2">@lang('label.contact_us_id')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center">
                        <a class="py-2 px-2 flex-grow-1" :href="history.url.detail" target="_blank">
                            <span>@{{ history.id }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light d-flex align-items-center">
                        <div class="py-2 px-2">@lang('label.search_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center">
                        <div class="py-2 px-2">@{{ history.ja.created_at }}</div>
                    </div>
                </div>
            </div>

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light d-flex align-items-center">
                        <div class="py-2 px-2">@lang('label.status')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center">
                        <div class="py-2 px-2">@{{ history.is_finish ? '対応済み' : '未対応' }}</div>
                    </div>
                </div>
            </div>

            
            <!-- Selling column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-md col-lg-200px col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.contact_us_type')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ history.contact_us_type.label }} </div>
                    </div>
                </div>
            </div>            
            <!-- Selling column - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light d-flex align-items-center">
                        <div class="py-2 px-2">@lang('label.property_id')</div>
                    </div>
                    <div v-if=" history.properties_id" class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center">
                        <a class="py-2 px-2 flex-grow-1" :href="history.url.property_detail" target="_blank">
                            <span>@{{ history.properties_id }}</span>
                        </a>
                    </div>
                    <div v-else class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center">
                        <div class="py-2 px-2 flex-grow-1">-</div>
                    </div>
                </div>
            </div>

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <div class="px-0 d-flex flex-column border-left col-12 col-lg ellipsis">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.description')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1">
                                <div class="px-1 col ellipsis d-flex align-items-center">
                                    <div class="ellipsis">@{{ history.text }}</div>
                                </div>
                                <div class="px-1" v-for="modal in [ 'modal-inquiry-' + ( index +1 )]">

                                    <button type="button" class="btn btn-sm btn-default fs-12" data-toggle="modal" :data-target="'#' + modal">
                                        <span>@lang('label.display')</span>
                                    </button>

                                    <!-- Content modal - Start -->
                                    <div class="modal fade" :id="modal" tabindex="-1" :aria-labelledby="modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">@lang('label.description')</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-wrap" v-html="history.text"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Content modal - End -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-60px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light d-flex align-items-center">
                        <div class="py-2 px-2">@lang('label.detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden d-flex align-items-center justify-content-lg-center">
                        <div class="py-2 px-2">
                            <a :href="history.url.detail" class="btn btn-sm btn-info fs-12" target="_blank">
                                @lang('label.detail')
                            </a>
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
        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'ResultItem', {
            // ------------------------------------------------------------------
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {};
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Current history item
                // --------------------------------------------------------------
                history: function(){ return this.value },
                // --------------------------------------------------------------
                star: function(){ 
                    //console.log(this.history.flag);
                    if(this.history.flag == 1 ) {
                        return 'fas fa-star';
                    } else {
                        return 'far fa-star';
                    }
                },
            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                starHandle: function(history){
                    var vm = this;
                    const url =  history.url.change_contact_flag;
                    var request = axios.get( url);
                    request.then( function( response ){
                        history.flag = response.data.flag;
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, {
                            type: 'success'
                        });
                    });
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

