<script type="text/x-template" id="generic-input-upload-tpl">
    <div class="row form-group">
        <div class="col-md-3 col-lg-2 col-header">

            <!-- Required / Optional labels - Start -->
            <span v-if="required" class="bg-danger label-required">@lang('label.required')</span>
            <span v-else class="bg-success label-required">@lang('label.optional')</span>
            <!-- Required / Optional labels - End -->

            <!-- Field label - Start -->
            <strong class="field-title">@{{ label }}</strong>
            <!-- Field label - End -->

        </div>
        <div class="col-md-9 col-lg-10 col-content">
            <!--<div class="row mx-n2 mt-2 mt-md-0">-->
                <!--<div class="px-2 col-lg-9">-->
                    
                    <!-- Uploaded property images - Start -->
                    <div v-if="propertyfiles.length || container.length">
                        <div v-for="(file,index) in propertyfiles" class="row align-items-center">
                            <div class="px-2 col-sm-6 col-xl-4 mt-3">
                                <ratiobox ratio="4-3">
                                    <div v-if="file.selected" class="ratiobox-control">
                                        <div class="ratiobox-checked d-flex align-items-center justify-content-center">
                                            <i class="fs-12 fas fa-check text-info"></i>
                                        </div>
                                    </div>
                                    <img :src="file.file.url.image" class="img-thumbnail" :class="{ 'border border-info': file.selected }" alt="">
                                </ratiobox>
                            </div>
                            
                            <div class="px-2 col-sm-6 col-xl-4 mt-3">
                                <button type="button" class="btn btn-block btn-default" @click="removeSelected(index)">
                                    <div class="row mx-n1 justify-content-center">
                                        <div class="px-1 col-auto">
                                            <i class="fal fa-trash"></i>
                                        </div>
                                        <div class="px-1 col-auto fs-14 d-flex align-items-center">
                                            <span>@lang('property.create.button.upload.remove')</span>                                        
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div v-for="(file,index) in container" class="row align-items-center">
                            <div class="px-2 col-sm-6 col-xl-4 mt-3">
                                <ratiobox ratio="4-3">
                                    <div v-if="file.selected" class="ratiobox-control">
                                        <div class="ratiobox-checked d-flex align-items-center justify-content-center">
                                            <i class="fs-12 fas fa-check text-info"></i>
                                        </div>
                                    </div>
                                    <img :src="file.url" class="img-thumbnail" :class="{ 'border border-info': file.selected }" alt="">
                                </ratiobox>
                            </div>
                            <div class="px-2 col-sm-6 col-xl-4 mt-3">
                                <button type="button" class="btn btn-block btn-default" @click="removeAppend(index)">
                                    <div class="row mx-n1 justify-content-center">
                                        <div class="px-1 col-auto">
                                            <i class="fal fa-trash"></i>
                                        </div>
                                        <div class="px-1 col-auto fs-14 d-flex align-items-center">
                                            <span>@lang('property.create.button.upload.remove')</span>                                        
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Uploaded property images - End -->

                    <div class="row mx-n1 mt-3">
                        <div class="px-1 col-6 col-md-160px">
                            <!-- Append button - Start -->
                            <input type="file" :id="id" style="visibility: hidden; height: 0" class="d-block" ref="upload" multiple @change="appendFiles" accept=".jpeg, .jpg, .png"/>
                            <label :for="id" class="btn btn-block btn-info cursor-pointer" :class="buttonSize">
                                <div class="row mx-n1 justify-content-center">
                                    <div class="px-1 col-auto">
                                        <i class="fal fa-plus-circle"></i>
                                    </div>
                                    <div class="px-1 col-auto fs-14 d-flex align-items-center">
                                        <span>@lang('property.create.button.upload.select')</span>
                                    </div>
                                </div>
                            </label>
                            <!-- Append button - End -->
                        </div>
                    </div>

               <!-- </div>-->
            <!--</div>-->
        </div>

    </div>
</script>

