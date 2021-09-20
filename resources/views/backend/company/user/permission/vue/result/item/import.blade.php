<script type="text/x-template" id="tablelike-result-item-tpl">
    <div class="tablelike-item border-top border-right mt-3 mt-xl-0">
        <div class="row mx-0">
            <!-- ID column - Start -->
            <div class="px-0 border-left col-lg-6 col-xl-180px">
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.team_leader')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- ID column - End -->

            <!-- Name column - Start -->
            <div class="px-0 border-left col-lg col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Email (@lang('label.leader'))</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">@{{ user.email }}</div>
                    </div>
                </div>
            </div>
            <!-- Name column - End -->

            <!-- User role column - Start  -->
            <div class="px-0 border-left col-lg-6 col-xl-180px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">@lang('label.browsing_target')</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2" v-for="users in user.company_user_teams_leader">@{{ users.company_user.name }}</div>
                    </div>
                </div>
            </div>
            <!-- User role column - End -->

            <!-- Company column - Start -->
            <div class="px-0 border-left col-lg-6 col-xl">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Email (@lang('label.member'))</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2" v-for="users in user.company_user_teams_leader">@{{ users.company_user.email }}</div>
                    </div>
                </div>
            </div>
            <!-- Company column - End -->

            <!-- Control column - Start -->
            <div class="px-0 border-left col-lg col-xl-130px">
                <div class="border-top d-xl-none"></div>
                <div class="row mx-0 flex-nowrap">
                    <div class="px-0 col-100px col-sm-150px border-right d-xl-none bg-light">
                        <div class="py-2 px-2">Option</div>
                    </div>
                    <div class="px-0 col col-xl-12 overflow-hidden">
                        <div class="py-2 px-2">
                            <div class="row mx-n1 justify-content-xl-center">
                                <div class="px-1 col-auto">
                                    <form :ref="`form` + user.id">
                                        <input class="d-none" type="file" name="uploaded_file" :ref="`file` + user.id" v-on:change="getValue($event.target.files, `${user.id}`)">
                                        <button type="button" class="btn btn-sm btn-primary" @click="onButtonClick(user.id)">@lang('label.upload_button')</button>                                                                                               
                                    </form>                                                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Control column - End -->
        </div>
        <div class="border-bottom d-xl-none"></div>
    </div>    
</script>

<script>
    @minify
        (function($, io, document, window, undefined) {            
            Vue.component('ResultItem', {                
                props: ['value', 'index'],
                template: '#tablelike-result-item-tpl',                
                data: function() {                                        
                    var data = {
                        member_id   : [],
                        company_id  : '',                        
                        file        : [],                        
                    };                                        
                    return data;                    
                },                                         
                computed: {                                        
                    user: function() {
                        return this.value
                    },
                    fileCsv: function() {
                        return this.file
                    },                                                                    
                },                                                
                methods: {
                    onButtonClick(user) {                        
                        this.$refs['file'+user].click();
                    },
                    getValue: function(fieldName, userId){
                        this.file.fileName = fieldName;
                        this.file.leader_id = userId;                          
                        this.$emit('getFile', this.file);
                        this.$refs['form'+userId].reset();
                    },                                        
                },                                     
                watch: {}
            });     
        }(jQuery, _, document, window));
    @endminify
</script>