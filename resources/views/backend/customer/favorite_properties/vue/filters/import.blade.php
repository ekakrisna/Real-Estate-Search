<script type="text/x-template" id="page-filters-tpl">    
    <div class="row mb-4">
        <!-- Reference: https://www.chatwork.com/#!rid204191276-1405383158962102272 -->
        <div class="col-12 mb-2">
            <div class="row mx-n3">
                <div class="px-3 col-auto fs-16">
                    <strong>@lang('label.table_filter')</strong>
                </div>
                <div class="px-3 col-auto d-flex align-items-center">
                    <a href="#" @click="resetFilter" id="resetFilter">@lang('label.reset_search_codition')</a>
                </div>
            </div>
        </div>    
        <div class="col-md-6">@relativeInclude('includes.date')</div>
        <div class="col-md-6">@relativeInclude('includes.location')</div>
        <div class="col-md-6">@relativeInclude('includes.price')</div>
        <div class="col-md-6">@relativeInclude('includes.land')</div>
        <div class="col-md-6">@relativeInclude('includes.build')</div>
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
                        datestart: null, datefinish: null, location: '',
                        minprice: null, maxprice: null, minland: null, maxland: null,
                        order: '', direction: null, 
                        page: 1, perpage: 10
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
            mounted: function(){                
            },
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
                // Minimum price filter model
                // --------------------------------------------------------------
                filterPriceMin: {
                    get: function(){ return io.parseInt( io.get( this.filter, 'minprice' ))},
                    set: function( value ){ this.filter.minprice = value }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Maximum price filter model
                // --------------------------------------------------------------
                filterPriceMax: {
                    get: function(){ return io.parseInt( io.get( this.filter, 'maxprice' ))},
                    set: function( value ){ this.filter.maxprice = value }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Minimum land area filter model
                // --------------------------------------------------------------
                filterLandAreaMin: {
                    get: function(){ return io.parseInt( io.get( this.filter, 'minland' ))},
                    set: function( value ){ this.filter.minland = value }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Maximum land area filter model
                // --------------------------------------------------------------
                filterLandAreaMax: {
                    get: function(){ return io.parseInt( io.get( this.filter, 'maxland' ))},
                    set: function( value ){ this.filter.maxland = value }
                },
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

                resetdatestart: function(){
                    this.filter.datestart = null;
                    this.applyFilter();
                },

                 resetdatefinish: function(){
                    this.filter.datefinish = null;
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
                },
                'filter.perpage': {
                    immediate: true,
                    handler: function( value ){
                        console.log( value );
                    }
                }
            }
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

