@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.detail', $id)}}">{{$customer_detail->name}} @lang('label.detail')</a></li>
        <li class="breadcrumb-item active">@lang('label.inquiry_list')</li>
    </ol>
@endsection

@section('top_buttons')
    <a href="{{route('admin.customer.edit', $id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>
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
                   <a href="{{route('admin.customer.edit', $customer_detail->id)}}" class="btn btn-info float-sm-right">@lang('label.edit')</a>
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
                {{ $customer_detail->name }}
            </div>
            <div class="col-4 col-sm-2">
                <button type="button" class="btn btn-sm btn-default" id="flag" @click.prevent="flagHandle(customer)">
                    <i :class="[`${flag}`]"></i>
                </button>
            </div>
            <div class="col-4 col-sm-2">
                @lang('label.person_charge')
            </div>
            <div class="col-6 col-sm-3">
                @if($customer_detail->company_user == null)
                ー
                @else
                {{ $customer_detail->company_user->name }} ({{ $customer_detail->company_user->company->company_name }})
                @endif
            </div>
        </div>
    </section>

    {{-- B8-2 New Table Like Section --}}
    <div class="content-header">
        <div class="container-fluid pb-2 border-bottom border-dark mb-2">
            <div class="row ">
                <div class="col-sm-12">
                <p class="m-0 text-dark h1title">
                   ■ @lang('label.inquiry_list')
                </p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid row mb-3">
            <div class="col-sm-12">

                <!-- Result filters - Start -->
                <Filters></Filters>
                <!-- Result filters - End -->

                <hr class="my-4" />

                <div class="tablelike">

                    <!-- Table header - Start -->
                    <div class="tablelike-header border-top border-right d-none d-xl-block">
                        <div class="row mx-0">
                            <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.favorite')</div></div>
                            <div class="px-0 border-left bg-light col-xl-120px"><div class="py-2 px-2">@lang('label.contact_us_id')</div></div>
                            <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.contact_us_date_and_time')</div></div>
                            <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.state')</div></div>
                            <div class="px-0 border-left bg-light col-xl-200px"><div class="py-2 px-2">@lang('label.contact_us_type')</div></div>
                            <div class="px-0 border-left bg-light col-xl-80px"><div class="py-2 px-2">@lang('label.property_id')</div></div>
                            <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.description')</div></div>
                            <div class="px-0 border-left bg-light col-xl-60px"><div class="py-2 px-2">@lang('label.detail')</div></div>
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

            </div>
        </div>
    </section>


@endsection

@push('vue-scripts')
@relativeInclude('vue.filters.import')
@relativeInclude('vue.result.import')
@relativeInclude('vue.placeholder.import')


<script> @minify
    (function( $, io, document, window, undefined ){
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/admin/customer/{{$id}}/contact_history', 
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
                        orders: [
                            { id: 'id', label: "@lang('label.contact_us_id')" },
                            { id: 'created_at', label: "@lang('label.contact_us_date_and_time')" },
                            { id: 'is_finish', label: "@lang('label.state')" },
                            { id: 'properties_id', label: "@lang('label.property_id')" }
                        ]
                    },

                    result: null 
                };

                //console.log( state );
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
                    cust : @json( $customer_detail )
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
                        return 'fal fa-flag text-secondary';
                    }
                },

                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
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
                }
            },
            watch: {
                $route: {
                    immediate: true, 
                    handler: function( to, from ){

                        var store = this.$store;
                        var url = @json( route( 'admin.customer.contact_history.filter', $id ));
                        var request = axios.post( url, { filter: to.query });

                        store.commit('setLoading'); // Set loading state

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
                        });
                        // ------------------------------------------------------

                        request.finally( function(){ store.commit('setLoading', false )});
                    }
                }
            }
        };

    }( jQuery, _, document, window ));
@endminify </script>
@endpush



