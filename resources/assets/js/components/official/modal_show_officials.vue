
<template>
  <div class="modal fade" id="modalOfficials" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Modal title {{ modalOfficials.length }}</h4>
        </div>
        <div class="modal-body">
              <table class="table table-hover table-bordered table-condensed table-striped" style="font-size: 12px">
                  <thead>
                      <tr>
                          <th class="text-center" style="width: 400px">POSITION</th>
                          <th style="width: 200px">FULLNAME</th>
                          <th width="50" class="text-center">TERMS</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="official in modalOfficials">
                          <td class="text-center">{{ official.POSITION_NAME }}</td>
                          <td><b>{{ official.LAST_NAME }}</b>, {{ official.FIRST_NAME }} {{ official.MIDDLE_NAME }}</td>
                          <td class="text-center"><b>{{ official.TERM_OFFICE }}</b></td>
                      </tr>
                  </tbody>
              </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            modalOfficials: {
                type: Array
            }
        }
    }
</script>
