@extends('backend._base.content_tablelike')

@section('content')
<!-- customer log activity section - Start -->

<!-- customer log activity header - Start -->
<div class="row border-bottom py-2 mb-3">
    <div class="col-sm-12">
        <span class="m-0 text-dark h1title">
            ■ @lang('dashboard.most_recently_used_customers')
        </span>
        ( <a target="_blank" href="{{route('admin.customer.list')}}">@lang('label.view_user_list')</a>)
    </div>
</div>
<!-- customer log activity header - End -->

<!-- customer log activity Table - Start -->
<div class="tablelike">
    <!-- Table header - Start -->
    <div class="tablelike-header border-top border-right d-none d-xl-block">
        <div class="row mx-0">
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.active_date_time')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.name')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-80px"><div class="py-2 px-2">@lang('label.flag')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_company')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_staff')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-200px"><div class="py-2 px-2">@lang('label.action_name')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.favorite_property_number')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.property_number_checked_recent_2_weeks')</div></div>
        </div>
    </div>
    <!-- Table header - End -->
    <div class="tablelike-content">
        <div class="tablelike-result">
            
            <div v-if="customerLogActivities.length == 0" class="text-center py-2 px-2 border">
                <span>対象のレコードはありません。</span>
            </div>
            
            <template v-else>
                <div v-for="customerLogActivity in customerLogActivities" :key="customerLogActivity.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">
                    <div class="row mx-0">
                        <!-- active_date_time column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.active_date_time')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.ja.access_time }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- active_date_time column - End -->

                        <!-- Name column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                            <div class="border-top"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        <a :href="customerLogActivity.customer.url.view" target="_blank">@{{ customerLogActivity.customer.name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Name column - End -->

                        <!-- ID column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12  col-xl-80px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.flag')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                                        <div class="px-2 py-2">
                                           
                                            <!-- Flag toggle button - Start -->
                                            <button-toggle v-model="customerLogActivity.customer.flag" :api="customerLogActivity.customer.url.manage_flag" 
                                                @input="flagHandle( customerLogActivity )">
                                            </button-toggle>
                                            <!-- Flag toggle button - End -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ID column - End -->

                        <!-- In charge company column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12  col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.in_charge_company')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        @{{ customerLogActivity.customer.company_user ? customerLogActivity.customer.company_user.company.company_name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge company column - End -->

                        <!-- In charge staff column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.in_charge_staff')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        @{{ customerLogActivity.customer.company_user ? customerLogActivity.customer.company_user.name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge staff  column - End -->

                        <!-- In charge staff column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-200px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.action_name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.action_type.label }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge staff  column - End -->

                        <!-- Favorite property number column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.favorite_property_number')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.customer.favorite_properties.length }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Favorite property number  column - End -->

                        <!-- Property number checked recent 2 weeks column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.property_number_checked_recent_2_weeks')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerLogActivity.customer.properties_checked.length }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Property number checked recent 2 weeks column - End -->
                    </div>
                    <div class="border-bottom d-xl-none"></div>
                </div>
            </template>

            <div class="border-bottom d-none d-xl-block"></div>
        </div>
    </div>
</div>
<!-- customer log activity Table - End -->

<!-- customer log activity section - End -->

<!-- customer search history section - Start -->

<!-- customer search history header - Start -->
<div class="row border-bottom py-2 mb-3 mt-4">
    <div class="col-sm-12">
        <span class="m-0 text-dark h1title">
            ■ @lang('dashboard.recent_customer_search_history')
        </span>
        ( <a target="_blank" href="{{route('admin.customer_all_search_history')}}">@lang('label.view_search_history')</a> )
    </div>
</div>
<!-- customer search history header - End -->

<!-- customer search history table - Start -->
<div class="tablelike">
    <!-- Table header - Start -->
    <div class="tablelike-header border-top border-right d-none d-xl-block">
        <div class="row mx-0">
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.name')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-80px"><div class="py-2 px-2">@lang('label.flag')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_company')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.in_charge_staff')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-140px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
            <div class="px-0 border-left bg-light col-12 col-xl-140px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
        </div>
    </div>

    <div class="tablelike-content">
        <div class="tablelike-result">

            <div v-if="customerSearchHistories.length == 0" class="text-center py-2 px-2 border">
                <span>対象のレコードはありません。</span>
            </div>

            <template v-else>
                <div v-for="customerSearchHistory in customerSearchHistories" :key="customerSearchHistory.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">
                    <div class="row mx-0">
                        <!-- active_date_time column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.datetime')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerSearchHistory.ja.created_at }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- active_date_time column - End -->

                        <!-- Name column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                            <div class="border-top"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        <a :href="customerSearchHistory.customer.url.view" target="_blank">@{{ customerSearchHistory.customer.name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Name column - End -->

                        <!-- ID column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-80px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row h-100 mx-0 my-auto flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.flag')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                                        <div class="px-2 py-2">

                                            <!-- Flag toggle button - Start -->
                                            <button-toggle v-model="customerSearchHistory.customer.flag" :api="customerSearchHistory.customer.url.manage_flag" 
                                                @input="flagHandle( customerSearchHistory )">
                                            </button-toggle>
                                            <!-- Flag toggle button - End -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ID column - End -->

                        <!-- In charge company column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.in_charge_company')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        @{{ customerSearchHistory.customer.company_user ? customerSearchHistory.customer.company_user.company.company_name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge company column - End -->

                        <!-- In charge staff column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.in_charge_staff')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        @{{ customerSearchHistory.customer.company_user ? customerSearchHistory.customer.company_user.name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- In charge staff  column - End -->

                        <!-- address column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.address')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{ customerSearchHistory.location }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- address  column - End -->

                        <!-- price column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-140px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.selling_price')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerSearchHistory.minimum_price && customerSearchHistory.maximum_price" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_price | toMan | numeral(0,0) }} ~
                                        @{{ customerSearchHistory.maximum_price | toMan | numeral(0,0) }}
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_price && customerSearchHistory.maximum_price == null" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_price | toMan | numeral(0,0) }} ~ @lang('label.no_upper_limit')
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_price == null && customerSearchHistory.maximum_price" class="py-2 px-2">
                                        @lang('label.no_lower_limit') ~ @{{ customerSearchHistory.maximum_price | toMan | numeral(0,0) }}
                                    </div>
                                    <div v-else class="py-2 px-2">
                                        @lang('label.none')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- price  column - End -->

                        <!-- land_area column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-140px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.land_area')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerSearchHistory.minimum_land_area && customerSearchHistory.maximum_land_area" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_land_area | toTsubo | numeral('0,0') }} ~
                                        @{{ customerSearchHistory.maximum_land_area | toTsubo | numeral('0,0') }}
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_land_area && customerSearchHistory.maximum_land_area == null" class="py-2 px-2">
                                        @{{ customerSearchHistory.minimum_land_area | toTsubo | numeral('0,0') }} ~ @lang('label.no_upper_limit')
                                    </div>
                                    <div v-else-if="customerSearchHistory.minimum_land_area == null && customerSearchHistory.maximum_land_area" class="py-2 px-2">
                                        @lang('label.no_lower_limit') ~ @{{ customerSearchHistory.maximum_land_area | toTsubo | numeral('0,0') }}
                                    </div>
                                    <div v-else class="py-2 px-2">
                                        @lang('label.none')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- land_area  column - End -->
                    </div>
                    <div class="border-bottom d-xl-none"></div>
                </div>
            </template>
            
            <div class="border-bottom d-none d-xl-block"></div>
        </div>
    </div>
</div>
<!-- customer search history table - End -->

<!-- customer search history section - End -->

<!-- customer contact us section - Start -->

<!-- customer contact us header - Start -->
<div class="row border-bottom py-2 mb-3 mt-4">
    <div class="col-sm-12">
        <span class="m-0 text-dark h1title">
            ■ @lang('dashboard.inquiries')
        </span>
        ( <a target="_blank" href="{{route('admin.customer_all_contact')}}">@lang('label.view_contact_us')</a> )
    </div>
</div>
<!-- customer contact us header - End -->

<!-- customer contact us table - Start -->
<div class="tablelike">
    <!-- Table header - Start -->
    <div class="tablelike-header border-top border-right d-none d-xl-block">
        <div class="row mx-0">
            <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.favorite')</div></div>
            <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.contact_us_id')</div></div>
            <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
            <div class="px-0 border-left bg-light col-xl-80px"><div class="py-2 px-2">@lang('label.dashboard_status')</div></div>
            <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.name')</div></div>
            <div class="px-0 border-left bg-light col-xl-80px"><div class="py-2 px-2">@lang('label.flag')</div></div>
            <div class="px-0 border-left bg-light col-xl-180px"><div class="py-2 px-2">@lang('label.corporate_in_charge')</div></div>
            <div class="px-0 border-left bg-light col-xl-180px"><div class="py-2 px-2">@lang('label.person_in_charge')</div></div>
            <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.detail')</div></div>
        </div>
    </div>

    <div class="tablelike-content">
        <div class="tablelike-result">

            <div v-if="customersContactUs.length == 0" class="text-center py-2 px-2 border">
                <span>対象のレコードはありません。</span>
            </div>

            <template v-else>
                <div v-for="customerContactUs in customersContactUs" :key="customerContactUs.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">
                    <div class="row mx-0">
                        
                        <!-- customer contact us flag column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-90px">
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.favorite')</div>
                                </div>
                                <div class="text-center px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        
                                        <div class="row mx-n1 justify-content-xl-center">                                
                                            <div class="px-1 col-auto">

                                                <!-- Star toggle button - Start -->
                                                <button-toggle v-model="customerContactUs.flag" :api="customerContactUs.url.change_star" :icon="'star'" @input="starHandle(customerContactUs)"></button-toggle>
                                                <!-- Star toggle button - End -->

                                            </div>
                                        </div>

                                    </div>             
                                </div>
                            </div>
                        </div>
                        <!-- customer contact us flag column - End -->
                        
                        <!-- customer contact us id column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-120px">
                            <div class="border-top"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.contact_us_id')</div>
                                </div>
                                <div class="text-center px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">
                                        <div class="row mx-n1 justify-content-xl-center">                                
                                            <div class="px-1 col-auto">
                                                @{{customerContactUs.id}} 
                                            </div>
                                        </div>
                                    </div>                      
                                </div>
                            </div>
                        </div>
                        <!-- customer contact us id column - End -->
                        
                        <!-- customer contact us created at column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                            <div class="border-top"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.datetime')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2">@{{customerContactUs.ja.created_at}}</div>
                                </div>
                            </div>
                        </div>
                        <!-- customer contact us created at column - End -->
            
                        <!-- Column break - Columns below this will start a new row - Start -->
                        <div class="col-12 d-xl-none"></div>
                        <!-- Column break - Columns below this will start a new row - End -->
                        
                        <!-- customer contact us is finish column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-80px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.dashboard_status')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                        <div v-if="customerContactUs.is_finish" class="py-2 px-2">@lang('label.acknowledged')</div>
                                        <div v-else class="py-2 px-2">@lang('label.not_compatible')</div>
                                </div>
                            </div>
                        </div>
                        <!-- customer contact us is finish column - End -->
                        
                        <!-- customer name column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-150px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.name')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerContactUs.customer" class="py-2 px-2">@{{ customerContactUs.customer.name }}</div>
                                    <div v-else class="py-2 px-2"> なし </div>
                                </div>
                            </div>
                        </div>
                        <!-- customer name column - End -->
                        
                        <!-- customer flag column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-80px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.flag')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div class="row-fluid h-100 justify-content-xl-center d-flex align-items-center">
                                        <div v-if="customerContactUs.customer" class="py-2 px-2">
                                             <!-- Flag toggle button - Start -->
                                            <button-toggle v-model="customerContactUs.customer.flag" :api="customerContactUs.customer.url.manage_flag" 
                                                @input="flagHandle( customerContactUs )">
                                            </button-toggle>
                                            <!-- Flag toggle button - End -->
                                        </div>
                                        <div v-else class="py-2 px-2"> なし </div>     

                                    </div>             
                                </div>
                            </div>
                        </div>
                        <!-- customer flag column - Start -->
            
                        <!-- Column break - Columns below this will start a new row - Start -->
                        <div class="col-12 d-xl-none"></div>
                        <!-- Column break - Columns below this will start a new row - End -->
                        
                        <!-- company column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12  col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.corporate_in_charge')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerContactUs.customer" class="py-2 px-2">
                                        @{{ customerContactUs.customer.company_user ? customerContactUs.customer.company_user.company.company_name : '' }} 
                                    </div>
                                    <div v-else class="py-2 px-2"> なし </div>     
                                </div>
                            </div>
                        </div>
                        <!-- company column - End -->
                        
                        <!-- company user column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl-180px">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.person_in_charge')</div>
                                </div>
                                <div class="px-0 col col-xl-12 overflow-hidden">
                                    <div v-if="customerContactUs.customer" class="py-2 px-2">
                                        @{{ customerContactUs.customer.company_user ? customerContactUs.customer.company_user.name : '' }}
                                    </div>
                                    <div v-else class="py-2 px-2"> なし </div>     
                                </div>
                            </div>
                        </div>
                        <!-- company user column - End -->
                        
                        <!-- option column - Start -->
                        <div class="px-0 d-flex flex-column border-left col-12 col-xl">
                            <div class="border-top d-xl-none"></div>
                            <div class="row mx-0 flex-nowrap flex-grow-1">
                                <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                                    <div class="py-2 px-2">@lang('label.detail')</div>
                                </div>
                                <div class="text-center px-0 col col-xl-12 overflow-hidden">
                                    <div class="py-2 px-2 d-flex justify-content-xl-left">  
                                        <div class="row mx-n1 justify-content-xl-left">                                
                                            <a class="btn btn-sm btn-info fs-12 text-white" :href="customerContactUs.url.detail" target="_blank">
                                                @lang('label.detail')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- option column - End -->
            
                    </div>
                    <div class="border-bottom d-xl-none"></div>
                </div>
            </template>  
            
            <div class="border-bottom d-none d-xl-block"></div>
        </div>
    </div>
</div>
<!-- customer contact us table - End -->

@endsection

@push('vue-scripts')

@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue router
        // ----------------------------------------------------------------------
        router = {};
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vuex store - Centralized data
        // ----------------------------------------------------------------------
        store = {};
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue mixin 
        // ----------------------------------------------------------------------
        mixin = {
            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return {
                    customerLogActivities : @json($customer_log_activites),
                    customerSearchHistories : @json($customer_search_histories),
                    customersContactUs : @json($customers_contact_us),
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted hook
            // ------------------------------------------------------------------
            mounted: function(){
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                // ------------------------------------------------------------------
                // function to change customerContactUs flag (star) value
                // ------------------------------------------------------------------
                starHandle: function(customerContactUs){
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                },
                // ------------------------------------------------------------------
                // function to change customer flag value
                // object parameter can be either customerLogActivity, customerContactUs,
                // or customerSearchHistory
                // ------------------------------------------------------------------
                flagHandle: function (object) {
                    // ------------------------------------------------------------------
                    // initialize variables
                    // ------------------------------------------------------------------
                    customer_flag = object.customer.flag;
                    customer_id = object.customer.id;
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // filter customer log activitites array 
                    // ------------------------------------------------------------------
                    var customerLogs = io.filter( this.customerLogActivities, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer_id === customerID;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // each data filtered, update it's flag property
                    // ------------------------------------------------------------------
                    io.each( customerLogs, function( history ){
                        history.customer.flag = customer_flag;
                    });
                    // ------------------------------------------------------------------
                    
                    // ------------------------------------------------------------------
                    // filter customer search history array 
                    // ------------------------------------------------------------------
                    var customerSearchs = io.filter( this.customerSearchHistories, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer_id === customerID;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // each data filtered, update it's flag property
                    // ------------------------------------------------------------------
                    io.each( customerSearchs, function( history ){
                        history.customer.flag = customer_flag;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // filter customer search history array 
                    // ------------------------------------------------------------------
                    var customerContacs = io.filter( this.customersContactUs, function( history ){
                        var customerID = io.get( history, 'customer.id' );
                        return customer_id === customerID;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // each data filtered, update it's flag property
                    // ------------------------------------------------------------------
                    io.each( customerContacs, function( history ){
                        history.customer.flag = customer_flag;
                    });
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                    // ------------------------------------------------------------------
                },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush