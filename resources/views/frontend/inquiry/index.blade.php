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

<div class="inquiry-section">
    <div class="container">

        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content">

                    <!-- Content mask loader - Start -->
                    <!-- Content mask loader - End -->

                    @include("frontend._includes.alert")
                    <div class="content-header">
                        <h1 class="title">お問い合わせ</h1>
                    </div>
                    <div class="content-body">
                        <form class="form form-inquiry" data-parsley>
                            <div class="form-group {{Auth::guard('user')->user() == null ? "pt-3" : ""}}">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <label>お問い合わせ種別</label>
                                        <div class="form-select">
                                            <select name="contact_us_types_id" v-model.number="contactUs.contact_us_types_id" class="form-control" required data-parsley-required-message="お問い合わせ種別を選択してください。">
                                                <option></option>
                                                <option v-for="option in preset.contactType" :value="option.id">@{{ option.label }}</option>
                                            </select>
                                            <i class="fa fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::guard('user')->user() == null)
                                <div class="form-group pt-3">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <label>氏名</label>
                                            <div class="form-select">
                                                <input type="text" name="contact_us_name" v-model="contactUs.name" class="form-control border-0" id="contact_us_name" required data-parsley-required-message="氏名を入力してください。">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group pt-3">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <label>メールアドレス</label>
                                            <div class="form-select">
                                                <input type="email" name="contact_us_email" v-model="contactUs.email" class="form-control border-0" id="contact_us_email" required data-parsley-required-message="メールアドレスを入力してください。">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif                            
                            <div class="form-group pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label>@lang('label.inquiry_detail')</label>
                                        <textarea name="text" class="form-control" v-model="contactUs.text" placeholder="お問い合わせ内容をご記入ください" required data-parsley-required-message="お問い合わせ内容を入力してください。"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-lg-5 mx-auto">
                                        <!-- Submit button - Start -->
                                        <button-action v-model="isLoading" type="submit" label="上記の内容で保存する"></button-action>
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
                contactUs: @json( $contact_us ),
                preset: {

                    // -----------------------------------------------------
                    // List Type
                    // -----------------------------------------------------
                    contactType: @json( $contact_type ),
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        store: @json( route( 'contact.store' ))
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

            // --------------------------------------------------------------
            // ReSet contact us
            // --------------------------------------------------------------
            resetContactUs: function( state, desc ){
                Vue.set( state.contactUs, 'text','');
                Vue.set( state.contactUs, 'contact_us_types_id', '' );
                Vue.set( state.contactUs, 'name', '' );
                Vue.set( state.contactUs, 'email', '' );
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
                    vm.$store.commit( 'setLoading', true );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( response.data ){
                        // --------------------------------------------------
                        $window.scrollTo( 0, { easing: 'easeOutQuart' });
                        // --------------------------------------------------

                        if(response.data.status == "success"){
                            // --------------------------------------------------
                            var message = '@lang('label.SUCCESS_SEND_MESSAGE')';
                            vm.$toasted.show( message, { type: 'success' });
                            // --------------------------------------------------

                            // --------------------------------------------------
                            // Redirect to the
                            // --------------------------------------------------
                            setTimeout( function(){
                                //var redirectPage = "{{route('frontend.change_email.complete')}}";
                                //window.location = redirectPage;
                            }, 1000 );
                            // --------------------------------------------------
                            vm.$store.commit( 'resetContactUs');
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