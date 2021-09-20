<script type="text/x-template" id="tablelike-pagination-tpl">
    <nav class="d-flex" :class="align">
        <ul class="pagination mb-0">

            <!-- First button - Start -->
            <li v-if="setting.first" class="page-item" :class="{ disabled: isFirst || isLoading }">
                <template v-if="setting.first.html">
                    <button type="button" :disabled="isFirst || isLoading" class="h-100 page-link" v-html="setting.first.html" @click="navigate('first')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="isFirst || isLoading" class="h-100 page-link" @click="navigate('first')">
                        <div class="row mx-n1">
                            <div v-if="setting.first.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.first.icon"></i>
                            </div>
                            <div v-if="setting.first.label" class="px-1 col-auto">
                                <span>@{{ setting.first.label }}</span>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- First button - End -->

            <!-- Previous button - Start -->
            <li v-if="setting.prev" class="page-item" :class="{ disabled: isFirst || isLoading }">
                <template v-if="setting.prev.html">
                    <button type="button" :disabled="isFirst || isLoading" class="h-100 page-link" v-html="setting.prev.html" @click="navigate('prev')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="isFirst || isLoading" class="h-100 page-link" @click="navigate('prev')">
                        <div class="row mx-n1">
                            <div v-if="setting.prev.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.prev.icon"></i>
                            </div>
                            <div v-if="setting.prev.label" class="px-1 col-auto">
                                <span>@{{ setting.prev.label }}</span>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- Previous button - End -->

            <!-- Page buttons - Start -->
            <template v-if="pages && pages.length" v-for="page in pages">
                <li class="page-item" :class="{ active: page === currentPage, disabled: isLoading, 'd-none d-sm-block': page !== currentPage }">
                    <button type="button" :disabled="isLoading" class="h-100 page-link" @click="navigate( page )">
                        <span>@{{ page }}</span>
                    </button>
                </li>
            </template>
            <!-- Page buttons - End -->

            <!-- Next button - Start -->
            <li v-if="setting.next" class="page-item" :class="{ disabled: isLast || isLoading }">
                <template v-if="setting.next.html">
                    <button type="button" :disabled="isLast || isLoading" class="h-100 page-link" v-html="setting.next.html" @click="navigate('next')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="isLast || isLoading" class="h-100 page-link" @click="navigate('next')">
                        <div class="row mx-n1">
                            <div v-if="setting.next.label" class="px-1 col-auto">
                                <span>@{{ setting.next.label }}</span>
                            </div>
                            <div v-if="setting.next.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.next.icon"></i>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- Next button - End -->

            <!-- Last button - Start -->
            <li v-if="setting.last" class="page-item" :class="{ disabled: isLast || isLoading }">
                <template v-if="setting.last.html">
                    <button type="button" :disabled="isLast || isLoading" class="h-100 page-link" v-html="setting.last.html" @click="navigate('last')"></button>
                </template>
                <template v-else>
                    <button type="button" :disabled="isLast || isLoading" class="h-100 page-link" @click="navigate('last')">
                        <div class="row mx-n1">
                            <div v-if="setting.last.label" class="px-1 col-auto">
                                <span>@{{ setting.last.label }}</span>
                            </div>
                            <div v-if="setting.last.icon" class="px-1 col-auto fs-10 d-flex align-items-center">
                                <i :class="setting.last.icon"></i>
                            </div>
                        </div>
                    </button>
                </template>
            </li>
            <!-- Last button - End -->

        </ul>
    </nav>
</script>

