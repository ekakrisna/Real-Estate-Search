@extends("backend._base.app")

@section("content-wrapper")
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark h1title">{{ $page_title ?? '' }}</h1>
                </div>
                <div class="col-sm-6 text-sm">
                    @hasSection('breadcrumbs') @yield('breadcrumbs') @endif
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        
        <div v-if="!mounted" class="preloader preloader-fullscreen d-flex justify-content-center align-items-center">
            <div class="folding-cube">
                <div class="cube cube-1"></div>
                <div class="cube cube-2"></div>
                <div class="cube cube-4"></div>
                <div class="cube cube-3"></div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endpush

@push('scripts')
    <script src="{{ asset( 'plugins/parsley/parsley.min.js' )}}"></script>
    @if( App::isLocale( 'en' ))
      <script src="{{ asset( 'plugins/parsley/i18n/en.js' )}}"></script>
    @elseif( App::isLocale( 'jp' ))
      <script src="{{ asset( 'plugins/parsley/i18n/ja.js' )}}"></script>
    @endif
@endpush


@push('vue-scripts')

    <!-- General table-like components - Start -->
    @include('backend.vue.components.tablelike-pagination.import')
    <!-- General table-like components - End -->

    <!-- Company and in-charge person filters - Start -->
    @include('backend.vue.components.filter-company.import')
    @include('backend.vue.components.filter-person.import')
    <!-- Company and in-charge person filters - End -->

    <!-- Order and perpage filter filters - Start -->
    @include('backend.vue.components.filter-order.import')
    @include('backend.vue.components.filter-perpage.import')
    <!-- Order and perpage filter filters - End -->
    
@endpush