@extends('backend._base.content_form_no_header')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        @if ($customer->id)
        <li class="breadcrumb-item"><a href="{{route('admin.customer.detail', $customer->id)}}">{{$customer->name}} @lang('label.detail')</a></li>
        @endif
        @if ($customer->id)
        <li class="breadcrumb-item"><a href="{{route('admin.customer.contact_history', $customer->id)}}">@lang('label.inquiry_list')</a></li>
        @endif
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('content')
    {{-- B13-1 Customer Info --}}
    <div class="row border-bottom border-dark py-2">
        <div class="col-6">
            ■ @lang('label.customer_basic_information')
            <span class="ml-2"> <i class="fas fa-sort-down" style="font-size: 30px"></i></span>
        </div>
        <div class="col-6">
            @if ($customer->id)                        
                <a href="{{route('admin.customer.edit', $customer->id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>                            
            @else            
                <a href="#" class="btn btn-info float-sm-right">@lang('label.edit')</a>            
            @endif
        </div>
    </div>
    
    <div class="row form-group border-0 mt-2">
        <div class="col-6">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                    @lang('label.name')
                </div>
                <div class="col-6 col-sm-9 col-md-6 col-lg-5 col-content" v-if="customer.name">
                    {{$customer->name}}
                </div>
                <div class="col-6 col-sm-9 col-md-6 col-lg-5 col-content" v-else>
                    {{$customer_contact_us->name}}
                </div>
                @if ($customer->id)
                <div class="col-6 col-sm-3 col-md-3 col-lg-5 col-content">

                    <!-- Flag toggle button - Start -->
                    <button-toggle v-model="customer.flag" :api="customer.url.flag" 
                        @input="flagHandle( customer )">
                    </button-toggle>
                    <!-- Flag toggle button - End -->

                </div>
                @endif                
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header ">
                    @lang('label.person_charge')
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                    {{$customer->company_user->name ?? 'ー'}} 
                    {{!isset($customer->company_user) ? '' :'('.$customer->company_user->company->company_name.')' }}
                </div>
            </div>
        </div>
    </div>


    {{-- B13-1 Customer Info End--}}
    
    {{-- B13-2 Customer Contact us Info--}}

    @component('backend._components.section_header', [ 'label' => __('label.inquiry_detail')]) @endcomponent

    <div class="row form-group">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
            @lang('label.contact_id')
        </div>
        <div class="col-6 col-sm-9 col-md-6 col-lg-5 col-content">
            {{$customer_contact_us->id}}
        </div>
        <div class="col-6 col-sm-3 col-md-3 col-lg-5 col-content">

            <!-- Star toggle button - Start -->
            <button-toggle v-model="customerContactUs.flag" :api="customerContactUs.url.change_star" :icon="'star'" @input="starHandle(customerContactUs)"></button-toggle>
            <!-- Star toggle button - End -->

        </div>
    </div>

    @component('backend._components.plain_text', [ 'label' => __('label.contact_us_date_and_time'), 'value' => $customer_contact_us->ja->created_at]) @endcomponent
    
    @component('backend._components.plain_text', [ 'label' => __('label.content_of_inquiry'), 'value' => $customer_contact_us->text]) @endcomponent

    @component('backend._components.form_container', ["action" => $form_action, "page_type" => $page_type, "files" => false])
        {{-- input radio --}}
        @component('backend._components.input_radio_custom', ['name' => 'is_finish', 'options' => [__('label.not_compatible'), __('label.acknowledged')], 'label' => __('label.customer_contact_us_status'), 'required' => 1, 'value' => $customer_contact_us->is_finish]) @endcomponent

        {{-- input title --}}
        @component('backend._components.input_text_custom', ['name' => 'person_in_charge', 'label' => __('label.in_charge_staff'), 'required' => 1, 'value' => $customer_contact_us->person_in_charge]) @endcomponent

        {{-- input note --}}
        @component('backend._components.input_text_area_custom', ['name' => 'note', 'label' => __('label.note'), 'required' => 1, 'value' => $customer_contact_us->note]) @endcomponent

        {{-- input button --}}
        @component('backend._components.input_buttons', ['page_type' => $page_type, 'create' => __('label.save'), 'update' => __('label.save')])@endcomponent
    @endcomponent
    {{-- B13-2 Customer Contact us Info End--}}

    @if(!empty($customer_contact_us->property->id))
        {{-- B13-3 Property  Info--}}
        @component('backend._components.section_header', [ 'label' => __('label.property_information')]) @endcomponent

        @component('backend._components.plain_text', [ 'label' => __('label.property_id'), 'value' => $customer_contact_us->property->id]) @endcomponent

        @component('backend._components.plain_text', [ 'label' => __('label.location'), 'value' => $customer_contact_us->property->location]) @endcomponent

        {{-- condition for showing price range --}}
        @if ($customer_contact_us->property->minimum_price && $customer_contact_us->property->maximum_price)
            @component('backend._components.plain_text', [ 
                'label' => __('label.selling_price'), 
                'value' => toManDisplay($customer_contact_us->property->minimum_price) . ' ~ ' . toManDisplay($customer_contact_us->property->maximum_price) ]) 
            @endcomponent
        @elseif($customer_contact_us->property->minimum_price && $customer_contact_us->property->maximum_price == null)
            @component('backend._components.plain_text', [ 
                'label' => __('label.selling_price'), 
                'value' => toManDisplay($customer_contact_us->property->minimum_price)]) 
            ])  
            @endcomponent
        @elseif($customer_contact_us->property->minimum_price == null && $customer_contact_us->property->maximum_price)
            @component('backend._components.plain_text', [ 
                'label' => __('label.selling_price'), 
                'value' => toManDisplay($customer_contact_us->property->maximum_price) ]) 
            @endcomponent
        @else
            @component('backend._components.plain_text', [ 
                'label' => __('label.price'), 
                'value' => __('label.none')]) 
            @endcomponent
        @endif

        {{-- condition for showing land area range --}}
        @if ($customer_contact_us->property->minimum_land_area && $customer_contact_us->property->maximum_land_area)
            @component('backend._components.plain_text', [ 
                'label' => __('label.land_area'), 
                'value' => numeral(toTsubo($customer_contact_us->property->minimum_land_area), '0,0') . ' ~ ' . numeral(toTsubo($customer_contact_us->property->maximum_land_area), '0,0') ]) 
            @endcomponent
        @elseif($customer_contact_us->property->minimum_land_area && $customer_contact_us->property->maximum_land_area == null)
            @component('backend._components.plain_text', [ 
                'label' => __('label.land_area'), 
                'value' => numeral(toTsubo($customer_contact_us->property->minimum_land_area), '0,0')
            ]) 
            @endcomponent
        @elseif($customer_contact_us->property->minimum_land_area == null && $customer_contact_us->property->maximum_land_area)
            @component('backend._components.plain_text', [ 
                'label' => __('label.land_area'), 
                'value' => numeral(toTsubo($customer_contact_us->property->maximum_land_area), '0,0')
            ]) 
            @endcomponent
        @else
            @component('backend._components.plain_text', [ 
                'label' => __('label.land_area'), 
                'value' => __('label.none')]) 
            @endcomponent
        @endif

        @component('backend._components.plain_text', [ 'label' => __('label.building_condition'), 'value' => $customer_contact_us->property->building_conditions_desc ?? __('label.none')]) @endcomponent

        @component('backend._components.plain_text', [ 'label' => __('label.property_contact_us'), 'value' => $customer_contact_us->property->contact_us]) @endcomponent
        
        @component('backend._components.plain_text', [ 'label' => __('label.publication_date_and_time'), 'value' => $customer_contact_us->property->ja->publish_time ?? '']) @endcomponent

        @component('backend._components.plain_text', [ 'label' => __('label.last_update_date_and_time'), 'value' => $customer_contact_us->property->ja->updated_at]) @endcomponent

        <div class="row form-group">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                @lang('label.publication_medium')
            </div>
            <div class="row">
                @foreach($customer_contact_us->property->property_publish as $publish)
                <div class="col-12 col-content">
                    <a href="{{$publish->url}}">
                        {{$publish->publication_destination}}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        
        @component('backend._components.input_show_images', [ 'label' => __('label.photo'), 'images' => $customer_contact_us->property->property_photos]) @endcomponent    

        @component('backend._components.input_show_images', [ 'label' => __('label.flyer'), 'images' => $customer_contact_us->property->property_flyers]) @endcomponent


        {{-- B13-3 Property  Info End--}}

        {{-- B13-4 Customer Property log activity Info--}}

        @component('backend._components.section_header', [ 'label' => __('label.change_log')]) @endcomponent  

        <!-- Result filters - Start -->
        <Filters></Filters>
        <!-- Result filters - End -->

        <hr class="my-4" />

        <div class="tablelike mt-2">

            <!-- Table header - Start -->
            <div class="tablelike-header border-top border-right d-none d-lg-block">
                <div class="row mx-0">
                    <div class="px-0 border-left bg-light col-lg-160px"><div class="py-2 px-2">@lang('label.last_update')</div></div>
                    <div class="px-0 border-left bg-light col-lg-160px"><div class="py-2 px-2">@lang('label.update_type')</div></div>
                    <div class="px-0 border-left bg-light col-lg"><div class="py-2 px-2">@lang('label.before_update')</div></div>
                    <div class="px-0 border-left bg-light col-lg"><div class="py-2 px-2">@lang('label.after_update')</div></div>
                </div>
            </div>
            <!-- Table header - End -->


            <!-- Table content - Start -->
            <div class="tablelike-content">
                
                <!-- Loading placeholder - Start -->
                <Placeholder v-if="isLoading" :count="$store.state.config.placeholder"></Placeholder>
                <!-- Loading placeholder - End -->

                <!-- Result items - Start -->
                <Result v-else v-model="resultData"></Result>
                <!-- Result items - End -->

            </div>
            <!-- Table content - End -->
            

        </div>

        <div class="mt-3">
            <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
        </div>

        <router-view></router-view>
        
        {{-- B13-4 Customer Property log activity Info End--}}
    @endif
