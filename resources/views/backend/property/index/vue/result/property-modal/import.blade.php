<script type="text/x-template" id="property-asset-modal-tpl">
    <div @paste="pasteImage($event)" ref="modalImage" id="modal-fullscreen" class="modal text-break" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div v-show="is_loading" class="modal-loading"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="modal-header">   
                    <h5 v-if="this.type === 'photo'" class="modal-title">@lang('label.upload_image')</h5>
                    <h5 v-else class="modal-title">@lang('label.upload_flyer')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pl-5 pr-5 pt-3">
                    
                    <div class="row form-group border-0">
                        <div class="col-md-3 col-lg-2 col-header">
                            <strong class="field-title">@lang( "label.report_bug" )</strong>
                        </div>
                        <div class="col-md-9 col-lg-10 col-content">
                            <div class="row px-n2" >
        
                                <div class="px-2 col-6 col-lg-auto" >
                                    <div class="icheck-cyan">
                                        <input id="is_bug_report" type="checkbox" value="1" v-model="property.is_bug_report" />                   
                                        <label for="is_bug_report" class="mr-5">@lang( "label.report_enable" )</label>
                                    </div>
                                </div>
        
                            </div>
                        </div>
                    </div>      
                    <div class="row border-bottom ">
                        <div class="col-sm-6 col-xl-1">
                            <p>@lang('label.property_id')</p>
                        </div>
                        <div class="col-sm-6 col-xl-11">
                            <p>@{{ property.id }}</p>
                        </div>
                    </div>
                    <div class="row border-bottom mt-3">
                        <div class="col-sm-6 col-xl-1">
                            <p>@lang('label.location')</p>
                        </div>
                        <div class="col-sm-6 col-xl-11">
                            <p>@{{ property.location }}</p>
                        </div>
                    </div>

                    <draggable v-model="images" class="sortable row border-0 form-group pb-0 mt-n3 mb-3">
                                                
                        <div v-for="( item, index ) in images" :key="item.id" class="col-md-4 col-xl-3 mt-3">
                            <ratiobox ratio="4-3" v-if="isPhotoModal">
                                <a class="d-block h-100" @click="selectImage(item.id)" href="javascript:" :class="{image_selected:selected_images.indexOf(item.id) !== -1}" >
                                    <img class="ratiobox-img img-thumbnail" :src="item.file.url.image" />
                                </a>
                            </ratiobox>

                            <ratiobox ratio="4-3" v-else-if="isFlyerModal">
                                <a class="d-block h-100 image_box" @click="selectImage(item.id)" href="javascript:" >
                                    <iframe class="ratiobox-img img-thumbnail" :src="item.file.url.image" ></iframe>
                                    <div class="image_selected_button">
                                        <i v-if="selected_images.indexOf(item.id) !== -1" class="far fa-check-square px-1"></i>
                                        <i v-else class="far fa-square px-1"></i>
                                    </div>
                                </a>
                            </ratiobox>

                        </div>
                    </draggable>
                    
                    <button @click="confirmDeleteImages" class="btn btn-sm btn-primary px-4" :disabled="selected_images.length <= 0">@lang('label.delete_image')</button>

                    <div class="row border-0 form-group mx-n4 pt-5">
                        <div v-if="'capture' == uploadMode" class="px-4 col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                            <ratiobox ratio="4-3">
                                <div class="w-100 h-100 border border-dark">
                                    <img v-show="pasted_image != null" class="ratiobox-img" ref="pasteImageCanvas" />
                                </div>
                            </ratiobox>
                        </div>
                        <div class="px-4 col mt-3 mt-sm-0" v-for="name in [ `property-${property.id}-${type}-upload` ]">

                            <div class="upload-mode">
                                <div class="icheck-cyan" v-for="id in [ `${name}-capture` ]">
                                    <input v-model="uploadMode" type="radio" value="capture" :name="name" :id="id"/>
                                    <label :for="id" class="mr-5">@lang('label.upload_from_capture')</label>
                                </div>
                            </div>

                            <div class="upload-mode mt-2">
                                <div class="icheck-cyan" v-for="id in [ `${name}-file` ]">
                                    <input v-model="uploadMode" type="radio" value="file" :name="name" :id="id"/>
                                    <label :for="id" class="mr-5">@lang('label.upload_from_file')</label>
                                </div>
                            </div>

                            <div class="mt-3">

                                <label v-if="'file' == uploadMode && isPhotoModal" class="btn btn-sm btn-primary px-4">
                                    @lang('label.upload_button') <input type="file" multiple="multiple" @change="uploadImage($event)" ref="input_file_property_image" hidden accept="image/*">
                                </label>

                                <label v-if="'file' == uploadMode && isFlyerModal" class="btn btn-sm btn-primary px-4">
                                    @lang('label.upload_button') <input type="file" multiple="multiple" @change="uploadImage($event)" ref="input_file_property_image" hidden accept="image/*,.pdf">
                                </label>
                                <!-- <button :disabled="pasted_image == null"  v-if="'file' != uploadMode"  @click="uploadImage($event, true)" class="btn btn-sm btn-primary px-4">
                                    アップロードする
                                </button>
                                !-->
                            </div>

                        </div>
                    </div>

                    <p v-if="'capture' == uploadMode">@lang('label.copy')</p>  

                    <!-- Property status - Start -->
                    <div class="row border-bottom form-group d-flex align-items-center">
                        <div class="col-3 col-sm-3 col-lg-2 ">
                            <p>@lang('label.status')</p>
                        </div>
                        <div class="col-3 col-sm-9 col-lg-10">
                            <div class="row" v-for="name in [ `property-${property.id}-${type}-status` ]">
                                <div class="px-2 col-sm-6 col-lg-auto" v-for="( option, index ) in propertyStatusOptions">
                                    <div class="icheck-cyan" v-for="id in [ `${name}-file-${property.id}-${index +1}` ]">                                        
                                        <input v-model="property.property_statuses_id" type="radio" :value="option.id" :id="id" :name="name" />
                                        <label :for="id" class="mr-5">@{{ option.label }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Company - Start -->
                    <div v-if="opened">
                        <property-limited v-model="customer" v-if="3 == property.property_statuses_id"></property-limited>
                    </div>                    
                    <!-- <property-limited v-model="customer" v-if="3 == property.property_statuses_id"></property-limited> -->
                    <div class="d-flex justify-content-center border-0 form-group">
                        <button type="button" class="btn btn-primary" @click="updateStatus">@lang('label.save_button_for_status')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script> @minify
    (function( $, io, document, window, undefined ){

        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'PropertyModal', {
            template: '#property-asset-modal-tpl',
            // ------------------------------------------------------------------
            props: {
                property: { type: Object, required: true },     // The property object
                type: { type: String, default: 'photo' },       // The modal type ( 'photo' | 'flyer' )
                opened: { type: Boolean, default: false },      // The modal state
            },
            // ------------------------------------------------------------------

            data: function(){
                var data = {
                    uploadMode: 'capture', 
                    is_loading: false,
                    selected_images: [],
                    pasted_image: null,
                    images: [],
                    data_type : this.type
                };

                if( 'photo' === this.type ) data.images = this.property.property_photos;
                else if( 'flyer' === this.type ) data.images = this.property.property_flyers;

                return data;
            },

            mounted(){

            },

            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                propertyStatusOptions: function(){ return io.get( this.$store.state, 'preset.status' )},
                customer: function(){ return io.get( this.$store.state, 'publishing.customer' )},
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Modal types
                // --------------------------------------------------------------
                isPhotoModal: function(){ return 'photo' === this.type },
                isFlyerModal: function(){ return 'flyer' === this.type },
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
    
            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                confirmDeleteImages: function(){
                    if (confirm("@lang('label.CONFIRM_DELETE_MESSAGE')")) {
                        this.removeImages();
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                updateStatus: function(){
                    // ------------------------------------------------------
                    // Perform data request
                    // ------------------------------------------------------
                    var vm = this;
                    var url = @json(route('api.property.update.status'));
                    var data = {};
                    data.id = vm.property.id;
                    data.is_bug_report = vm.property.is_bug_report;
                    data.status_id = vm.property.property_statuses_id;
                    data.customer = {};
                    // ----------------------------------------------------------
                    // Add customer data based on the selection
                    // Add publishing/customer data only when status is "Limited" 
                    // and the real-estate or home-maker selection is checked
                    // ----------------------------------------------------------
                    var propertyStatus = Number( io.get( vm.property, 'property_statuses_id' ));
                    if( 3 === propertyStatus ){
                        // ------------------------------------------------------
                        var homeMaker = io.get( vm.$store.state, 'selection.homeMaker' );                        
                        if( homeMaker ) data.customer.homeMaker = io.filter( vm.customer.homeMaker, function( item ){
                            return item.companies_id || item.company_users_id || item.customers_id;
                        });
                        // ------------------------------------------------------
                        var realEstate = io.get( vm.$store.state, 'selection.realEstate' );
                        if( realEstate ) data.customer.realEstate = io.filter( vm.customer.realEstate, function( item ){
                            return item.companies_id || item.company_users_id || item.customers_id;
                        });
                        // ------------------------------------------------------
                    }
                    var request = axios.post(url, data);
                    // ------------------------------------------------------
                    // On success
                    // ------------------------------------------------------
                    request.then(function(response) {                                                  
                        if (response.data.status == 'success') {
                            vm.customer.homeMaker = response.data.homeMaker;
                            vm.customer.realEstate = response.data.realEstate;                            
                            vm.$parent.value.property_status.label = response.data.property.property_status.label;
                            var message = "@lang('label.SUCCESS_UPDATE_MESSAGE')";
                            vm.$toasted.show(message, {
                                type: 'success'
                            });
                        }
                    });
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                uploadImage(event, is_from_paste = false) {
                    this.is_loading = true;
                    var vm = this;
                    var url = (this.type == "photo") ? this.property.url.upload_property_photo : this.property.url.upload_property_flyer;
                    var formData = new FormData();

                    if(is_from_paste == false){
                        var formData = new FormData();
                        for(var i = 0 ; i < event.target.files.length; i++){
                            formData.append('file[]', event.target.files[i]); 
                            // console.log(event.target.files[i]);
                        }
                    } else {
                        formData.append('file[]', this.pasted_image);
                    }

                    var options = { headers: { 'Content-Type': 'multipart/form-data' }};

                    axios.post( url, formData, options )
                    .then(
                        response => {
                            // console.log(response.data);
                            for(var i = 0 ; i < response.data.length ; i++){
                                if( vm.type == "photo" ){
                                    vm.property.property_photos.push( response.data[i] );
                                } else {
                                    vm.property.property_flyers.push(response.data[i]);
                                }
                            }
                            
                            toastr.success( "@lang('label.dialog_upload_success')");
                            if(is_from_paste){
                                var canvas = vm.$refs.pasteImageCanvas;
                                canvas.src = "";
                                vm.pasted_image = null;
                            }
                        }
                    )
                    .catch(
                        error => {
                            toastr.error( "@lang('label.dialog_upload_failed')");
                            console.log(error);
                        }
                    ).finally(
                        () => {  
                            vm.is_loading = false;
                        }
                    ); 
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                selectImage: function(id){
                    if(!this.selected_images.some(data => data === id)){
                        this.selected_images.push(id);     
                    }else{
                        var index = this.selected_images.indexOf(id);
                        this.selected_images.splice(index, 1);
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                removeImages: function(){
                    this.is_loading = true;
                    var vm = this;
                    var url = (this.type == "photo") ? this.property.url.delete_property_photo : this.property.url.delete_property_flyer;
                    var formData = new FormData();
                    var selectedImages = vm.selected_images;

                    formData.append('ids', JSON.stringify( selectedImages )); 
                    var options = { headers: { 'Content-Type': 'application/json' }};

                    var request = axios.post( url, formData, options );
                    request.then( function( response ){
                        toastr.success("@lang('label.dialog_delete_success')");

                        var images = vm.property.property_photos;
                        if( 'flyer' == vm.type ) images = vm.property.property_flyers;

                        io.remove( images, function( image ){
                            return selectedImages.indexOf( image.id ) >= 0;
                        });

                        vm.selected_images = [];
                    });

                    request.catch( function( error ){ toastr.error("@lang('label.dialog_delete_failed')") });
                    request.finally( function(){ vm.is_loading = false });
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                retrieveImageFromClipboardAsBlob: async function(pasteEvent){

                    if(pasteEvent.clipboardData == false){
                        return undefined;
                    };

                    var items = await (pasteEvent.clipboardData || pasteEvent.originalEvent.clipboardData).items;

                    if(items == undefined){
                        return undefined;
                    };

                    // console.log(items);
                    for (const item of items) {
                        if (item.type.indexOf('image') === 0) {
                            blob = item.getAsFile();
                            return blob;
                        }
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                pasteImage: async function(event){
                    var vm = this;
                    console.log("paste event triggered");
                    // console.log(event);
                    var imageBlob = await this.retrieveImageFromClipboardAsBlob(event);

                    if(imageBlob){
                        // console.log(imageBlob);
                        // Save to data
                        vm.pasted_image = imageBlob;

                        var canvas = vm.$refs.pasteImageCanvas;

                        var reader = new FileReader();
                        reader.onload = function(event) {
                            canvas.src = event.target.result;
                        };
                
                        reader.readAsDataURL(imageBlob);
                        this.uploadImage(null,true);
                    }
                },
                // --------------------------------------------------------------


                // --------------------------------------------------------------
                // Move an array element by index
                // Mutates the original array
                // --------------------------------------------------------------
                moveArrayElement: function( array, from, to ){
                    var deleted = 1;
                    var elm = array.splice( from, deleted )[0];
                    deleted = 0;
                    array.splice( to, deleted, elm );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Move the image to previous place
                // --------------------------------------------------------------
                movePrev: function( index ){
                    var prevImage = this.images[ index -1 ];
                    if( prevImage ) this.moveArrayElement( this.images, index, index -1 );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Move the image to next place
                // --------------------------------------------------------------
                moveNext: function( index ){
                    var nextImage = this.images[ index +1 ];
                    if( nextImage ) this.moveArrayElement( this.images, index, index+1 );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Store ordered images
                // --------------------------------------------------------------
                storeOrders: function( images ){
                    // ----------------------------------------------------------
                    var vm = this;
                    this.is_loading = true;
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    // Create order list
                    // ----------------------------------------------------------
                    var imageOrders = io.map( images, function( image, index ){
                        return { id: image.id, order: index +1 };
                    });
                    // ----------------------------------------------------------

                    // ----------------------------------------------------------
                    var url = this.property.url.order_images;
                    var data = {
                        type: vm.type,
                        property: vm.property.id,
                        items: imageOrders
                    };
                    // ----------------------------------------------------------
                    var request = axios.post( url, data );
                    request.then( function( response ){
                        vm.is_loading = false;
                    });
                    // ----------------------------------------------------------
                    request.catch( function(e){ console.log(e) });
                    request.finally( function(){ vm.is_loading = false });
                    // ----------------------------------------------------------
                }
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // When modal is opened
                // --------------------------------------------------------------
                opened: function( value ){
                    if( value ) this.pasted_image = null;
                },
                // --------------------------------------------------------------


                // --------------------------------------------------------------
                // Watch when the image list is ordered
                // Submit the orders to the server
                // --------------------------------------------------------------
                images: function( images ){
                    this.storeOrders( images );
                }
                // --------------------------------------------------------------
            }
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>

