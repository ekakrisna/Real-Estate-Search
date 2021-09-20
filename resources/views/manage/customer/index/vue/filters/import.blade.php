<script type="text/x-template" id="page-filters-tpl">
    <div class="row">

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
        
        <div class="col-md-6">@relativeInclude('includes.name')</div>
        <div class="col-md-6">@relativeInclude('includes.license')</div>
        <div class="col-md-6">@relativeInclude('includes.company_user')</div>
        <div class="col-md-6">@relativeInclude('includes.registration_date')</div>
        <div class="col-md-6">@relativeInclude('includes.last_use_date')</div>

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

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Filters
        // ----------------------------------------------------------------------
        Vue.component( 'Filters', {
            // ------------------------------------------------------------------
            props: [],
            template: '#page-filters-tpl',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {
                    filter: {},
                    defaultFilter: {
                        last_use_date_min: null, last_use_date_max: null,
                        regis_date_min: null, regis_date_max: null, 
                        name: '', company_user: '', 
                        license: null, order: '', direction: null, 
                        page: 1, perpage: 10
                    },
                    currentCompany : {},
                    defaultCompanyUser: this.$store.state.preset.company_users,
                    format: {
                        date: 'YYYY-MM-DD'
                    }
                };
                // --------------------------------------------------------------
    
                // --------------------------------------------------------------
                // Replicate the route query
                // --------------------------------------------------------------
                var query = this.$route.query;
                data.filter = io.assign({}, data.defaultFilter, query );
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted hook
            // ------------------------------------------------------------------
            mounted: function(){},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Route query
                // --------------------------------------------------------------
                query: function(){ return io.get( this.$route.query )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Minimum ID filter model
                // --------------------------------------------------------------
                minimumID: {
                    get: function(){ return io.get( this.query, 'min_id' )},
                    set: function( value ){ this.navigate({ min_id: value, page: 1 })}
                }
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
                navigate: function( query ){
                    this.$router.push({ name: 'index', query: query }).catch( function(e){});
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Reset Registration Date min filter
                // --------------------------------------------------------------
                resetRegisMin: function(){
                    this.filter.regis_date_min = null;
                    this.applyFilter();
                },
                // --------------------------------------------------------------
                // Reset Registration Date max filter
                // --------------------------------------------------------------
                resetRegisMax: function(){
                    this.filter.regis_date_max = null;
                    this.applyFilter();
                },
                // --------------------------------------------------------------
                // Reset Last Use Date min filter
                // --------------------------------------------------------------
                resetLastUseMin: function(){
                    this.filter.last_use_date_min = null;
                    this.applyFilter();
                },
                // --------------------------------------------------------------
                // Reset Last Use Date max filter
                // --------------------------------------------------------------
                resetLastUseMax: function(){
                    this.filter.last_use_date_max = null;
                    this.applyFilter();
                },
                // --------------------------------------------------------------
                // Reset filter
                // --------------------------------------------------------------
                resetFilter: function(){
                    this.filter = io.cloneDeep( this.defaultFilter );
                    this.applyFilter();
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Apply the filter with throttling by 500ms
                // --------------------------------------------------------------
                applyFilter: io.throttle( function( resetPage ){
                    // ----------------------------------------------------------
                    // Pick only filters that is not null or not empty string
                    // ----------------------------------------------------------
                    var filters = io.pickBy( this.filter, function( filter ){
                        if( io.isString( filter )) return io.trim( filter ).length;
                        else return !io.isNull( filter );
                    });
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // If filter needs to reset the page
                    // ----------------------------------------------------------
                    if( resetPage ) filters.page = 1;
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    this.navigate( filters );
                    // ----------------------------------------------------------
                }, 500 );
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {
                $route: function( to, from ){
                    // ----------------------------------------------------------
                    // Reset direction when order is not defined
                    // ----------------------------------------------------------
                    var order = io.get( to.query, 'order' );
                    if( !order ){
                        var query = io.cloneDeep( to.query );
                        delete( query.direction );
                        this.$router.push({  name: 'index', query: query }).catch( function(e){});
                    }
                    // ----------------------------------------------------------
                }
            }
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

