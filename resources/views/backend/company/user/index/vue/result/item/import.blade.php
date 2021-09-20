<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- Username - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-140px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.user_name')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- Username - End -->

            <!-- Email - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.e-mail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2 text-break">@{{ user.email }}</div>
                    </div>
                </div>
            </div>
            <!-- Email - End -->

            <!-- Permission - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-100px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.permission')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden text-lg-center text-xl-center">
                        <div class="py-2 px-2">@{{ user.role.label }}</div>
                    </div>
                </div>
            </div>
            <!-- Permission - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- Registration date - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.register_date_time')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.ja.created_at }}</div>
                    </div>
                </div>
            </div>
            <!-- Registration date - End -->

            <!-- Use date - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-140px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.last_use_date')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div v-if="userlog" class="py-2 px-2">
                            <span>@{{ userlog.ja.access_time }}</span></span>
                        </div>
                        <div v-else class="py-2 px-2">なし</div>
                    </div>
                </div>
            </div>
            <!-- Use date - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- Number of customers - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-80px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.number_of_customer')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.customer_count }}</div>
                    </div>
                </div>
            </div>
            <!-- Number of customers - End -->

            <!-- License - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.license')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ license }}</div>
                    </div>
                </div>
            </div>
            <!-- License - End -->

            <!-- Edit - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-60px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.detail')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <a :href="editPage" class="btn btn-sm btn-info fs-12">
                                @lang('label.detail')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit - End -->

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
               user: function(){ return this.value },
                userlog: function(){ return io.get( this.user, 'latest_log_activities' )},
                license: function(){ 
                    if(this.user.is_active == 1 ) {
                        return '稼働';
                    } else {
                        return '停止';
                    }
                },
                editPage: function(){ return io.get( this.value, 'url.edit' )},
            },
            methods: {},
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

