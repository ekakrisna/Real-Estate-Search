<div class="row mt-2">
    <div class="col-3 d-flex align-items-center">@lang('label.user_action')</div>
    <div class="col-9">

        <select class="form-control form-control-sm" v-model.number="filter.status" @change="applyFilter">
            <option value=""></option>
            <option v-for="actiontype in $store.state.preset.actiontype" :value="actiontype.id">@{{ actiontype.label }}</option>
        </select>

    </div>
</div>