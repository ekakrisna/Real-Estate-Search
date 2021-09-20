
    <!-- Modal Content - Start -->           
    <div ref="modalContent" id="modal-cancel" class="modal sm-modal text-break" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <p class="mb-2 font-weight-bold" v-if="approvalProperty.id">ID</p>
                                    <p class="mb-2 font-weight-bold" v-if="approvalProperty.location">@lang('label.location')</p>
                                    <p class="mb-2 font-weight-bold" v-if="landprice.minimum_price">@lang('label.minimum_price')</p>
                                    <p class="mb-2 font-weight-bold" v-if="landprice.maximum_price">@lang('label.maximum_price')</p>
                                    <p class="mb-2 font-weight-bold" v-if="landprice.minimum_land_area">@lang('label.minimum_land_area')</p>
                                    <p class="mb-2 font-weight-bold" v-if="landprice.maximum_land_area">@lang('label.maximum_land_area')</p>                                    
                                    <p class="mb-2 font-weight-bold" v-if="approvalProperty.building_conditions">@lang('label.building_condition')</p>
                                    <p class="mb-2 font-weight-bold" v-if="approvalProperty.property_publish">@lang('label.publish_site')</p>               
                                </div>
                                <div class="col-6">
                                    <p class="mb-2" v-if="approvalProperty.id">@{{ approvalProperty.id }}</p>
                                    <p class="mb-2" v-if="approvalProperty.location">@{{ approvalProperty.location }}</p>
                                    <p class="mb-2" v-if="landprice.minimum_price">@{{ landprice.minimum_price  | numeral('0,0') }}</p>
                                    <p class="mb-2" v-if="landprice.maximum_price">@{{ landprice.maximum_price  | numeral('0,0') }}</p>
                                    <p class="mb-2" v-if="landprice.minimum_land_area">@{{ landprice.minimum_land_area  | numeral('0,0') }}</p>
                                    <p class="mb-2" v-if="landprice.maximum_land_area">@{{ landprice.maximum_land_area  | numeral('0,0') }}</p>
                                    <p class="mb-2" v-if="approvalProperty.building_conditions">@{{ approvalProperty.building_conditions_desc }}</p>
                                    <p class="mb-1" v-if="approvalProperty.property_publish" v-for="( data, index ) in area.propertyPublish">@{{ data.publication_destination }}</p>                                    
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