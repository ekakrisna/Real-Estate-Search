<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.license')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.license" @change="applyFilter">
            <option value=""></option>
            <option value="1">稼働</option>
            <option value="0">停止</option>
        </select>

    </div>
</div>