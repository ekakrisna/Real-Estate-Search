<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.order')</div>
    <div class="col-9">

        <div class="row">
            <div class="px-2 col">
                <select class="form-control form-control-sm" v-model.number="filter.order" @change="applyFilter">
                    <option value=""></option>
                    <option v-for="order in $store.state.preset.orders" :value="order.id">@{{ order.label }}</option>
                </select>
            </div>
            <div class="px-2 col">
                <select class="form-control form-control-sm" v-model="filter.direction" @change="applyFilter" :disabled="!filter.order">
                    <option value="asc">@lang('label.ascending')</option>
                    <option value="desc">@lang('label.descending')</option>
                </select>
            </div>
        </div>

    </div>
</div>