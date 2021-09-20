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
            <div class="row mx-n2 mt-2 mt-md-0">
                <div class="px-2 col-lg-9">
                    
                    <!-- Uploaded property images - Start -->
                    <div v-if="propertyfiles.length || container.length" class="row mx-n2 mt-n3">
                        <div v-for="file in propertyfiles" class="px-2 col-sm-6 col-xl-4 mt-3">
                            
                            <ratiobox ratio="4-3" :class="[ new_file_selected ? 'cursore-none' : 'cursor-pointer' ]" @click="togglePropertyFileSelected(file)">
                                <div v-if="file.selected" class="ratiobox-control">
                                    <div class="ratiobox-checked d-flex align-items-center justify-content-center">
                                        <i class="fs-12 fas fa-check text-info"></i>
                                    </div>
                                </div>
                                <img :src="file.file.url.image" class="img-thumbnail" :class="{ 'border border-info': file.selected }" alt="">
                            </ratiobox>
                            
                        </div>

                        <div v-for="file in container" class="px-2 col-sm-6 col-xl-4 mt-3">
                            
                            <ratiobox ratio="4-3" :class="[ old_file_selected ? 'cursore-none' : 'cursor-pointer' ]" @click="toggleSelected(file)">
                                <div v-if="file.selected" class="ratiobox-control">
                                    <div class="ratiobox-checked d-flex align-items-center justify-content-center">
                                        <i class="fs-12 fas fa-check text-info"></i>
                                    </div>
                                </div>
                                <img :src="file.url" class="img-thumbnail" :class="{ 'border border-info': file.selected }" alt="">
                            </ratiobox>

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
                        <div class="px-1 col-6 col-md-140px">

                            <!-- Remove button - Start -->
                            <button type="button" class="btn btn-block btn-default" :class="buttonSize" 
                                :disabled="removeButtonDisable" @click="removeSelected">

                                <div class="row mx-n1 justify-content-center">
                                    <div class="px-1 col-auto">
                                        <i class="fal fa-trash"></i>
                                    </div>
                                    <div class="px-1 col-auto fs-14 d-flex align-items-center">
                                        <span>@lang('property.create.button.upload.remove')</span>
                                        <template v-if=" selectedPropertyFiles.length || selectedFiles.length">
                                            (<span>@{{ removeItemCount }}</span>)
                                        </template>
                                    </div>
                                </div>
                            </button>
                            <!-- Remove button - End -->

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</script>

