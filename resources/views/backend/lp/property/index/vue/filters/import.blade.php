<script type="text/x-template" id="page-filters-tpl">
    <div class="row mb-4">
        
        <div class="col-12 mb-2">
            <div class="row mx-n3">
                <div class="px-3 col-auto fs-16">
                    <strong>@lang('label.table_filter')</strong>
                </div>
                <div class="px-3 col-auto d-flex align-items-center">
                    <a href="javascript:;" @click="resetFilter">@lang('label.reset_search_condition')</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">@relativeInclude('includes.update_date')</div>
        <div class="col-md-6">@relativeInclude('includes.contracted_year')</div>
        <div class="col-md-6">@relativeInclude('includes.id')</div>
        <div class="col-md-6">@relativeInclude('includes.location')</div>
        <div class="col-md-6">@relativeInclude('includes.price')</div>
        <div class="col-md-6">@relativeInclude('includes.land_area')</div>
        <div class="col-md-6">@relativeInclude('includes.building_area')</div>        
        <div class="col-md-6">@relativeInclude('includes.property_status')</div>
        <div class="col-md-6">@relativeInclude('includes.building_age')</div>
        <div class="col-md-6">@relativeInclude('includes.house_layout')</div>
        <div class="col-md-6">@relativeInclude('includes.conecting_road')</div>

        <!-- Separator - Start -->
        <div class="col-12"><hr class="mt-4 mb-3"/></div>
        <!-- Separator - End -->

        <!-- Order filter - Start -->
        <div class="col-md-6">
            <filter-order v-model.number="filter.order" :options="$store.state.preset.orders"
                :direction.sync="filter.direction" @change="applyFilter(true)">
            </filter-order>
        </div>
        <!-- Order filter - End -->

        <!-- Order direction filter - Start -->
        <div class="col-md-6">
            <filter-perpage v-model.number="filter.perpage" @change="applyFilter"></filter-perpage>
        </div>
        <!-- Order direction filter - End -->

    </div>
</script>

<script>
    @minify
        (function($, io, document, window, undefined) {
            // ----------------------------------------------------------------------
            // Filters
            // ----------------------------------------------------------------------
            Vue.component('Filters', {
                // ------------------------------------------------------------------
                props: [],
                template: '#page-filters-tpl',
                // ------------------------------------------------------------------
                data: function() {
                    // --------------------------------------------------------------
                    var data = {
                        filter: {},
                        defaultFilter: {
                            id: null,
                            location: '',
                            connecting_road: '',
                            contractDateStart: '',
                            contractDateEnd: '',
                            house_layout: '',
                            priceMin: '',
                            priceMax: '',
                            landAreaMin: '',
                            landAreaMax: '',
                            BuildingAgeMin: '',
                            BuildingAgeMax: '',
                            BuildingAreaMin: '',
                            BuildingAreaMax: '',
                            updateDateStart: '',
                            updateDateEnd: '',
                            propertyStatus: '',
                            order: '',
                            direction: null,
                            page: 1,
                            perpage: 10
                        }
                    };
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // Replicate the route query
                    // --------------------------------------------------------------
                    var query = this.$route.query;
                    data.filter = io.assign({}, data.defaultFilter, query);
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    return data;
                    // --------------------------------------------------------------
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // On mounted hook
                // ------------------------------------------------------------------
                mounted: function() {},
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Computed properties
                // ------------------------------------------------------------------
                computed: {
                    // --------------------------------------------------------------
                    // Route query
                    // --------------------------------------------------------------
                    query: function() {
                        return io.get(this.$route.query)
                    },
                    // --------------------------------------------------------------                    
                    filterBuildingAgeMin: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'BuildingAgeMin'))
                        },
                        set: function(value) {
                            this.filter.BuildingAgeMin = value
                        }
                    },
                    filterBuildingAgeMax: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'BuildingAgeMax'))
                        },
                        set: function(value) {
                            this.filter.BuildingAgeMax = value
                        }
                    },
                    // --------------------------------------------------------------                    
                    filterBuildingAreaMin: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'BuildingAreaMin'))
                        },
                        set: function(value) {
                            this.filter.BuildingAreaMin = value
                        }
                    },
                    filterBuildingAreaMax: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'BuildingAreaMax'))
                        },
                        set: function(value) {
                            this.filter.BuildingAreaMax = value
                        }
                    },
                    // --------------------------------------------------------------                    
                    filterId: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'id'))
                        },
                        set: function(value) {
                            this.filter.id = value
                        }
                    },
                    // --------------------------------------------------------------                    
                    filterPriceMin: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'priceMin'))
                        },
                        set: function(value) {
                            this.filter.priceMin = value
                        }
                    },
                    // --------------------------------------------------------------                    
                    filterPriceMax: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'priceMax'))
                        },
                        set: function(value) {
                            this.filter.priceMax = value
                        }
                    },
                    // --------------------------------------------------------------                    
                    filterLandAreaMin: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'landAreaMin'))
                        },
                        set: function(value) {
                            this.filter.landAreaMin = value
                        }
                    },
                    // --------------------------------------------------------------                    
                    filterLandAreaMax: {
                        get: function() {
                            return io.parseInt(io.get(this.filter, 'landAreaMax'))
                        },
                        set: function(value) {
                            this.filter.landAreaMax = value
                        }
                    },
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Call to action methods
                // ------------------------------------------------------------------
                methods: {
                    // --------------------------------------------------------------
                    // Navigate to a new route
                    // --------------------------------------------------------------
                    navigate: function(query) {
                        this.$router.push({
                            name: 'index',
                            query: query
                        }).catch(function(e) {});
                    },
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // Reset filter
                    // --------------------------------------------------------------
                    resetFilter: function() {
                        this.filter = io.cloneDeep(this.defaultFilter);
                        this.applyFilter();
                    },
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // Apply the filter with throttling by 500ms
                    // --------------------------------------------------------------
                    applyFilter: io.throttle(function(resetPage) {
                        // ----------------------------------------------------------
                        // Pick only filters that is not null or not empty string
                        // ----------------------------------------------------------
                        var filters = io.pickBy(this.filter, function(filter) {
                            if (io.isString(filter)) return io.trim(filter).length;
                            else return !io.isNull(filter);
                        });
                        // ----------------------------------------------------------

                        // ----------------------------------------------------------
                        // If filter needs to reset the page
                        // ----------------------------------------------------------
                        if (resetPage) filters.page = 1;
                        // ----------------------------------------------------------

                        // ----------------------------------------------------------
                        this.navigate(filters);
                        // ----------------------------------------------------------
                    }, 500);
                    // --------------------------------------------------------------
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Wacthers
                // ------------------------------------------------------------------
                watch: {
                    $route: function(to, from) {
                        // ----------------------------------------------------------
                        // Reset direction when order is not defined
                        // ----------------------------------------------------------
                        var order = io.get(to.query, 'order');
                        if (!order) {
                            var query = io.cloneDeep(to.query);
                            delete(query.direction);
                            this.$router.push({
                                name: 'index',
                                query: query
                            }).catch(function(e) {});
                        }
                        // ----------------------------------------------------------
                    }
                }
                // ------------------------------------------------------------------
            });
            // ----------------------------------------------------------------------
        }(jQuery, _, document, window));
    @endminify

</script>
