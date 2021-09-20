<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">Date Range</div>
    <div class="col-9">

        <date-picker v-model="filterDateRange" type="date" class="w-100" input-class="form-control form-control-sm" range 
            format="YYYY-MM-DD" valueType="format" @change="applyFilter(true)">
        </date-picker>
        
    </div>
</div>