<script> @minify
    (function( $, io, document, window, undefined ){
        Vue.component( 'InputUpload', {
            // ------------------------------------------------------------------
            template: '#generic-input-upload-tpl',
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Aavailable properties
            // ------------------------------------------------------------------
            props: {
                value: { required: true },
                id: { type: String, default: 'generic-upload' },
                name: { type: String, default: null },
                required: { type: Boolean, default: false },
                label: { type: String, default: 'Label' },
                size: { type: String, default: 'md' },
                // ------------------------------------------------------------------
                propertyfiles: { default: 0 },  //uploaded prorperty photo from db
                // ------------------------------------------------------------------
            },
            // ------------------------------------------------------------------


            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return { 
                    container: [], // File container
                    // ------------------------------------------------------------------
                    new_file_selected: false,   //handling select new file only
                    // ------------------------------------------------------------------
                    old_file_selected: false,   //handling select uploaded file only
                    // ------------------------------------------------------------------
                    removeButtonDisable: true,
                }
            },
            // ------------------------------------------------------------------


            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Filter only selected property files
                // --------------------------------------------------------------
                selectedPropertyFiles: function(){
                    return io.filter( this.propertyfiles, function( file ){
                        return file.selected
                    }) || [];
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Filter only selected files
                // --------------------------------------------------------------
                selectedFiles: function(){
                    return io.filter( this.container, function( file ){
                        return file.selected
                    }) || [];
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Button size
                // --------------------------------------------------------------
                buttonSize: function(){
                    var size = io.toLower( io.trim( this.size ));
                    if( !size || 'md' == size ) return null;
                    return 'btn-' +size;
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Remove item count
                // --------------------------------------------------------------
                removeItemCount: function(){
                    if( this.old_file_selected ) return this.selectedPropertyFiles.length;
                    if( this.new_file_selected ) return this.selectedFiles.length;
                }
                // --------------------------------------------------------------

            },
            // ------------------------------------------------------------------


            // ------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------
            methods: {
                // --------------------------------------------------------------
                // Append new files
                // --------------------------------------------------------------
                appendFiles: function(){
                    // ----------------------------------------------------------
                    var container = this.container;
                    var files = io.get( this.$refs, 'upload.files' ) || [];
                    if(files[0]['type']==='image/jpeg' || files[0]['type']==='image/jpg' || files[0]['type']==='image/png'){
                        // ----------------------------------------------------------
                        io.each( files, function( file ){
                            container.push({ 
                                removed: false,
                                selected: false,
                                file: file, url: URL.createObjectURL( file )
                            });
                        });
                        // ----------------------------------------------------------
                        this.$emit( 'input', container ); // Emit updates to the parent component
                        //console.log( this.$store.state.photos );
                        // ----------------------------------------------------------
                    }else{
                        this.$parent.alertFileType;
                        // --------------------------------------------------
                        //alert("asd");
                        // --------------------------------------------------
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Toggle selected state
                // --------------------------------------------------------------
                toggleSelected: function( file ){
                    // --------------------------------------------------------------
                    // function excecuted if user doesn't select any uploaded image file
                    // --------------------------------------------------------------
                    if( !this.old_file_selected ){
                    // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // make user can't select uploaded image file (from create)
                        // --------------------------------------------------------------
                        this.new_file_selected = true;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // make remove button clickable
                        // --------------------------------------------------------------
                        this.removeButtonDisable = false;
                        // --------------------------------------------------------------

                        Vue.set( file, 'selected', !file.selected );
                        Vue.set( file, 'removed', !file.removed );
                    }
                    // --------------------------------------------------------------
                    // if there is no new photo selected, change new file select to false
                    // --------------------------------------------------------------
                    if( !this.selectedFiles.length ){
                    // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // make user can select uploaded image file
                        // --------------------------------------------------------------
                        this.new_file_selected = false;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // make remove button unclickable
                        // --------------------------------------------------------------
                        this.removeButtonDisable = true;
                        // --------------------------------------------------------------
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Toggle selected property state
                // --------------------------------------------------------------
                togglePropertyFileSelected: function(file){
                    // --------------------------------------------------------------
                    // function excecuted if user doesn't select any new image file
                    // --------------------------------------------------------------
                    if( !this.new_file_selected ){
                    // --------------------------------------------------------------    
                        
                        // --------------------------------------------------------------
                        // make user can't select new image file
                        // --------------------------------------------------------------
                        this.old_file_selected = true;
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // make remove button clickable
                        // --------------------------------------------------------------
                        this.removeButtonDisable = false;
                        // --------------------------------------------------------------

                        Vue.set( file, 'selected', !file.selected );
                    }
                    // --------------------------------------------------------------
                    // if there is no uploaded photo selected, change uploaded file select to false
                    // --------------------------------------------------------------
                    if( !this.selectedPropertyFiles.length ){

                        // --------------------------------------------------------------
                        // make user can select new image file
                        // --------------------------------------------------------------
                        this.old_file_selected = false;

                        // --------------------------------------------------------------
                        // make remove button unclickable
                        // --------------------------------------------------------------
                        this.removeButtonDisable = true;
                    }
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Remove selected files
                // --------------------------------------------------------------
                removeSelected: function(){

                    // --------------------------------------------------------------
                    // delete property photo from database
                    // --------------------------------------------------------------
                    if( this.selectedPropertyFiles.length ){
                    // --------------------------------------------------------------

                        var confirmed = confirm( @json( __( 'label.CONFIRM_DELETE_MESSAGE' )));;

                        if( confirmed ){
                            // --------------------------------------------------------------
                            // collect property photo id where photo is selected
                            // --------------------------------------------------------------
                            let id = [];
                            this.propertyfiles.map( function( item ){
                                if( item.selected == true ){
                                    id.push(item.id);   
                                } 
                            });
                            // --------------------------------------------------------------

                            // --------------------------------------------------------------
                            // call change property photos mutation (in parent's)
                            // --------------------------------------------------------------
                            if( this.name == 'photos' ) this.$store.commit( 'setPropertyPhotos', id );
                            if( this.name == 'flyers' ) this.$store.commit( 'setPropertyFlyers', id );
                            // --------------------------------------------------------------
                        }
                    
                    // --------------------------------------------------------------
                    // new photo will not uploaded
                    // --------------------------------------------------------------
                    } else if( this.selectedFiles.length ){
                        var unselected = io.filter( this.container, function( file ){
                            return !file.selected
                        });

                        Vue.set( this, 'container', unselected );
                    }
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // make remove button unclickable
                    // --------------------------------------------------------------
                    this.removeButtonDisable = true;
                    this.new_file_selected = false;
                    this.old_file_selected = false;
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
