<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.location')</div>
    <div class="col-9">

        <input class="form-control form-control-sm" v-model="filter.location" @change="applyFilter" />

    </div>
</div>