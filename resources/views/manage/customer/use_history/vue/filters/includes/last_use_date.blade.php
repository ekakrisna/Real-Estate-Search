<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.search_date_time')</div>
    <div class="col-9">
 
        <div class="row mx-n1">
            <div class="px-1 col-xl">

                <date-picker v-model="filter.last_use_date_min" type="date" class="w-100" input-class="form-control form-control-sm"
                    format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
                </date-picker>
                
            </div>
            <div class="px-1 col-xl-auto d-none align-items-center d-xl-flex">~</div>
            <div class="px-1 col-xl mt-2 mt-xl-0">

                <date-picker v-model="filter.last_use_date_max" type="date" class="w-100" input-class="form-control form-control-sm"
                    format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
                </date-picker>

            </div>
        </div>
    </div>
</div>