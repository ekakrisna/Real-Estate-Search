@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.company.list')}}">@lang('label.list_of_corp')</a></li>
        <li class="breadcrumb-item active">{{$page_title}}</li>
    </ol>
@endsection

@section('top_buttons')
@endsection

@section('content')

    {{-- B17-1 Section --}}
    <div class="content-header">
        <div class="container-fluid pb-2 border-bottom border-dark mb-2">
            <div class="row">
                <div class="col-6">
                     ■ @lang('label.user_search')
                    <span class="ml-2"> <i class="fas fa-sort-down" style="font-size: 30px"></i></span>
                </div>
                <div class="col-6">
                    <a href="{{route('admin.company.user.create',$company->id)}}" class="btn btn-info float-sm-right">@lang('label.create_new_user')</a>
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

                <div class="row pb-2 border-bottom border-dark mb-2">
                    <div class="col-sm-12">
                        <p class="m-0 text-dark h1title">
                            ■ @lang('label.list_of_users') (<a href="{{ $company->url->permission->index }}"> @lang('label.setting_of_permision') </a>)
                        </p>
                    </div>
                </div>
                
                <div class="tablelike">

                    <!-- Table header - Start -->
                    <div class="tablelike-header border-top border-right d-none d-xl-block">
                        <div class="row mx-0">
                            <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.user_name')</div></div>
                            <div class="px-0 border-left bg-light col-xl"><div class="py-2 px-2">@lang('label.e-mail')</div></div>
                            <div class="px-0 border-left bg-light col-xl-100px"><div class="py-2 px-2">@lang('label.permission')</div></div>

                            <div class="px-0 border-left bg-light col-xl-150px"><div class="py-2 px-2">@lang('label.register_date_time')</div></div>
                            <div class="px-0 border-left bg-light col-xl-140px"><div class="py-2 px-2">@lang('label.last_use_date')</div></div>
                            
                            <div class="px-0 border-left bg-light col-xl-80px"><div class="py-2 px-2">@lang('label.number_of_customer')</div></div>
                            <div class="px-0 border-left bg-light col-xl-90px"><div class="py-2 px-2">@lang('label.license')</div></div>
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
                name: 'index', path: '/admin/company/{{ $id }}/user', 
                component: { template: '<div/>' }
            }]
        };
        store = {
            // ------------------------------------------------------------------
            // Reactive central data
            // ------------------------------------------------------------------
            state: function(){
                var state = {
                    status: { loading: false },
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    preset: {
                        roles: @json( $roles ),
                        orders: [
                            { id: 'company_users.name', label: "@lang('label.user_name')" },
                            { id: 'company_users.email', label: "@lang('label.e-mail')" },
                            { id: 'company_users.created_at', label: "@lang('label.register_date_time')" },
                            { id: 'access_time', label: "@lang('label.last_use_date')" },
                            { id: 'company_users.is_active', label: "@lang('label.license')" }
                        ]
                    },
                    result: null 
                };
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
            // ------------------------------------------------------------------
        };

        // ----------------------------------------------------------------------
        // Vue mixin 
        // ----------------------------------------------------------------------
        mixin = {
            data: function(){
                return {}
            },
            mounted: function(){
            },
            computed: {
                isLoading: function(){ return io.get( this.$store.state, 'status.loading' )},
                resultMeta: function(){ return io.get( this.$store.state, 'result' )},
                resultData: function(){ return io.get( this.$store.state, 'result.data' ) || []}
            },
            methods: {},
            watch: {
                $route: {
                    immediate: true, 
                    handler: function( to, from ){
                        // ------------------------------------------------------
                        // Perform data request
                        // ------------------------------------------------------
                        var store = this.$store;
                        var url = @json( route( 'admin.company.user.list.filter', $id ));
                        var request = axios.post( url, { filter: to.query });
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        store.commit('setLoading'); // Set loading state
                        // ------------------------------------------------------

                        // ------------------------------------------------------
                        // On success
                        // ------------------------------------------------------
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
                        // ------------------------------------------------------
                    }
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
