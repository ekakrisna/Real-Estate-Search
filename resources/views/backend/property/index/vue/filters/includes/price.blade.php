<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.range_selling_price')</div>
    <div class="col-9">
 
        <div class="row mx-n1">
            <div class="px-1 col-xl">
                <template>
                    <currency-input class="form-control form-control-sm" v-model.number="filterPriceMin" @keyup="applyFilter"
                    :currency="null" :precision="{min:0, max: 6}" :allow-negative="false"/>
                </template>
            </div>
            <div class="px-1 col-xl-auto d-none align-items-center d-xl-flex">~</div>
            
            <div class="px-1 col-xl mt-2 mt-xl-0">
                <template>
                    <currency-input class="form-control form-control-sm" v-model.number="filterPriceMax" @keyup="applyFilter"
                    :currency="null" :precision="{min:0, max: 6}" :allow-negative="false"/>
                </template>
            </div>
        </div>
    </div>
</div>