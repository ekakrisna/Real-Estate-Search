<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.update_date_and_time')</div>
    <div class="col-9">
        <div class="row mx-n1">
            <div class="px-1 col-lg">

                <date-picker v-model="filter.updateDateStart" type="date" class="w-100" input-class="form-control form-control-sm"
                    format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
                </date-picker>

            </div>

            <div class="px-1 col-auto d-none d-lg-block">
                <div class="py-2">~</div>
            </div>

            <div class="px-1 col-lg mt-2 mt-lg-0">

                <date-picker v-model="filter.updateDatEnd" type="date" class="w-100" input-class="form-control form-control-sm" 
                    format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
                </date-picker>

            </div>
        </div>
    </div>
</div>