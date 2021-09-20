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
        <div class="row justify-content-center">
            <div class="col-12 col-sm-7 col-lg-5 mx-auto">
                <div class="content">
                    @include("frontend._includes.alert")
                    <div class="content-header">
                        <h2 class="title title-pages">
                            <img src="{{ asset('frontend/assets/images/icons/icon_password.png') }}" alt="icon_password" class="icon-title">
                            <span class="text-title">パスワードをお忘れの方</span>
                        </h2>                        
                    </div>
                    <div class="content-body">
                        <div class="row">
                            <div class="col">
                                <form class="form" data-parsley>
                                    <div class="form-group mb-48">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label>登録メールアドレス</label>
                                                <input type="email" class="form-control" placeholder="example@example.com"
                                                v-model="customerPassword.email"
                                                required data-parsley-required-message="正しくメールアドレスを入力してください。" 
                                                data-parsley-type-message="正しくメールアドレスを入力してください。" data-parsley-trigger="focusout">
                                            </div>                                                                                        
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label>新しいパスワード</label>
                                                <input type="password" id="input-password" name="password" class="form-control form-control-setting" 
                                                v-model="customerPassword.new_password" required data-parsley-trigger="focusout" data-parsley-required-message="パスワード入力してください。"/>                                                
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label>新しいパスワード（確認用）</label>
                                                <input type="password" id="input-password_equal" name="password_equal" class="form-control form-control-setting" 
                                                    required data-parsley-trigger="focusout" v-model="customerPassword.old_password"
                                                    data-parsley-required-message="パスワード入力してください。" 
                                                    data-parsley-equalto="#input-password" data-parsley-equalto-message="パスワードが一致しません。">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-11 col-lg-6 mx-auto">
                                                <!-- Submit button - Start -->
                                                <button-action v-model="isLoading" type="submit" label="パスワードを変更する"></button-action>
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
                password: {
                    email        : "",
                    old_password : "",
                    new_password : "",
                    token        : @json($token)
                },                
                preset: {

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        update: @json( route( 'password_reissue.adapt' )),
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
            isMounted: function(){ return this.$store.state.status.mounted },
            // --------------------------------------------------------------

            // -------------------------------------------------------------
            // Reference shortcuts
            // -------------------------------------------------------------
            preset: function(){ return this.$store.state.preset },
            customerPassword: function(){ return this.$store.state.password },            
            // -------------------------------------------------------------            
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
            password: { 
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
                var url = io.get( state.preset, 'api.update' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Populate data
                // ----------------------------------------------------------
                data.password = vm.customerPassword;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // console.log( data ); console.log( url ); return; // Debugging
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
                            var message = response.data.message;
                            vm.$toasted.show( message, { type: 'success' });
                            // --------------------------------------------------


                            // --------------------------------------------------
                            // Redirect to the
                            // --------------------------------------------------
                            setTimeout( function(){
                                var redirectPage = response.data.redirect;                                
                                window.location = redirectPage;
                            }, 1000 );
                            // --------------------------------------------------
                        }else{
                            // --------------------------------------------------
                            var message =  response.data.message;;
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
    });
    // ----------------------------------------------------------------------
}( _, jQuery, window, document ))
@endminify </script>
@endpush