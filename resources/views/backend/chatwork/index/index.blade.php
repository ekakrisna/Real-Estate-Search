@extends('backend._base.content_tablelike')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">Chatwork</li>
    </ol>
@endsection

@section('content')
<form class="parsley-minimal" data-parsley>
        <input-text v-model="message" label="Message" required name="message"></input-text>    
            <!-- Form buttons - Start -->
        <div class="row mx-n2 justify-content-center mt-5 mb-5">
            <div class="px-2 col-12 col-md-240px">

                <!-- Submit button - Start -->
                <button type="submit" class="btn btn-block btn-info">
                    <div class="row mx-n1 justify-content-center">
                        <div v-if="isLoading" class="px-1 col-auto">
                            <i class="fal fa-cog fa-spin"></i>
                        </div>
                        <div class="px-1 col-auto">
                            <span>Send</span>
                        </div>
                    </div>
                </button>
                <!-- Submit button - End -->

            </div>
        </div>
        <!-- Form buttons - End -->
</form>
@endsection



@push('vue-scripts')

@include('backend.vue.components.input-text.import')

<script> @minify
    (function( $, io, document, window, undefined ){
        window.queue = {}; // Event queue
        store = {
            // ------------------------------------------------------------------
            // Reactive central data
            // ------------------------------------------------------------------
            state: function(){
                var state = {
                    status: { mounted: false, loading: false },
                    config: {
                        placeholder: 3 // Item placeholder count
                    },
                    preset: {
                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        send: @json( route( 'admin.chatwork.send' )),                                                
                    },
                    // -----------------------------------------------------
                    },
                    result: null 
                };
                // console.log( state );
                return state;
            },
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
        // Vue mixin 
        // ----------------------------------------------------------------------
        mixin = {
            data: function(){
                return {
                    message: '',
                }
            },
            mounted: function(){
                this.$store.commit( 'setMounted', true );
                $(document).trigger( 'vue-loaded', this );
            },
            computed: {
                 // --------------------------------------------------------------
                // Loading and mounted status
                // --------------------------------------------------------------
                isLoading: function(){ return this.$store.state.status.loading },
                isMounted: function(){ return this.$store.state.status.mounted },
                // --------------------------------------------------------------
            },
            methods: {},
            watch: {
                $route: {}
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        };
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
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            //console.log(form.isValid());
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
                var data = vm.message;
                var formData = new FormData();
                var url = io.get( state.preset, 'api.send' );                
                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'message', data );
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
                    console.log( response );
                    vm.$store.commit( 'setLoading', true );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( response.data ){                        
                        // --------------------------------------------------
                        $window.scrollTo( 0, { easing: 'easeOutQuart' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                        vm.$toasted.show( message, { type: 'success' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Redirect to the property page
                        // --------------------------------------------------
                        setTimeout( function(){                            
                            window.location = @json(route( 'admin.chatwork.message' ));
                        }, 1000 );
                        // --------------------------------------------------
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
    }( jQuery, _, document, window ));
@endminify </script>
@endpush
