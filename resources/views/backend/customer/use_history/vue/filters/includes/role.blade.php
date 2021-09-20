<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">User Role</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.role" @change="applyFilter">
            <option value=""></option>
            <option v-for="role in $store.state.preset.roles" :value="role.id">@{{ role.label }}</option>
        </select>

    </div>
</div>