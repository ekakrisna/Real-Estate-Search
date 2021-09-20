@extends('frontend._base.vueform')

@section('title', $title)
@section('description', '')

@section('form-content')
    <div class="section-notification">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <div class="content-header">
                            <h2 class="title title-pages">
                                <img src="{{ asset('frontend/assets/images/icons/icon_info.png') }}" alt="icon_info" class="icon-title">
                                <span class="text-title">新着情報</span>
                            </h2>
                        </div>
                        <div class="content-body">                              
                            <div class="section-notif-no-data" style="padding: 8rem 0;" v-if="!isLoading && !customers.length">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="content text-center">
                                                <img src="{{ asset('frontend/assets/images/icons/bg_info_nodata.png') }}" alt="img-no-data">
                                                <span>新着情報はありません</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                            <template v-else>                                                                    
                                    <template v-if="isLoading">                                        
                                        <div v-for="( dataLoading, dataIndex ) in customers" :key="dataLoading.id">                                              
                                            <div class="row" v-if='dataLoading.type == 1'>                                                                      
                                                <div class="col-12 pb-3">
                                                    <div class="card-content glimmer">
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-4 col-lg-2">
                                                                    <div class="content-img">
                                                                        <div class="ratiobox ratio--1-1 glimmer">
                                                                            <div class="ratiobox-innerset glimmer-bar"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-8 col-lg-10">                                                        
                                                                    <div class="glimmer glimmer-bar double"></div>  
                                                                    <div class="row">
                                                                        <div class="col-8 col-lg-10">                                                                    
                                                                        </div>
                                                                        <div class="col-4 col-lg-2">                                                        
                                                                            <div class="glimmer glimmer-bar"></div>                                                                    
                                                                        </div>             
                                                                    </div>                                                                  
                                                                </div>                                                                                                                                                   
                                                            </div>                                                                                                                  
                                                        </div>
                                                    </div>
                                                </div>                                                                    
                                            </div>
                                            <div class="row" v-if='dataLoading.type == 2'>                                                                                                      
                                                <div class="col-12 pb-3">
                                                    <div class="card-content glimmer">
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-12">                                                        
                                                                    <div class="glimmer glimmer-bar double"></div>                                                                    
                                                                </div>       
                                                                <div class="col-8 col-lg-10">                                                                    
                                                                </div>
                                                                <div class="col-4 col-lg-2">                                                        
                                                                    <div class="glimmer glimmer-bar"></div>                                                                    
                                                                </div>   
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>                                                                                                                                                                              
                                            </div>
                                            <div class="row" v-if='dataLoading.type == 3'>                                                                
                                                <div class="col-12 pb-3">
                                                    <div class="card-content glimmer">
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-4 col-lg-2">
                                                                    <div class="content-img">
                                                                        <div class="ratiobox ratio--1-1 glimmer">
                                                                            <div class="ratiobox-innerset glimmer-bar"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-8 col-lg-10">                                                        
                                                                    <div class="glimmer glimmer-bar double"></div>                                                                    
                                                                    <div class="row">
                                                                        <div class="col-8 col-lg-10">                                                                    
                                                                        </div>
                                                                        <div class="col-4 col-lg-2">                                                        
                                                                            <div class="glimmer glimmer-bar"></div>                                                                    
                                                                        </div>             
                                                                    </div>     
                                                                </div>       
                                                            </div>                                                            
                                                        </div>                                                        
                                                    </div>
                                                </div>                                
                                            </div>
                                            <div class="row" v-if='dataLoading.type == 4'>                                                                                                
                                                <div class="col-12 pb-3">
                                                    <div class="card-content glimmer">
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-4 ">
                                                                    <div class="glimmer glimmer-bar"></div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="glimmer glimmer-bar"></div>         
                                                                </div>
                                                                <div class="col-8 col-lg-10">                                                                    
                                                                </div>
                                                                <div class="col-4 col-lg-2">                                                        
                                                                    <div class="glimmer glimmer-bar"></div>                                                                    
                                                                </div>   
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>                                                                                                                                                                                          
                                            </div>                                            
                                        </div>
                                    </template>                                    
                                    <template v-else>
                                        <div v-for="( data, dataIndex ) in customers" :key="data.id">                                              
                                            <div class="row" v-if='data.type == 1'>                                                                      
                                                <div class="col-12 pb-3">
                                                    <div class="card-content">
                                                        <div class="card-header">
                                                            <span class="notif"></span>
                                                            <h3 class="title">オススメ物件</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-4 col-lg-2">
                                                                    <div class="content-img" v-if="data.property_deliveries.property.property_photos.length > 0">
                                                                        <ratiobox ratio="1-1">
                                                                            <img :src="data.property_deliveries.property.property_photos[0].file.url.image" v-bind:alt="data.property_deliveries.property.property_photos[0].file.name" class="w-100 rounded">
                                                                        </ratiobox>
                                                                    </div>
                                                                    <div class="content-img" v-else>                                                            
                                                                        <ratiobox ratio="1-1">
                                                                            <image-placeholder alt="Empty Image"></image-placeholder>
                                                                        </ratiobox>   
                                                                    </div>
                                                                </div>
                                                                <div class="col-8 col-lg-10">                                                        
                                                                    <p class="text">@{{data.customer.name}} 様にオススメの物件がございます。</p>
                                                                    <p class="text">@{{data.property_deliveries.subject}}</p>
                                                                    <p class="text">@{{data.property_deliveries.text}}</p>
                                                                    <br>
                                                                    <a :href="data.property_deliveries.property.url.frontend_view" class="text"><u>物件情報はこちら</u></a>
                                                                </div>                                                
                                                            </div>
                                                            <p class="date">@{{data.ja.updated_at}}</p>
                                                        </div>
                                                        <a :href="data.property_deliveries.property.url.frontend_view" class="btn-show-more">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </a>
                                                    </div>
                                                </div>                                                                    
                                            </div>
                                            <div class="row" v-if='data.type == 2'>                                                                                                      
                                                <div class="col-12 pb-3">
                                                    <div class="card-content">
                                                        <div class="card-header">
                                                            <span class="notif"></span>
                                                            <h3 class="title">新着物件のお知らせ</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-12">
                                                                    <p class="text">
                                                                        お気に入りエリア                                                                                                                         
                                                                        <a :href="data.url.frontendDetailProperty">
                                                                            <u>
                                                                                「@{{data.location}}」
                                                                            </u>                                                            
                                                                        </a>
                                                                        に新しい土地が追加されました。
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <p class="date">@{{data.ja.updated_at}}</p>
                                                        </div>
                                                        <a :href="data.url.frontendDetailProperty" class="btn-show-more">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </a>
                                                    </div>
                                                </div>                                                                                                                                                                              
                                            </div>
                                            <div class="row" v-if='data.type == 3'>                                                                
                                                <div class="col-12 pb-3">
                                                    <div class="card-content">
                                                        <div class="card-header">
                                                            <span class="notif"></span>
                                                            <h3 class="title">物件更新</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-4 col-lg-2">
                                                                    <div class="content-img" v-if="data.customer_news_property.length > 0">                                                                        
                                                                        <ratiobox ratio="1-1" v-if="data.customer_news_property[0].property.property_photos.length > 0">
                                                                            <img :src="data.customer_news_property[0].property.property_photos[0].file.url.image" v-bind:alt="data.customer_news_property[0].property.property_photos[0].file.name" class="w-100 rounded">
                                                                        </ratiobox>
                                                                        <ratiobox ratio="1-1" v-else>
                                                                            <image-placeholder alt="Empty Image"></image-placeholder>
                                                                        </ratiobox>  
                                                                    </div>
                                                                    <div class="content-img" v-else>                                                            
                                                                        <ratiobox ratio="1-1">
                                                                            <image-placeholder alt="Empty Image"></image-placeholder>
                                                                        </ratiobox>   
                                                                    </div>
                                                                </div>
                                                                <div class="col-8 col-lg-10">
                                                                    <p class="text">お気に入り物件の情報が更新されました。</p>
                                                                    <p class="text" v-if="data.customer_news_property.length > 0">
                                                                        <a :href="data.customer_news_property[0].property.url.frontend_view">
                                                                            <u>
                                                                                物件情報はこちら
                                                                            </u>                                                            
                                                                        </a>
                                                                    </p>
                                                                    <p class="text" v-else>
                                                                        <a href="#">
                                                                            <u>
                                                                                物件情報はこちら
                                                                            </u>                                                            
                                                                        </a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <p class="date">@{{data.ja.updated_at}}</p>
                                                        </div>
                                                        <a :href="data.customer_news_property[0].property.url.frontend_view" class="btn-show-more" v-if="data.customer_news_property.length > 0">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </a>
                                                        <a href="#" class="btn-show-more" v-else>
                                                            <u>
                                                                物件情報はこちら
                                                            </u>                                                            
                                                        </a>
                                                    </div>
                                                </div>                                
                                            </div>
                                            <div class="row" v-if='data.type == 4'>                                                                                                
                                                <div class="col-12 pb-3">
                                                    <div class="card-content">
                                                        <div class="card-header">
                                                            <h3 class="title">物件掲載終了のお知らせ</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row row-base">
                                                                <div class="col-12">
                                                                    <p class="text">お気に入り物件の掲載が終了しました。</p>
                                                                </div>
                                                            </div>
                                                            <p class="date">@{{data.ja.updated_at}}</p>
                                                        </div>
                                                    </div>
                                                </div>                                                                                                                                                                                          
                                            </div>                                            
                                        </div>
                                        <div class="text-center mt-3">
                                            <pagination v-model="pagination"></pagination>
                                        </div>
                                    </template>                                    
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</template>  
@endsection
@push('vue-scripts')
@include('backend.vue.components.image-placeholder.import')
@include('backend.vue.components.ratiobox.import')

