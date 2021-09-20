<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-4 col-xl-80px">
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.flag')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="px-2 py-2">
                                
                                <!-- Flag toggle button - Start -->
                                <button-toggle v-model="user.flag" :api="user.url.manage_flag" 
                                    @input="flagHandle( user )">
                                </button-toggle>
                                <!-- Flag toggle button - End -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <!-- Name column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-8 col-xl">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.name')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- Name column - End -->

            <!-- In charge staff column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-8 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.in_charge_staff')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.company_user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- In charge staff  column - End -->

            <!-- Registration date and time column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-4 col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.registration_date_and_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.ja.created_at }}</div>
                    </div>
                </div>
            </div>
            <!-- Registration date and time  column - End -->

            <!-- Last use date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-4 col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.last_use_date')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.customer_last_activity ? user.customer_last_activity.ja.access_time : ''}}</div>
                    </div>
                </div>
            </div>
            <!-- Last use date column - End -->

            <!-- Favorite property number column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-4 col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.favorite_property_number')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.favorite_properties_count }}</div>
                    </div>
                </div>
            </div>
            <!-- Favorite property number  column - End -->

            <!-- Property number checked recent 2 weeks column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-4 col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.property_number_checked_recent_2_weeks')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.properties_checked_count }}</div>
                    </div>
                </div>
            </div>
            <!-- Property number checked recent 2 weeks column - End -->

            <!-- Active status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-4 col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.license')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.license ? '@lang('label.true')' : '@lang('label.false')' }}</div>
                    </div>
                </div>
            </div>
            <!-- Active status column - End -->

           <!-- Company edit button - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.edit')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2 d-flex justify-content-lg-center">
                            <a target="_blank" class="btn btn-sm btn-info text-white fs-12" :href="user.url.manage_edit">
                                @lang('label.edit')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company edit button - End -->  
            <!-- Company user button - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2 d-flex justify-content-lg-center">                                
                            <a target="_blank" class="btn btn-sm btn-info text-white fs-12 fs-xs-0-9" :href="user.url.manage_view">
                                @lang('label.detail')
                            </a>                                                                                                
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company user button - End -->

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
                // Current user item
                // --------------------------------------------------------------
                user: function(){ return this.value },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // User properties
                // --------------------------------------------------------------
                role: function(){ return io.get( this.user, 'role.label' )},
                company: function(){ return io.get( this.user, 'company.company_name' )},
                // --------------------------------------------------------------

            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                flagHandle: function(user){
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
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

