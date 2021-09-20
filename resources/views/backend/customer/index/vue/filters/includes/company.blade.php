<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.in_charge_company')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model="currentCompany" @change="applyCompanyFilter
        ">
            <option value=""></option>
            <option v-for="company in $store.state.preset.companies" :value="company">@{{ company.company_name }}</option>
        </select>

    </div>
</div>