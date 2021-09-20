<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.status')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model="filter.status" @change="applyFilter">
            <option value="">@lang('label.all')</option>
            <option value="1">@lang('label.true')</option>
            <option value="2">@lang('label.false')</option>
        </select>

    </div>
</div>