
<script type="text/x-template" id="empty-content-placeholder-tpl">
    <div class="bg-light border border-dark-50 rounded px-3 py-2 mt-3 text-center">
        <span class="text-muted">@{{ label }}</span>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'EmptyPlaceholder', {
            template: '#empty-content-placeholder-tpl',
            props: {
                label: { type: String, default: @json( __( 'label.empty' ))}
            }
        });
    }( jQuery, _, document, window ));
@endminify </script>