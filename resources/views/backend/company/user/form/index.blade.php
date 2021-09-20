@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.company.list')}}">@lang('label.list_of_corp')</a></li>
        <li class="breadcrumb-item"><a href="{{ $company->url->user }}">{{ $company->company_name }} @lang('label.user_management_of')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
@endsection

@section('content')
    @component('backend._components.form_container', ["action" => $form_action, "page_type" => $page_type, "files" => false])

        @component('backend._components.input_text', ['name' => 'name', 'label' => __('label.user_name_company'), 'required' => 1, 'value' => $item->name]) @endcomponent

        <!-- Email input - Start -->
        @relativeInclude('includes.email')
        <!-- Email input - End -->
        
        @component('backend._components.input_select', ['name' => 'company_user_roles_id', 'options' => $item->company_user_role_options, 'label' => __('label.permission'), 'required' => 1, 'value' => $item->company_user_roles_id]) @endcomponent

        @if ($page_type == "create")
            @component('backend._components.input_password') @endcomponent
        @else
            @component('backend._components.input_password_edit') @endcomponent
        @endif

        @if ($page_type == "edit")
            @if( $item->is_active == 1)
                @component('backend._components.input_radio', ['name' => 'is_active', 'options' => ['稼働', '停止'], 'label' => __('label.license'), 'required' => 0, 'is_bool_value_reverse' => 1, 'value' => 1]) @endcomponent
            @else
                @component('backend._components.input_radio', ['name' => 'is_active', 'options' => ['稼働', '停止'], 'label' => __('label.license'), 'required' => 0, 'is_bool_value_reverse' => 1, 'value' => 0]) @endcomponent
            @endif
        @endif

        @component('backend._components.input_buttons', ['page_type' => $page_type, 'create' => __('label.save'), 'update' => __('label.save')])@endcomponent
    @endcomponent
@endsection
