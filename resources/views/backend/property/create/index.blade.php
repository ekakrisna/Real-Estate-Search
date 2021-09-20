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
    <!-- Page preloader - End -->
    <div v-show="is_loading" class="modal-loading"><i class="fas fa-spinner fa-spin"></i></div>
    <!-- Form content - Start -->
    <!-- Form content - Start -->
    <form class="parsley-minimal" data-parsley>
        <div class="position-relative">

            <!-- Content mask loader - Start -->
            <mask-loader :loading="isLoading"></mask-loader>
            <!-- Content mask loader - End -->

            <!-- Property status - Start -->
            <property-status v-model="propertyStatus" :options="statusOptions" :required="true"></property-status>

            <property-limited v-model="customer" v-if="3 == propertyStatus"></property-limited>

            <property-reserve v-model="property.is_reserve" :options="conditionOptions"></property-reserve>            
            <!-- Property status - End -->
        
            <!-- Property condition - Start -->
            <property-condition v-model="property.building_conditions" 
                :desc.sync="property.building_conditions_desc" :options="conditionOptions">
            </property-condition>
            <!-- Property condition - End -->
        
            <!-- Property location - Start -->
            <input-text v-model="property.location" label="@lang( "{$label}.location" )"></input-text>
            <!-- Property location - End -->
        
            <!-- Property price - Start -->
            <input-range :min.sync="property.minimum_price" :max.sync="property.maximum_price"
                :precision="{min:0, max:4}" label="@lang( "{$label}.price" )" append="万円" :names="[ 'min_price', 'max_price' ]">
            </input-range>
            <!-- Property price - End -->
        
            <!-- Property land area - Start -->
            <input-range :min.sync="property.minimum_land_area" :max.sync="property.maximum_land_area" 
                :precision="{min:0, max:4}" label="@lang( "{$label}.area" )" append="坪" :names="[ 'min_land', 'max_land' ]">
            </input-range>
            <!-- Property land area - End -->
        
            <!-- Property contact - Start -->
            <input-textarea v-model="property.contact_us" label="@lang( "{$label}.contact" )" rows="5"></input-textarea>
            <!-- Property contact - End -->
        
            <!-- Property photo - Start -->            
            <div v-if="property.is_reserve" class="row form-group" @paste="pasteImagePhoto($event)" >
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang( "{$label}.photo")</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">
                    <div class="col-12 col-content">                                            

                        <draggable v-model="containerPhoto" class="sortable row border-0 form-group pb-0 mt-n3 mb-3">                                            
                            <div v-for="( item, index ) in containerPhoto" :key="item.id" class="col-md-4 col-xl-3 mt-3">
                                <ratiobox ratio="4-3">
                                    <a class="d-block h-100 image_box" @click="selectImagePhoto(item.id)" href="javascript:" >
                                        <iframe class="ratiobox-img img-thumbnail" :src="item.url" ></iframe>
                                        <div class="image_selected_button">
                                            <i v-if="selected_images_photo.indexOf(item.id) !== -1" class="far fa-check-square px-1"></i>
                                            <i v-else class="far fa-square px-1"></i>
                                        </div>
                                    </a>
                                </ratiobox>
                            </div>
                        </draggable>

                        <button type="button" @click="removePhotoImages" class="btn btn-sm btn-primary px-4" :disabled="selected_images_photo.length <= 0">@lang('label.delete_image')</button>
                        
                        <div class="row border-0 form-group mx-n4">
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
                                        アップロードする <input type="file" multiple="multiple" @change="uploadImagePhoto($event)" ref="input_file_property_photo" hidden accept="image/*">
                                    </label>
                                    <button type="button" :disabled="pasted_image_photo == null"  v-if="'file' != uploadModePhoto"  @click="uploadImagePhoto($event, true)" class="btn btn-sm btn-primary px-4">
                                        アップロードする
                                    </button>
                                </div>
                            </div>
                        </div>
            
                        <p v-if="'capture' == uploadModePhoto">@lang('label.copy')</p>
                    </div>
                </div>
            </div>
            <!-- Property photo - End -->

            <!-- Property flyer - Start -->            
            <div v-if="property.is_reserve" class="row form-group" @paste="pasteImageFlyer($event)" >
                <div class="col-md-3 col-lg-2 col-header">
                    <strong class="field-title">@lang( "{$label}.flyer")</strong>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">
                    <div class="col-12 col-content">                        
                          <draggable v-model="containerFlyer" class="sortable row border-0 form-group pb-0 mt-n3 mb-3">                                            
                                <div v-for="( item, index ) in containerFlyer" :key="item.id" class="col-md-4 col-xl-3 mt-3">
                                    <ratiobox ratio="4-3">
                                        <a class="d-block h-100 image_box" @click="selectImageFlyer(item.id)" href="javascript:" >
                                            <iframe class="ratiobox-img img-thumbnail" :src="item.url" ></iframe>
                                            <div class="image_selected_button">
                                                <i v-if="selected_images_flyer.indexOf(item.id) !== -1" class="far fa-check-square px-1"></i>
                                                <i v-else class="far fa-square px-1"></i>
                                            </div>
                                        </a>
                                    </ratiobox>
                                </div>
                            </draggable>                                                                                            
                        <button type="button" @click="removeFlyerImages" class="btn btn-sm btn-primary px-4" :disabled="selected_images_flyer.length <= 0">@lang('label.delete_image')</button>
                        <div class="row border-0 form-group mx-n4">
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
                                        アップロードする <input type="file" multiple="multiple" @change="uploadImageFlyer($event)" ref="input_file_property_flyer" hidden accept="image/*,.pdf">
                                    </label>
                                    <button type="button" :disabled="pasted_image_flyer == null"  v-if="'file' != uploadModeFlyer"  @click="uploadImageFlyer($event, true)" class="btn btn-sm btn-primary px-4">
                                        アップロードする
                                    </button>
                                </div>
                            </div>
                        </div>
            
                        <p v-if="'capture' == uploadModeFlyer">@lang('label.copy')</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form content - End -->
    
        <!-- Form buttons - Start -->
        <div class="row mx-n2 justify-content-center mt-5 mb-5">
            <div class="px-2 col-12 col-md-240px">

                <!-- Submit button - Start -->
                <button type="submit" class="btn btn-block btn-primary">
                    <div class="row mx-n1 justify-content-center">
                        <div v-if="isLoading" class="px-1 col-auto">
                            <i class="fal fa-cog fa-spin"></i>
                        </div>
                        <div class="px-1 col-auto">
                            <span>@lang('property.create.button.create')</span>
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
                status: { loading: false },
                property: @json( $property ),

                selection: { homeMaker: false, realEstate: false },
                publishing: {
                    options: @json( $publishingOptions ),
                    customer: {
                        homeMaker: @json( $customer->homeMaker ),
                        realEstate: @json( $customer->realEstate )
                    },
                },                                
                template: @json( $template ),
                preset: {
                    // -----------------------------------------------------
                    // List company
                    // -----------------------------------------------------
                    company: {
                        homeMaker: @json( $company->homeMaker ),
                        realEstate: @json( $company->realEstate )
                    },
                    // -----------------------------------------------------

                    // -----------------------------------------------------
                    // API endpoints
                    // -----------------------------------------------------
                    api: {
                        user: @json( route( 'api.company.users' )),
                        customer: @json( route( 'api.user.customers' )),
                        store: @json( route( 'admin.property.create.store' ))
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
            console.log( state );
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
            // Toggle home-maker selection
            // --------------------------------------------------------------
            toggleHomeMaker: function( state ){
                var selection = state.selection.homeMaker;
                Vue.set( state.selection, 'homeMaker', !selection );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Toggle real-estate selection
            // --------------------------------------------------------------
            toggleRealEstate: function( state ){
                var selection = state.selection.realEstate;
                Vue.set( state.selection, 'realEstate', !selection );
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
                selected_images_photo: [],
                selected_images_flyer: [],
                containerPhoto: [], // File container
                containerFlyer: [], // File container
                pasted_image_photo: null,
                pasted_image_flyer: null,
            }
        },
        // ------------------------------------------------------------------
        // On mounted hook
        // ------------------------------------------------------------------
        mounted: function(){
            $(document).trigger( 'vue-loaded', this );
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Loading status
            // --------------------------------------------------------------
            isLoading: function(){ return this.$store.state.status.loading },
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
                get: function(){ return io.get( this.property, 'property_statuses_id' )},
                set: function( value ){ this.$store.commit( 'assignProperty', { property_statuses_id: value })}
            },
            // --------------------------------------------------------------           
            // --------------------------------------------------------------
            statusOptions: function(){ return io.get( this.preset, 'status' )},
            conditionOptions: function(){ return io.get( this.preset, 'condition' )},
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------
        methods: {                        
            uploadImagePhoto(event, is_from_paste = false) {
                var vm = this;                
                vm.is_loading = true;
                var container = vm.containerPhoto;
                var pastePhoto = vm.pasted_image_photo;
                var files = event.target.files || [];
                if(is_from_paste == false){
                    if(files[0]['type']==='image/jpeg' || files[0]['type']==='image/jpg' || files[0]['type']==='image/png'){                        
                        var i = 1;
                        io.each( files, function( file ){                        
                            container.push({ 
                                id: container.length + 1,                                
                                sort_number: container.length + 1,                                
                                file: file, url: URL.createObjectURL( file )
                            });
                        });                                                
                        vm.is_loading = false;
                    }else{                        
                        vm.is_loading = false;
                    }                    
                } else {                    
                    if (container.length == 0) {                        
                        container.push({ 
                            id: 1,                            
                            sort_number: 1,                            
                            file: pastePhoto, url: URL.createObjectURL( pastePhoto )
                        });                        
                        vm.is_loading = false;
                    }else{
                        var i = 1;
                        vm.containerPhoto.forEach((element, index) => {
                            i++;
                        });

                        container.push({ 
                            id: i + 1,                            
                            sort_number: i + 1,
                            file: pastePhoto, url: URL.createObjectURL( pastePhoto )
                        });
                        vm.is_loading = false;
                    }                    
                }                
            },
            selectImagePhoto: function(id){
                if(!this.selected_images_photo.some(data => data === id)){
                    this.selected_images_photo.push(id);     
                }else{
                    var index = this.selected_images_photo.indexOf(id);
                    this.selected_images_photo.splice(index, 1);
                }
            },            
            removePhotoImages: function(){
                var vm = this;
                const spliceFunc = (key) =>{
                    var i = 1;
                    vm.containerPhoto.forEach((element, index) => {
                        if(element.id === key){
                            vm.containerPhoto.splice(index,1);
                        }
                        vm.containerPhoto.id = i++;
                        vm.containerPhoto.sort_number = i++;
                    });
                };

                vm.selected_images_photo.forEach((el, index)=>{
                    spliceFunc(el);
                    vm.selected_images_photo.splice(index, 1);
                });
            },
            orderImagePhoto: function(index, action){
                this.is_loading = true;
                var vm = this;
                if (action == 'next') {                    
                    function array_move(arr, old_index, new_index) {
                        if (new_index >= arr.length) {
                            var k = new_index - arr.length + 1;
                            while (k--) {
                                arr.push(undefined);
                            }
                        }
                        arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
                        return arr; // for testing
                    };           

                    if (index > 0) {
                        new_index = index++;
                        array_move(vm.containerPhoto, index, new_index);
                        vm.is_loading = false;
                    }else{
                        array_move(vm.containerPhoto, index, 1);
                        vm.is_loading = false;
                    }
                }
                if (action == 'prev') {
                    function array_move(arr, old_index, new_index) {
                        if (new_index >= arr.length) {
                            var k = new_index - arr.length + 1;
                            while (k--) {
                                arr.push(undefined);
                            }
                        }
                        arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
                        return arr; // for testing
                    };           
                             
                    if (index > 0) {
                        new_index = index--;
                        array_move(vm.containerPhoto, index, new_index);
                        vm.is_loading = false;
                    }else{
                        array_move(vm.containerPhoto, index, 1);
                        vm.is_loading = false;
                    }                    
                }                
            },
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
            pasteImagePhoto: async function(event){
                var vm = this;
                console.log("paste event triggered");                
                var imageBlob = await this.retrieveImageFromClipboardAsBlobPhoto(event);
                if(imageBlob){
                    vm.pasted_image_photo = imageBlob;
                    var canvas = vm.$refs.pasteImageCanvasPhoto;
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        canvas.src = event.target.result;
                    };            
                    reader.readAsDataURL(imageBlob);
                }
            },
            // <!-- Property photo - End -->  

            // <!-- Property Flyer - Start -->  
            uploadImageFlyer(event, is_from_paste = false) {                
                var vm = this;                
                vm.is_loading = true;
                var container = vm.containerFlyer;
                var pasteFlyer = vm.pasted_image_flyer;
                var files = event.target.files || [];
                if(is_from_paste == false){
                    if(files[0]['type']==='image/jpeg' || files[0]['type']==='image/jpg' || files[0]['type']==='image/png'){                        
                        var i = 1;
                        io.each( files, function( file ){                        
                            container.push({ 
                                id: container.length + 1,                                
                                sort_number: container.length + 1,                                
                                file: file, url: URL.createObjectURL( file )
                            });
                        });
                        vm.is_loading = false;
                    }else{                        
                        vm.is_loading = false;
                    }
                } else {                    
                    if (container.length == 0) {                        
                        container.push({ 
                            id: 1,                            
                            sort_number: 1,                            
                            file: pasteFlyer, url: URL.createObjectURL( pasteFlyer )
                        });
                        vm.is_loading = false;      
                    }else{
                        var i = 1;
                        vm.containerPhoto.forEach((element, index) => {
                            i++;
                        });

                        container.push({ 
                            id: i + 1,                            
                            sort_number: i + 1,
                            file: pasteFlyer, url: URL.createObjectURL( pasteFlyer )
                        });                        
                        vm.is_loading = false;
                    }                    
                }
            },
            selectImageFlyer: function(id){
                if(!this.selected_images_flyer.some(data => data === id)){
                    this.selected_images_flyer.push(id);     
                }else{
                    var index = this.selected_images_flyer.indexOf(id);
                    this.selected_images_flyer.splice(index, 1);
                }
            },            
            removeFlyerImages: function(){
                var vm = this;
                const spliceFunc = (key) =>{
                    var i = 1;
                    vm.containerFlyer.forEach((element, index) => {
                        if(element.id === key){
                            vm.containerFlyer.splice(index,1);
                        }
                        vm.containerFlyer.id = i++;
                        vm.containerFlyer.sort_number = i++;
                    });
                };

                vm.selected_images_flyer.forEach((el, index)=>{
                    spliceFunc(el);
                    vm.selected_images_flyer.splice(index, 1);
                });
            },
            orderImageFlyer: function(index, action){
                var vm = this;
                vm.is_loading = true;
                if (action == 'next') {                    
                    function array_move(arr, old_index, new_index) {
                        if (new_index >= arr.length) {
                            var k = new_index - arr.length + 1;
                            while (k--) {
                                arr.push(undefined);
                            }
                        }
                        arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
                        return arr; // for testing
                    };           

                    if (index > 0) {
                        new_index = index++;
                        array_move(vm.containerFlyer, index, new_index);
                    }else{
                        array_move(vm.containerFlyer, index, 1);
                    }
                    vm.is_loading = false;
                }
                if (action == 'prev') {
                    function array_move(arr, old_index, new_index) {
                        if (new_index >= arr.length) {
                            var k = new_index - arr.length + 1;
                            while (k--) {
                                arr.push(undefined);
                            }
                        }
                        arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
                        return arr; // for testing
                    };           
                             
                    if (index > 0) {
                        new_index = index--;
                        array_move(vm.containerFlyer, index, new_index);
                    }else{
                        array_move(vm.containerFlyer, index, 1);
                    }
                    vm.is_loading = false;
                }                
            },
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
            pasteImageFlyer: async function(event){
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
                }
            },
            // <!-- Property Flyer - End -->  
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
            customer: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Watch the user selection to refresh the validation
            // --------------------------------------------------------------
            selection: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Watch the property status to refresh the validation
            // --------------------------------------------------------------
            propertyStatus: { 
                deep: true, handler: io.throttle( function(){
                    this.$store.commit( 'refreshParsley' );
                }, 100 )
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
                io.each( vm.containerPhoto, function( photo ){
                    formData.append( 'photos[]', photo.file );
                    formData.append( 'sort_number_photo[]', photo.sort_number );
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Append flyer files
                // ----------------------------------------------------------
                io.each( vm.containerFlyer, function( flyer ){
                    formData.append( 'flyers[]', flyer.file );
                    formData.append( 'sort_number_flyer[]', flyer.sort_number );
                });
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
                    // console.log( response );
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