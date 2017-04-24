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
                        <!-- <input type="text" id="hidden-id"> -->
                    <div class="col-md-5">
                        <label>OFFICE ADDRESS</label>
                        <input id="OFFICE_ADDRESS" v-model="currentOfficial.OFFICE_ADDRESS" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>CONTACT</label>
                        <input id="CONTACT" v-model="currentOfficial.CONTACT" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>EMAIL</label>
                        <input id="EMAIL" v-model="currentOfficial.EMAIL" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>CELLPHONE NUMBER</label>
                        <input id="CELLPHONE" v-model="currentOfficial.CELLPHONE" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>RELIGION</label>
                        <input id="RELIGION" v-model="currentOfficial.RELIGION" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                    <div class="col-md-5">
                        <label>BASIS_OF_ASSUMPTION</label>
                        <input id="BASIS_OF_ASSUMPTION" v-model="currentOfficial.BASIS_OF_ASSUMPTION" style="width: 300px" type="text" class="form-control" /><br>
                    </div>
                   
                    <div class="col-md-5">
                        <label>SERVICE RECORD</label>
                        <textarea id="SERVICE_RECORD" style="margin-top: 20px; width: 300px; height: 100px"  v-model="currentOfficial.SERVICE_RECORD" class="form-control" ></textarea>
                        <!-- <input  style="width: 300px" type="text" /><br> -->
                    </div>
                    <div class="col-md-5">
                        <label>EDUCATIONAL ATTAINMENT</label>
                        <textarea id="EDUCATIONAL_ATTAINMENT" v-model="currentOfficial.EDUCATIONAL_ATTAINMENT" style="width: 300px; height: 100px; margin-top: 21px" class="form-control"></textarea>
                    </div>
                     <div class="col-md-5">
                        <label>TRAINING STUDY & SCHOOLARSHIP GRANTS</label>
                        <textarea id="TRAINING_STUDY_AND_SCHOOLARSHIP_GRANTS" v-model="currentOfficial.TRAINING_STUDY_AND_SCHOOLARSHIP_GRANTS" style="width: 300px; height: 100px" class="form-control"></textarea>
                    </div>
                    <br>
                    <div class="col-md-5" style="margin-top: 20px">
                        <label>MEMBERSHIP_IN_ORGANIZATION</label>
                        <textarea id="MEMBERSHIP_IN_ORGANIZATION" v-model="currentOfficial.MEMBERSHIP_IN_ORGANIZATION" style="width: 300px; height: 100px" class="form-control"></textarea>
                    </div>
                    <div class="col-md-5" style="margin-top: 20px">
                        <label>ACHIEVEMENT_RECORD</label>

                        <textarea id="ACHIEVEMENT_RECORD" v-model="currentOfficial.ACHIEVEMENT_RECORD" style="width: 300px; height: 100px" class="form-control"></textarea>
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
    import alertify from 'alertify.js'
    export default {
        mounted() {
            let self = this;
            this.initJquery();
        },
        props: {    
            currentOfficial: {
                type: Object
            },
            officials: {
                type: Array
            }
        },
        methods: {
            updateElectedOfficial(newValue, id, key){
                let self = this;
                let form = {
                    value: newValue,
                    id: id,
                    key: key
                };
                self.$http.put('/official', form).then((resp) => {
                   if (resp.status === 200) {
                       let json = resp.body;
                       if (json.updated) {
                            let rs = _.filter(self.officials, {id: id});
                            if (rs.length) {
                                let first = _.first(rs);
                                self[key] = newValue;
                                alertify.success('Official successfully updated');
                            }
                       }
                   }
               }, (resp) => {
                   if (resp.status === 422) {
                     console.log(resp)
                   }
               });

            },
            initJquery(){
                let self = this;
                let $el = $("#modalAdditionalDetails");
                $(function() {
                    let id = null, key = ';'
                    self.timer = null;
                    let value = '';
                    $el.find('input,textarea').keyup(function(event) {
                        id = self.currentOfficial.id;
                        key = this.id;
                        value = $(this).val();
                        clearTimeout(self.timer);
                        self.timer = setTimeout(function(){
                            self.updateElectedOfficial(value, id, key);
                        }, 1000);
                        // console.log(value);                        
                    });
                });
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