@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')
@relativeInclude('vue.pagination.import')

<!-- Order and perpage filter filters - Start -->
@include('backend.vue.components.filter-order.import')
@include('backend.vue.components.filter-perpage.import')
<!-- Order and perpage filter filters - End -->

@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vue router
        // ----------------------------------------------------------------------
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/admin/contact/' + @json( $customer_contact_us->id ) + '/detail', 
                component: { template: '<div/>' }
            }]
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Vuex store - Centralized data
        // ----------------------------------------------------------------------
        store = {
            // ------------------------------------------------------------------
            // Reactive central data
            // ------------------------------------------------------------------
            state: function(){
                var state = {
                    // ----------------------------------------------------------
                    // Status flags
                    // ----------------------------------------------------------
                    status: { loading: false },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Configs
                    // ----------------------------------------------------------
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Preset data
                    // ----------------------------------------------------------
                    preset: {
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // Order options
                        // Reminder:
                        // Ordering based on relation requires Join query in Laravel
                        // Eager loading doesn't support ordering based on relation
                        // ------------------------------------------------------
                        orders: [
                            { id: 'created_at', label: '@lang('label.last_update')' },
                            { id: 'before_update_text', label: '@lang('label.before_update')' },
                            { id: 'after_update_text', label: '@lang('label.after_update')' },
                        ]
                        // ------------------------------------------------------
                    },
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Request result will go here
                    // ----------------------------------------------------------
                    result: null 
                    // ----------------------------------------------------------
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // console.log( state );
                return state;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Updating state data will need to go through these mutations
            // ------------------------------------------------------------------
            mutations: {
                // --------------------------------------------------------------
                // Set loading state
                // --------------------------------------------------------------
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', loading );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Set result
                // --------------------------------------------------------------
                setResult: function( state, result ){
                    Vue.set( state, 'result', result );
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
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
                    cust : @json( $customer ),
                    custContactUs : @json( $customer_contact_us),
                    customerFlagValue : @json( $customer->flag ),
                    custContactUsFlagValue : @json( $customer_contact_us->flag),
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
            computed: {
                customer : function() {
                    return this.cust;
                },
                customerContactUs : function() {
                    return this.custContactUs;
                },
                // --------------------------------------------------------------
                // Loading state
                // --------------------------------------------------------------
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Meta data of the paginated result
                // --------------------------------------------------------------
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Data of the paginated result
                // --------------------------------------------------------------
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                flagHandle: function(customer){
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                },
                starHandle: function(customerContactUs){
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // Watch the route changes
                // This will run everytime the route is changing
                // It runs immediately after page load
                // --------------------------------------------------------------
                $route: {
                    immediate: true, 
                    handler: function( to, from ){
                        // ------------------------------------------------------
                        // Perform data request
                        // ------------------------------------------------------
                        var store = this.$store;
                        var url = @json( route( 'admin.property.detail.filter', empty($customer_contact_us->property->id) ? '': $customer_contact_us->property->id));
                        var request = axios.post( url, { filter: to.query });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        store.commit('setLoading'); // Set loading state
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // On success
                        // ------------------------------------------------------
                        request.then( function( response ){
                            // console.log( response );
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var result = io.get( response, 'data.result' );
                            // --------------------------------------------------
                            if( 200 === status && result ){
                                store.commit( 'setResult', result );
                            }
                            // --------------------------------------------------
                        });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        request.finally( function(){ store.commit('setLoading', false )});
                        // ------------------------------------------------------
                    }
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush