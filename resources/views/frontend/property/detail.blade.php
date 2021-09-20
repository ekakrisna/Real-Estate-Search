@extends('frontend._base.vueform')
@section('title', $title)
@section('description', '')
@section('form-content')
    <div class="section-property-detail" v-if="properties">
        <div class="content-slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <template v-if="properties.property_photos.length > 0">
                        <div class="swiper-slide" v-for="(data, index) in properties.property_photos">                                              
                            <ratiobox>
                                <img :src="data.file.url.image" v-bind:alt="data.file.name" class="w-100 img-slider">                            
                            </ratiobox>           
                        </div>    
                    </template>
                    <!--          
                    <template v-else>
                        <div class="swiper-slide">   
                            <ratiobox>
                                <image-placeholder alt="Empty Image"></image-placeholder>
                            </ratiobox>                                                        
                        </div>    
                    </template>   
                    -->                                 
                </div>
                <!-- swiper navigation -->
                <div class="swiper-button-next">
                    <i class="fa fa-chevron-right"></i>
                </div>
                <div class="swiper-button-prev">
                    <i class="fa fa-chevron-left"></i>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="content">
                        <div class="content-label">
                            <div class="row">
                                <div class="col">

                                    <div class="list-badge h-auto row mx-n1">
                                        <div v-if="hasIsReserveLabel" class="px-1 col-auto">
                                            <property-label type="is_reserve" :property="properties" padding="3"></property-label>
                                        </div>
                                        <div v-if="hasNewLabel" class="px-1 col-auto">
                                            <property-label type="new" :property="properties" padding="3"></property-label>
                                        </div>
                                        <div v-if="hasUpdatedLabel"class="px-1 col-auto">
                                            <property-label type="updated" :property="properties" padding="3"></property-label>
                                        </div>
                                        <div v-if="hasNoConditionLabel" class="px-1 col-auto">
                                            <property-label type="noCondition" :property="properties" padding="3"></property-label>
                                        </div>
                                    </div>

                                    {{-- <div class="list-badge">

                                        <!-- Property labels - Start -->
                                        <property-label type="new" :property="properties"></property-label>
                                        <property-label type="updated" :property="properties"></property-label>
                                        <property-label type="noCondition" :property="properties"></property-label>
                                        <!-- Property labels - End -->

                                    </div> --}}
                                </div>
                                <div class="col-auto">
                                    @if (Auth::guard('user')->user())
                                    <!-- Favorite button - Start -->
                                    <button-favorite v-model="properties.favorited" :property="properties"></button-favorite>
                                    <!-- Favorite button - End -->
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="content-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="content-description">
                                        <h2 class="title">@{{properties.location}}</h2>
                                        <p class="price" v-if="properties.minimum_price && properties.maximum_price">
                                            @{{ properties.minimum_price | toManDisplay }} ~ @{{ properties.maximum_price | toManDisplay  }} 
                                        </p>
                                        <p class="price" v-else-if="properties.minimum_price && properties.maximum_price == null || properties.maximum_price == 0">
                                            @{{ properties.minimum_price | toManDisplay }}
                                        </p>
                                        <p class="price" v-else-if="properties.minimum_price == null || properties.minimum_price == 0 && properties.maximum_price">
                                            @{{ properties.maximum_price | toManDisplay }} 
                                        </p>
                                        <p class="price" v-else>-</p>                                        
                                        <div class="content-specs">
                                            <div class="list-specs">
                                                <span class="title-specs">土地</span>
                                                <span class="text-specs w-auto" v-if="properties.minimum_land_area && properties.maximum_land_area">
                                                    @{{ properties.minimum_land_area | numeral('0,0') }}m² (@{{ properties.minimum_land_area | toTsubo | numeral('0,0')}}坪) ~ @{{ properties.maximum_land_area | numeral('0,0') }}m² (@{{ properties.maximum_land_area | toTsubo | numeral('0,0')}}坪)
                                                </span>
                                                <span class="text-specs w-auto" v-else-if="properties.minimum_land_area && properties.maximum_land_area == null || properties.maximum_land_area == 0">
                                                    @{{ properties.minimum_land_area | numeral('0,0') }}m² (@{{ properties.minimum_land_area | toTsubo | numeral('0,0')}}坪)
                                                </span>
                                                <span class="text-specs w-auto" v-else-if="properties.minimum_land_area == null | properties.minimum_land_area == 0 && properties.maximum_land_area">
                                                    @{{ properties.maximum_land_area | numeral('0,0') }}m² (@{{ properties.maximum_land_area | toTsubo | numeral('0,0')}}坪)
                                                </span>
                                                <span class="text-specs w-auto" v-else>-</span>                                                                                      
                                            </div>
                                            <div class="list-specs">
                                                <span class="title-specs">最終更新日</span>
                                                <span class="text-specs">@{{properties.ja.updated_at}}</span>
                                            </div>
                                            <div class="list-specs">
                                                <span class="title-specs">物件ID</span>
                                                <span class="text-specs">@{{properties.id}}</span>
                                            </div>
                                            <div class="list-specs align-items-start" v-if="properties.property_flyers.length > 0">
                                                <span class="title-specs">チラシ</span>
                                                <span class="text-specs">
                                                    <a v-for="(data, index) in properties.property_flyers" class="text-primary d-none d-md-block" target="_blank" :href="data.file.url.image">@{{ data ? data.file.url.original_name + '(' + convertByte(data.file.size_byte,true) + ')' : '-'}}<br></a>
                                                </span>
                                            </div>
                                            <div v-else class="list-specs">
                                                <span class="title-specs">チラシ</span>
                                                <span class="text-specs">-</span>
                                            </div>
                                            <div class="row">
                                                <span class="col-sm-12 d-md-none">
                                                    <a v-for="(data, index) in properties.property_flyers" class="text-primary" target="_blank" :href="data.file.url.image">@{{ data ? data.file.url.original_name + '(' + convertByte(data.file.size_byte,true) + ')' : '-'}}<br></a>
                                                </span>
                                            </div>                                            
                                        </div>
                                    </div>
                                    <p class="text-note">ご覧いただいている物件は販売終了している可能性がございます。<br class="d-none d-lg-block">詳しくは以下よりお問い合わせください。</p>

                                    <div class="alert d-flex align-items-center" style="background-color: #FBDFD4" v-if="properties.is_reserve">
                                        <i class="fal fa-exclamation-circle fa-2x pr-2 text-danger"></i>
                                        <p class="text-danger">この物件は"予約/契約 済"となりました。現在、お取引できない可能性が高いです。</p>                                        
                                    </div>                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                    <div class="company-info content-inquiry">
                                        <h2 class="title">電話でのお問い合わせ</h2>
                                        <a :href="`tel:${properties.company.phone}`" class="btn-call">
                                            <i class="fa fa-phone fa-flip-horizontal"></i>
                                            <span>@{{properties.company.phone}}</span>
                                        </a>
                                        <div class="list-inquiry">
                                            <div class="list-item">
                                                <span class="title-list">会社</span>
                                                <span class="text-list w-100">@{{properties.company.company_name}}</span>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="content-inquiry">
                                        <h2 class="title">メールでのお問い合わせ</h2>
                                        @if (Auth::guard('user')->user() == null)
                                            <!--a href="mailto:info@tochi-s.net?subject=物件問い合わせ&amp;body={{$email_body ?? ''}}" class="btn-call">
                                                <i class="far fa-envelope fa-flip-horizontal fa-lg"></i>
                                                <span>メールはこちらへ</span>
                                            </a>-->
                                            <form class="form" data-parsley>
                                                <input type="email" name="customer_email" v-model="customer_email" class="form-control border-0" style="margin-bottom:5px; border-radius: .25rem" placeholder="メールアドレスを入力してください" required data-parsley-required-message="メールが必要です">

                                                <textarea name="text" v-model="text" class="form-control" placeholder="ここに当物件へのお問い合わせ内容をご記載ください。" required data-parsley-required-message="お問い合わせ内容を入力してください。"></textarea>

                                                <textarea name="text_detail" v-model="text_detail" class="form-control" style="display:none" required></textarea>

                                                <p v-html="text_detail"></p>

                                                <div class="row">
                                                    <div class="col-6 col-lg-8 mx-auto mt-4 px-0">
                                                        <!-- Submit button - Start -->
                                                        <button-action v-model="isLoading" type="submit" label="送信する" :disabled="isLoading"></button-action>
                                                        <!-- Submit button - End -->
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                        <form class="form" data-parsley>
                                            <textarea name="text" v-model="text" class="form-control" placeholder="お問い合わせ内容をご記入ください" required data-parsley-required-message="お問い合わせ内容を入力してください。"></textarea>
                                            <div class="row">
                                                <div class="col-6 col-lg-8 mx-auto mt-4 px-0">
                                                    <!-- Submit button - Start -->
                                                    <button-action v-model="isLoading" type="submit" label="送信する" :disabled="isLoading"></button-action>
                                                    <!-- Submit button - End -->
                                                </div>
                                            </div>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-else class="flex-grow-1 d-flex flex-column justify-content-center">
        <div class="section-notif-no-data p-0">
            <div class="container">
                <div class="row pt-5">
                    <div class="col-12 pt-5">
                        <div class="content text-center">
                            <img src="{{ asset('frontend/assets/images/icons/bg_error.png') }}" alt="img-no-data">
                            <span>{{$errormessage}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset( 'plugins/icheck-bootstrap/icheck-bootstrap.min.css' )}}">
