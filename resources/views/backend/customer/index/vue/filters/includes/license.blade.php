<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.license')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model="filter.license" @change="applyFilter">
            <option value="">All</option>
            <option value="1">@lang('label.true')</option>
            <option value="2">@lang('label.false')</option>
        </select>

    </div>
</div>