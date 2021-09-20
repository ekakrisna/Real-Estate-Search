<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.status')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.propertyStatus" @change="applyFilter">
            <option value=""></option>
            <option v-for="property_status in $store.state.preset.property_statuses" :value="property_status.id">@{{ property_status.label }}</option>
        </select>

    </div>
</div>