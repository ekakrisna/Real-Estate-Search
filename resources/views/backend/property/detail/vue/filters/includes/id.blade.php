<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.last_update')</div>
    <div class="col-9">

        <div class="row mx-n2">
            <div class="px-2 col">
                <input type="date" class="form-control form-control-sm" v-model="filter.min" @change="applyFilter" />
            </div>
            <div class="px-2 col-auto d-flex align-items-center">~</div>
            <div class="px-2 col">
                <input type="date" class="form-control form-control-sm" v-model="filter.max" @change="applyFilter" />
            </div>
        </div>
    </div>
</div>