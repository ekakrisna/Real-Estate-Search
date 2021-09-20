<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">Status</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model="filter.status" @change="applyFilter">
            <option value="">All</option>
            <option value="1">Active</option>
            <option value="0">Non active</option>
        </select>

    </div>
</div>