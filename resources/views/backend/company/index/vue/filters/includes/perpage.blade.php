<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.perpage')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.perpage" @change="applyFilter(true)">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>

    </div>
</div>