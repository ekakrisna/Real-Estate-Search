@extends('frontend._base.vueform')
@section('title', $title)
@section('form-content')

<!-- Page preloader - Start -->
<div v-if="!isMounted" class="preloader preloader-fullscreen d-flex justify-content-center align-items-center">
    <div class="folding-cube">
        <div class="cube cube-1"></div>
        <div class="cube cube-2"></div>
        <div class="cube cube-4"></div>
        <div class="cube cube-3"></div>
    </div>
</div>
<!-- Page preloader - End -->

<div class="section-setting">
    <div class="container">

        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">
                    @include("frontend._includes.alert")
                    <div class="content-header">
                        <h2 class="title title-pages">
                            <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}" alt="icon_account_setting" class="icon-title">
                            <span class="text-title">アカウント設定</span>
                        </h2>
                    </div>
                    <div class="content-body">
                        <form class="form-setting parsley-minimal" data-parsley>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>氏名</label>
                                                <label class="form-control form-control-red-left"> @{{ customer.name }} </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>メールアドレス</label>
                                                <label class="form-control form-control-red-left"> @{{ customer.email }} </label>
                                                <!--<input type="text" name="email" class="form-control form-control-red-left" placeholder="a-ban@grune.co.jp">-->
                                            </div>
                                            <div class="col-11 col-lg-6 d-flex align-items-end justify-content-lg-start justify-content-end pt-2 pt-lg-0">
                                                <a href="{{ route('frontend.change_email') }}" class="edit-email">
                                                    <img src="{{ asset('frontend/assets/images/icons/icon_change_mail.png') }}" alt="icon-edit">
                                                    <span>メールアドレスを変更する</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>電話番号</label>
                                                <input type="text" id="input-phone" name="phone" class="form-control form-control-setting" 
                                                required v-model="customer.phone"
                                                data-parsley-minlength="8" data-parsley-phone-number data-parsley-required-message="電話番号を入力してください。" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="label-form">プライバシー設定</p>
                                    <div class="form-group form-caution">
                                        <label class="label-caution checkmark-custom">
                                            ハウスメーカに閲覧した物件情報や検索履歴を送らない
                                            <input type="checkbox" name="not_leave_record" class="checkbox" v-model="customer.not_leave_record" >
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="label-form">担当の会社を変更する</p>
                                    <div class="row">
                                        <div class="col-12">
                                            <select name="company_users_id" class="form-control" v-model="customer.company_users_id" >

                                                <option v-for="option in company_user_list" :value="option.id">@{{ option.company.company_name }} ( @{{option.name}} )</option>

                                            </select>
                                            <i class="fa fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit button - Start -->
                            <div class="row">
                                <div class="col-11 col-lg-4 mt-48 mx-auto">

                                    <!-- Submit button - Start -->
                                    <button-action v-model="isLoading" type="submit" label="上記の内容で保存する"></button-action>
                                    <!-- Submit button - End -->

                                </div>
                            </div>
                            <!-- Submit button - End -->

                            <div class="row">
                                <div class="col-12 col-lg-8 mx-auto">
                                    <div class="content-note">
                                        <p class="text">アカウントを解約した後に再度作成した場合、現アカウントの登録情報および各種設定は引き 継ぐことができません。事前にご了承ください。</p>
                                        <div class="row">
                                            <div class="col-12 col-lg-7 mx-auto">

                                                <!-- Cancel button - Start -->
                                                <button-action label="了承の上で解約する" context="secondary" data-toggle="modal" data-target="#modal-cancel"></button-action>
                                                <!-- Cancel button - End -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="modal-cancel" class="modal modal-cancel fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">

        <!-- Modal content-->
        <form class="form-setting" data-parsley-cancel>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">了承の上で解約する</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>本当に解約しますか？解約後、現アカウントの登録情報および各種設定は復元する事ができません。</p>
                </div>
                <div class="modal-footer">

                    <!-- Submit button - Start -->
                    <button-action v-model="isLoadingCancellation" type="submit" label="解約する"></button-action>
                    <!-- Submit button - End -->

                    <!-- Cancel button - Start -->
                    <button-action type="button" label="キャンセル" data-dismiss="modal"></button-action>
                    <!-- Cancel button - End -->

                </div>
            </div>
        </form>

    </div>
