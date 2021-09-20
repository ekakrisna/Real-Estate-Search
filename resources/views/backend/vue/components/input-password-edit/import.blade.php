<script type="text/x-template" id="generic-input-password-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
            <span v-else class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    <div class="row mx-n1">
                        
                    <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-content">
                        <button @click="inputHidden" type="button" name="reset" id="reset-button" class="btn btn-outline-info">@lang('label.change')</button>
                    </div>
                    <div id="reset-field" class="col-xs-8 col-sm-10 col-md-10 col-lg-10 col-content" :class="isHidden">
                        <input :type="type"  id="input-password" name="password" class="form-control form-control-sm" data-parsley-trigger="focusout" data-parsley-minlength="8"
                        @input="$emit( 'input', $event.target.value )" :required="required ? true : false" />
                        <div class="icheck-primary">
                            <input type="checkbox" id="show-password" name="show-password" @click="showPassword" />
                            <label for="show-password">@lang('label.showPassword')</label>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'InputPassword', {
            // ------------------------------------------------------------------
            template: '#generic-input-password-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { type: String, default: null },
                name: { type: String, default: null },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' }
            },
            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return { 
                    type: "password",
                    isHidden : "d-none"
                }
            },
            // ------------------------------------------------------------------
            methods: {
                showPassword() {
                    let type = this.type;
                    type = this.type === 'password' ? 'text' : 'password';
                    Vue.set( this, 'type', type );
                },
                inputHidden() {
                    let isHidden = this.isHidden;
                    isHidden = this.isHidden === 'd-none' ? '' : 'd-none';
                    Vue.set( this, 'isHidden', isHidden );
                }
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
