
<script type="text/x-template" id="icheck-checkbox-tpl">
    <div class="icheck-cyan">
        <input type="checkbox" :checked="value == true" :value="value" :id="name" :name="name" @input="$emit( 'input', $event.target.checked )" 
            :true-value="true" :false-value="false" :required="required" />
        <label :for="name" class="mr-5">@{{ label }}</label>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'Icheck', {
            template: '#icheck-checkbox-tpl',
            props: {
                value: { required: true },
                name: { type: String, required: true },
                label: { type: String, required: true },
                required: { type: Boolean, default: false }
            }
        });
    }( jQuery, _, document, window ));
@endminify </script>