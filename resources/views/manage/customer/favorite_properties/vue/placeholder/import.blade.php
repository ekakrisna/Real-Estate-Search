
@relativeInclude('item.import')
<script type="text/x-template" id="tablelike-placeholder-tpl">
    <div class="tablelike-placeholder">

        <!-- Placeholder item - Start -->
        <PlaceholderItem v-for="item in placeholders" :key="item" :index="item"></PlaceholderItem>
        <!-- Placeholder item - End -->

        <div class="border-bottom d-none d-lg-block"></div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Placeholder
        // ----------------------------------------------------------------------
        Vue.component( 'Placeholder', {
            // ------------------------------------------------------------------
            props: [ 'count' ],
            template: '#tablelike-placeholder-tpl',
            // ------------------------------------------------------------------
            data: function(){ return {}},
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Placeholder counts
                // --------------------------------------------------------------
                placeholders: function(){
                    var perpage = io.get( this.$route, 'query.perpage' ) || 10;
                    if( perpage <= this.count ) return perpage;
                    return this.count;
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

