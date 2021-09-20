<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.report_bug')</div>
    <div class="col-9">
        <div class="row mx-n2">
            <div class="px-2 col-auto">

                <div class="icheck-cyan" v-for="name in [ 'reportbug-true' ]">
                    <input type="checkbox" v-model="filter.reportBugTrue" :id="name" :name="name" @change="applyFilter" :disabled="!filter.reportBugFalse" 
                        :true-value="1" :false-value="0" />
                    <label :for="name">@lang('label.yes')</label>
                </div>

            </div>
            <div class="px-2 col-auto">

                <div class="icheck-cyan" v-for="name in [ 'reportbug-false' ]">
                    <input type="checkbox" v-model="filter.reportBugFalse" :id="name" :name="name" @change="applyFilter" :disabled="!filter.reportBugTrue"
                        :true-value="1" :false-value="0" />
                    <label :for="name">@lang('label.none')</label>
                </div>

            </div>
        </div>
    </div>
</div>