</div>
@endsection


@push('vue-scripts')

<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
<!-- Preloaders - End -->

<!-- Frontend components - Start -->
@include('frontend.vue.button-action.import')
<!-- Frontend components - End -->

<script> @minify
(function( io, $, window, document, undefined ){
    // ----------------------------------------------------------------------
    window.queue = {}; // Event queue
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Vuex store
    // ----------------------------------------------------------------------
    store = {
        // ------------------------------------------------------------------
        // Reactive state
        // ------------------------------------------------------------------
        state: function(){
            // --------------------------------------------------------------
            var state = {
                status: { mounted: false, loading: false },
                cancellation: { loading: false },
                customer: @json( $customer_detail ),
                company_user_list: @json( $company_user_list ),
                preset: {

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        store: @json( route( 'frontend.account_settings.edit' )),
                        cancel: @json( route( 'frontend.account_settings.cancel' ))
                    },
                    // -----------------------------------------------------
                    
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
        // Mutations
        // ------------------------------------------------------------------
        mutations: {
            // --------------------------------------------------------------
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

            setLoadingCancellation: function( state, loading ){
                if( io.isUndefined( loading )) loading = true;
                Vue.set( state.cancellation, 'loading', !! loading );
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
    // Vue mixin / Root component
    // ----------------------------------------------------------------------
    mixin = {
        // ------------------------------------------------------------------
        // On mounted hook
        // ------------------------------------------------------------------
        mounted: function(){
            this.$store.commit( 'setMounted', true );
            $(document).trigger( 'vue-loaded', this );
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Loading and mounted status
            // --------------------------------------------------------------
            isLoading: function(){ return this.$store.state.status.loading },
            isLoadingCancellation: function(){ return this.$store.state.cancellation.loading },
            isMounted: function(){ return this.$store.state.status.mounted },
            // --------------------------------------------------------------

            // -------------------------------------------------------------
            // Reference shortcuts
            // -------------------------------------------------------------
            preset: function(){ return this.$store.state.preset },
            customer: function(){ return this.$store.state.customer },
            company_user_list: function(){ return this.$store.state.company_user_list },
            // -------------------------------------------------------------

            model: {
                get: function(){},
                set: function(){}
            }
        },

        methods: {
        },

        // ------------------------------------------------------------------
        // Wacthers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Watch customer data to refresh the validation
            // Since the customer data is dynamic / addable, 
            // we need to refresh the validation each time the data has changed
            // --------------------------------------------------------------
            customer: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

    };
    // ----------------------------------------------------------------------
    

    // ----------------------------------------------------------------------
    // When vue has been mounted/loaded
    // ----------------------------------------------------------------------
    $(document).on( 'vue-loaded', function( event, vm ){
        // ------------------------------------------------------------------
        // Init parsley form validation
        // ------------------------------------------------------------------
        var $window = $(window);
        var $form = $('form[data-parsley]');
        var form = $form.parsley();
        var queue = window.queue;
        var store = vm.$store;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Form Cancelation
        var $formcancel = $('form[data-parsley-cancel]');
        var formcancel = $formcancel.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit Account Settings
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // --------------------------------------------------------------
            // On form invalid, 
            // navigate/scroll the page to the validation messages
            // --------------------------------------------------------------
            var validForm = form.isValid();
            if( validForm==false ) navigateValidation( validForm );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // On form valid
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                var state = vm.$store.state;
                vm.$store.commit( 'setLoading', true );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Prepare request
                // ----------------------------------------------------------
                var data = {};
                var formData = new FormData();
                var url = io.get( state.preset, 'api.store' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Populate data
                // ----------------------------------------------------------
                data.customer = vm.customer;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // console.log( data ); return; // Debugging
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create multipart request with formData as the post data
                // ----------------------------------------------------------
                var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                queue.save = axios.post( url, formData, options ); // Do the request
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle success response
                // ----------------------------------------------------------
                queue.save.then( function( response ){
                    // ------------------------------------------------------
                    // console.log( response );
                    vm.$store.commit( 'setLoading', true );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( response.data ){
                        // --------------------------------------------------
                        $window.scrollTo( 0, { easing: 'easeOutQuart' });
                        // --------------------------------------------------

                        if(response.data.status == "success"){
                            // --------------------------------------------------
                            var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                            vm.$toasted.show( message, { type: 'success' });
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Redirect to the
                            // --------------------------------------------------
                            setTimeout( function(){
                                //var redirectPage = io.get( response.data, 'customer.url.view' );
                                //window.location = redirectPage;
                            }, 1000 );
                            // --------------------------------------------------
                        }else{
                            // --------------------------------------------------
                            var message = '@lang('label.FAILED_UPDATE_MESSAGE')';
                            vm.$toasted.show( message, { type: 'error' });
                            // --------------------------------------------------
                        }

                        
                    }
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle other response
                // ----------------------------------------------------------
                queue.save.catch( function(e){ console.log( e )});
                queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // On form submit Cancel
        // ------------------------------------------------------------------
        formcancel.on( 'form:validated', function(){

            // ----------------------------------------------------------
            var state = vm.$store.state;
            vm.$store.commit( 'setLoadingCancellation', true );
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // Prepare request
            // ----------------------------------------------------------
            var data = {};
            var formData = new FormData();
            var url = io.get( state.preset, 'api.cancel' );
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // Populate data
            // ----------------------------------------------------------
            data.customer = vm.customer;
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // Append data to the formData
            // ----------------------------------------------------------
            formData.append( 'dataset', JSON.stringify( data ));
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // console.log( data ); return; // Debugging
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // Create multipart request with formData as the post data
            // ----------------------------------------------------------
            var options = { headers: { 'Content-Type': 'multipart/form-data' }};
            queue.save = axios.post( url, formData, options ); // Do the request
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // Handle success response
            // ----------------------------------------------------------
            queue.save.then( function( response ){
                // ------------------------------------------------------
                // console.log( response );
                vm.$store.commit( 'setLoadingCancellation', true );
                // ------------------------------------------------------

                // ------------------------------------------------------
                if( response.data ){
                    // --------------------------------------------------
                    $window.scrollTo( 0, { easing: 'easeOutQuart' });
                    // --------------------------------------------------

                    if(response.data.status == "success"){
                        // --------------------------------------------------
                        var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, { type: 'success' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Redirect to the
                        // --------------------------------------------------
                        setTimeout( function(){
                            var redirectPage = @json( route( 'customer.logout' ));
                            window.location = redirectPage;
                        }, 1000 );
                        // --------------------------------------------------
                    }else{
                        // --------------------------------------------------
                        var message = '@lang('label.FAILED_UPDATE_MESSAGE')';
                        vm.$toasted.show( message, { type: 'error' });
                        // --------------------------------------------------
                    }

                    
                }
                // ------------------------------------------------------
            });
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            // Handle other response
            // ----------------------------------------------------------
            queue.save.catch( function(e){ console.log( e )});
            queue.save.finally( function(){ vm.$store.commit( 'setLoadingCancellation', false ) });
            // ----------------------------------------------------------

            // ----------------------------------------------------------
            return false;
            // ----------------------------------------------------------
            
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------
        
    });
    // ----------------------------------------------------------------------
}( _, jQuery, window, document ))
@endminify </script>
@endpush