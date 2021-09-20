@extends('backend._base.content_form')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"> Top </a></li>
        @if ($page_type == "create")
            <li class="breadcrumb-item active">{{ $page_title }}</li>
        @else
            <li class="breadcrumb-item"><a href="{{route('manage.customer.detail', $item->id)}}">{{ $page_title2 }}</a></li>
            <li class="breadcrumb-item active">{{ $page_title }}</li>
        @endif
    </ol>
@endsection

@section('top_buttons')
@endsection

@section('content')
    @component('backend._components.form_container', ["action" => $form_action, "page_type" => $page_type, "files" => false, "id" => "formuser"])

        @if ($page_type == "create")
            @component('backend._components.input_text', ['name' => 'name', 'label' => __('label.name'), 'required' => 1, 'value' => $item->name]) @endcomponent
        @else
            @component('backend._components.input_text_with_flag', ['id' => $item->id, 'name' => 'name', 'label' => __('label.name'), 'required' => 1, 'value' => $item->name, 'name2' => 'flag', 'label2' => __('label.flag'), 'required2' => 0, 'value2' => $item->flag]) @endcomponent
        @endif


        @component('backend._components.input_email', ['name' => 'email', 'label' => __('label.email'), 'required' => 1, 'value' => $item->email]) @endcomponent

        @component('backend._components.input_text', ['name' => 'phone', 'label' => __('label.phone'), 'required' => 0, 'value' => $item->phone]) @endcomponent

        @component('backend._components.input_select_multiple', ['page_type' => $page_type, 'foreach_edit' => $item->customer_desired_area, 'name' => 'cities_id[]', 'name2' => 'towns_id[]', 'options' => $item->city_options, 'options2' => $item->town_options, 'label' => __('label.desired_area'), 'required' => 1, 'required2' => 1, 'value' => '', 'value2' => '', 'uniq_class' => 'get_towns', 'uniq_class2' => 'town_list']) @endcomponent
        
        @component('backend._components.input_select_2', ['name' => 'minimum_price', 'name2' => 'maximum_price', 'options' => $item->amount_options, 'options_type' => 'price', 'options2' => $item->amount_options, 'options2_type' => 'price', 'label' => __('label.min_consideration'), 'required' => 1, 'required2' => 1, 'uniq_class' => 'min_price', 'uniq_class2' => 'max_price', 'value' => $item->minimum_price, 'value2' => $item->maximum_price]) @endcomponent

        @component('backend._components.input_select_2', ['name' => 'minimum_price_land_area', 'name2' => 'maximum_price_land_area', 'options' => $item->amount_options, 'options_type' => 'price', 'options2' => $item->amount_options, 'option2_type' => 'price', 'label' => __('label.min_land'), 'required' => 0, 'required2' => 0, 'uniq_class' => 'min_price', 'uniq_class2' => 'max_price', 'value' => $item->minimum_price_land_area, 'value2' => $item->maximum_price_land_area]) @endcomponent

        @component('backend._components.input_select_2', ['name' => 'minimum_land_area', 'name2' => 'maximum_land_area', 'options' => $item->land_options, 'options_type' => 'land', 'options2' => $item->land_options, 'options2_type' => 'land', 'label' => __('label.min_desired_land'), 'required' => 0, 'required2' => 0, 'uniq_class' => 'min_price', 'uniq_class2' => 'max_price', 'value' => $item->minimum_land_area, 'value2' => $item->maximum_land_area]) @endcomponent

        @if ($page_type == "create")
            @component('backend._components.input_password', ['name' => 'password', 'label' => __('label.password'), 'required' => 1, 'value' => $item->password]) @endcomponent
        @else
            @component('backend._components.input_password_edit', ['name' => 'password', 'label' => __('label.password'), 'required' => 1, 'value' => $item->password]) @endcomponent
        @endif


        @component('backend._components.input_select', ['name' => 'company_users_id', 'options' => $item->companyuser_options, 'label' => __('label.person_charge'), 'required' => 1, 'value' => '']) @endcomponent

        
        @if ($page_type == "edit")
            @if( $item->license == 1)
                @component('backend._components.input_radio', ['name' => 'license', 'options' => ['有効', '停止'], 'label' => __('label.license'), 'required' => 0, 'is_bool_value' => 1, 'value' => 1]) @endcomponent
            @else
                @component('backend._components.input_radio', ['name' => 'license', 'options' => ['有効', '停止'], 'label' => __('label.license'), 'required' => 0, 'is_bool_value' => 1, 'value' => 0]) @endcomponent
            @endif
        @endif

        @component('backend._components.input_buttons', ['page_type' => $page_type, 'create' => __('label.btn_create_user'), 'update' => __('label.btn_update_user')])@endcomponent
    @endcomponent
@endsection


@push('scripts')
<script>
    //cities on change
    $(document).on('change', '.get_towns', function () {
        var $id_city= $(this).val();
        var $t = $(this);
        $.ajax({
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            url: '{{url("admin/user/get_town")}}',
            method: 'POST',
            data: {id_city:$id_city},
            success: function(data) {
                let opt = $t.parent().next().find('.town_list');
                opt.html("");
                $.each(data, function(key, value) {
                    opt.append("<option value='"+ key +"'>" + value + "</option>");
                });
            }
        });
    });

    $(document).on('change', '.min_price', function () {
        var $minprice= $("option:selected", this).text();
        let opt = $(this).parent().next().find('.max_price option:selected');
        var $maxprice= opt.text();

        if($maxprice<$minprice){
            $(this).parent().parent().after("<div class='validation' style='color:red;'>@lang('label.min_max_amount_notif')</div>");
        }else{
            $(this).parent().parent().parent().find('.validation').html("")
        }
    });

    $(document).on('change', '.max_price', function () {
        var $maxprice= $("option:selected", this).text();
        let opt = $(this).parent().prev().find('.min_price option:selected');
        var $minprice= opt.text();

        if($maxprice<$minprice){
            $(this).parent().parent().after("<div class='validation' style='color:red;'>@lang('label.min_max_amount_notif')</div>");
        }else{
            $(this).parent().parent().parent().find('.validation').html("")
        }
    });

    $(".get_towns").change();


</script>
@endpush