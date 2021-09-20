@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.detail', $id)}}">@{{ user.name }} @lang('label.detail')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
    </ol>
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
                    <a :href="user.url.edit" class="btn btn-info float-right">@lang('label.edit')</a>
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
    
                <button type="button" class="btn btn-sm btn-default fs-14" id="star" @click="flagHandle">
                    <i v-if="this.flag" class="fas fa-flag"></i>
                    <i v-else class="fal fa-flag"></i>
                </button>  
    
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
                    ■ @lang('label.list_favorite_properties')
                </div>
            </div>
        </div>
    </div>

{{-- table like section --}}
<section class="content">
    <div class="container-fluid">

    <Filters></Filters>
    <div class="tablelike">        
        <div class="tablelike-header border-top border-right d-none d-xl-block">
            <div class="row mx-0">                
                <div class="px-0 border-left bg-light col-12 col-xl-150px"><div class="py-2 px-2">@lang('label.registered_date')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-90px"><div class="py-2 px-2">@lang('label.property_id')</div></div>                    
                <div class="px-0 border-left bg-light col-12 col-xl"><div class="py-2 px-2">@lang('label.location')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-180px"><div class="py-2 px-2">@lang('label.building_condition')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-100px"><div class="py-2 px-2">@lang('label.selling_price_yuan')</div></div>
                <div class="px-0 border-left bg-light col-12 col-xl-100px"><div class="py-2 px-2">@lang('label.land_area_m')</div></div>                    
            </div>
        </div>            
        <div class="tablelike-content">                
            <Placeholder v-if="isLoading" :count="$store.state.config.placeholder"></Placeholder>                
            <Result v-else v-model="resultData"></Result>                
        </div>            
    </div>
    <div class="mt-3 my-4">
        <Pagination v-model="resultMeta" :loading="isLoading"></Pagination>
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
                name: 'index', path: '/admin/customer/{{$id}}/fav_history',                 
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
                            { id: 'created_at', label: '@lang('label.registration_date_and_time')' },
                            { id: 'property_id', label: '@lang('label.property_id')' },                            
                            { id: 'location', label: '@lang('label.location')' },
                            { id: 'building', label: '@lang('label.building_condition')' },
                            { id: 'selling', label: '@lang('label.selling_price')' },
                            { id: 'land_area', label: '@lang('label.land_area')' },                            
                        ],
                    },
                    result: null 
                };
                // console.log( state );
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
                    page_title : @json( $page_title),
                    customer : @json( $customer )
                }
            },

            mounted: function(){
            },

            computed: {
                user : function() {
                    return this.customer;
                },
                flag: function(){ return this.user.flag },                
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
            },

            methods: {
                flagHandle: function(){
                    var vm = this;
                    var user = this.user;
                    
                    const url =  user.url.change_flag;                                        
                    var request = axios.get( url);
                    request.then( function( response ){
                        user.flag = response.data.flag;
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, {
                            type: 'success'
                        });
                    });
                },          
            },

            watch: {
                $route: {
                    immediate: true, 
                    handler: function( to, from ){

                        var store = this.$store;
                        var url = @json( route( 'admin.customer.fav_history.favfilter', $id ));
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