@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('label.dashboard')</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.company.list')}}">@lang('label.list_of_corp')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
@endsection

@section('content')
    @component('backend._components.form_container', ["action" => $form_action, "page_type" => $page_type, "files" => false])

        @component('backend._components.input_text', ['name' => 'company_name', 'label' => __('label.company_name'), 'required' => 1, 'value' => $item->company_name]) @endcomponent
        @component('backend._components.input_select', ['name' => 'company_roles_id', 'options' => $item->company_role_options, 'label' => __('label.type'), 'required' => 1, 'value' => $item->company_roles_id]) @endcomponent
        @component('backend._components.input_text', ['name' => 'address', 'label' => __('label.address'), 'required' => 1, 'value' => $item->address]) @endcomponent
        @component('backend._components.input_text', ['name' => 'phone', 'label' => __('label.phone'), 'required' => 1, 'value' => $item->phone]) @endcomponent

        @if ($page_type == "edit")
            @if( $item->is_active == 1)
                @component('backend._components.input_radio', ['name' => 'is_active', 'options' => ['稼働', '停止'], 'label' => __('label.status'), 'required' => 0, 'is_bool_value_reverse' => 1, 'value' => 1]) @endcomponent
            @else
                @component('backend._components.input_radio', ['name' => 'is_active', 'options' => ['稼働', '停止'], 'label' => __('label.status'), 'required' => 0, 'is_bool_value_reverse' => 1, 'value' => 0]) @endcomponent
            @endif
        @endif

        @component('backend._components.input_buttons', ['page_type' => $page_type, 'create' => __('label.btn_create_company'), 'update' => __('label.btn_update_company')])@endcomponent
    @endcomponent
@endsection
