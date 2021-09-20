<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.order')</div>
    <div class="col-9">

        <div class="row mx-n1">
            <div class="px-1 col">
                <select class="form-control form-control-sm" v-model.number="filter.order" @change="applyFilter">
                    <option value=""></option>
                    <option v-for="order in $store.state.preset.orders" :value="order.id">@{{ order.label }}</option>
                </select>
            </div>
            <div class="px-1 col-auto d-flex align-items-center"> </div>
            <div class="px-1 col">
                <select class="form-control form-control-sm" v-model="filter.direction" @change="applyFilter" :disabled="!filter.order">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

    </div>
</div>