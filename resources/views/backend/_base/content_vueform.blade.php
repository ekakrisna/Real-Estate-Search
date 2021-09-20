@extends("backend._base.app")

@section("content-wrapper")
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark h1title">{{$page_title}}</h1>
                </div>
                <div class="col-sm-6 text-sm">
                    @yield("breadcrumbs")
                </div>
            </div>
        </div>
    </div>

    @include("backend._includes.alert")

    <section class="content">

        <div v-if="!mounted" class="preloader preloader-fullscreen d-flex justify-content-center align-items-center">
            <div class="folding-cube">
                <div class="cube cube-1"></div>
                <div class="cube cube-2"></div>
                <div class="cube cube-4"></div>
                <div class="cube cube-3"></div>
            </div>
        </div>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-sm-6 card-title">
                                    @if ($page_type == "create")
                                        <h3 class="card-title">@lang('label.add')</h3>
                                    @elseif($page_type == "detail")
                                        <h3 class="card-title">@lang('label.detail')</h3>
                                    @else
                                        <h3 class="card-title">@lang('label.edit')</h3>
                                    @endif
                                </div>
                                <div class="col-sm-6 card-header-link">
                                    @yield('top_buttons')
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('css')
    <link rel="stylesheet" href="{{ asset( 'plugins/icheck-bootstrap/icheck-bootstrap.min.css' )}}">
@endpush

@push('scripts')
    <script src="{{ asset( 'plugins/parsley/parsley.min.js' )}}"></script>
    <script src="{{ asset( 'backend/dist/js/parsley.js' )}}"></script>

    @if( App::isLocale( 'en' ))
        <script src="{{ asset( 'plugins/parsley/i18n/en.js' )}}"></script>
    @elseif( App::isLocale( 'jp' ))
        <script src="{{ asset( 'plugins/parsley/i18n/ja.js' )}}"></script>
    @endif

    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script>
        (function( $, io, document, window, undefined ){
            // --------------------------------------------------------------        
            var options = {
                uiEnabled: true,
                errorClass: 'is-invalid',
                successClass: 'is-valid'
            };
            // --------------------------------------------------------------
            options.successClass = false;
            options.excluded = 'input[type=button], input[type=submit], input[type=reset], '+
                'input[type=hidden], input.parsley-excluded, [data-parsley-excluded]';
            // --------------------------------------------------------------
            options.errorsContainer = function( field ){

                var formResult = '.form-result';
                var $element = $( field.$element );
                var $result = $element.siblings( formResult );

                if( $result.length ) return $result;
                else {
                    
                    var $parent = $element.parent();
                    if( $parent.is('.input-group')){
                        $result = $parent.siblings( formResult );
                        if( $result.length ) return $result;
                    }
                    
                    var $row = $element.closest('.row');
                    $result = $row.siblings('.form-result');
                    
                    if( $result.length ) return $result;
                    else {
                        
                        var $group = $element.closest('.form-group');
                        $result = $group.next( formResult );

                        if( $result.length ) return $result;
                    }
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Stack to extend the parsley options
            // --------------------------------------------------------------
            @stack('extend-parsley')
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Initiate the parsley
            // --------------------------------------------------------------
            $('[data-parsley]').parsley( options );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Navigate to the validation messages
            // --------------------------------------------------------------
            window.navigateValidation = function( valid ){
                if( !valid ) setTimeout( function(){
                    // ------------------------------------------------------
                    var $error = $('.parsley-errors-list.filled').first();
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If error is inside a collapsible, open it
                    // ------------------------------------------------------
                    if( $error.closest('.collapsible').length ){
                        var $collapsible = $error.closest('.collapsible');
                        $collapsible.find('.btn-accordion.collapsed').trigger('click');
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If error is inside a tab panel, activate the tab
                    // ------------------------------------------------------
                    if( $error.closest('.tab-pane').length ){
                        // --------------------------------------------------
                        var $panel = $error.closest('.tab-pane');
                        var tabID = $panel.attr('id');
                        var $tabs = $panel.parent().siblings('.tabs');
                        // --------------------------------------------------
                        $tabs.find('li a[href="#' +tabID+ '"]').tab('show');
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Scroll to the error
                    // ------------------------------------------------------
                    setTimeout( function(){
                        $(window).scrollTo( $error, 600, { offset: -200, easing: 'easeOutQuart' });
                    }, 100 );
                    // ------------------------------------------------------

                    // ----------------------------------------------------------
                    return false;
                    // ----------------------------------------------------------
                });
            }
            // --------------------------------------------------------------
        }( jQuery, _, document, window ))
    </script>
@endpush