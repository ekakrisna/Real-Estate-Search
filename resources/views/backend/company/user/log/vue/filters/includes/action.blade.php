<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.action')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.action" @change="applyFilter">
            <option value=""></option>
            <option v-for="actionType in $store.state.preset.actionType" :value="actionType.label">@{{ actionType.label }}</option>
        </select>

    </div>
</div>