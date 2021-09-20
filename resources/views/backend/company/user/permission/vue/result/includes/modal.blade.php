<!-- Modal Content - Start -->           
<div ref="modalContent" id="modal-cancel" class="modal sm-modal text-break" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('label.set_user_permission')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>                                                        
            <form :ref="`form-modal`">                                
                <div class="modal-body">
                    <div class="container col-md-12 col-lg-12 col-xl-12">                                                                
                        <div class="row">
                            <div class="col-12 mb-3" v-if="!show">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" :style="{width: progressBar + '%'}" 
                                    :aria-valuenow="progressBar" aria-valuemin="0" aria-valuemax="100">
                                      {{-- @{{progressBar}}% --}}
                                    </div>
                                </div>  
                            </div>     
                            <template v-if="show">                                
                                <div class="col-6">
                                    <p class="mb-2 font-weight-bold">@lang('label.browsing_target')</p>
                                    <p class="mb-1" v-for="nameMember in this.nameMember">@{{nameMember}}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2 font-weight-bold">@lang('label.email')</p>
                                    <p class="mb-1" v-for="emailMember in this.emailMember">@{{emailMember}}</p>                                        
                                </div>    
                            </template>                                        
                        </div>                  
                    </div>                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" @click="submit">@lang('label.update_button')</button>                  
                </div>     
            </form>                   
        </div>
    </div>
</div>
<!-- Modal - End -->