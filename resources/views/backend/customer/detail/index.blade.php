@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}"> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{$customer_detail->name}} @lang('label.detail')</li>
    </ol>
@endsection

@section('content')

    <div class="content-header">
        <div class="container-fluid pb-2 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-6">
                    <p class="m-0 text-dark h1title position-absolute bottom-0" >
                        ■ @lang('label.customer_basic_information')
                    </p>
                </div>
                <div class="col-sm-6">
                    <a href="{{route('admin.customer.edit', $id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-12">

                    {{-- B5-1-1, B5-1-2, B5-1-3 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.name')
                                </div>
                                <div class="col-sm-6">
                                    <input type="hidden" name="customer-flag" value="{{$id}}">
                                    {{$customer_detail->name}} &nbsp

                                    <button type="button" class="btn btn-sm btn-default" @click.prevent="flagHandle(customer)">
                                        <i :class="[`${flag}`]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.license')
                                </div>
                                <div class="col-sm-6">
                                    @if ($customer_detail->license == 1)
                                        有効
                                    @else
                                        無効
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B5-1-5, B5-1-6, B5-1-7 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.e-mail')
                                </div>
                                <div class="col-sm-6">
                                    {{$customer_detail->email}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.person_charge')
                                </div>
                                <div class="col-sm-6">
                                    {{$customer_detail->company_user->name ?? 'ー'}}
                                    {{!isset($customer_company->company_name) ? '' :'('.$customer_company->company_name.')' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B5-1-8 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.phone')
                                </div>
                                <div class="col-sm-6">
                                    {{$customer_detail->phone}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- B5-1-9 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.desired_area')
                                </div>
                                <div class="col-sm-6">
                                    @foreach ($customer_desired_area as $cda)
                                    <p>{{$cda->city->prefecture->name}}{{$cda->city->name}}{{$cda->city_areas != null  ? $cda->city_areas->display_name :''}}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B5-1-10, B5-1-11, B5-1-12, B5-1-13 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.min_consideration')
                                </div>
                                <div class="col-sm-6">
                                    @if(!empty($customer_detail->minimum_price) && !empty($customer_detail->maximum_price))
                                        {{toManDisplay($customer_detail->minimum_price) }} ~ {{ toManDisplay($customer_detail->maximum_price)}}
                                    @elseif(!empty($customer_detail->minimum_price) && empty($customer_detail->maximum_price))
                                        {{toManDisplay($customer_detail->minimum_price) }}  ~ @lang('label.no_upper_limit')
                                    @elseif(empty($customer_detail->minimum_price) && !empty($customer_detail->maximum_price))
                                        @lang('label.no_lower_limit') ~ {{ toManDisplay($customer_detail->maximum_price)}}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.min_land')
                                </div>
                                <div class="col-sm-6">
                                    @if(!empty($customer_detail->minimum_price_land_area) && !empty($customer_detail->maximum_price_land_area))
                                        {{ toManDisplay($customer_detail->minimum_price_land_area) }} ~ {{ toManDisplay($customer_detail->maximum_price_land_area)}}
                                    @elseif(!empty($customer_detail->minimum_price_land_area) && empty($customer_detail->maximum_price_land_area))
                                    {{ toManDisplay($customer_detail->minimum_price_land_area) }} ~ @lang('label.no_upper_limit')
                                    @elseif(empty($customer_detail->minimum_price_land_area) && !empty($customer_detail->maximum_price_land_area))
                                        @lang('label.no_lower_limit') ~  {{ toManDisplay($customer_detail->maximum_price_land_area)}}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B5-1-14, B5-1-15 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.min_desired_land')
                                </div>
                                <div class="col-sm-6">
                                    @if(!empty($customer_detail->minimum_land_area) && !empty($customer_detail->maximum_land_area))
                                        {{ numeral(toTsubo($customer_detail->minimum_land_area),'0,0')  }} 坪 ~ {{ numeral(toTsubo( $customer_detail->maximum_land_area),'0,0') }} 坪
                                    @elseif(!empty($customer_detail->minimum_land_area) && empty($customer_detail->maximum_land_area))
                                        {{ numeral(toTsubo($customer_detail->minimum_land_area),'0,0') }} 坪 ~ @lang('label.no_upper_limit')
                                    @elseif(empty($customer_detail->minimum_land_area) && !empty($customer_detail->maximum_land_area))
                                        @lang('label.no_lower_limit') ~ {{ numeral(toTsubo($customer_detail->maximum_land_area),'0,0') }} 坪
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B5-1-16, B5-1-17 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.register_date_time')
                                </div>
                                <div class="col-sm-6">
                                    @if($customer_detail->created_at<>"")
                                    {{$customer_detail->ja->created_at}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.last_use_date')
                                </div>
                                <div class="col-sm-6">
                                    @if($customer_last_use_date<>"")
                                    {{$customer_last_use_date->ja->access_time}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B5-1-18, B5-1-19, B5-1-20, B5-1-21 Section --}}
                    <div class=" d-flex align-items-center pt-3 pb-3 border-bottom">
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.fav_area_count')
                                </div>
                                <div class="col-sm-6">
                                    {{$customer_favorite_properties_count}} 件 <a href="{{route('admin.customer.fav_history', $id)}} ">(@lang('label.show_fav_area'))</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    @lang('label.view_detail_viewed_properties')
                                </div>
                                <div class="col-sm-6">
                                    {{$property_checked_count}}  件 <a href="{{route('admin.customer.use_history.index', $id)}}">(@lang('label.show_detail_properties_count'))</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- B5-2 Section --}}
    <div class="content-header">
        <div class="container-fluid pb-2 mt-4 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-12">
                <p class="m-0 text-dark h1title">
                    ■ @lang('label.recent_usage_history')
                    <a href="{{route('admin.customer.use_history.index', $id)}}">(@lang('label.view_all_list_of_usage_history'))</a> <!--route('admin.customer.$id.use_history')-->
                </p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Result filters - Start -->

            <div class="tablelike">

                <!-- Table header - Start -->
                <div class="tablelike-header border-top border-right d-none d-xl-block">
                    <div class="row mx-0">
                        <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
                        <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.action')</div></div>
                        <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                        <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                        <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
                        <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
                        <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.building_condition')</div></div>
                        <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.favorite')</div></div>
                    </div>
                </div>
                <!-- Table header - End -->


                <div class="tablelike-content">
                    <div class="tablelike-result">

                        <div v-if="cust_log.length == 0" class="text-center py-2 px-2 border">
                            <span>対象のレコードはありません。</span>
                        </div>

                        <template v-else>
                            <div v-for="log in cust_log" :key="log.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">

                                <div class="row mx-0">
                                    <!-- Date column - Start -->
                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.datetime')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ log.ja.access_time }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-150px">
                                        <div class="border-top d-lg-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.action')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ log.action_type.label }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-90px">
                                        <div class="border-top d-lg-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.property_id')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div v-if="log.property" class="py-2 px-2">
                                                    <a :href="log.property.url.view"  target="_blank" rel="noopener noreferrer">@{{ log.property.id }}</a>
                                                </div>
                                                <div v-else class="py-2 px-2">-</div>
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
                                                <div class="py-2 px-2">@lang('label.location')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div v-if="log.property" class="py-2 px-2">
                                                    <div class="py-2 px-2">@{{ log.property.location }}</div>
                                                </div>
                                                <div v-else class="py-2 px-2">-</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-120px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.selling_price')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div v-if="log.property" class="py-2 px-2">
                                                <div v-if="log.property.minimum_price && log.property.maximum_price" class="py-2 px-2">
                                                        <span>@{{ log.property.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                                                        <span>~</span>
                                                        <span>@{{ log.property.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                                                    </div>

                                                    <div v-else-if="log.property.minimum_price && !log.property.maximum_price" class="py-2 px-2">
                                                        <span>@{{ log.property.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                                                    </div>

                                                    <div v-else-if="!log.property.minimum_price && log.property.maximum_price" class="py-2 px-2">
                                                        <span>@{{ log.property.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                                                    </div>

                                                    <div v-else class="py-2 px-2">
                                                        <span>なし</span>
                                                    </div>
                                                </div>
                                                <div v-else class="py-2 px-2">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Company column - End -->

                                    <!-- Email column - Start -->
                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-120px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.land_area')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div v-if="log.property" class="py-2 px-2">
                                                    <div v-if="log.property.minimum_land_area && log.property.maximum_land_area" class="py-2 px-2">
                                                        <span>@{{ log.property.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                                                        <span>~</span>
                                                        <span>@{{ log.property.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                                                    </div>

                                                    <div v-else-if="log.property.minimum_land_area && !log.property.maximum_land_area" class="py-2 px-2">
                                                        <span>@{{ log.property.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                                                    </div>

                                                    <div v-else-if="!log.property.minimum_land_area && log.property.maximum_land_area" class="py-2 px-2">
                                                        <span>@{{ log.property.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                                                    </div>

                                                    <div v-else class="py-2 px-2">
                                                        <span>なし</span>
                                                    </div>
                                                </div>
                                                <div v-else class="py-2 px-2">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email column - End -->

                                    <!-- Column break - Columns below this will start a new row - Start -->
                                    <div class="col-12 d-xl-none"></div>
                                    <!-- Column break - Columns below this will start a new row - End -->

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-100px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.building_condition')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div v-if="log.property" class="py-2 px-2">
                                                    <span>@{{ log.property.building_conditions_desc }}</span>
                                                </div>
                                                <div v-else class="py-2 px-2">-</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-90px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.favorite')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2 justify-content-center d-flex align-items-center">
                                                    <div v-if="log.customer_favorite_property.length" class="py-2 px-2">
                                                        <i class="far fa-circle"></i>
                                                    </div>
                                                    <div v-else class="py-2 px-2">
                                                        -
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="border-bottom d-xl-none"></div>
                            </div>
                        </template>

                        <div class="border-bottom d-none d-xl-block"></div>

                    </div>
                </div>
                <!-- Table content - End -->


            </div>

        </div>
    </section>
    {{-- B5-2 Section --}}


    {{-- B5-3 Section --}}
    <div class="content-header">
        <div class="container-fluid pb-2 mt-4 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-12">
                <p class="m-0 text-dark h1title">
                    ■ @lang('label.recent_search_history')
                    <a href="{{route('admin.customer.search_history', $id)}}">( @lang('label.view_all_list_of_search_history') ) </a>
                </p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Result filters - Start -->

            <div class="tablelike">

                <!-- Table header - Start -->
                <div class="tablelike-header border-top border-right d-none d-xl-block">
                    <div class="row mx-0">
                        <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
                        <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                        <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
                        <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
                    </div>
                </div>
                <!-- Table header - End -->


                 <div class="tablelike-content">
                    <div class="tablelike-result">

                        <div v-if="history.length == 0" class="text-center py-2 px-2 border">
                            <span>対象のレコードはありません。</span>
                        </div>

                        <template v-else>
                            <div v-for="hist in history" :key="hist.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">

                                <div class="row mx-0">
                                    <!-- Date column - Start -->

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.datetime')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ hist.ja.created_at }}</div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Active status column - Start -->
                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.location')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ hist.location }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Column break - Columns below this will start a new row - Start -->
                                    <div class="col-12 d-xl-none"></div>
                                    <!-- Column break - Columns below this will start a new row - End -->

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.selling_price')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div v-if="hist.minimum_price && hist.maximum_price" class="py-2 px-2">
                                                    <span>@{{ hist.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                                                    <span>~</span>
                                                    <span>@{{ hist.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                                                </div>

                                                <div v-else-if="hist.minimum_price && !hist.maximum_price" class="py-2 px-2">
                                                    <span>@{{ hist.minimum_price | toMan | numeral('0,0.[00]') }}</span>
                                                    <span>~</span>
                                                    <span> @lang('label.no_upper_limit') </span>
                                                </div>

                                                <div v-else-if="!hist.minimum_price && hist.maximum_price" class="py-2 px-2">
                                                    <span> @lang('label.no_lower_limit') </span>
                                                    <span>~</span>
                                                    <span>@{{ hist.maximum_price | toMan | numeral('0,0.[00]') }}</span>
                                                </div>

                                                <div v-else class="py-2 px-2">
                                                    <span>なし</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Active status column - Start -->
                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.land_area')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div v-if="hist.minimum_land_area && hist.maximum_land_area" class="py-2 px-2">
                                                    <span>@{{ hist.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                                                    <span>~</span>
                                                    <span>@{{ hist.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                                                </div>

                                                <div v-else-if="hist.minimum_land_area && !hist.maximum_land_area" class="py-2 px-2">
                                                    <span>@{{ hist.minimum_land_area | toTsubo | numeral('0,0') }}</span>
                                                    <span>~</span>
                                                    <span> @lang('label.no_upper_limit') </span>
                                                </div>

                                                <div v-else-if="!hist.minimum_land_area && hist.maximum_land_area" class="py-2 px-2">
                                                    <span> @lang('label.no_lower_limit') </span>
                                                    <span>~</span>
                                                    <span>@{{ hist.maximum_land_area | toTsubo | numeral('0,0') }}</span>
                                                </div>

                                                <div v-else class="py-2 px-2">
                                                    <span>なし</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="border-bottom d-xl-none"></div>
                            </div>
                        </template>

                        <div class="border-bottom d-none d-xl-block"></div>

                    </div>
                </div>
                <!-- Table content - End -->

            </div>

        </div>
    </section>


    {{-- B5-4 Section --}}
    <div class="content-header">
        <div class="container-fluid pb-2 mt-4 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-12">
                <p class="m-0 text-dark h1title">
                    ■ @lang('label.inquiries')
                    <a href="{{route('admin.customer.contact_history', $id)}}">( @lang('label.view_all_list_of_contact_us') ) </a>
                </p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Result filters - Start -->

            <div class="tablelike">

                <!-- Table header - Start -->
                <div class="tablelike-header border-top border-right d-none d-xl-block">
                    <div class="row mx-0">
                        <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.favorite')</div></div>
                        <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.contact_id')</div></div>
                        <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
                        <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.dashboard_status')</div></div>
                        <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                        <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.contents')</div></div>
                        <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.detail')</div></div>
                    </div>
                </div>
                <!-- Table header - End -->

                <!-- Table content - Start -->
                 <div class="tablelike-content">
                    <div class="tablelike-result">

                        <div v-if="contact.length == 0" class="text-center py-2 px-2 border">
                            <span>対象のレコードはありません。</span>
                        </div>

                        <template v-else>
                            <div v-for="(cont, index) in contact" :key="cont.id" class="tablelike-item border-top border-right mt-3 mt-xl-0">

                                <div class="row mx-0">

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-100px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.favorite')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden text-lg-center text-xl-center">
                                               <div class="py-2 px-2">
                                                    <button type="button" class="btn btn-sm btn-default fs-13" @click.prevent="starHandle(cont)">
                                                         <div v-if="cont.flag==1">
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div v-else>
                                                            <i class="far fa-star"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-120px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.contact_id')</div>
                                            </div>
                                            <div v-if="cont && cont.url " class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2"><a :href="cont.url.detail" target="_blank" rel="noopener noreferrer">@{{ cont.id }}</a></div>
                                            </div>
                                            <div v-else>
                                                cont もしくはcont.urlが無効の状態です
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-150px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.datetime')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2">@{{ cont.ja.created_at }}</div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Column break - Columns below this will start a new row - Start -->
                                    <div class="col-12 d-xl-none"></div>
                                    <!-- Column break - Columns below this will start a new row - End -->


                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-120px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.dashboard_status')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div v-if="cont.is_finish==1" class="py-2 px-2">
                                                    <span>対応済み</span>
                                                </div>
                                                <div v-else class="py-2 px-2">未対応</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left border-bottom col-12 col-lg-200px col-xl-120px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.property_id')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden">
                                                <div v-if="cont.property" class="py-2 px-2">
                                                    <a :href="cont.property.url.view"  target="_blank" rel="noopener noreferrer">@{{ cont.properties_id }}</a>
                                                </div>
                                                <div v-else class="py-2 px-2">なし</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-0 d-flex flex-column border-left border-bottom col-12 col-lg ellipsis">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.contents')</div>
                                            </div>
                                            <div class="px-0 col col-xl-12 overflow-hidden">
                                                <div class="py-2 px-2">
                                                    <div class="row mx-n1">
                                                        <div class="px-1 col ellipsis d-flex align-items-center">
                                                            <div class="ellipsis">@{{ cont.text }}</div>
                                                        </div>
                                                        <div class="px-1 col-50px" v-for="modal in [ 'modal-inquiry-' + ( index +1 )]">

                                                            <button type="button" class="btn btn-sm btn-default fs-12 px-2 " data-toggle="modal" :data-target="'#' + modal">
                                                                <span>@lang('label.display')</span>
                                                            </button>

                                                            <!-- Content modal - Start -->
                                                            <div class="modal fade" :id="modal" tabindex="-1" :aria-labelledby="modal" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">@lang('label.contents')</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="text-wrap" v-html="cont.text"></p>
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

                                    <!-- Column break - Columns below this will start a new row - Start -->
                                    <div class="col-12 d-xl-none"></div>
                                    <!-- Column break - Columns below this will start a new row - End -->

                                    <div class="px-0 d-flex flex-column border-left col-12 col-lg-200px col-xl-120px">
                                        <div class="border-top d-xl-none"></div>
                                        <div class="row mx-0 flex-nowrap flex-grow-1">
                                            <div class="px-0 col-100px border-right d-xl-none bg-light">
                                                <div class="py-2 px-2">@lang('label.detail')</div>
                                            </div>
                                            <div class="px-0 col col-lg-12 overflow-hidden text-center">
                                                <div class="py-2 px-2">
                                                     <div v-if="cont&&cont.url" class="col-12 mt-2 mt-sm-0">
                                                        <a class="btn btn-info btn-sm btn-block" :href="cont.url.detail"  target="_blank" rel="noopener noreferrer">@lang('label.detail')</a>
                                                    </div>
                                                    <div v-else>
                                                        cont: cont もしくはcont.urlが無効の状態です
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="border-bottom d-xl-none"></div>
                            </div>
                        </template>

                        <div class="border-bottom d-none d-xl-block"></div>

                    </div>
                </div>
                <!-- Table content - End -->
                </br>
                <span class="float-sm-right"><a href="{{route('admin.customer.contact_history', $id)}}">@lang('label.show_list_contact')</a></span>

            </div>

        </div>
    </section>


@endsection



@push('vue-scripts')

<script> @minify
    (function( $, io, document, window, undefined ){
        router = {
        };

        store = {
        };

        mixin = {
            data: function(){
                return {
                    cust : @json( $customer_detail ),
                    cust_log : @json( $customer_log ),
                    contact : @json( $customers_contact_us ),
                    history : @json( $customer_search_histories ),
                }
            },

            mounted: function(){
            },

            computed: {
                customer : function() {
                    return this.cust;
                },
                flag: function(){
                    if( this.customer.flag  == 1 ) {
                        return 'fas fa-flag';
                    } else {
                        return 'far fa-flag';
                    }
                },
            },

            methods: {
                flagHandle: function(customer){
                    var vm = this;
                    const url =  customer.url.change_flag;
                    var request = axios.get( url);
                    request.then( function( response ){
                        customer.flag = response.data.flag;
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, {
                            type: 'success'
                        });
                    });
                },
                starHandle: function(contact){
                    var vm = this;
                    const url =  contact.url.change_contact_flag;
                    var request = axios.get( url);
                    let icon = $(this).children();
                    request.then( function( response ){
                        contact.flag = response.data.flag;
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, {
                            type: 'success'
                        });
                    });
                }
            },

            watch: {
            }
        };
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
