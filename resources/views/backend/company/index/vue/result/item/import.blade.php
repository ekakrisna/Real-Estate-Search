<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-60px">
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">ID</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100">
                            <div class="py-2 px-2">@{{ company.id }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <!-- Company name - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.company_named')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ company.company_name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company name - End -->

            <div class="col-12 d-xl-none"></div>

            <!-- Company role column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.type')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ company.company_roles.label }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company role column - End -->

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.register_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ company.ja.created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->

            <!-- Company status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.status')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ company.is_active ? '@lang('label.true')' : '@lang('label.false')' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company status column - End -->

            <div class="col-12 d-xl-none"></div>

            <!-- Users count column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.user_count')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                            <div class="py-2 px-2">@{{ company.company_users_count }}</div>                        
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users count column - End -->    
            
            <!-- Company edit button - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.dashboard_detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2 d-flex justify-content-lg-center">
                            <a target="_blank" class="btn btn-sm btn-info text-white fs-12" :href="company.url.edit">
                                @lang('label.detail')・@lang('label.edit')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company edit button - End -->  

            <!-- Company user button - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-220px col-xl-110px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.dashboard_detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2 d-flex justify-content-lg-center">                                
                            <a target="_blank" class="btn btn-sm btn-info text-white fs-12 fs-xs-0-9" :href="company.url.user">
                                @lang('label.user_management')
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

<script>
    @minify
        (function ($, io, document, window, undefined) {
            // ----------------------------------------------------------------------
            // Result item
            // ----------------------------------------------------------------------
            Vue.component('ResultItem', {
                // ------------------------------------------------------------------
                props: ['value', 'index'],
                template: '#tablelike-result-item-tpl',
                // ------------------------------------------------------------------
                data: function () {
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
                    // Current company item
                    // --------------------------------------------------------------
                    company: function () {
                        return this.value
                    },
                    // --------------------------------------------------------------
                    // --------------------------------------------------------------
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Call to action methods
                // ------------------------------------------------------------------
                methods: {},
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Wacthers
                // ------------------------------------------------------------------
                watch: {}
                // ------------------------------------------------------------------
            });
            // ----------------------------------------------------------------------
        }(jQuery, _, document, window));
    @endminify

</script>
