@php
    if(Auth::guard('web')->check()){
        $username   = Auth::guard('web')->user()->name;
        $role       = Auth::guard('web')->user()->company->company_roles->name;
    } else if(Auth::guard('user')->check()) {
        $username   = Auth::user()->name;
        $role       = Auth::user()->company->company_roles->name;
    }
@endphp
<aside class="main-sidebar elevation-1 sidebar-light-info">
    <!-- Brand Logo -->
    @if($role == 'ADMIN')
    <a href="{{route('admin.index')}}" class="brand-link navbar-info text-center">
    @else
    <a href="{{route('manage.index')}}" class="brand-link navbar-info text-center">
    @endif
        <span class="brand-text">{{ config('app.name')  }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('img/backend/adminlte/avatar.png')}}" class="img-circle">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $username }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul id="nav-left" class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">MAIN NAVIGATION</li>




                <!--<li class="nav-item has-treeview" id="tree_admins">
                    <a href="#" class="nav-link">
                        <i class="fas fa-question"></i>
                        <p> @lang('label.implementation_sample') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="list_admin" class="nav-item">
                            <a href="{{route('sample.index')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>The basics</p>
                            </a>
                        </li>
                    </ul>
                </li>-->

                @if($role == 'ADMIN')
                <li class="nav-item">
                    <a href="{{route('admin.index')}}" class="nav-link">
                        <i class="fas fa-list-alt nav-icon"></i>
                        <p>@lang('label.dashboard')</p>
                    </a>
                </li>

                <!--START B2 Customers - List & Create New-->
                <li class="nav-item has-treeview" id="tree_user">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p> @lang('label.customers') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="list_user" class="nav-item">
                            <a href="{{route('admin.customer.list')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                        <li id="create_user" class="nav-item">
                            <a href="{{route('admin.customer.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.customer_all_use_history')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.dashboard_view_usage_history')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.customer_all_search_history')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.dashboard_view_search_history')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.customer_all_contact')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.dashboard_view_inquiry_history')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- END B2 Customers - List & Create New-->

                <!--START B15 Company - List & Create New-->
                <li class="nav-item has-treeview" id="tree_companies">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p> @lang('label.company') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="create_company" class="nav-item">
                            <a href="{{route('admin.company.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li id="list_company" class="nav-item">
                            <a href="{{route('admin.company.list')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- END B15 Company - List & Create New-->

                <!--START B15 Company - List & Create New-->
                <li class="nav-item has-treeview" id="tree_properties">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p> @lang('label.property') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="create_company" class="nav-item">
                            <a href="{{route('admin.property.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li id="list_company" class="nav-item">
                            <a href="{{route('admin.property')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                        <li id="list_company" class="nav-item">
                            <a href="{{route('admin.approval')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.dashboard_view_approval')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- END B15 Company - List & Create New-->

                <li class="nav-item">
                    <a href="{{route('admin.company.user_log')}}" class="nav-link">
                        <i class="fas fa-list-alt nav-icon"></i>
                        <p>@lang('label.usage_history_home_maker')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin.scraping')}}" class="nav-link">
                        <i class="fas fa-list-alt nav-icon"></i>
                        <p>@lang('label.scraping_upload')</p>
                    </a>
                </li>

                <!--START - menu on sider bar-->
                <li class="nav-item has-treeview" id="tree_user">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-poll"></i>
                        <p> @lang('label.sales_service') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="list_approval" class="nav-item">
                            <a href="{{ route('admin.lp.approval') }}" class="nav-link">
                                <i class="fas fa-clipboard-check nav-icon"></i>
                                <p>@lang('label.lp_public_approval')</p>
                            </a>
                        </li>
                        <li id="list_property" class="nav-item">
                            <a href="{{route('admin.lp.property')}}" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <p>@lang('label.lp_property')</p>
                            </a>
                        </li>
                        <li id="list_upload" class="nav-item">
                            <a href="{{ route('admin.lp.scraping') }}" class="nav-link">
                                <i class="far fa-cloud-upload-alt nav-icon"></i>
                                <p>@lang('label.lp_data_upload')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--END - menu on sider bar-->
                @endif

                @if($role == 'HOME_MAKER')
                <!--START A1 Dashboard - List -->
                <li class="nav-item">
                    <a href="{{route('manage.index')}}" class="nav-link">
                        <i class="fas fa-list-alt nav-icon"></i>
                        <p>@lang('label.dashboard')</p>
                    </a>
                </li>
                <!--END A1 Dashboard - List -->
                <!--START A2 & A3 Customers - List & Create New-->
                <li class="nav-item has-treeview" id="tree_user">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p> @lang('label.customers') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="list_user" class="nav-item">
                            <a href="{{route('manage.customer.index')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                        <li id="create_user" class="nav-item">
                            <a href="{{route('manage.customer.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li id="list_user" class="nav-item">
                            <a href="{{route('manage.customer.customer_all_use_history')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.dashboard_view_usage_history')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('manage.customer_all_search_history')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.dashboard_view_search_history')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview" id="tree_properties">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p> @lang('label.property') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="list_company" class="nav-item">
                            <a href="{{route('manage.property.search')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- END A2 & A3 Customers - List & Create New-->

                @endif


                <!-- OLD FILE -->
                 <!--
                <li class="nav-item has-treeview" id="tree_admins">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p> @lang('label.admin') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="create_admin" class="nav-item">
                            <a href="{{route('admin.admins.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li id="list_admin" class="nav-item">
                            <a href="{{route('admin.admins.index')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview" id="tree_companies">
                    <a href="{{route('admin.company.list')}}" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p> @lang('label.company')<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="create_company" class="nav-item">
                            <a href="{{route('admin.company.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li id="list_company" class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview" id="tree_news">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p> @lang('label.news') <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li id="create_news" class="nav-item">
                            <a href="{{route('admin.news.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.createNew')</p>
                            </a>
                        </li>
                        <li id="list_admin" class="nav-item">
                            <a href="{{route('admin.news.index')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('label.list')</p>
                            </a>
                        </li>
                    </ul>
                </li>-->

                @if($role == 'company_admin')
                <!-- OLD FILE -->
                 <!--
                    <li class="nav-item">
                        <a href="{{route('admin.company.edit', Auth::user()->company->id)}}" class="nav-link">
                            <i class="fas fa-building nav-icon"></i>
                            <p>@lang('label.company')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.company.user.index', Auth::user()->company->id)}}" class="nav-link">
                            <i class="fas fa-users nav-icon"></i>
                            <p>@lang('label.user')</p>
                        </a>
                    </li>
                @endif

                @if($role == 'super_admin')
                <li class="nav-item">
                    <a href="{{route('admin.logactivities.index')}}" class="nav-link">
                        <i class="fas fa-list-alt nav-icon"></i>
                        <p>@lang('label.log_activity')</p>
                    </a>
                </li> -->
                @endif

                @if(Auth::guard('user')->check())
                <!-- OLD FILE -->
                 <!--
                    <li class="nav-item">
                        <a href="{{route('userowner-edit')}}" class="nav-link">
                            <i class="fas fa-user nav-icon"></i>
                            <p>@lang('label.user')</p>
                        </a>
                    </li>-->
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