<script>
    @minify
        (function($, io, document, window, undefined) {
            Vue.component('InputUpload', {
                // ------------------------------------------------------------------
                template: '#generic-input-upload-tpl',
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Aavailable properties
                // ------------------------------------------------------------------
                props: {
                    value: {
                        required: true
                    },
                    id: {
                        type: String,
                        default: 'generic-upload'
                    },
                    name: {
                        type: String,
                        default: null
                    },
                    required: {
                        type: Boolean,
                        default: false
                    },
                    label: {
                        type: String,
                        default: 'Label'
                    },
                    size: {
                        type: String,
                        default: 'md'
                    },
                    // ------------------------------------------------------------------
                    propertyfiles: {
                        default: 0
                    }, //uploaded prorperty photo from db
                    // ------------------------------------------------------------------
                },
                // ------------------------------------------------------------------


                // ------------------------------------------------------------------
                // Reactive data
                // ------------------------------------------------------------------
                data: function() {
                    return {
                        container: [], // File container                    
                        // ------------------------------------------------------------------
                    }
                },
                // ------------------------------------------------------------------


                // ------------------------------------------------------------------
                // Computed properties
                // ------------------------------------------------------------------
                computed: {
                    // --------------------------------------------------------------
                    // Button size
                    // --------------------------------------------------------------
                    buttonSize: function() {
                        var size = io.toLower(io.trim(this.size));
                        if (!size || 'md' == size) return null;
                        return 'btn-' + size;
                    },
                    // --------------------------------------------------------------

                    property_images: function() {
                        return this.propertyfiles
                    }
                },
                // ------------------------------------------------------------------


                // ------------------------------------------------------------------
                // Methods
                // ------------------------------------------------------------------
                methods: {
                    // --------------------------------------------------------------
                    // Append new files
                    // --------------------------------------------------------------
                    appendFiles: function() {
                        // ----------------------------------------------------------
                        var container = this.container;
                        var files = io.get(this.$refs, 'upload.files') || [];
                        if (files[0]['type'] === 'image/jpeg' || files[0]['type'] === 'image/jpg' ||
                            files[0]['type'] === 'image/png') {
                            // ----------------------------------------------------------
                            io.each(files, function(file) {
                                container.push({
                                    file: file,
                                    url: URL.createObjectURL(file)
                                });
                            });
                            // ----------------------------------------------------------
                            this.$emit('input', container); // Emit updates to the parent component
                            //console.log( this.$store.state.photos );
                            // ----------------------------------------------------------
                        } else {
                            this.$parent.alertFileType;
                            // --------------------------------------------------
                            //alert("asd");
                            // --------------------------------------------------
                        }
                    },
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // Remove selected files
                    // --------------------------------------------------------------
                    removeSelected: function(index) {
                        var arr = {};
                        arr.index = index;
                        arr.name = this.name;
                        this.$emit('delete_images', arr);
                    },
                    // --------------------------------------------------------------
                    // --------------------------------------------------------------
                    // Remove append files
                    // --------------------------------------------------------------
                    removeAppend: function(index) {
                        var vm = this;                        
                        if (vm.name == 'photos') {
                            var arr = vm.container;                            
                            const filteredItems = arr.slice(0, index).concat(arr.slice(index + 1, arr.length));
                            vm.container = filteredItems;
                            var data = vm.$store.state.photos;
                            const filteredPhotos = data.slice(0, index).concat(data.slice(index + 1, data.length));
                            vm.$store.state.photos = filteredPhotos;
                        } else {
                            var arr = vm.container;
                            const filteredItems = arr.slice(0, index).concat(arr.slice(index + 1, arr.length));
                            vm.container = filteredItems;
                            var data = vm.$store.state.flyers;
                            const filteredFlyers = data.slice(0, index).concat(data.slice(index + 1, data.length));
                            vm.$store.state.flyers = filteredFlyers;                            
                        }
                    }
                    // --------------------------------------------------------------
                }
                // ------------------------------------------------------------------
            });
        }(jQuery, _, document, window));
    @endminify

</script>
