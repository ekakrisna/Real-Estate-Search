<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.photo')</div>
    <div class="col-9">
        <div class="row mx-n2">
            <div class="px-2 col-auto">

                <div class="icheck-cyan" v-for="name in [ 'photo-true' ]">
                    <input type="checkbox" v-model="filter.photoTrue" :id="name" :name="name" @change="applyFilter" :disabled="!filter.photoFalse" 
                        :true-value="1" :false-value="0" />
                    <label :for="name">@lang('label.yes')</label>
                </div>

            </div>
            <div class="px-2 col-auto">

                <div class="icheck-cyan" v-for="name in [ 'photo-false' ]">
                    <input type="checkbox" v-model="filter.photoFalse" :id="name" :name="name" @change="applyFilter" :disabled="!filter.photoTrue"
                        :true-value="1" :false-value="0" />
                    <label :for="name">@lang('label.none')</label>
                </div>

            </div>
        </div>
    </div>
</div>