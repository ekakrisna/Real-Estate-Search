<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">

            <!-- ID column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-40px">
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">ID</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden text-left text-xl-center">
                        <div class="py-2 px-2">@{{ user.id }}</div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <!-- Name column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Name</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- Name column - End -->

            <!-- Date column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-150px">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Date</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.ja.created_at }}</div>
                    </div>
                </div>
            </div>
            <!-- Date column - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- User role column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-250px col-xl-120px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">User Role</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ role }}</div>
                    </div>
                </div>
            </div>
            <!-- User role column - End -->

            <!-- Company column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Company</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ company }}</div>
                    </div>
                </div>
            </div>
            <!-- Company column - End -->

            <!-- Email column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-220px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Email</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.email }}</div>
                    </div>
                </div>
            </div>
            <!-- Email column - End -->

            <!-- Column break - Columns below this will start a new row - Start -->
            <div class="col-12 d-xl-none"></div>
            <!-- Column break - Columns below this will start a new row - End -->

            <!-- Active status column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg col-xl-100px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Active status</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ status ? 'Active' : 'Non Active' }}</div>
                    </div>
                </div>
            </div>
            <!-- Active status column - End -->

            <!-- Control column - Start -->
            <div class="px-0 d-flex flex-column border-left col-12 col-lg-260px col-xl-90px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap flex-grow-1">
                    <div class="px-0 col-100px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Option</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1 justify-content-xl-center">
                                <div class="px-1 col-auto">
                                    <a target="_blank" class="btn btn-sm btn-info fs-12" :href="editPage">
                                        <i class="far fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="px-1 col-auto">
                                    <a target="_blank" class="btn btn-sm btn-secondary fs-12" :href="viewPage">
                                        <i class="far fa-external-link-square"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Control column - End -->

        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'ResultItem', {
            // ------------------------------------------------------------------
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {};
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------
                // Current user item
                // --------------------------------------------------------------
                user: function(){ return this.value },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // User properties
                // --------------------------------------------------------------
                role: function(){ 
                    var roleName = io.get( this.user, 'role.name' );
                    return io.startCase( io.lowerCase( roleName ));
                },
                company: function(){ return io.get( this.user, 'company.company_name' )},
                status: function(){ return io.parseInt( io.get( this.user, 'is_active' ))},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Page URLs
                // Comes from laravel model accessor
                // --------------------------------------------------------------
                editPage: function(){ return io.get( this.value, 'url.edit' )},
                viewPage: function(){ return io.get( this.value, 'url.view' )},
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {}
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

