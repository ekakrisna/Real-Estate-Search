@extends('frontend._base.vueform')
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

<div class="password-section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">

                    @include("frontend._includes.alert")
                    <div class="content-header">
                        <h2 class="title title-pages">
                            <img src="{{ asset('frontend/assets/images/icons/icon_password.png') }}" alt="icon_password" class="icon-title">
                            <span class="text-title">パスワードをお忘れの方</span>
                        </h2>
                        <p class="desc">ご登録メールアドレスを送信してください。パスワード再設定ページのリンクを送付します。</p>
                    </div>
                    <div class="content-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="form" data-parsley>
                                    <div class="form-group mb-48">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>登録メールアドレス</label>
                                                <input type="email" class="form-control" placeholder="example@example.com" 
                                                v-model="sendEmail.email"
                                                required data-parsley-required-message="正しい形式でメールアドレスを入力してください。" 
                                                data-parsley-type-message="正しい形式でメールアドレスを入力してください。" data-parsley-trigger="focusout">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-11 col-lg-4 mx-auto">
                                                <!-- Submit button - Start -->
                                                <button-action v-model="isLoading" type="submit" label="送信する"></button-action>
                                                <!-- Submit button - End -->
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <hr class="separator">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h2 class="title title-pages">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_mail.png') }}" alt="icon_mail" class="icon-title">
                                    <span class="text-title">登録メールアドレスがわからない方</span>
                                </h2>
                                <p class="desc">下記を入力の上、送信してください。担当者よりご連絡いたします。</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form class="form" data-parsley-forgotemail>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>名前</label>
                                                <input type="text" class="form-control" placeholder="仙台　太郎"
                                                v-model="contactUs.name"
                                                data-parsley-trigger="focusout" required data-parsley-required-message="お名前を入力してください。">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>ご登録いただいた会社名または店舗名</label>
                                                <input type="text" class="form-control" placeholder="〇〇株式会社" 
                                                v-model="contactUs.company_name"
                                                data-parsley-trigger="focusout" required data-parsley-required-message="会社名または店舗名を入力してください。">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-48">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>ご連絡先メールアドレス</label>
                                                <input type="email" class="form-control" placeholder="example@example.com"
                                                v-model="contactUs.email"
                                                data-parsley-trigger="focusout" required data-parsley-required-message="メールアドレスを入力してください。" data-parsley-type-message="正しい形式でメールアドレスを入力してください。">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-11 col-lg-4 mx-auto">

                                                <!-- Submit button - Start -->
                                                <button-action v-model="isLoadingForgot" type="submit" label="送信する"></button-action>
                                                <!-- Submit button - End -->
                                                
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
    </div>
</div>
@endsection


@push('vue-scripts')

<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
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
                forgot: { loading: false },
                sendEmail: {
                    email : ""
                },
                contactUs: @json( $contact_us ),
                preset: {

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        sendEmail: @json( route( 'password_reissue.sendemail' )),
                        forgotEmail: @json( route( 'password_reissue.forgotemail' )),
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
            // --------------------------------------------------------------

             // --------------------------------------------------------------
            // Set loading Forgot Email state
            // --------------------------------------------------------------
            setLoadingForgot: function( state, loading ){
                if( io.isUndefined( loading )) loading = true;
                Vue.set( state.forgot, 'loading', !! loading );
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
            isLoadingForgot: function(){ return this.$store.state.forgot.loading },
            isMounted: function(){ return this.$store.state.status.mounted },
            // --------------------------------------------------------------

            // -------------------------------------------------------------
            // Reference shortcuts
            // -------------------------------------------------------------
            preset: function(){ return this.$store.state.preset },
            sendEmail: function(){ return this.$store.state.sendEmail },
            contactUs: function(){ return this.$store.state.contactUs },
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
            sendEmail: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            contactUs: { 
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
        // Form forgotemail
        var $formforgotemail = $('form[data-parsley-forgotemail]');
        var formforgotemail = $formforgotemail.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit Send Email
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
                var url = io.get( state.preset, 'api.sendEmail' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Populate data
                // ----------------------------------------------------------
                data.sendEmail = vm.sendEmail;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                //console.log( data ); return; // Debugging
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
                        setTimeout( function(){
                                var redirectPage = "{{route('password_reissue.aftersend')}}";
                                window.location = redirectPage;
                        }, 1000 );
                        /*
                        if(response.data.status == "success"){
                            // --------------------------------------------------
                            var message = response.data.message;
                            //vm.$toasted.show( message, { type: 'success' });
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Redirect to the
                            // --------------------------------------------------
                            setTimeout( function(){
                                var redirectPage = "{{route('password_reissue.aftersend')}}";
                                window.location = redirectPage;
                            }, 1000 );
                            // --------------------------------------------------
                        }else{
                            // --------------------------------------------------
                            var message = '@lang('label.FAILED_UPDATE_MESSAGE')';
                            vm.$toasted.show( message, { type: 'error' });
                            // --------------------------------------------------
                        }
                        */                        
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
        // On form submit Forgot Email
        // ------------------------------------------------------------------
        formforgotemail.on( 'form:validated', function(){
            // --------------------------------------------------------------
            // On form invalid, 
            // navigate/scroll the page to the validation messages
            // --------------------------------------------------------------
            var validForm = formforgotemail.isValid();
            if( validForm==false ) navigateValidation( validForm );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // On form valid
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                var state = vm.$store.state;
                vm.$store.commit( 'setLoadingForgot', true );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Prepare request
                // ----------------------------------------------------------
                var data = {};
                var formData = new FormData();
                var url = io.get( state.preset, 'api.forgotEmail' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Populate data
                // ----------------------------------------------------------
                data.contactUs = vm.contactUs;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                //console.log( data ); return; // Debugging
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
                    vm.$store.commit( 'setLoadingForgot', true );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( response.data ){
                        // --------------------------------------------------
                        $window.scrollTo( 0, { easing: 'easeOutQuart' });
                        // --------------------------------------------------

                        if(response.data.status == "success"){
                            // --------------------------------------------------
                            var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                            vm.$toasted.show( message, { type: 'success' });
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Redirect to the
                            // --------------------------------------------------
                            setTimeout( function(){
                                //var redirectPage = "{{route('password_reissue')}}";
                                //window.location = redirectPage;
                            }, 1000 );
                            // --------------------------------------------------
                        }else{
                            // --------------------------------------------------
                            var message = '@lang('label.FAILED_CREATE_MESSAGE')';
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
                queue.save.finally( function(){ vm.$store.commit( 'setLoadingForgot', false ) });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------

        
    });
    // ----------------------------------------------------------------------
}( _, jQuery, window, document ))
@endminify </script>
@endpush