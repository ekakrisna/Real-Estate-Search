<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.selling_price_yuan')</div>
    <div class="col-9">

        <div class="row mx-n1">
            <div class="px-1 col">
                <template>
                    <currency-input class="form-control form-control-sm" v-model.number="filter.minprice" @keyup="applyFilter"
                    :currency="null" :precision="{min:0, max: 6}" :allow-negative="false"/>
                </template>                
            </div>
            <div class="px-1 col-auto d-flex align-items-center">~</div>
            <div class="px-1 col">
                <template>
                    <currency-input class="form-control form-control-sm" v-model.number="filter.maxprice" @keyup="applyFilter"
                    :currency="null" :precision="{min:0, max: 6}" :allow-negative="false"/>
                </template>                
            </div>
        </div>
    </div>
</div>