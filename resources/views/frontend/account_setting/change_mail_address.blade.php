@extends('frontend._base.vueform')
@section('form-content')
@section('title', $title)

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
                            <img src="{{ asset('frontend/assets/images/icons/icon_mail.png') }}" alt="icon_email" class="icon-title">
                            <span class="text-title">メールアドレス変更</span>
                        </h2>
                    </div>
                    <div class="content-body">
                        <form class="form-setting" data-parsley>
                            <p class="desc pb-53">変更にはメールによる認証が必要です。認証用URLが記載されたメールをお送りしますので、新しいメールアドレスをご入力してください。</p>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>現在のメールアドレス</label>
                                                
                                                <input type="email" id="input-email_old" name="email_old" class="form-control form-control-setting" 
                                                    v-model="customer.email" required
                                                    disabled="disabled"
                                                    data-parsley-required-message="メールアドレスを入力してください。" data-parsley-type-message="正しくメールアドレスを入力してください。" />

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
                                                <label>新しいメールアドレス</label>

                                                <input type="email" id="input-email" name="email" class="form-control form-control-setting" 
                                                    v-model="email.new_email" required data-parsley-trigger="focusout" placeholder="example@example.com"
                                                    data-parsley-email-exists="[{{ route( 'api.customer_email.exists' )}},{{ $customer_detail->id }}]"
                                                    data-parsley-required-message="メールアドレスを入力してください。" data-parsley-type-message="正しい形式でメールアドレスを入力してください。"
                                                    data-parsley-notequalto="#input-email_old" data-parsley-notequalto-message="新しいメールは現在のメールと同じであってはなりません"/>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label>新しいメールアドレス（確認用）</label>
                                                <input type="email" id="input-email_equal" name="email_equal" class="form-control form-control-setting" 
                                                    required placeholder="example@example.com" data-parsley-trigger="focusout" 
                                                    data-parsley-required-message="メールアドレスを入力してください。" data-parsley-type-message="正しい形式でメールアドレスを入力してください。" 
                                                    data-parsley-equalto="#input-email" data-parsley-equalto-message="メールアドレスが一致しません。">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-11 col-lg-4 mt-48 mt-48-sp mx-auto">

                                    <!-- Submit button - Start -->
                                    <button-action v-model="isLoading" type="submit" label="メールアドレスを変更する"></button-action>
                                    <!-- Submit button - End -->

                                </div>
                            </div>
                        </form>
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
                customer: @json( $customer_detail ),
                email: {
                    old_email : "",
                    new_email : ""
                },
                preset: {

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        store: @json( route( 'frontend.change_email.edit' ))
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
            customer: function(){ return this.$store.state.customer },
            email: function(){ return this.$store.state.email },
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
            email: { 
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
                data.email = vm.email;
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

                        if(response.data.status == "success"){
                            // --------------------------------------------------
                            /*
                            var message = '@lang('label.SUCCESS_UPDATE_MESSAGE')';
                            vm.$toasted.show( message, { type: 'success' });
                            */
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Redirect to the
                            // --------------------------------------------------
                            setTimeout( function(){
                                var redirectPage = "{{route('frontend.change_email.aftersend')}}";
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