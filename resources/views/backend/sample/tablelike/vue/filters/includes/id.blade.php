<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">ID</div>
    <div class="col-9">

        <div class="row mx-n2">
            <div class="px-2 col">
                <template>
                    <currency-input v-model.number="filterMin" class="form-control form-control-sm" :currency="null" 
                        :precision="{min:0, max:0}" :allow-negative="false" @keyup="applyFilter" />
                </template>
            </div>
            <div class="px-2 col-auto d-flex align-items-center">~</div>
            <div class="px-2 col">
                <template>
                    <currency-input v-model.number="filterMax" class="form-control form-control-sm" :currency="null" 
                        :precision="{min:0, max:0}" :allow-negative="false" @keyup="applyFilter" />
                </template>
            </div>
        </div>
    </div>
</div>