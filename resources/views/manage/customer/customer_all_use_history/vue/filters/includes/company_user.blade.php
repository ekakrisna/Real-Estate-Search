<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.in_charge_staff')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.company_user" @change="applyFilter">
            <option value=""></option>
            <option v-for="company_user in $store.state.preset.company_users" :value="company_user.id">@{{ company_user.name }}</option>
        </select>

    </div>
</div>