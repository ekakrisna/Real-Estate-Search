<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-2 mt-lg-0">
        <div class="row mx-0">

            <div class="px-0 border-left col-lg">
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.access_time }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.label }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2"></div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2"><a :href="editPage">@{{ activity.properties_id }}</a></div>
                    </div>
                </div>
            </div>

            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">Company</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.location }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">Email</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.minimum_price }} ~ @{{ activity.maximum_price }}</div>
                    </div>
                </div>
            </div>

            <!-- Active status column - Start -->
            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">Active status</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.minimum_land_area }} ~ @{{ activity.maximum_land_area }}</div>
                    </div>
                </div>
            </div>

            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">Active status</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.building_conditions_desc }} </div>
                    </div>
                </div>
            </div>


            <div class="px-0 border-left col-lg">
                <div class="border-top d-lg-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px border-right d-lg-none bg-light">
                        <div class="py-2 px-2">Active status</div>
                    </div>
                    <div class="px-0 col col-lg-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ activity.customer_favorite_property.length >= 1 ? 'O' : 'X' }}  </div>
                    </div>
                </div>
            </div>
            

        </div>
        <div class="border-bottom d-lg-none"></div>
    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'ResultItem', {
            props: [ 'value', 'index' ],
            template: '#tablelike-result-item-tpl',
            data: function(){
                var data = {};
                return data;
            },
            computed: {
                activity: function(){ return this.value },
                //status: function(){ return io.parseInt( io.get( this.user, 'is_active' ))},
                editPage: function(){ return io.get( this.value, 'url.edit' )},
                // --------------------------------------------------------------
            },
            methods: {},
            watch: {}
        });
    }( jQuery, _, document, window ));
@endminify </script>

