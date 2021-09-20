<script type="text/x-template" id="property-button-append-customer-tpl">
    <button type="button" class="btn btn-block" @click="$emit('click')">
        <div class="row mx-n1 justify-content-center fs-14">
            <div class="px-1 col-auto">
                <i class="fs-14" :class="icon"></i>
            </div>
            <div class="px-1 col-auto">
                <span>@{{ label }}</span>
            </div>
        </div>
    </button>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ButtonAppend', {
            template: '#property-button-append-customer-tpl',
            props: {
                icon: { type: String, default: 'far fa-plus-circle' },
                label: { type: String, default: @json( __( 'property.create.button.customer.append' ))},
            }
        });
    }( jQuery, _, document, window ));
@endminify </script>
