<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.property_id')</div>
    <div class="col-9">
        <div class="row mx-n2">
            <div class="px-2 col">
                <template>
                    <currency-input v-model.number="filterId" class="form-control form-control-sm" :currency="null"
                        :precision="{min:0, max:0}" :allow-negative="false" @keyup="applyFilter" />
                </template>
            </div>
        </div>
    </div>
</div>
