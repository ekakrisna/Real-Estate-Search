<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.selling_price') </div>
    <div class="col-9">

        <div class="row mx-n1">
            <div class="px-1 col">
                <template>
                    <currency-input v-model.number="filter.minprice" class="form-control form-control-sm"
                    :currency="null" :precision="{min:0, max: 6}" :allow-negative="false" @keyup="applyFilter" />
                </template>
            </div>
            <div class="px-1 col-auto d-flex align-items-center">~</div>
            <div class="px-1 col">
                <template>
                    <currency-input v-model.number="filter.maxprice" class="form-control form-control-sm"
                    :currency="null" :precision="{min:0, max: 6}" :allow-negative="false" @keyup="applyFilter" />
                </template>
            </div>
        </div>
        
    </div>
</div>