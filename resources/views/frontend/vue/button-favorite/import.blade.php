<script type="text/x-template" id="frontend-property-button-favorite-tpl">
    <div class="p-1 d-flex cursor-pointer" :class="[ buttonPulse ]" @click.stop="toggle">
        <i v-if="loading" class="fas text-silver" :class="[ iconSize, iconType ]"></i>
        <template v-else>
            <i v-if="value" class="fas text-red" :class="[ iconSize, iconType ]"></i>
            <i v-else class="fal text-muted" :class="[ iconSize, iconType ]"></i>
        </template>
    </div>
</script>

<script>
    @minify
        (function($, io, document, window, undefined) {
            Vue.component('ButtonFavorite', {
                // ------------------------------------------------------------------
                template: '#frontend-property-button-favorite-tpl',
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Aavailable properties
                // ------------------------------------------------------------------
                props: {
                    value: {
                        required: true
                    },
                    property: {
                        required: true,
                    },
                    size: {
                        default: 24
                    },
                    icon: {
                        type: String,
                        default: 'heart'
                    },
                    pulse: {
                        type: Boolean,
                        default: true
                    },
                    api: {
                        type: String,
                        default: @json(route('frontend.property.favorite'))
                    },
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                data: function() {
                    return {
                        loading: false
                    }
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                computed: {
                    // --------------------------------------------------------------
                    iconSize: function() {
                        return 'fs-' + this.size
                    },
                    iconType: function() {
                        return 'fa-' + this.icon
                    },
                    // --------------------------------------------------------------
                    buttonPulse: function() {
                        return this.pulse ? 'btn-pulse' : ''
                    },
                    // --------------------------------------------------------------
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Methods
                // ------------------------------------------------------------------
                methods: {
                    toggle: function() {
                        // ----------------------------------------------------------
                        var vm = this;
                        vm.loading = true;
                        // ----------------------------------------------------------

                        // ----------------------------------------------------------
                        // Request to API
                        // ----------------------------------------------------------
                        var data = {
                            property: io.get(vm.property, 'id')
                        };
                        var request = axios.post(vm.api, data);
                        // ----------------------------------------------------------
                        request.then(function(response) {
                            // ------------------------------------------------------
                            var status = io.get(response, 'status');
                            var success = 'success' == io.get(response.data, 'status');
                            var result = io.get(response.data, 'result');                            

                            io.each(vm.$store.state.result.data, function(properties) {                                
                                if (properties.hasOwnProperty('property') != false) {
                                    if (properties.property.id == io.get(vm.property, 'id')) {
                                        properties.property.favorited = result;
                                    }
                                }else{
                                    if (properties.id == io.get(vm.property, 'id')) {
                                        properties.favorited = result;
                                    }
                                }                                
                            });

                            // ------------------------------------------------------
                            if (200 === status && success) {
                                if (!io.isUndefined(result)) vm.$emit('input', result);
                            }
                            // ------------------------------------------------------
                        });
                        // ----------------------------------------------------------

                        // ----------------------------------------------------------
                        request.finally(function() {
                            vm.loading = false
                        });
                        // ----------------------------------------------------------
                    }
                }
                // ------------------------------------------------------------------
            });
        }(jQuery, _, document, window));
    @endminify

</script>
