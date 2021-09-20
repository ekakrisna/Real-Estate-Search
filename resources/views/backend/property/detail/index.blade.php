@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.property')}}">@lang('label.list_of_properties')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    <a href="{{route('admin.property.edit', $property->id)}}" class="btn btn-info float-sm-right mb-2 mb-xl-0">@lang('label.edit_property_information')</a>
    <a href="{{route('admin.property.delivery', $property->id)}}" class="btn btn-danger float-sm-right">@lang('label.deliver_property_information')</a>
@endsection

@section('content')
    
    @component('backend._components.plain_text', [ 'label' => __('label.report_bug'), 'value' => empty($property->is_bug_report)? '- ': __('label.report_display')]) @endcomponent
    @component('backend._components.plain_text', [ 'label' => __('property.create.label.status'), 'value' => $property->property_status->label ]) @endcomponent
    @component('backend._components.plain_text', [ 'label' => __('label.is_reserve'), 'value' =>  empty($property->is_reserve)? __('label.none'): __('label.yes')]) @endcomponent
    <div class="row form-group">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
            @lang('label.property_id')
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">
            <div class="row">
                @foreach($property->property_publish as $publish)
                <div class="col-12 col-content">
                    <p>{{$publish->publication_destination}} : {{$publish->property_number}}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @component('backend._components.plain_text', [ 'label' => __('label.location'), 'value' => $property->location]) @endcomponent

    {{-- condition for showing price range --}}
    @if ($property->minimum_price && $property->maximum_price)
        @component('backend._components.plain_text', [ 
            'label' => __('label.selling_price'), 
            'value' => toManDisplay($property->minimum_price) . ' ~ ' .toManDisplay($property->maximum_price)]) 
        @endcomponent
    @elseif($property->minimum_price && $property->maximum_price == null)
        @component('backend._components.plain_text', [ 
            'label' => __('label.selling_price'), 
            'value' => toManDisplay($property->minimum_price)]) 
        @endcomponent
    @elseif($property->minimum_price == null && $property->maximum_price)
        @component('backend._components.plain_text', [ 
            'label' => __('label.selling_price'), 
            'value' => toManDisplay($property->maximum_price)]) 
        @endcomponent
    @else
        @component('backend._components.plain_text', [ 
            'label' => __('label.price'), 
            'value' => __('label.none')]) 
        @endcomponent
    @endif

    {{-- condition for showing land area range --}}
    @if ($property->minimum_land_area && $property->maximum_land_area)
        @component('backend._components.plain_text', [ 
            'label' => __('label.land_area'), 
            'value' => numeral(toTsubo($property->minimum_land_area), '0,0').'坪('.$property->minimum_land_area.'㎡)' . ' ~ ' . numeral(toTsubo($property->maximum_land_area), '0,0').'坪('.$property->maximum_land_area.'㎡)'  ]) 
        @endcomponent
    @elseif($property->minimum_land_area && $property->maximum_land_area == null)
        @component('backend._components.plain_text', [ 
            'label' => __('label.land_area'), 
            'value' => numeral(toTsubo($property->minimum_land_area), '0,0').'坪('.$property->minimum_land_area.'㎡)'])
        @endcomponent
    @elseif($property->minimum_land_area == null && $property->maximum_land_area)
        @component('backend._components.plain_text', [ 
            'label' => __('label.land_area'), 
            'value' => numeral(toTsubo($property->maximum_land_area), '0,0').'坪('.$property->maximum_land_area.'㎡)']) 
        @endcomponent
    @else
        @component('backend._components.plain_text', [ 
            'label' => __('label.land_area'), 
            'value' => __('label.none')]) 
        @endcomponent
    @endif

    @component('backend._components.plain_text', [ 'label' => __('label.building_condition'), 'value' => $property->building_conditions_desc ?? __('label.none')]) @endcomponent

    @component('backend._components.plain_text', [ 'label' => __('label.property_contact_us'), 'value' => $property->contact_us]) @endcomponent
    
    @component('backend._components.plain_text', [ 'label' => __('label.publication_date_and_time'), 'value' => $property->ja->publish_date ?? '']) @endcomponent

    @component('backend._components.plain_text', [ 'label' => __('label.last_update_date_and_time'), 'value' => $property->ja->updated_date]) @endcomponent

    <div class="row form-group">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
            @lang('label.publication_medium')
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
            <div class="row">
                @foreach($property->property_publish as $publish)
                <div class="col-12 col-content">
                    @if($publish->url === "")
                        <p>{{$publish->publication_destination}}</p>
                    @else
                    <a href="{{$publish->url}}">
                        {{$publish->publication_destination}}
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    

    @component('backend._components.input_show_images', [ 'label' => __('label.photo'), 'images' => $property->property_photos]) @endcomponent    

    @component('backend._components.input_show_flyer', [ 'label' => __('label.flyer'), 'images' => $property->property_flyers]) @endcomponent
    {{-- B22-1 Property  Info End--}}

    {{-- B13-4 Customer Property log activity Info--}}

    @component('backend._components.section_header', [ 'label' => __('label.change_log')]) @endcomponent
    
    {{-- B13-4 Customer Property log activity Info End--}}
    <!-- Result filters - Start -->
    <Filters></Filters>
    <!-- Result filters - End -->

    <hr class="my-4" />

    <div class="tablelike mt-2">

        <!-- Table header - Start -->
        <div class="tablelike-header border-top border-right d-none d-lg-block">
            <div class="row mx-0">
                <div class="px-0 border-left bg-light col-lg-160px"><div class="py-2 px-2">@lang('label.datetime')</div></div>
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
@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')
@relativeInclude('vue.pagination.import')

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
                name: 'index', path: '/admin/property/' + @json( $property->id ) , 
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
                console.log( state );
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
                return {}
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
            methods: {},
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
                        var url = @json( route( 'admin.property.detail.filter', $property->id  ));
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