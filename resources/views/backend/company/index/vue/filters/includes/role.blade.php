<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.type')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.role" @change="applyFilter">
            <option value=""></option>
            <option v-for="company in $store.state.preset.companyRole" :value="company.id">@{{ company.label }}</option>
        </select>

    </div>
</div>