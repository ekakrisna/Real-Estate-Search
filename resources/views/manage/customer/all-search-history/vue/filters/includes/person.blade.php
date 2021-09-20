<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.person_charge')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.person" @change="applyFilter">
            <option value=""></option>
            <option v-for="companies_user in $store.state.preset.companies_user" :value="companies_user.id">@{{ companies_user.name }}</option>
        </select>

    </div>
</div>