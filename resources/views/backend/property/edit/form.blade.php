@extends('backend._base.content_vueform')

@php
    $label       = 'property.create.label';
    $status      = 'property.create.status';

    $pending     = __( "{$status}.pending" );
    $published   = __( "{$status}.published" );
    $limited     = __( "{$status}.limited" );
    $unpublished = __( "{$status}.unpublished" );
    $notpublish  = __( "{$status}.notpublish" );

    $none        = __('label.none');
    $available   = __('label.yes');
@endphp

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('admin.index')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.property')}}">@lang('label.list_of_properties')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('content')    

    <!-- Page preloader - Start -->
    <div v-if="!isMounted" class="preloader preloader-fullscreen d-flex justify-content-center align-items-center">
        <div class="folding-cube">
            <div class="cube cube-1"></div>
            <div class="cube cube-2"></div>
            <div class="cube cube-4"></div>
            <div class="cube cube-3"></div>
        </div>
    </div>
    <!-- Page preloader - End -->
    <div v-show="is_loading" class="modal-loading"><i class="fas fa-spinner fa-spin"></i></div>
    <!-- Form content - Start -->
    <form class="parsley-minimal" data-parsley>
        <div class="position-relative">

            <!-- Content mask loader - Start -->
            <mask-loader :loading="isLoading"></mask-loader>            
            <!-- Content mask loader - End -->

            <div class="row form-group">
                <div class="col-md-3 col-lg-2 col-header">
                    <span class="bg-success label-required">@lang('label.optional')</span>
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

            <!-- Property status - Start -->
            <property-status v-model="propertyStatus" :options="statusOptions" :required="true"></property-status>
            <property-limited v-model="customer" v-if="3 == propertyStatus"></property-limited>
            <property-reserve v-model="reserveCondition" :options="conditionOptions"></property-reserve>
            <!-- Property status - End -->
            
            <div class="row form-group">
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang( "label.property_id")</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                    <div class="row" v-for="property_publish in property.property_publish" >
                        <div class="col-12 col-content">
                            <p>@{{property_publish.publication_destination}} : @{{property_publish.property_number}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property condition - End -->

            <!-- Property condition - Start -->
            <property-condition v-model="buildingCondition" 
                :desc.sync="property.building_conditions_desc" :options="conditionOptions">
            </property-condition>
            <!-- Property condition - End -->
        
            <!-- Property location - Start -->
            <show-plain-data-form v-model="property.location" label="@lang( "label.location" )" ></show-plain-data-form>
            <!-- Property location - End -->
        
            <!-- Property price - Start -->
            <show-price-form :min.sync="property.minimum_price" :max.sync="property.maximum_price" label="@lang( "label.selling_price" )" ></show-price-form>
            <!-- Property price - End -->

            <!-- Property land area - Start -->
            <show-land-area-form :min.sync="property.minimum_land_area" :max.sync="property.maximum_land_area" label="@lang( "label.land_area" )" ></show-land-area-form>
            <!-- Property land area - End -->

            <!-- Property contact us - Start -->
            <show-plain-data-form v-model="property.contact_us" label="@lang( "label.property_contact_us" )" ></show-plain-data-form>
            <!-- Property contact us - End -->

            <!-- Property contact us - Start -->
            <!-- Property contact us - End -->            

            <div class="row form-group">
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang( "label.publication_medium")</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                    <div class="row" v-for="property_publish in property.property_publish" >
                        <div class="col-12 col-content">
                            <p v-if="property_publish.url ==='' ">
                                @{{property_publish.publication_destination}}</p>
                            <a v-else v-bind:href="property_publish.url" target="_blank" rel="noopener noreferrer">
                                @{{property_publish.publication_destination}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property photo - Start -->  
            <div class="row form-group d-flex align-items-start" @paste="pastePhotoImage($event)" >
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang( "{$label}.photo")</strong>
                </div>          
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">

                    <draggable v-model="property.property_photos" class="sortable row border-0 form-group pb-0 mt-n3 mb-3">                                            
                        <div v-for="( item, index ) in property.property_photos" :key="item.id" class="col-md-4 col-xl-3 mt-3">
                            <ratiobox ratio="4-3">
                                <a class="d-block h-100" @click="selectPhotoImage(item.id)" href="javascript:" :class="{image_selected:selected_photo_images.indexOf(item.id) !== -1}" >
                                    <img class="ratiobox-img img-thumbnail" :src="item.file.url.image" />
                                </a>
                            </ratiobox>
                        </div>
                    </draggable>

                    <button type="button" @click="removePhotoImages" class="btn btn-sm btn-primary px-4" :disabled="selected_photo_images.length <= 0">@lang('label.delete_image')</button>

                    <div class="row border-0 form-group mx-n4 pt-5">
                        <div v-if="'capture' == uploadModePhoto" class="px-4 col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                            <ratiobox ratio="4-3">
                                <div class="w-100 h-100 border border-dark">
                                    <img v-show="pasted_image_photo != null" class="ratiobox-img" ref="pasteImageCanvasPhoto" />
                                </div>
                            </ratiobox>
                        </div>
                        <div class="px-4 col mt-3 mt-sm-0" v-for="name in [ `property-${property.id}-photo-upload` ]">

                            <div class="upload-mode">
                                <div class="icheck-cyan" v-for="id in [ `${name}-capture` ]">
                                    <input v-model="uploadModePhoto" type="radio" value="capture" :name="name" :id="id"/>
                                    <label :for="id" class="mr-5">@lang('label.upload_from_capture')</label>
                                </div>
                            </div>

                            <div class="upload-mode mt-2">
                                <div class="icheck-cyan" v-for="id in [ `${name}-file` ]">
                                    <input v-model="uploadModePhoto" type="radio" value="file" :name="name" :id="id"/>
                                    <label :for="id" class="mr-5">@lang('label.upload_from_file')</label>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label v-if="'file' == uploadModePhoto" class="btn btn-sm btn-primary px-4">
                                    アップロードする <input type="file" multiple="multiple" @change="uploadPhotoImage($event)" ref="input_file_property_photo" hidden accept="image/*">
                                </label>
                                <button type="button" :disabled="pasted_image_photo == null"  v-if="'file' != uploadModePhoto"  @click="uploadPhotoImage($event, true)" class="btn btn-sm btn-primary px-4">
                                    アップロードする
                                </button>
                            </div>

                        </div>
                    </div>

                    <p v-if="'capture' == uploadModePhoto">@lang('label.copy')</p>
                </div>      
            </div>      
            <!-- Property photo - End -->


            <!-- Property flyer - Start -->            
            <div class="row form-group align-items-start" @paste="pasteFlyerImage($event)" >
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang( "{$label}.flyer")</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">                    

                    <draggable v-model="property.property_flyers" class="sortable row border-0 form-group pb-0 mt-n3 mb-3">                                            
                        <div v-for="( item, index ) in property.property_flyers" :key="item.id" class="col-md-4 col-xl-3 mt-3">
                            <ratiobox ratio="4-3">
                                <a class="d-block h-100 image_box" @click="selectFlyerImage(item.id)" href="javascript:" >
                                    <iframe class="ratiobox-img img-thumbnail" :src="item.file.url.image" ></iframe>
                                    <div class="image_selected_button">
                                        <i v-if="selected_flyer_images.indexOf(item.id) !== -1" class="far fa-check-square px-1"></i>
                                        <i v-else class="far fa-square px-1"></i>
                                    </div>
                                </a>
                            </ratiobox>
                        </div>
                    </draggable>
                        
                        <button type="button" @click="removeFlyerImages" class="btn btn-sm btn-primary px-4" :disabled="selected_flyer_images.length <= 0">@lang('label.delete_image')</button>

                        <div class="row border-0 form-group mx-n4 pt-5">
                            <div v-if="'capture' == uploadModeFlyer" class="px-4 col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                                <ratiobox ratio="4-3">
                                    <div class="w-100 h-100 border border-dark">
                                        <img v-show="pasted_image_flyer != null" class="ratiobox-img" ref="pasteImageCanvasFlyer" />
                                    </div>
                                </ratiobox>
                            </div>
                            <div class="px-4 col mt-3 mt-sm-0" v-for="name in [ `property-${property.id}-flyer-upload` ]">
                                <div class="upload-mode">
                                    <div class="icheck-cyan" v-for="id in [ `${name}-capture` ]">
                                        <input v-model="uploadModeFlyer" type="radio" value="capture" :name="name" :id="id"/>
                                        <label :for="id" class="mr-5">@lang('label.upload_from_capture')</label>
                                    </div>
                                </div>
                                <div class="upload-mode mt-2">
                                    <div class="icheck-cyan" v-for="id in [ `${name}-file` ]">
                                        <input v-model="uploadModeFlyer" type="radio" value="file" :name="name" :id="id"/>
                                        <label :for="id" class="mr-5">@lang('label.upload_from_file')</label>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label v-if="'file' == uploadModeFlyer" class="btn btn-sm btn-primary px-4">
                                        アップロードする <input type="file" multiple="multiple" @change="uploadFlyerImage($event)" ref="input_file_property_flyer" hidden accept="image/*,.pdf">
                                    </label>
                                    <button type="button" :disabled="pasted_image_flyer == null"  v-if="'file' != uploadModeFlyer"  @click="uploadFlyerImage($event, true)" class="btn btn-sm btn-primary px-4">
                                        アップロードする
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p v-if="'capture' == uploadModeFlyer">@lang('label.copy')</p>                    
                </div>
            </div>
            <!-- Property flyer - End -->     
        </div>
        <!-- Form content - End -->
    
        <!-- Form buttons - Start -->
        <div class="row mx-n2 justify-content-center mt-5 mb-5">
            <div class="px-2 col-12 col-md-240px">

                <!-- Submit button - Start -->
                <button type="submit" class="btn btn-block btn-info">
                    <div class="row mx-n1 justify-content-center">
                        <div v-if="isLoading" class="px-1 col-auto">
                            <i class="fal fa-cog fa-spin"></i>
                        </div>
                        <div class="px-1 col-auto">
                            <span>@lang('label.save')</span>
                        </div>
                    </div>
                </button>
                <!-- Submit button - End -->

            </div>
        </div>
        <!-- Form buttons - End -->
    
    </form>
@endsection

@push('vue-scripts')

<!-- General components - Start -->
@include('backend.vue.components.icheck.import')
@include('backend.vue.components.input-text.import')
@include('backend.vue.components.input-textarea.import')
@include('backend.vue.components.input-range.import')
@include('backend.vue.components.empty-placeholder.import')
@include('backend.vue.components.show-price-form.import')
@include('backend.vue.components.show-land-area-form.import')
@include('backend.vue.components.show-plain-data-form.import')
@include('backend.vue.components.show-anchor-data-form.import')
<!-- General components - End -->

<!-- Preloaders - Start -->
@include('backend.vue.components.page-loader.import')
@include('backend.vue.components.mask-loader.import')
<!-- Preloaders - End -->

<!-- Upload components - Start -->
@include('backend.vue.components.ratiobox.import')
<!-- Upload components - End -->

<!-- Shared property components - Start -->
@include('backend.property.vue.property-status.import')
@include('backend.property.vue.property-limited.import')
@include('backend.property.vue.property-condition.import')
@include('backend.property.vue.property-area.import')
@include('backend.property.vue.property-reserve.import')
<!-- Shared property components - End -->

<!-- Local components - Start -->
@relativeInclude('vue.input-upload.import')
<!-- Local components - End -->

<script> @minify
(function( io, $, window, document, undefined ){
    // ----------------------------------------------------------------------
    window.queue = {}; // Event queue
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Vuex store
    // ----------------------------------------------------------------------
    store = {
        // ------------------------------------------------------------------
        // Reactive state
        // ------------------------------------------------------------------
        state: function(){
            // --------------------------------------------------------------
            var state = {
                status: { mounted: false, loading: false },
                property: @json( $property ),

                selection: @json( $publishingSelection ),
                publishing: {
                    options: @json( $publishingOptions ),
                    customer: @json( $publishingCustomer )
                },
                template: @json( $template ),
                preset: {
                    // -----------------------------------------------------
                    // List company
                    // -----------------------------------------------------
                    company: {
                        homeMaker: @json( $company['homeMaker'] ),
                        realEstate: @json( $company['realEstate'] )
                    },
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        user: @json( route( 'api.company.users' )),
                        customer: @json( route( 'api.user.customers' )),
                        store: @json( route( 'admin.property.update', $property->id )),
                        photos: @json( route( 'api.property.photos' )),
                        flyers: @json( route( 'api.property.flyers' )),
                    },
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // Property status options
                    // -----------------------------------------------------
                    status: [
                        { id: 1, name: 'pending', label: @json( $pending )},
                        { id: 2, name: 'published', label: @json( $published )},
                        { id: 3, name: 'limited', label: @json( $limited )},
                        { id: 4, name: 'unpublished', label: @json( $unpublished )},
                        { id: 5, name: 'notpublish', label: @json( $notpublish )}
                    ],
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // Building condition options
                    // -----------------------------------------------------
                    condition: [
                        { id: 1, name: 'available', label: @json( $available )},
                        { id: 2, name: 'none', label: @json( $none )},
                    ]
                    // -----------------------------------------------------
                    
                }
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( state );
            return state;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Mutations
        // ------------------------------------------------------------------
        mutations: {
            // --------------------------------------------------------------
            refreshParsley: function(){
                setTimeout( function(){
                    var $form = $('form[data-parsley]');
                    $form.parsley().refresh();
                });
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Set loading state
            // --------------------------------------------------------------
            setLoading: function( state, loading ){
                if( io.isUndefined( loading )) loading = true;
                Vue.set( state.status, 'loading', !! loading );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Set mounted state
            // --------------------------------------------------------------
            setMounted: function( state, mounted ){
                if( io.isUndefined( mounted )) mounted = true;
                Vue.set( state.status, 'mounted', !! mounted );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle home-maker selection
            // --------------------------------------------------------------
            toggleHomeMaker: function( state ){
                var homeMaker = io.get( state.selection, 'homeMaker' );
                Vue.set( state.selection, 'homeMaker', !homeMaker );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle real-estate selection
            // --------------------------------------------------------------
            toggleRealEstate: function( state ){
                var realEstate = io.get( state.selection, 'realEstate' );
                Vue.set( state.selection, 'realEstate', !realEstate );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign property properties
            // --------------------------------------------------------------
            assignProperty: function( state, dataset ){
                io.assign( state.property, dataset );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create / append new customer
            // --------------------------------------------------------------
            appendCustomer: function( state, type ){
                if( 'realEstate' == type ){
                    var entry = io.clone( state.template.realEstate );
                    state.publishing.customer.realEstate.push( entry );
                }
                else if( 'homeMaker' == type ){
                    var entry = io.clone( state.template.homeMaker );
                    state.publishing.customer.homeMaker.push( entry );
                }
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Remove property customer 
            // --------------------------------------------------------------
            removeCustomer: function( state, { type, index }){
                if( 'realEstate' == type ) state.publishing.customer.realEstate.splice( index, 1 );
                else if( 'homeMaker' == type ) state.publishing.customer.homeMaker.splice( index, 1 );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Change building condition 
            // --------------------------------------------------------------
            setBuildingCondtion: function( state, condition ){
                var building_condition = condition == 1 ? true : false;
                Vue.set( state.property, 'building_conditions', building_condition );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Change Is Reseve condition 
            // --------------------------------------------------------------
            setReserveCondtion: function( state, condition ){
                var is_reserve = condition == 1 ? true : false;                
                Vue.set( state.property, 'is_reserve', is_reserve );
            },
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Vue mixin / Root component
    // ----------------------------------------------------------------------
    mixin = {
        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            return {
                uploadModePhoto: 'capture', 
                uploadModeFlyer: 'capture', 
                is_loading: false,
                selected_photo_images: [],
                selected_flyer_images: [],
                pasted_image_photo: null,
                pasted_image_flyer: null,
            }
        },
        // ------------------------------------------------------------------
        // On mounted hook
        // ------------------------------------------------------------------
        mounted: function(){
            this.$store.commit( 'setMounted', true );
            $(document).trigger( 'vue-loaded', this );
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Loading and mounted status
            // --------------------------------------------------------------
            isLoading: function(){ return this.$store.state.status.loading },
            isMounted: function(){ return this.$store.state.status.mounted },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Reference shortcuts
            // --------------------------------------------------------------
            preset: function(){ return this.$store.state.preset },
            property: function(){ return this.$store.state.property },
            customer: function(){ return this.$store.state.publishing.customer },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Property status model
            // --------------------------------------------------------------
            propertyStatus: {
                get: function(){ return this.$store.state.property.property_statuses_id },
                set: function( value ){ this.$store.commit( 'assignProperty', { property_statuses_id: value })}
            },
            // --------------------------------------------------------------
                        
            // --------------------------------------------------------------
            statusOptions: function(){ return io.get( this.preset, 'status' )},
            conditionOptions: function(){ return io.get( this.preset, 'condition' )},
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Building condtion
            // --------------------------------------------------------------
            buildingCondition: {
                get: function(){ return this.$store.state.property.building_conditions },
                set: function( value ){ this.$store.commit( 'setBuildingCondtion', value )}
            },

            // --------------------------------------------------------------
            // Is Reserve condtion
            // --------------------------------------------------------------
            reserveCondition: {
                get: function(){ return this.$store.state.property.is_reserve },
                set: function( value ){ this.$store.commit( 'setReserveCondtion', value )}
            },
        },
        // ------------------------------------------------------------------

        methods: {
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
            uploadPhotoImage(event, is_from_paste = false) {
                this.is_loading = true;
                var vm = this;
                var url = this.property.url.upload_property_photo;
                var formData = new FormData();

                if(is_from_paste == false){
                    var formData = new FormData();
                    for(var i = 0 ; i < event.target.files.length; i++){
                        formData.append('file[]', event.target.files[i]); 
                    }
                } else {
                    formData.append('file[]', this.pasted_image_photo);
                }
                var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                axios.post( url, formData, options ).then(response => {
                    for(var i = 0 ; i < response.data.length ; i++){                        
                        vm.property.property_photos.push(response.data[i]);                     
                    }
                    toastr.success('画像をアップロードしました。');
                    if(is_from_paste){
                        var canvas = vm.$refs.pasteImageCanvasPhoto;
                        canvas.src = "";
                        vm.pasted_image_photo = null;
                    }
                }).catch(error => {
                    toastr.error('画像のアップロードが正常に行えませんでした。');
                    console.log(error);
                }).finally(() => {  
                    vm.is_loading = false;
                });  
            },
            // --------------------------------------------------------------
            selectPhotoImage: function(id){
                if(!this.selected_photo_images.some(data => data === id)){
                    this.selected_photo_images.push(id);     
                }else{
                    var index = this.selected_photo_images.indexOf(id);
                    this.selected_photo_images.splice(index, 1);
                }
            },
            // --------------------------------------------------------------
            removePhotoImages: function(){
                if (confirm("@lang('label.CONFIRM_DELETE_MESSAGE')")) {
                    this.is_loading = true;
                    var vm = this;
                    var url = this.property.url.delete_property_photo;
                    var formData = new FormData();

                    var selectedImages = this.selected_photo_images;
                    var images = vm.property.property_photos;

                    formData.append('ids', JSON.stringify( selectedImages )); 
                    var options = { headers: { 'Content-Type': 'application/json' }};

                    axios.post( url, formData, options ).then(response => {
                        toastr.success('選択した画像を削除しました');                    
                        
                        io.remove( images, function( image ){
                            return selectedImages.indexOf( image.id ) >= 0;
                        });

                        vm.selected_photo_images = [];
                    }).catch(error => {
                        toastr.error('選択した画像の削除が正常に行えませんでした。');
                    }).finally(() => {  
                        vm.is_loading = false;
                    });
                }
            },
            // --------------------------------------------------------------
            retrieveImageFromClipboardAsBlobPhoto: async function(pasteEvent){                
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
            pastePhotoImage: async function(event){
                var vm = this;
                console.log("paste event triggered");
                // console.log(event);
                var imageBlob = await this.retrieveImageFromClipboardAsBlobPhoto(event);
                if(imageBlob){
                    // console.log(imageBlob);
                    // Save to data
                    vm.pasted_image_photo = imageBlob;
                    var canvas = vm.$refs.pasteImageCanvasPhoto;
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        canvas.src = event.target.result;
                    };            
                    reader.readAsDataURL(imageBlob);
                    this.uploadPhotoImage( null, true );
                }
            },
            // --------------------------------------------------------------
            // <!-- Property photo - End -->  


            // <!-- Property photo - Start -->  
            uploadFlyerImage( event, is_from_paste = false) {                
                this.is_loading = true;
                var vm = this;
                var url = this.property.url.upload_property_flyer;
                var formData = new FormData();

                if(is_from_paste == false){
                    var formData = new FormData();
                    for(var i = 0 ; i < event.target.files.length; i++){
                        formData.append('file[]', event.target.files[i]); 
                    }
                } else {
                    formData.append('file[]', this.pasted_image_flyer);
                }
                var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                axios.post( url, formData, options ).then(response => {
                    // console.log(response.data);
                    for(var i = 0 ; i < response.data.length ; i++){                        
                        vm.property.property_flyers.push(response.data[i]);                   
                    }
                    toastr.success('画像をアップロードしました。');
                    if(is_from_paste){
                        var canvas = vm.$refs.pasteImageCanvasFlyer;
                        canvas.src = "";
                        vm.pasted_image_flyer = null;
                    }
                }).catch(error => {
                    toastr.error('画像のアップロードが正常に行えませんでした。');
                    console.log(error);
                }).finally(() => {  
                    vm.is_loading = false;
                });  
            },
            // --------------------------------------------------------------
            selectFlyerImage: function(id){
                if(!this.selected_flyer_images.some(data => data === id)){
                    this.selected_flyer_images.push(id);     
                }else{
                    var index = this.selected_flyer_images.indexOf(id);
                    this.selected_flyer_images.splice(index, 1);
                }
            },
            // --------------------------------------------------------------
            removeFlyerImages: function(){
                if (confirm("@lang('label.CONFIRM_DELETE_MESSAGE')")) {
                    this.is_loading = true;
                    var vm = this;
                    var url = this.property.url.delete_property_flyer;
                    var formData = new FormData();
                    
                    var selectedImages = this.selected_flyer_images;
                    var images = vm.property.property_flyers;

                    formData.append('ids', JSON.stringify( selectedImages )); 
                    var options = { headers: { 'Content-Type': 'application/json' }};

                    axios.post( url, formData, options ).then(response => {
                        toastr.success('選択した画像を削除しました');

                        io.remove( images, function( image ){
                            return selectedImages.indexOf( image.id ) >= 0;
                        });

                        vm.selected_flyer_images = [];

                    }).catch(error => {
                        toastr.error('選択した画像の削除が正常に行えませんでした。');
                    }).finally(() => {  
                        vm.is_loading = false;
                    });
                }
            },
            // --------------------------------------------------------------
            retrieveImageFromClipboardAsBlobFlyer: async function(pasteEvent){                
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
            pasteFlyerImage: async function(event){
                var vm = this;
                console.log("paste event triggered");
                // console.log(event);
                var imageBlob = await this.retrieveImageFromClipboardAsBlobFlyer(event);
                if(imageBlob){
                    // console.log(imageBlob);
                    // Save to data
                    vm.pasted_image_flyer = imageBlob;
                    var canvas = vm.$refs.pasteImageCanvasFlyer;
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        canvas.src = event.target.result;
                    };            
                    reader.readAsDataURL(imageBlob);
                    this.uploadFlyerImage( null, true );
                }
            },
            // --------------------------------------------------------------
            // <!-- Property photo - End -->  


            // --------------------------------------------------------------
            // Move the image to previous place
            // --------------------------------------------------------------
            moveImagePrev: function( images, index ){
                var prevImage = images[ index -1 ];
                if( prevImage ) this.moveArrayElement( images, index, index -1 );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Move the image to next place
            // --------------------------------------------------------------
            moveImageNext: function( images, index ){
                var nextImage = images[ index +1 ];
                if( nextImage ) this.moveArrayElement( images, index, index+1 );
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Store ordered images
            // --------------------------------------------------------------
            storeOrders: function( type, images ){
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
                var url = vm.property.url.order_images;
                var data = {
                    type: type,
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
        // Wacthers
        // ------------------------------------------------------------------
        watch: {
            // --------------------------------------------------------------
            // Watch customer data to refresh the validation
            // Since the customer data is dynamic / addable, 
            // we need to refresh the validation each time the data has changed
            // --------------------------------------------------------------
            'publishing.customer': { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            // --------------------------------------------------------------
            // Watch photos ordering, store the orders
            // --------------------------------------------------------------
            'property.property_photos': function( images ){                
                this.storeOrders( 'photo', images )
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Watch flyers ordering, store the orders
            // --------------------------------------------------------------
            'property.property_flyers': function( images ){
                this.storeOrders( 'flyer', images );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    };
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // When vue has been mounted/loaded
    // ----------------------------------------------------------------------
    $(document).on( 'vue-loaded', function( event, vm ){
        // ------------------------------------------------------------------
        // Init parsley form validation
        // ------------------------------------------------------------------
        var $window = $(window);
        var $form = $('form[data-parsley]');
        var form = $form.parsley();
        var queue = window.queue;
        var store = vm.$store;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // --------------------------------------------------------------
            // On form invalid, 
            // navigate/scroll the page to the validation messages
            // --------------------------------------------------------------
            var validForm = form.isValid();
            if( !validForm ) navigateValidation( validForm );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // On form valid
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                var state = vm.$store.state;
                vm.$store.commit( 'setLoading', true );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Prepare request
                // ----------------------------------------------------------
                var data = {};
                var formData = new FormData();
                var url = io.get( state.preset, 'api.store' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Populate data
                // ----------------------------------------------------------
                data.customer = {};
                data.property = vm.property;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append photo files
                // ----------------------------------------------------------
                // io.each( vm.photos, function( photo ){
                //     formData.append( 'photos[]', photo.file );
                // });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append flyer files
                // ----------------------------------------------------------
                // io.each( vm.flyers, function( flyer ){
                //     formData.append( 'flyers[]', flyer.file );
                // });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Data photo files
                // ----------------------------------------------------------
                // data.photos = vm.property.property_photos;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Data flyer files
                // ----------------------------------------------------------
                // data.flyers = vm.property.property_flyers;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Add customer data based on the selection
                // Add publishing/customer data only when status is "Limited" 
                // and the real-estate or home-maker selection is checked
                // ----------------------------------------------------------
                var propertyStatus = Number( io.get( state, 'property.property_statuses_id' ));
                if( 3 === propertyStatus ){
                    // ------------------------------------------------------
                    var homeMaker = io.get( state, 'selection.homeMaker' );
                    if( homeMaker ) data.customer.homeMaker = io.filter( vm.customer.homeMaker, function( item ){
                        return item.companies_id || item.company_users_id || item.customers_id;
                    });
                    // ------------------------------------------------------
                    var realEstate = io.get( state, 'selection.realEstate' );
                    if( realEstate ) data.customer.realEstate = io.filter( vm.customer.realEstate, function( item ){
                        return item.companies_id || item.company_users_id || item.customers_id;
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append data to the formData
                // ----------------------------------------------------------
                formData.append( 'dataset', JSON.stringify( data ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // console.log( data ); return; // Debugging
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create multipart request with formData as the post data
                // ----------------------------------------------------------
                var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                queue.save = axios.post( url, formData, options ); // Do the request
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle success response
                // ----------------------------------------------------------
                queue.save.then( function( response ){
                    // ------------------------------------------------------
                    vm.$store.commit( 'setLoading', true );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    if( response.data ){
                        // --------------------------------------------------
                        $window.scrollTo( 0, { easing: 'easeOutQuart' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                        vm.$toasted.show( message, { type: 'success' });
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Redirect to the property page
                        // --------------------------------------------------
                        setTimeout( function(){
                            var propertyPage = io.get( response.data, 'property.url.view' );
                            window.location = propertyPage;
                        }, 1000 );
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Handle other response
                // ----------------------------------------------------------
                queue.save.catch( function(e){ console.log( e )});
                queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return false;
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( _, jQuery, window, document ))
@endminify </script>
@endpush