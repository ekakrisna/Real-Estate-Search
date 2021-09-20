<script type="text/x-template" id="page-filters-tpl">    
    <div class="row mb-4">            
        <div class="col-12 mb-2">
            <div class="row mx-n3">
                <div class="px-3 col-auto fs-16">
                    <strong>@lang('label.table_filter')</strong>
                </div>
                <div class="px-3 col-auto d-flex align-items-center">
                    <a href="#" @click.prevent="resetFilter">@lang('label.reset_search_codition')</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">@relativeInclude('includes.date')</div>        
        <div class="col-md-6">@relativeInclude('includes.name')</div>
        <div class="col-md-6">@relativeInclude('includes.action')</div>  
        <div class="col-md-6">@relativeInclude('includes.location')</div>            
        <div class="col-md-6">@relativeInclude('includes.build')</div>
        <div class="col-12"></div>            
        <div class="col-md-6">
            <filter-company v-model.number="filter.corporate" source="{{ route( 'api.company.all' )}}" 
                :person.sync="filter.person" @change="applyFilter">
            </filter-company>
        </div>                        
        <div class="col-md-6">
            <filter-person v-model.number="filter.person" source="{{ route( 'api.company.persons.users' )}}" 
                :company="filter.corporate" @change="applyFilter">
            </filter-person>
        </div>            
        <div class="col-12"><hr class="mt-4 mb-3"/></div>            
        <div class="col-md-6">
            <filter-order v-model.number="filter.order" :options="$store.state.preset.orders"
                :direction.sync="filter.direction" @change="applyFilter(true)">
            </filter-order>
        </div>            
        <div class="col-md-6">
            <filter-perpage v-model.number="filter.perpage" @change="applyFilter"></filter-perpage>
        </div>            
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){        
        // Filters
        Vue.component( 'Filters', {            
            props: [],
            template: '#page-filters-tpl',            
            data: function(){                
                var data = {
                    filter: {},
                    defaultFilter: {
                        min: null, max: null, search: '',
                        person: '', coroprate: '', status: '',
                        company:'', order: '', direction: null, 
                        page: 1, perpage: 10
                    }
                };                                
                // Replicate the route query                
                var query = this.$route.query;
                data.filter = io.assign({}, data.defaultFilter, query );                                
                return data;                
            },                    
            // On mounted hook            
            mounted: function(){},                        
            // Computed properties            
            computed: {                
                // Route query                
                query: function(){ return io.get( this.$route.query )},                                
                // Minimum ID filter model                
                minimumID: {
                    get: function(){ return io.get( this.query, 'min_id' )},
                    set: function( value ){ this.navigate({ min_id: value, page: 1 })}
                }                
            },                            
            // Call to action methods            
            methods: {                                
                navigate: function( query ){
                    this.$router.push({ name: 'index', query: query }).catch( function(e){});
                },                                          
                resetFilter: function(){
                    this.filter = io.cloneDeep( this.defaultFilter );
                    this.applyFilter();
                },                            
                applyFilter: io.throttle( function( resetPage ){                    
                    var filters = io.pickBy( this.filter, function( filter ){
                        if( io.isString( filter )) return io.trim( filter ).length;
                        else return !io.isNull( filter );
                    });                                                            
                    if( resetPage ) filters.page = 1;                                        
                    this.navigate( filters );                    
                }, 500 );                
            },            
            
            // Wacthers            
            watch: {
                $route: function( to, from ){                    
                    // Reset direction when order is not defined                    
                    var order = io.get( to.query, 'order' );
                    if( !order ){
                        var query = io.cloneDeep( to.query );
                        delete( query.direction );
                        this.$router.push({  name: 'index', query: query }).catch( function(e){});
                    }                    
                },
                'filter.perpage': {
                    immediate: true,
                    handler: function( value ){
                        // console.log( value );
                    }
                }
            }            
        });        
    }( jQuery, _, document, window ));
@endminify </script>

