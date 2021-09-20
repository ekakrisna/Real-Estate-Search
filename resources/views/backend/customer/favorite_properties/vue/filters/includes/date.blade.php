<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.registered_date')</div>
    <div class="col-9">

        <div class="row mx-n1">
            <div class="px-1 col-md">

                <date-picker v-model="filter.datestart" type="date" class="w-100" input-class="form-control form-control-sm"
                    format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
                </date-picker>

            </div>

            <div class="px-1 col-auto d-none d-md-block">
                <div class="py-2">~</div>
            </div>

            <div class="px-1 col-md mt-2 mt-md-0">

                <date-picker v-model="filter.datefinish" type="date" class="w-100" input-class="form-control form-control-sm" 
                    format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
                </date-picker>

            </div>
        </div>
    </div>
</div>