@endpush

@push('vue-scripts')
<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
@include('backend.vue.components.image-placeholder.import')
@include('backend.vue.components.ratiobox.import')
<!-- Preloaders - End -->

<!-- Frontend components - Start -->
@include('frontend.vue.button-action.import')
@include('frontend.vue.property-label.import')
@include('frontend.vue.button-favorite.import')
<!-- Frontend components - End -->

<script> @minify    
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Vue root component
        // ----------------------------------------------------------------------
        window.queue = {}; // Event queue
        // ----------------------------------------------------------------------
        // Vue router
        // ----------------------------------------------------------------------
        router = {
            mode: 'history',
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
                // --------------------------------------------------------------
                var state = {
                    status: { mounted: false, loading: false },
                    cancellation: { loading: false },                    
                    preset: {
                        // -----------------------------------------------------
                        // API endpoints
                        // -----------------------------------------------------
                        api: {
                            store: @json( route( 'frontend.property.store',$idProperties )),
                            storeCustomer: @json( route( 'api.property.uuid',$idProperties )),
                        },
                        // -----------------------------------------------------                                              
                    },
                    result : {
                        data: [@json( $property )],
                    }
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                //console.log( state );
                return state;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Updating state data will need to go through these mutations
            // ------------------------------------------------------------------
            mutations: {
                refreshParsley: function(){
                    setTimeout( function(){
                        var $form = $('form[data-parsley]');
                        $form.parsley().refresh();
                    });
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Set loading state
                // --------------------------------------------------------------
                setLoading: function( state, loading ){
                    if( io.isUndefined( loading )) loading = true;
                    Vue.set( state.status, 'loading', !! loading );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Set mounted state
                // --------------------------------------------------------------
                setMounted: function( state, mounted ){
                    if( io.isUndefined( mounted )) mounted = true;
                    Vue.set( state.status, 'mounted', !! mounted );
                },
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
                    text            : '',
                    text_detail     : @json($email_body) ?? '',
                    customer_email  : '',
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted hook
            // ------------------------------------------------------------------
            mounted: function(){                                   
                this.$store.commit( 'setMounted', true );
                $(document).trigger( 'vue-loaded', this );
            },
            // ------------------------------------------------------------------
            // ------------------------------------------------------------------
            // Created
            // ------------------------------------------------------------------
            created(){
                this.storeBeforeLogin()
            },
            // ------------------------------------------------------------------
            // Computed
            // ------------------------------------------------------------------
            computed: {                
                properties : function() {
                    return this.$store.state.result.data[0];
                },              
                building_conditions : function() {
                    return this.properties.building_conditions;
                },       
                // --------------------------------------------------------------
                // Loading and mounted status
                // --------------------------------------------------------------
                isLoading: function(){ return this.$store.state.status.loading },
                isMounted: function(){ return this.$store.state.status.mounted },
                // --------------------------------------------------------------

                // -------------------------------------------------------------
                // Reference shortcuts
                // -------------------------------------------------------------
                preset: function(){ return this.$store.state.preset },
                // -------------------------------------------------------------

                // --------------------------------------------------------------
                // Property labels
                // --------------------------------------------------------------
                hasNewLabel: function(){ return io.get( this.$store.state.result.data[0], 'label.new' )},
                hasUpdatedLabel: function(){ return io.get( this.$store.state.result.data[0], 'label.updated' )},
                hasNoConditionLabel: function(){ return io.get( this.$store.state.result.data[0], 'label.noCondition' )},
                hasIsReserveLabel: function(){ return io.get( this.$store.state.result.data[0], 'label.isReserve' )},
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                convertByte: function(bytes, si = false, dp = 1){                    
                    const thresh = si ? 1000 : 1024;
                    if (Math.abs(bytes) < thresh) {
                        return bytes + ' B';
                    }
                    const units = si 
                        ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] 
                        : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
                    let u = -1;
                    const r = 10**dp;
                    do {
                        bytes /= thresh;
                        ++u;
                    } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);
                    return bytes.toFixed(dp) + ' ' + units[u];                    
                },

                storeBeforeLogin: function(){
                    var vm = this;
                    var state = vm.$store.state;
                    var uuid = window.deviceUuid.DeviceUUID().get();
                    const data = {                                
                        uuid: uuid,
                    };
                    var url = io.get( state.preset, 'api.storeCustomer' );   
                                        
                    const self = this;
                    var request = axios.post(url, data);                    
                    request.then( function(response){
                        
                    });                            
                    console.log(uuid);
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Watchers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        };
        // ----------------------------------------------------------------------        
        if (@json( $property )) {                    
            $(document).on( 'vue-loaded', function( event, vm ){            
                var $window = $(window);
                var $form = $('form[data-parsley]');
                var form = $form.parsley();
                var queue = window.queue;               
                var store = vm.$store;                                
                // console.log(store);
                form.on( 'form:validated', function(){                
                    var validForm = form.isValid();
                    if( validForm==false ) navigateValidation( validForm );                
                    else {                    
                        var state = vm.$store.state;
                        vm.$store.commit( 'setLoading', true );                                                                      
                        var data = {};
                        var formData = new FormData();
                        var url = io.get( state.preset, 'api.store' );                                                        
                        data.text = vm.text;                                                            
                        data.customer_email = vm.customer_email;                                         
                        data.text_detail = vm.text_detail;                                                           
                        formData.append( 'dataset', JSON.stringify( data ));                                        

                        // console.log( formData ); return;

                        var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                        queue.save = axios.post( url, formData, options ); // Do the request                                                    
                        queue.save.then( function( response ){                        
                            // console.log( response );
                            vm.$store.commit( 'setLoading', true );                                                
                            if( response.data ){                            
                                $window.scrollTo( 0, { easing: 'easeOutQuart' });                            
                                if(response.data.status == "success"){                                
                                    var message = '@lang('label.contact_inquiry_submitted')';
                                    vm.$toasted.show( message, { type: 'success' });                                
                                    vm.text = '';
                                    // Redirect to the                                
                                    setTimeout( function(){
                                        //var redirectPage = io.get( response.data, 'customer.url.view' );
                                        //window.location = redirectPage;
                                    }, 1000 );                                
                                }else{                                
                                    var message = '@lang('label.FAILED_UPDATE_MESSAGE')';
                                    vm.$toasted.show( message, { type: 'error' });                                
                                }                            
                            }                        
                        });                                        
                        // Handle other response                    
                        queue.save.catch( function(e){ console.log( e )});
                        queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });                                        
                        return false;                    
                    }                
                }).on('form:submit', function(){ return false });                        
            });    
        }    
    }( jQuery, _, document, window ));
@endminify </script>
@endpush