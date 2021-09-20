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
                    <input :type="type" :value="value" class="form-control form-control-sm" :name="name" :id="id" :required="required ? true : false"
                        @input="$emit( 'input', $event.target.value )" data-parsley-minlength="8"  data-parsley-trigger="focusout" />

                        <div class="icheck-primary">
                            <input type="checkbox" id="show-password" name="show-password" @click="showPassword" />
                            <label for="show-password">@lang('label.showPassword')</label>
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
                label: { type: String, default: 'Label' },
            },
            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return { 
                    type: "password"
                }
            },
            // ------------------------------------------------------------------
            methods: {
                showPassword() {
                    let type = this.type;
                    type = this.type === 'password' ? 'text' : 'password';
                    Vue.set( this, 'type', type );
                }
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
