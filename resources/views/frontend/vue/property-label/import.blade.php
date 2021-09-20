<script type="text/x-template" id="frontend-property-label-tpl">
    <label v-if="isQualified" class="bg-badge w-auto d-block m-0 mb-2" :class="[ badgeColor, badgePadding ]">@{{ badgeLabel }}</label>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'PropertyLabel', {
            // ------------------------------------------------------------------
            template: '#frontend-property-label-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                property: { required: true },
                type: { type: String, default: 'new' },
                padding: { default: 3 }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Cleaned badge type
                // --------------------------------------------------------------
                cleanType: function(){ return io.trim( io.camelCase( this.type ))},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Find if this label is qualified.
                // Unqualified label will NOT be displayed.
                // e.g. []
                // --------------------------------------------------------------
                isQualified: function(){ return io.get( this.property, `label.${this.cleanType}` )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Contextual badge color
                // --------------------------------------------------------------
                badgeColor: function(){
                    if( 'new' === this.cleanType ) return 'badge-red';
                    else if( 'updated' === this.cleanType ) return 'badge-blue';
                    else if( 'noCondition' === this.cleanType ) return 'badge-green';
                    else if( 'isReserve' === this.cleanType ) return 'badge-orange';
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // The badge label
                // --------------------------------------------------------------
                badgeLabel: function(){
                    if( 'new' === this.cleanType ) return '新着';
                    else if( 'updated' === this.cleanType ) return '更新';
                    else if( 'noCondition' === this.cleanType ) return '建築条件なし';
                    else if( 'isReserve' === this.cleanType ) return '予約/契約 済';
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // The badge horizontal padding
                // --------------------------------------------------------------
                badgePadding: function(){ if( this.padding ) return `px-${this.padding}` },
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
