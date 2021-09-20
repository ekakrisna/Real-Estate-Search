<script type="text/x-template" id="page-filters-tpl">
    <div class="row">

        <div class="col-md-6">@relativeInclude('includes.id')</div>
        <div class="col-md-6">@relativeInclude('includes.search')</div>
        <div class="col-md-6">@relativeInclude('includes.role')</div>
        <div class="col-md-6">@relativeInclude('includes.company')</div>
        <div class="col-md-6">@relativeInclude('includes.status')</div>
        <div class="col-md-6">@relativeInclude('includes.order')</div>
        <div class="col-md-6">@relativeInclude('includes.perpage')</div>

        <hr class="w-100"/>

        <div class="col-md-6">@relativeInclude('includes.date')</div>
        <div class="col-md-6">@relativeInclude('includes.daterange')</div>

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
                        min: null, max: null, search: '',
                        company: '', role: '', status: '',
                        order: '', direction: null, 
                        start: '', end: '',
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
                filterMin: {
                    get: function(){ return io.parseInt( io.get( this.filter, 'min' ))},
                    set: function( value ){ this.filter.min = value }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Maximum ID filter model
                // --------------------------------------------------------------
                filterMax: {
                    get: function(){ return io.parseInt( io.get( this.filter, 'max' ))},
                    set: function( value ){ this.filter.max = value }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Date range model.
                // In this example, this will apply the selected range 
                // to filter.start and filter.end.
                // --------------------------------------------------------------
                filterDateRange: {
                    get: function(){ return [ this.filter.start, this.filter.end ]},
                    set: function( value ){
                        if( value.length && value.length == 2 ){
                            Vue.set( this.filter, 'start', value[0]);
                            Vue.set( this.filter, 'end', value[1]);
                        }
                    }
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

