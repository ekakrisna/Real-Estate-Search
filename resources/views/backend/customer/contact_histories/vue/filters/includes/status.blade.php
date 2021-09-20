<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.dashboard_status')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model="filter.status" @change="applyFilter">
            <option value=""></option>
            <option value="1">@lang('label.active')</option>
            <option value="2">@lang('label.not_active')</option>        
        </select>

    </div>
</div>