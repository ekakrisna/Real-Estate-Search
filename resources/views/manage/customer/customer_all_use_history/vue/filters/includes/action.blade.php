<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.action_name')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.action" @change="applyFilter">
            <option value=""></option>
            <option v-for="action in $store.state.preset.actions" :value="action.id">@{{ action.label }}</option>
        </select>

    </div>
</div>