@include('frontend.vue.pagination.import')
<script> @minify    
    (function( $, io, document, window, undefined ){        
        router = {
            mode: 'history',
            routes: [{ 
                name: 'index', path: '/news', 
                component: { template: '<div/>' }
            }]
        };
        store = {            
            state: function(){
                var state = {                
                    status: { loading: false },                                                                                
                    result: {},
                    customerNews: @json( $customer_news ),
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
                },                
                setNews: function( state, news ){
                    Vue.set( state, 'customerNews', news );
                }                
            }            
        };
        mixin = {                        
            mounted: function(){                  
            },            
            computed: {            
                state: function(){ return this.$store.state },
                isLoading: function(){ return io.get( this.state, 'status.loading' )},                                            
                news: function(){ return io.get( this.state, 'customerNews' )},                               
                pagination: function(){ return io.get( this.state, 'result' )},
                customers: function(){ return io.get( this.state, 'result.data' ) || []},                         
            },            
            methods: {                
            },            
            watch: {                            
                $route: {
                    immediate: true, handler: function( route ){                        
                        var vm = this, store = this.$store;
                        var url = @json( route( 'frontend.news.list.filter' ));
                        var query = io.get( route, 'query' );                                                                                              
                        var page = io.get( query, 'page' );
                        if( !page ){
                            var data = io.assign({}, query, { page: 1 });
                            vm.$router.push({ name: 'index', query: data }).catch( function(){});
                        }                        
                        var request = axios.post( url, { filter: route.query });
                        store.commit( 'setLoading' ); // Set loading state                        
                        request.then( function( response ){                            
                            var status = io.get( response, 'status' );
                            var result = io.get( response, 'data.result' );
                            var news = io.get( response, 'data.news' );                            
                            if( 200 === status ){
                                if( result ){                                    
                                    var currentPage = io.get( result, 'current_page' );
                                    var lastPage = io.get( result, 'last_page' );                                    
                                    if( currentPage > lastPage ){
                                        var query = io.assign({}, vm.$route.query, { page: lastPage });
                                        vm.$router.push({ name: 'index', query: query }).catch( function(){});
                                    }                                    
                                    store.commit( 'setResult', result );                                    
                                }                                                                                                                             
                                if( news ) store.commit( 'setNews', news );                                
                            }                            
                        });                                                
                        request.finally( function(){ store.commit( 'setLoading', false )});                        
                    }                            
                }
            }            
        };        
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
