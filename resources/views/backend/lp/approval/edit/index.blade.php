@extends('backend._base.content_vueform')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.lp.approval') }}">@lang('label.lp_list_approval')</a></li>
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

    <!-- Form content - Start -->
    <form class="parsley-minimal" data-parsley>
        <div class="position-relative">
            <!-- Property id - Start -->
            <show-plain-data-form v-model="approvalProperty.id" label="@lang("label.property_id")"></show-plain-data-form>
            <!-- Property id - End -->

            <!-- Property contracted year - Start -->
            <show-plain-data-form v-model="approvalProperty.ja.contracted_years" label="@lang("label.contracted_years")"></show-plain-data-form>
            <!-- Property contracted year - End -->

            <!-- Property location - Start -->
            <input-text v-model="approvalProperty.location" label="@lang("label.location")" :filed-color="locationColor"></input-text>
            <!-- Property location - End -->

            <!-- Property Price - Start -->
            <input-range :min.sync="landprice.minimum_price" :max.sync="landprice.maximum_price" :filed-color="priceColor" 
                :precision="{min:0, max:4}" label="@lang("label.b27_selling_price_approval")" append="万円" 
                :names="[ 'minimum_price', 'maximum_price' ]">
            </input-range>
            <!-- Property Price - End -->

            <!-- Property Land Area - Start -->
            <input-range :min.sync="landprice.minimum_land_area" :max.sync="landprice.maximum_land_area"
                :filed-color="LandAreaColor" :precision="{min:0, max:4}" label="@lang("label.b27_land_area_approval")"
                append="㎡" :names="[ 'minimum_land_area', 'maximum_land_area' ]">
            </input-range>
            <!-- Property Land Area - End -->

            <!-- Property Building Area - Start -->
            <show-land-area-form :min.sync="approvalProperty.building_area" label="@lang( "label.buliding_area_lp" )"  append="坪"></show-land-area-form>
            <!-- Property Building Area - End -->

            <!-- Property id - Start -->
            <show-plain-data-form v-model="approvalProperty.building_age" label="@lang("label.buliding_age_lp")" append="年"></show-plain-data-form>
            <!-- Property id - End -->

            <!-- Property id - Start -->
            <show-plain-data-form v-model="approvalProperty.house_layout" label="@lang("label.house_layout")"></show-plain-data-form>
            <!-- Property id - End -->

            <!-- Property id - Start -->
            <show-plain-data-form v-model="approvalProperty.connecting_road" label="@lang("label.connecting_road")"></show-plain-data-form>
            <!-- Property id - End -->
        </div>
        <div class="pt-3">
            <p class="text-red">@lang('label.need_to_fix_point')</p>
        </div>

        <!-- Form buttons - Start -->
        <div class="row mx-n2 justify-content-center mt-5 mb-5">
            <div class="px-2 col-12 col-md-240px">
                <!-- Submit button - Start -->
                <button type="button" @click="showModal" class="btn btn-block btn-info">
                    <div class="row mx-n1 justify-content-center">
                        <div class="px-1 col-auto">
                            <span>@lang('label.fix_button')</span>
                        </div>
                    </div>
                </button>
                <!-- Submit button - End -->
            </div>
        </div>
        <!-- Form buttons - End -->
    </form>
    <!-- Form content - End -->

    <!-- Modal - Start -->
    <property-modal ref="modalContent" v-model="approvalProperty" :price="landprice"></property-modal>
    <!-- Modal - End -->

@endsection

@push('vue-scripts')

    <!-- General components - Start -->
    @include('backend.vue.components.show-plain-data-form.import')
    @include('backend.vue.components.show-land-area-form.import')
    @include('backend.vue.components.empty-placeholder.import')
    @include('backend.vue.components.input-text.import')
    @include('backend.vue.components.input-range.import')
    <!-- General components - End -->

    <!-- Custome Modal - Start -->
    @relativeInclude('property-modal.import')
    <!-- Custome Modal - End -->

    <script>
        @minify
            (function(io, $, window, document, undefined) {
                // ----------------------------------------------------------------------
                // Vuex store
                // ----------------------------------------------------------------------
                store = {
                    // ------------------------------------------------------------------
                    // Reactive state
                    // ------------------------------------------------------------------
                    state: function() {
                        // --------------------------------------------------------------
                        var state = {
                            status: {
                                mounted: false,
                                loading: false
                            },
                            property: @json($property),
                            priceland: @json($priceland),
                            preset: {
                                // -----------------------------------------------------
                                // Building condition options
                                // -----------------------------------------------------
                                condition: [{
                                        id: 1,
                                        name: 'available',
                                        label: @json($available)
                                    },
                                    {
                                        id: 2,
                                        name: 'none',
                                        label: @json($none)
                                    },
                                ],
                                api: {
                                    store: @json(route('admin.lp.approval.update', $id)),
                                },
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
                        refreshParsley: function() {
                            setTimeout(function() {
                                var $form = $('form[data-parsley]');
                                $form.parsley().refresh();
                            });
                        },
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // Set loading state
                        // --------------------------------------------------------------
                        setLoading: function(state, loading) {
                            if (io.isUndefined(loading)) loading = true;
                            Vue.set(state.status, 'loading', !!loading);
                        },
                        // --------------------------------------------------------------

                        // --------------------------------------------------------------
                        // Set mounted state
                        // --------------------------------------------------------------
                        setMounted: function(state, mounted) {
                            if (io.isUndefined(mounted)) mounted = true;
                            Vue.set(state.status, 'mounted', !!mounted);
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
                    // On mounted hook
                    // ------------------------------------------------------------------
                    mounted: function() {
                        this.$store.commit('setMounted', true);
                        $(document).trigger('vue-loaded', this);
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // Computed properties
                    // ------------------------------------------------------------------
                    computed: {
                        // --------------------------------------------------------------
                        // Loading and mounted status
                        // --------------------------------------------------------------
                        isLoading: function() {
                            return this.$store.state.status.loading;
                        },
                        isMounted: function() {
                            return this.$store.state.status.mounted;
                        },
                        // --------------------------------------------------------------
                        approvalProperty: function() {
                            return this.$store.state.property;
                        },
                        // --------------------------------------------------------------
                        landprice: function() {
                            return this.$store.state.priceland;
                        },
                        // --------------------------------------------------------------
                        locationColor: function() {
                            return this.$store.state.property.lp_property_convert_status_id == 100 ?
                                'text-red' : '';
                        },
                        priceColor: function() {
                            return this.$store.state.property.lp_property_convert_status_id == 200 ?
                                'text-red' : '';
                        },
                        LandAreaColor: function() {
                            return this.$store.state.property.lp_property_convert_status_id == 300 ?
                                'text-red' : '';
                        }
                    },

                    methods: {
                        showModal() {
                            var vm = this;                            
                            var element = vm.$refs.modalContent.$el;                            
                            $(element).modal('show');
                        },
                    },

                    // ------------------------------------------------------------------
                    // Wacthers
                    // ------------------------------------------------------------------
                    watch: {}
                    // ------------------------------------------------------------------

                };
            }(_, jQuery, window, document))
        @endminify
    </script>
@endpush
