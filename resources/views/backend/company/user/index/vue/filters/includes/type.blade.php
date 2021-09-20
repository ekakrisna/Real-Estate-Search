<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.permission')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.type" @change="applyFilter">
            <option value=""></option>
            <option v-for="roles in $store.state.preset.roles" :value="roles.id">@{{ roles.label }}</option>
        </select>

    </div>
</div>