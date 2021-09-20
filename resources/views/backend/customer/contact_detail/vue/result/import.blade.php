
@relativeInclude('item.import')
<script type="text/x-template" id="tablelike-result-tpl">
    <div class="tablelike-result">

        <div v-if="isEmpty" class="text-center py-2 px-2 border">
            <span>対象のレコードはありません。</span>
        </div>

        <template v-else>

            <!-- Result item - Start -->
            <ResultItem v-for="( item, index ) in value" :key="item.id" v-model="value[index]" :index="index"></ResultItem>
            <!-- Result item - End -->
            
            <div class="border-bottom d-none d-lg-block"></div>

        </template>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Result
        // ----------------------------------------------------------------------
        Vue.component( 'Result', {
            // ------------------------------------------------------------------
            props: [ 'value' ],
            template: '#tablelike-result-tpl',
            // ------------------------------------------------------------------
            data: function(){ return {}},
            // ------------------------------------------------------------------
            computed: {
                isEmpty: function(){ return !this.value.length }
            }
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

