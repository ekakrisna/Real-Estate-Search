@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> breadcrumb-test</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

{{-- Company user create button : SUPER_ADMIN or own company can edit company-users --}}
@section('top_buttons')
    <a href="" class="btn btn-info">TEST</a>
@endsection

@section('content_before_table')
<style>
    .form-group { padding: 10px 0; background-color: #cceeee; }
    div.sample-guidance-whole{font-size:120%;}
    div.sample-guidance{padding:10px; margin: 10px 0; background-color:#fff2; border:#8a3 solid 2px;}
    div.sample-item{padding-left: 50px ; background-color:#fff2; border:#888 solid 1px;}
</style>
<div class="col-12 sample-guidance-whole">
    <div class="sample-guidance">
        <h2>Icon Samples</h2>
        <label>You can search and use icons by fontausome >> <a href="https://fontawesome.com/icons/">https://fontawesome.com/icons/</a> </label>
        <div>
            <p>SAMPLE: &lt;i class="fa fa-spinner fa-spin"&gt;&lt;/i&gt;</p>
            <p>The follows are class names using generally.</p>
        </div>
        <div class="row">
            <div class="col-4 sample-item"> fa fa-spinner fa-spin <i class="fa fa-spinner fa-spin"></i> </div>
            <div class="col-4 sample-item"> fas fa-user <i class="fas fa-user"></i> </div>
            <div class="col-4 sample-item"> fas fa-users <i class="fas fa-users"></i> </div>
            <div class="col-4 sample-item"> fas fa-building <i class="fas fa-building"></i> </div>
            <div class="col-4 sample-item"> fas fa-user-alt <i class="fas fa-user-alt"></i> </div>
            <div class="col-4 sample-item"> fas fa-newspaper <i class="fas fa-newspaper"></i> </div>
            <div class="col-4 sample-item"> fa fa-language <i class="fa fa-language"></i> </div>
            <div class="col-4 sample-item">  <i class=""></i> </div>
            <div class="col-4 sample-item">  <i class=""></i> </div>
        </div>
    </div>
    <div class="col-8">
        <h2>Form samples</h2>
        <div>
            <p>You can use some components in resources/views/backend/_components </p>
            <p>Please check while see resources/views/backend/sample/index.blade.php</p>
        </div>
        @component('backend._components.form_container', ["action" => $form_action, "page_type" => $page_type, "files" => false])
            @component('backend._components.input_text', ['name' => 'display_name', 'label' => 'input_text', 'required' => 1, 'value' => $item->admin->display_name]) @endcomponent
            @component('backend._components.input_password', ['caption' => 'input_password'] ) @endcomponent
            @component('backend._components.input_password_edit', ['caption' => 'input_password_edit'] ) @endcomponent
            @component('backend._components.input_postcode', ['name' => 'post_code', 'label' => __('label.post_code'), 'required' => 1, 'value' => $item->post_code]) @endcomponent
            @component('backend._components.input_radio', ['name' => 'status', 'options' => ['pending', 'active', 'block'], 'label' => 'input_radio', 'required' => 1, 'value' => $item->status]) @endcomponent
            @component('backend._components.input_buttons', ['page_type' => $page_type])@endcomponent
        @endcomponent

        <label> Abe adjust the follows to component. </label>
        <div id="form-group--delete_unnecessary_items" class="row form-group align-items-start">
            <style>
                label.clause-name{
                    margin:8px 8px;
                    font-weight: bold;
                    font-size: 120%;
                }
                label.clause-item-name{
                    margin:8px 12px;
                    min-width: 180px;
                }
                label.clause-item-content{
                    display:block;
                    margin:8px 24px;
                }
                label.debug-text{
                    position: absolute;
                    top: 16px;
                    right: 10%;
                    color:blueviolet;
                }

            </style>
            <div class="row">
                @for( $i = 0 ; $i < 2; $i++ )
                    <div id="checkbox-parent-{{$i}}" class="col-12 list_checkbox pb-4">
                        <div class="row">
                            <div class="col-12 parent-checkbox">
                                <div class="icheck-cyan">
                                    <input
                                        type="checkbox"
                                        value="1"
                                        id="parent-{{$i}}"
                                        name="parent-{{$i}}" />
                                    <label
                                        for="parent-{{$i}}"
                                        class="clause-name"
                                    >Parent {{$i}} : linked with children (please check js code)</label>
                                    @if( env('APP_DEBUG') )
                                        <label class="debug-text">debug display</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @for( $j = 0 ; $j < 3; $j++ )
                                <div class="col-12 child-checkbox pl-3 pl-lg-5">
                                    <div class="icheck-cyan">
                                        <input type="checkbox"
                                            value="{{$j}}"
                                            id="child-{{$j}}"
                                            name="child-{{$j}}"
                                        />
                                        <label
                                            for="child-{{$j}}"
                                            class="clause-item-name"
                                        >Child {{$j}}</label>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <div class="sample-guidance">
        <h2>About section</h2>
        <pre>
        The section between @@section and @@endsection is going to assign to @@yield('') in parent-layout.
        ※ Parent-layout specify by @@extends. this keywork must define 1 place in 1 blade file.
        ※ If there is no @@section, yield is going to ignore.
        ※ Here is 'content_before_table'.

        Parent-layout define the Layout briefly and child content define contents of some @@yield as @@section.

        The follows are data-table implementation as sample based "log-activity".
        </pre>
        <p>Please check while see the follows files.<br>
            - resources/views/backend/sample/index.blade.php<br>
            - resources/views/backend/_base/content_datatables.blade.php</p>
    </div>
</div>
@endsection

@section('content')
    <th data-col="id">ID</th>
    <th data-col="admin.display_name">@lang('label.name')</th>
    <th data-col="admin.email">@lang('label.email')</th>
    <th data-col="activity">@lang('label.activity')</th>
    <th data-col="detail">@lang('label.detail')</th>
    <th data-col="ip">@lang('label.ip')</th>
    {{-- example custom filter --}}
    <th data-col="formatted_access_time" data-name="formatted_access_time" data-datepicker="true">@lang('label.access_time')</th>
@endsection

@push('scripts')
    <script>
        // -------------------------------------------------
        // text line-through for parent checkbox
        // -------------------------------------------------
        $('.list_checkbox').each(function(){
            var $this = $(this);
            var $parent_checkbox = $this.find('.parent-checkbox input[type=checkbox]');
            var $parent_label    = $this.find('.parent-checkbox label');
            var $child_checkbox  = $this.find('.child-checkbox input[type=checkbox]');
            var $child_label     = $this.find('.child-checkbox label');

            $parent_checkbox.on('click', function() {
                if ($parent_checkbox.is(':checked')) {
                    $parent_label.css('text-decoration','line-through');
                    $child_checkbox.prop('checked',true); // Overwrite by safety of icheck.
                    $child_label.css('text-decoration','line-through');
                } else {
                    $parent_label.css('text-decoration','');
                    $child_checkbox.prop('checked',false); // Overwrite by safety of icheck.
                    $child_label.css('text-decoration','');
                }
            });
        });
        // -------------------------------------------------

        // -------------------------------------------------
        // text line-through for child checkbox
        // -------------------------------------------------
        $('.list_checkbox .child-checkbox').each(function(){
            var $this = $(this);
            var $checbox = $this.find('input[type=checkbox]');
            var $label = $this.find('label');

            $checbox.on('click', function(){
                if($checbox.is(':checked')) {
                    $label.css('text-decoration','line-through');
                } else{
                    $label.css('text-decoration','');
                }
            });
        });
        // -------------------------------------------------

    </script>
@endpush