<script> @minify
    // --------------------------------------------------------------------------
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
    
        // ----------------------------------------------------------------------
        // Pagination component
        // ----------------------------------------------------------------------
        Vue.component( 'Pagination', {
            // ------------------------------------------------------------------
            props: [ 'value', 'loading', 'option' ],
            template: '#tablelike-pagination-tpl',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {
                    defaults: {
                        // ------------------------------------------------------
                        first: {
                            icon: 'far fa-chevron-double-left',
                            label: false, html: false
                        },
                        // ------------------------------------------------------
                        last: {
                            icon: 'far fa-chevron-double-right',
                            label: false, html: false
                        },
                        // ------------------------------------------------------
                        prev: {
                            icon: 'far fa-chevron-left',
                            label: false, html: false
                        },
                        // ------------------------------------------------------
                        next: {
                            icon: 'far fa-chevron-right',
                            label: false, html: false
                        },
                        // ------------------------------------------------------
                        label: 'Search result pages',
                        size: 'md', maxlength: 5, align: 'center'
                        // ------------------------------------------------------
                    }
                    // ----------------------------------------------------------
                };
                // --------------------------------------------------------------
    
                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // On mounted
            // ------------------------------------------------------------------
            mounted: function(){},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                meta: function(){ return this.value },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Conditions
                // --------------------------------------------------------------
                isLoading: function(){ return this.loading },
                isFirst: function(){ return 1 === this.currentPage },
                isLast: function(){ return this.lastPage === this.currentPage },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Pages
                // --------------------------------------------------------------
                firstPage: function(){ return 1 },
                lastPage: function(){ return io.parseInt( io.get( this.meta, 'last_page' ))},
                currentPage: function(){ return io.parseInt( io.get( this.$route, 'query.page' ) || 1 )},
                // --------------------------------------------------------------
    
                // --------------------------------------------------------------
                // Build the pagination button
                // --------------------------------------------------------------
                pages: function(){
                    // ----------------------------------------------------------
                    var pages = [];
                    var last = this.lastPage;
                    var current = this.currentPage;
                    var maxlength = io.get( this.setting, 'maxlength' );
                    // ----------------------------------------------------------
                    if( last ) io.times( last, function( index ){
                        pages.push( index +1 );
                    });
                    // ----------------------------------------------------------
    
                    // ----------------------------------------------------------
                    // Limit pages to the maximum length
                    // ----------------------------------------------------------
                    if( maxlength && pages.length > maxlength ){
                        // ------------------------------------------------------
                        var length = maxlength;
                        var travel = Math.floor( length / 2 );
                        var index = pages.indexOf( current );
                        // ------------------------------------------------------
                        if( index <= travel ) return io.take( pages, length );
                        if( index >= last - travel -1 ) return io.takeRight( pages, length );
                        // ------------------------------------------------------
                        var start = index - travel; 
                        var end = start + length;
                        return io.slice( pages, start, end );
                        // ------------------------------------------------------
                    }
                    // ----------------------------------------------------------
    
                    // ----------------------------------------------------------
                    return pages;
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------
    
                // --------------------------------------------------------------
                // Extend default config with user defined one
                // --------------------------------------------------------------
                setting: function(){
                    // ----------------------------------------------------------
                    var config = io.assign({}, this.defaults, this.option );
                    // ----------------------------------------------------------
                    if( io.isString( config.first )) config.first = { label: config.first };
                    if( io.isString( config.prev )) config.prev = { label: config.prev };
                    if( io.isString( config.next )) config.next = { label: config.next };
                    if( io.isString( config.last )) config.last = { label: config.last };
                    // ----------------------------------------------------------
                    if( config.length && config.length % 2 == 0 ) config.length += 1;
                    // ----------------------------------------------------------
                    return config;
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------
    
                // --------------------------------------------------------------
                // HTML helpers
                // --------------------------------------------------------------
                align: function(){
                    // ----------------------------------------------------------
                    var align = false;
                    var prefix = 'justify-content-';
                    var config = io.get( this, 'config.align' ) || 'center';
                    // ----------------------------------------------------------
                    if( 'left' == config ) align = prefix+ 'start';
                    else if( 'center' == config ) align = prefix+ 'center';
                    else if( 'right' == config ) align = prefix+ 'end';
                    // ----------------------------------------------------------
                    return align;
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Page navigation
                // --------------------------------------------------------------
                navigate: function( page ){
                    // ----------------------------------------------------------
                    if( 'first' == page ) page = 1;
                    else if( 'prev' == page ) page = this.currentPage - 1 || 1;
                    else if( 'next' == page ){
                        page = this.currentPage + 1;
                        if( page > this.lastPage ) page = this.lastPage;
                    }
                    else if( 'last' == page ) page = this.lastPage;
                    // ----------------------------------------------------------
                    if( io.isInteger( page )){
                        var query = io.assign({}, this.$route.query, { page: page });
                        this.$router.push({ name: 'index', query: query }).catch(function(){});
                    }
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
    // --------------------------------------------------------------------------
@endminify </script>

