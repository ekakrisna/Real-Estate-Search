
@relativeInclude('item.import')
<script type="text/x-template" id="tablelike-result-tpl">
    <div class="tablelike-result">

        <div v-if="isEmpty" class="text-center py-2 px-2 border">
            <span>対象のレコードはありません。</span>
        </div>

        <template v-else>            
            <!-- Result item - Start -->
            <ResultItem v-for="( item, index ) in value" :key="item.id" v-model="value[index]" :index="index" @getFile="changeFile"></ResultItem>
            @relativeInclude('includes.modal')        
            <!-- Result item - End -->
            <div class="border-bottom d-none d-lg-block"></div>            

        </template>        
    </div>
</script>


<script> @minify
    (function( $, io, document, window, undefined ){     
        window.queue = {};               
        Vue.component( 'Result', {            
            props: [ 'value' ],
            template: '#tablelike-result-tpl',            
            data: function(){                 
                return {
                    memberId    : [],
                    emailMember : [],
                    nameMember  : [],
                    leaderId    : '',
                    progressBar : 0,      
                    show        : false,              
                }
            }, 
            computed: {
                isEmpty: function(){ return !this.value.length },
                leader_id: function(){ return 'leader_id' },
                member_id: function(){ return 'member_id' },
            },            
            methods: {                
                changeFile: function(fileCsv){        
                    const _this = this;                      

                    filename    = fileCsv.fileName[0].name;
                    extention   = filename.split('.').pop();                    
                    if(extention == 'csv'){                        
                        _this.progressBar = 0;             
                        _this.show = false;            
                        let element = _this.$refs.modalContent;                        
                        $(element).modal('show');

                        let formData = new FormData();                                       
                        formData.append("file", fileCsv.fileName[0]);                                        
                        formData.append("leader_id", fileCsv.leader_id); 
                        const url = @json(route('admin.company.user.permission.upload',$company_id));
                        axios.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                            onUploadProgress: function( progressEvent ) {                                 
                                _this.progressBar = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total));                                
                            }.bind(_this);
                        }).then(function(data) {   
                            // setTimeout( function(){
                                if(data.data.company_user_id != null && data.data.email != null && data.data.name != null){                                    
                                    _this.leaderId = fileCsv.leader_id;
                                    _this.memberId = data.data.company_user_id;
                                    _this.emailMember = data.data.email;
                                    _this.nameMember = data.data.name;                                
                                    _this.show = true;    
                                    let element = _this.$refs.modalContent;                        
                                    $(element).modal('show');                                                           
                                    _this.progressBar = 0;                                                               
                                }else{
                                    _this.leaderId = '';
                                    _this.memberId = '';
                                    _this.emailMember = '';
                                    _this.nameMember = '';                                
                                    let element = _this.$refs.modalContent;                        
                                    $(element).modal('hide');                                
                                    _this.progressBar = '';
                                    var message = "@lang('label.FAILED_UPDATE_MESSAGE')";
                                    _this.$toasted.show(message, {
                                        type: 'failed',
                                    });          
                                    _this.progressBar = 0;   
                                }
                            // }, 1000 );                                                                                                                                                             
                        }).catch(function() {
                            _this.progressBar = 0;  
                            var message = "@lang('label.csv_format')";
                            _this.$toasted.show(message, {
                                type: 'failed',
                            });
                        });  
                    }else{
                        var message = "@lang('label.csv_format')";
                        _this.$toasted.show(message, {
                            type: 'failed',
                        });
                    }                                
                },   
                submit: function(){
                    var vm          = this;
                    var valueLeader = vm.leaderId;
                    var valueMember = vm.memberId;
                    var link        = @json(route('admin.company.user.permission.addTeam', $company_id));                    
                    axios.post(link, {
                        leader_id: valueLeader,
                        member_id: valueMember
                    }).then(function (response) {                         
                        if (response.data.success == "success") {                                        
                            vm.$store.state.result.data = response.data.data;                            
                            let element = vm.$refs.modalContent;                        
                            $(element).modal('hide');                                                            
                            var message = "@lang('label.SUCCESS_UPDATE_MESSAGE')";
                            vm.$toasted.show(message, {
                                type: 'success',
                            });                                                                                                             
                        }                        
                    })
                }
            },            
        });  
    }( jQuery, _, document, window ));
@endminify </script>

