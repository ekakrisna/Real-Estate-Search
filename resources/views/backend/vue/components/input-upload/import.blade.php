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

                    <div v-if="container.length" class="row mx-n2 mt-n3">
                        <div v-for="file in container" class="px-2 col-sm-6 col-xl-4 mt-3">

                            <ratiobox ratio="4-3" class="cursor-pointer" @click="toggleSelected(file)">
                                <div v-if="file.selected" class="ratiobox-control">
                                    <div class="ratiobox-checked d-flex align-items-center justify-content-center">
                                        <i class="fs-12 fas fa-check text-info"></i>
                                    </div>
                                </div>
                                <img :src="file.url" class="img-thumbnail" :class="{ 'border border-info': file.selected }" alt="">
                            </ratiobox>

                        </div>
                    </div>

                    <div class="row mx-n1 mt-3">
                        <div class="px-1 col-6 col-md-160px">

                            <!-- Append button - Start -->
                            <input type="file" :id="id" style="visibility: hidden; height: 0" class="d-block" ref="upload" multiple @change="appendFiles"/>
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
                                :disabled="!selectedFiles.length" @click="removeSelected">

                                <div class="row mx-n1 justify-content-center">
                                    <div class="px-1 col-auto">
                                        <i class="fal fa-trash"></i>
                                    </div>
                                    <div class="px-1 col-auto fs-14 d-flex align-items-center">
                                        <span>@lang('property.create.button.upload.remove')</span>
                                        <template v-if="selectedFiles.length">
                                            (<span>@{{ selectedFiles.length }}</span>)
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
                size: { type: String, default: 'md' }
            },
            // ------------------------------------------------------------------


            // ------------------------------------------------------------------
            // Reactive data
            // ------------------------------------------------------------------
            data: function(){
                return { 
                    container: [] // File container
                }
            },
            // ------------------------------------------------------------------


            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {
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
                    // ----------------------------------------------------------
                    io.each( files, function( file ){
                        container.push({ 
                            selected: false,
                            file: file, url: URL.createObjectURL( file )
                        });
                    });
                    // ----------------------------------------------------------
                    this.$emit( 'input', container ); // Emit updates to the parent component
                    // ----------------------------------------------------------
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Toggle selected state
                // --------------------------------------------------------------
                toggleSelected: function( file ){
                    Vue.set( file, 'selected', !file.selected );
                },
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Remove selected files
                // --------------------------------------------------------------
                removeSelected: function(){
                    var unselected = io.filter( this.container, function( file ){
                        return !file.selected
                    });
                    Vue.set( this, 'container', unselected );
                }
                // --------------------------------------------------------------
            }
            // ------------------------------------------------------------------
        });
    }( jQuery, _, document, window ));
@endminify </script>
