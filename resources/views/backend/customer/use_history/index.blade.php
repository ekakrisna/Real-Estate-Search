@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.detail', $customer->id)}}">{{$customer->name}} @lang('label.detail')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
    </ol>
@endsection

@section('top_buttons')
    <a href="{{route('admin.customer.edit', $customer->id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>
@endsection

@section('content')

<div class="content-header">
    <div class="container-fluid pb-2 border-bottom border-dark mb-2">
        <div class="row">
            <div class="col-6">
                ■ @lang('label.customer_basic_information')
                <span class="ml-2"> <i class="fas fa-sort-down" style="font-size: 30px"></i></span>
            </div>
            <div class="col-6">
                <a href="{{route('admin.customer.edit', $customer->id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid row mb-3 d-flex align-items-center">
        <div class="col-4 col-sm-2">
            @lang('label.name')
        </div>
        <div class="col-4 col-sm-2 text-sm-right">
            {{ $customer->name }}
        </div>
        <div class="col-4 col-sm-2">

            <!-- Flag toggle button - Start -->
            <button-toggle v-model="customer.flag" :api="customer.url.flag" 
                @input="flagHandle( customer )">
            </button-toggle>
            <!-- Flag toggle button - End -->

        </div>
        <div class="col-4 col-sm-2">
            @lang('label.person_charge')
        </div>
        <div class="col-6 col-sm-3">
            @if($customer->company_user == null)
            ー
            @else
            {{ $customer->company_user->name }} ({{ $customer->company_user->company->company_name }})
            @endif
        </div>
    </div>
</section>

<div class="content-header">
    <div class="container-fluid pb-2 border-bottom border-dark mb-2">
        <div class="row ">
            <div class="col-sm-12">
                ■ @lang('label.customer_usage_history')
            </div>
        </div>
    </div>
</div>

{{-- table like section --}}
<section class="content">
    <div class="container-fluid">
        <!-- Result filters - Start -->
        
        <Filters></Filters>
        <div class="tablelike">

            <!-- Table header - Start -->
            <div class="tablelike-header border-top border-right d-none d-xl-block">
                <div class="row mx-0">
                    <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.search_date_time')</div></div>
                    <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.action_name')</div></div>
                    <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                    <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                    <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.selling_price')</div></div>
                    <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.land_area')</div></div>
                    <div class="px-0 border-left bg-light col-xl-80px"><div class="py-2 px-2">@lang('label.building_condition')</div></div>
                    <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.favorite')</div></div>
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

        <div class="mt-3  my-4">
            <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
        </div>

        <router-view></router-view>
    </div>   
</section>
{{-- table like section end --}}

@endsection

@push('vue-scripts')

@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')
@relativeInclude('vue.pagination.import')

@include('backend.vue.components.button-toggle.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/admin/customer/'+ @json($customer->id) + '/use_history', 
                component: { template: '<div/>' }
            }]
        };
        store = {
            state: function(){
                var state = {
                    status: { loading: false },
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    preset: {
                        actions: @json($actions),
                        orders: [
                            { id: 'access_time', label: '@lang('label.datetime')' },
                            { id: 'action_types.label', label: '@lang('label.action')' },
                            { id: 'properties_id', label: '@lang('label.property_id')' },
                            { id: 'properties.location', label: '@lang('label.location')' },
                            { id: 'properties.minimum_price', label: '@lang('label.selling_price')' },
                            { id: 'properties.minimum_land_area', label: '@lang('label.land_area')' },
                            { id: 'properties.building_conditions_desc', label: '@lang('label.building_condition')' },
                        ]
                    },
                    result: null 
                };
                console.log( state );
                return state;
            },
            mutations: {
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', loading );
                },
                setResult: function( state, result ){
                    Vue.set( state, 'result', result );
                }
            }
        };

        mixin = {
            data: function(){
                return {
                    cust : @json( $customer )
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
                        return 'fas fa-flag text-dark';
                    } else {
                        return 'far fa-flag text-secondary';
                    }
                },
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
            },

            methods: {
                flagHandle: function(customer){
                    var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                    this.$toasted.show( message, {
                        type: 'success'
                    });
                },
            },

            watch: {
                $route: {
                    immediate: true, 
                    handler: function( to, from ){

                        var store = this.$store;
                        var url = @json( route( 'admin.customer.use_history.filter', $customer->id ));
                        var request = axios.post( url, { filter: to.query });

                        store.commit('setLoading'); // Set loading state

                        request.then( function( response ){
                            // --------------------------------------------------
                            var status = io.get( response, 'status' );
                            var result = io.get( response, 'data.result' );
                            // --------------------------------------------------
                            if( 200 === status && result ){
                                store.commit( 'setResult', result );
                            }
                            // --------------------------------------------------
                        });

                        request.finally( function(){ store.commit('setLoading', false )});
                    }
                }
            }
        };
    }( jQuery, _, document, window ));
@endminify </script>
@endpush