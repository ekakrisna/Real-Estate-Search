<script type="text/x-template" id="generic-show-land-area-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Field label - Start -->
            <strong class="field-title">@{{label}}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    <template v-if="min && max">
                        @{{ min | toTsubo | numeral('0,0') }} @{{append}} ~ @{{ max | toTsubo | numeral('0,0')}} @{{append}}
                    </template>
                    <template v-else-if="min && max == null" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                        @{{ min | toTsubo | numeral('0,0') }} @{{append}}
                    </template>
                    <template v-else-if="min == null && max" class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                        @{{ max | toTsubo | numeral('0,0') }} @{{append}}
                    </template>
                    <template v-else class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                        @lang('label.none')
                    </template>
                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ShowLandAreaForm', {
            // ------------------------------------------------------------------
            template: '#generic-show-land-area-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                label: { type: String, default: 'Label' },
                min: {type: Number, default: null},
                max: {type: Number, default: null},
                append: { type: String, default: null },
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            computed: {}
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>