<script type="text/x-template" id="property-asset-modal-tpl">
    <!-- Modal Content - Start -->
    <div ref="modalContent" id="modal-cancel" class="modal sm-modal text-break" tabindex="-1" role="dialog"aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <p>以上の内容でサイトに表示する。</p>    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form :ref="`form-modal`">
                    <div class="modal-body">
                        <div class="container col-md-12 col-lg-12 col-xl-12">                            
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2 font-weight-bold">ID</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.contracted_years')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.location')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.minimum_price')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.maximum_price')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.minimum_land_area')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.maximum_land_area')</p>   
                                    <p class="mb-2 font-weight-bold">@lang('label.buliding_area_lp')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.buliding_age_lp')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.house_layout')</p>
                                    <p class="mb-2 font-weight-bold">@lang('label.connecting_road')</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">@{{ approvalProperty.id }}</p>
                                    <p class="mb-2">@{{ approvalProperty.ja.contracted_years }}</p>
                                    <p class="mb-2">@{{ approvalProperty.location }}</p>
                                    <p class="mb-2"> @{{ (approvalProperty.minimum_price)  | toMan | numeral('0,0') }} 万円</p>
                                    <p class="mb-2" v-if="price.maximum_price">@{{ (price.maximum_price)  | toMan | numeral('0,0') }} 万円</p>
                                    <p class="mb-2" v-else> - </p>

                                    <p class="mb-2"> @{{ (approvalProperty.minimum_land_area) | numeral('0,0') }} ㎡</p>                                    
                                    <p class="mb-2" v-if="price.maximum_land_area">@{{ price.maximum_land_area  | numeral('0,0') }} ㎡</p>
                                    <p class="mb-2" v-else> - </p>

                                    <p class="mb-2">@{{ approvalProperty.building_area | toTsubo | numeral('0,0')}} 坪</p>
                                    <p class="mb-2">@{{ approvalProperty.building_age }} 年</p>
                                    <p class="mb-2">@{{ approvalProperty.house_layout }}</p>
                                    <p class="mb-2">@{{ approvalProperty.connecting_road }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-primary" @click="submit()">@lang('label.yes_button')</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">@lang('label.no_button')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal - End -->
</script>

<script>
    @minify
        (function($, io, document, window, undefined) {
            // ----------------------------------------------------------------------
            // Result item
            // ----------------------------------------------------------------------
            Vue.component('PropertyModal', {
                // ------------------------------------------------------------------
                props: [
                    'value',
                    'price'
                ],
                template: '#property-asset-modal-tpl',
                // ------------------------------------------------------------------
                data: function() {
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
                    approvalProperty: function() {
                        return this.value;
                    },
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Call to action methods
                // ------------------------------------------------------------------
                methods: {
                    submit: function(){
                        var vm          = this;                          
                        var link        = vm.$store.state.preset.api.store;                                                  
                        axios.post(link, {
                            location            : vm.approvalProperty.location,
                            minimum_price       : vm.price.minimum_price,
                            maximum_price       : vm.price.maximum_price,
                            minimum_land_area   : vm.price.minimum_land_area,
                            maximum_land_area   : vm.price.maximum_land_area,
                        }).then(function (response) {   
                            console.log(response);
                            if (response.data.status == "success") {                                                                                                                           
                                var message = "@lang('label.SUCCESS_UPDATE_MESSAGE')";
                                vm.$toasted.show(message, {
                                    type: 'success',
                                });
                                setTimeout( function(){
                                    var redirectPage = @json( route( 'admin.lp.approval' ));
                                    window.location = redirectPage;
                                }, 1000 );
                            }
                        })
                    }
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Wacthers
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
            });
            // ----------------------------------------------------------------------
        }(jQuery, _, document, window));
    @endminify

</script>
