<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.building_condition')</div>
    <div class="col-9">
        
        <div class="row mx-n2">
            <div class="px-2 col-auto">

                <div class="icheck-cyan" v-for="name in [ 'building-condition-true' ]">
                    <input type="checkbox" v-model="filter.buildingConditionYes" :id="name" :name="name" @change="applyFilter" :disabled="!filter.buildingConditionNo"
                        :true-value="1" :false-value="0" />
                    <label :for="name">@lang('label.yes')</label>
                </div>

            </div>
            <div class="px-2 col-auto">

                <div class="icheck-cyan" v-for="name in [ 'building-condition-false' ]">
                    <input type="checkbox" v-model="filter.buildingConditionNo" :id="name" :name="name" @change="applyFilter" :disabled="!filter.buildingConditionYes"
                    :true-value="1" :false-value="0" />
                    <label :for="name">@lang('label.none')</label>
                </div>

            </div>
        </div>

    </div>
</div>