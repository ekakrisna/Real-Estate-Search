<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">Company</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.company" @change="applyFilter">
            <option value=""></option>
            <option v-for="company in $store.state.preset.companies" :value="company.id">@{{ company.company_name }}</option>
        </select>

    </div>
</div>