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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-header">
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
                        </div> --}}
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
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush

@push('scripts')
    <script src="{{ asset( 'plugins/parsley/parsley.min.js' )}}"></script>
    <script src="{{ asset( 'backend/dist/js/parsley.js' )}}"></script>

    @if (App::isLocale('en'))
      <script src="{{asset('plugins/parsley/i18n/en.js')}}"></script>
    @elseif (App::isLocale('jp'))
      <script src="{{asset('plugins/parsley/i18n/ja.js')}}"></script>
    @endif

    <script src="{{asset('plugins/summernote/summernote.js')}}"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/i18n/ja.js')}}"></script>
    <script>
        $(function () {
            // init: show tooltip on hover
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });

            // show password field only after 'change password' is clicked
            $('#reset-button').click(function (e) {
                if($("#reset-field").hasClass("d-none")){
                    $('#reset-field').removeClass('d-none');
                    $('#show-password-check').removeClass('d-none');
                    // to always uncheck the checkbox after button click
                    $('#show-password').prop('checked', false);
                    $("#input-password").prop("type", "password");
                    $("#input-password").val('');
                }else{
                    $('#reset-field').addClass('d-none');
                    $('#show-password-check').addClass('d-none');
                    $("#input-password").val('');
                }

            });

            // toggle password in plaintext if checkbox is selected
            $("#show-password").click(function () {
                $(this).is(":checked") ? $("#input-password").prop("type", "text") : $("#input-password").prop("type", "password");
            });

            $('[data-parsley]').parsley({
                uiEnabled: true,
                errorClass: 'is-invalid',
                successClass: 'is-valid'
            })

            $('.text-editor').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['table', 'link', 'hr']],
                    ['misc', ['fullscreen', 'codeview', 'undo', 'redo']]
                ],
                height: 200
            });

            $('.input-datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-M-D'
                }
            });

            //for address search
            $('.address-search').on('click', function(e){
                e.preventDefault();
                $postcode_btn = $(this);
                $postcode_form = $(this).siblings('input')
                $address_form = $("#input-address")
                $url = window.location.origin + '/api/v1/postcode/'+ $postcode_form.val()
                let request = $.ajax({
                    url: $url,
                    type: 'GET',
                    dataType: 'json',
                })
                
                request.done(function(data){
                    console.log(data);
                    $address_form.val(data.address)
                    $postcode_btn.closest('.postcode-group').siblings('.address-error-text').prop('hidden', true);

                })
                request.fail(function(data){
                    $address_form.val('')
                    $postcode_btn.closest('.postcode-group').siblings('.address-error-text').prop('hidden', false);
                })
            })      

            $("body").on('change', '.input-image', function() {
                input = this;
                var img = $(input).closest('.field-group').find('img');
                var input_remove = $(input).closest('.field-group').find('.input-remove-image');
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        img.attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                } else {
                    img.attr('src', img.data('default'));
                    input_remove.val('false');
                }
            });

            $('body').on('click', '.remove-image', function(){
                var img = $(this).closest('.field-group').find('img');

                $(this).closest('.field-group').find('.input-image').val('');
                $(this).closest('.image-preview').find('.input-remove-image').val( 'true' );
                img.attr('src', img.data('empty'));
            })

            $('.select2ajax').each(function(){
                $(this).select2({
                    theme: 'bootstrap4',
                    minimumInputLength: 0,
                    {!! App::isLocale('jp') ? 'language: "ja",' : '' !!}
                    ajax: {
                        delay: 500,
                        url: $(this).data('url'),
                        data: function (params) {
                            var query = {
                                q: params.term,
                                page: params.page || 1
                            };
                            return query;
                        },
                        processResults: function (response) {
                            var is_more = response.items.next_page_url !== null ? true : false;
                            return {
                                results:  $.map(response.items.data, function (item) {
                                    return {
                                        text: item[response.display],
                                        id: item[response.value]
                                    }
                                }),
                                pagination: {
                                    "more": is_more
                                }
                            };
                        },
                        cache: true
                    }
                });
                $(this).on('change', function(){
                    var selected_label = $(this).find('option:selected').text();
                    $(this).closest('.col-content').find('.selected-label').val( selected_label );
                });
            })

            $('.input-decimal-ratio').each(function(){
                $( '#' + $(this).data('target') ).val(
                    parseFloat( $(this).val() * $(this).data('multiply') ).toFixed(2)
                );

                $(this).on('change keyup', function(){
                    $( '#' + $(this).data('target') ).val(
                        parseFloat( $(this).val() * $(this).data('multiply') ).toFixed(2)
                    );
                });
            });

            //$("input[required], select[required]").attr("oninvalid", "this.setCustomValidity('asadad')");
            //$("input[required], select[required]").attr("oninput", "setCustomValidity('')");

        });

    </script>
@endpush
