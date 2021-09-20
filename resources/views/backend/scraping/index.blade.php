@extends('backend._base.content_tablelike')

@push('css')
    <style>
        .my-custom-scrollbar {
            position: relative;
            height: 300px;
            overflow: auto;
        }

        .table-wrapper-scroll-y {
            display: block;
        }

    </style>
@endpush

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('label.scraping_upload')</li>
    </ol>
@endsection

@section('content')

    <!-- Content mask loader - Start -->
    <mask-loader :loading="is_Loading"></mask-loader>
    <!-- Content mask loader - End -->

    <form @submit="formSubmit" ref="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-2 d-flex align-items-center">
                <strong>@lang('label.scraping_file')</strong>
            </div>
            <div class="col-10">
                <input class="d-none" type="file" name="uploaded_file" ref="fileInput" accept=".csv"
                    v-on:change="onFilePicked">
                <button type="button" class="btn btn-primary" @click="onPickFile">@lang('label.file_upload')</button>
            </div>
        </div>

        <div class="row">
            <div class="col-2 d-flex align-items-center mt-2">
                <strong>@lang('label.file_name')</strong>
            </div>
            <div class="col-10 mt-2">
                 @{{name}}
            </div>
        </div>

        <div class="row mt-5 mb-5">
            <div class="col text-center">
                <button type="submit" class="btn btn-primary" :disabled="!file">
                    <div class="row mx-n1 justify-content-center">
                        <div class="px-1 col-auto">
                            <span>@lang('label.upload_button')</span>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </form>

    <div class="pb-3">
        <strong>@lang('label.upload_history')</strong>
    </div>
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-bordered table-striped mb-0">
            <tbody v-if="isEmpty">
                <tr v-for="(data, index) in scrapingFile" :key="`data-${index}`">
                    <th>(@{{ data . ja . created_at }}) @{{ data . file_name }}</th>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <th class="text-center">@lang('label.not_find_history')</th>
                </tr>
            </tbody>
        </table>
    </div>
@endsection


@push('vue-scripts')
@include('backend.vue.components.mask-loader.import')
    <script>
        @minify
            (function($, io, document, window, undefined) {
                router = {
                    mode: 'history',
                    routes: [{
                        name: 'index',
                        path: '/admin/scraping-upload',
                        component: {
                            template: '<div/>'
                        }
                    }]
                };
                store = {
                    // ------------------------------------------------------------------
                    // Reactive central data
                    // ------------------------------------------------------------------
                    state: function() {
                        var state = {
                            status: {},
                            config: {},
                            preset: {
                                scraping: @json($scraping),
                                api: {
                                    store: @json(route('admin.scraping.upload'))
                                },
                            },
                            result: null
                        };
                        // console.log( state );
                        return state;
                    },
                    mutations: {}
                    // ------------------------------------------------------------------
                };

                // ----------------------------------------------------------------------
                // Vue mixin
                // ----------------------------------------------------------------------
                mixin = {
                    data: function() {
                        return {
                            name: '',
                            file: '',
                            data: '',
                            is_load : false,
                        }
                    },
                    mounted: function() {},
                    computed: {
                        scrapingFile: function() {
                            return io.get(this.$store.state.preset, 'scraping')
                        },
                        isEmpty: function() {
                            if (this.scrapingFile.length) {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        is_Loading:function(){return this.is_load;}
                    },
                    methods: {
                        onPickFile() {
                            this.$refs.fileInput.click();
                        },
                        onFilePicked(event) {
                            _this = this;
                            const files = event.target.files;
                            let filename = files[0].name;
                            _this.name = filename;
                            let extention = filename.split(".");


                            if ('csv' === extention[extention.length - 1]) {
                                _this.file = files[0];
                            } else {
                                var message = "@lang('label.csv_only')";
                                _this.$toasted.show(message, {
                                    type: 'failed',
                                });
                            }
                        },
                        formSubmit(e) {
                            _this = this;
                            e.preventDefault();
                            let currentObj = this;
                            const config = {
                                headers: {
                                    'content-type': 'multipart/form-data'
                                },
                                timeout:0
                            };
                            if (this.file) {
                                _this.is_load = true;
                                var url = currentObj.$store.state.preset.api.store;
                                let formData = new FormData();
                                formData.append('file', this.file);

                                axios.post(url, formData, config)
                                    .then(function(response) {
                                        if (response.data.status == 'Error:isFileAlreadyExists') {
                                            currentObj.file = '';
                                            var message = "@lang('label.same_name')";
                                            currentObj.$toasted.show(message, {
                                                type: 'failed',
                                            });
                                            _this.$refs.form.reset();
                                        }
                                        else if(response.data.status.startsWith('Error')){
                                            var message = "@lang('label.error_csv')\n"+response.data.status;
                                            currentObj.$toasted.show(message, {
                                                type: 'failed',
                                            });
                                            _this.$refs.form.reset();
                                        }
                                         else {
                                            currentObj.$store.state.preset.scraping = response.data.data;
                                            currentObj.file = '';
                                            var message = "@lang('label.SUCCESS_CREATE_MESSAGE')";
                                            currentObj.$toasted.show(message, {
                                                type: 'success',
                                            });
                                            _this.$refs.form.reset();
                                        }
                                    })
                                    .catch(function(error) {
                                        currentObj.status = error;
                                        console.error(error);
                                        var message = "@lang('label.error_csv')\n"+error.response.data.status;
                                        currentObj.$toasted.show(message, {
                                            type: 'failed',
                                        });
                                        _this.is_load = false;
                                        _this.$refs.form.reset();
                                    }).finally(() => {
                                        _this.name = '';
                                        _this.file = '';
                                        _this.is_load = false;
                                        _this.$refs.form.reset();
                                    })
                            } else {
                                var message = "@lang('label.error_csv')";
                                currentObj.$toasted.show(message, {
                                    type: 'failed',
                                });
                            }
                        }
                    },
                    watch: {}
                    // ------------------------------------------------------------------
                };
            }(jQuery, _, document, window));
        @endminify

    </script>
@endpush
