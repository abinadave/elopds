<template>
    <div class="modal fade" id="modalAdditionalDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 800px">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Additional Description</h4>
          </div>
          <div class="modal-body">
                <form class="form-details">
                    <div class="col-md-5">
                        <label>OFFICE ADDRESS</label>
                        <input v-model="currentOfficial.OFFICE_ADDRESS" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>CONTACT</label>
                        <input v-model="currentOfficial.CONTACT" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>EMAIL</label>
                        <input v-model="currentOfficial.EMAIL" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>CELLPHONE NUMBER</label>
                        <input v-model="currentOfficial.CELLPHONE" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>RELIGION</label>
                        <input v-model="currentOfficial.RELIGION" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>BASIS_OF_ASSUMPTION</label>
                        <input v-model="currentOfficial.BASIS_OF_ASSUMPTION" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                   
                    <div class="col-md-5">
                        <label>SERVICE RECORD</label>
                        <textarea style="margin-top: 20px; width: 300px; height: 100px"  v-model="currentOfficial.SERVICE_RECORD" class="form-control" ></textarea>
                        <!-- <input  style="width: 300px" type="text" /><br> -->
                    </div>
                    <div class="col-md-5">
                        <label>DEDUCATIONAL ATTAINMENT</label>
                        <textarea v-model="currentOfficial.EDUCATIONAL_ATTAINMENT" style="width: 300px; height: 100px; margin-top: 21px" class="form-control"></textarea>
                    </div>
                     <div class="col-md-5">
                        <label>TRAINING STUDY & SCHOOLARSHIP GRANTS</label>
                        <textarea v-model="currentOfficial.TRAINING_STUDY_AND_SCHOOLARSHIP_GRANTS" style="width: 300px; height: 100px" class="form-control"></textarea>
                    </div>
                    <br>
                    <div class="col-md-5" style="margin-top: 20px">
                        <label>MEMBERSHIP_IN_ORGANIZATION</label>
                        <textarea v-model="currentOfficial.MEMBERSHIP_IN_ORGANIZATION" style="width: 300px; height: 100px" class="form-control"></textarea>
                    </div>
                    <div class="col-md-5" style="margin-top: 20px">
                        <label>ACHIEVEMENT_RECORD</label>

                        <textarea v-model="currentOfficial.ACHIEVEMENT_RECORD" style="width: 300px; height: 100px" class="form-control"></textarea>
                    </div>
                    <!-- <button class="btn btn-info"></button> -->
                </form> 
          </div>
          <div class="modal-footer" style="border-color: transparent;">
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>
</template>

<style type="text/css">
    .form-details input {
        padding: 2px;
        width: 300px;
    }
</style>


<script>
    export default {
        mounted() {
            let self = this;
            // $('#modalAdditionalDetails').on('hidden.bs.modal', function(){
            //     $.each(newObj, function(index, val) {
            //         if (val == '') {
            //             self.currentOfficial[index] = '';
            //         }
            //     });
            // });
        },
        props: {    
            currentOfficial: {
                type: Object
            }
        },
        watch: {
            'currentOfficial': function(newObj){
                let self = this;
                // $.each(newObj, function(index, val) {
                //     if (val == '') {
                //         self.currentOfficial[index] = 'none';
                //     }
                // });
            }
        }
    }
</script>
