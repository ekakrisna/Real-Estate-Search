<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">ID</div>
    <div class="col-9">

        <div class="row mx-n2">
            <div class="px-2 col">
                <input type="text" class="form-control form-control-sm" v-model.number="filter.min" @keyup="applyFilter" />
            </div>
            <div class="px-2 col-auto d-flex align-items-center">~</div>
            <div class="px-2 col">
                <input type="text" class="form-control form-control-sm" v-model.number="filter.max" @keyup="applyFilter" />
            </div>
        </div>
    </div>
</div>