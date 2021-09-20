<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.land_area_m')</div>
    <div class="col-9">
        <div class="row mx-n1">
            <div class="px-1 col">                
                    <currency-input class="form-control form-control-sm" v-model.number="filterLandAreaMin" @keyup="applyFilter" 
                    :precision="{min:0, max:4}" :allow-negative="false" :currency="null"/>                            
            </div>
            <div class="px-1 col-auto d-flex align-items-center">~</div>
            <div class="px-1 col">                
                    <currency-input class="form-control form-control-sm" v-model.number="filterLandAreaMax" @keyup="applyFilter" 
                    :precision="{min:0, max:4}" :allow-negative="false" :currency="null"/>                    
            </div>
        </div>
    </div>
</div>