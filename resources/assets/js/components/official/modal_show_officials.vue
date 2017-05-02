
<template>
  <div class="modal fade" id="modal-current-officials" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">ELECTED OFFICIALS <b class="text-primary"></b> &nbsp;&nbsp;&nbsp;({{ modalOfficials.length }})</h4>
        </div>
        <div class="modal-body">
              <table id="table-show-officials" class="table table-hover table-bordered table-condensed table-striped">
                  <thead>
                      <tr>
                          <th class="text-center">PROVINCE</th>
                          <th class="text-center">LGU</th>
                          <th class="text-center" style="width: 400px">POSITION</th>
                          <th style="width: 200px">FULLNAME</th>
                          <th class="text-center">STATUS</th>
                          <th width="50" class="text-center">TERMS</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="official in modalOfficials">
                          <td class="text-center">{{ official.PROVINCE }}</td>
                          <td class="text-center">{{ official.CITYMUN }}</td>
                          <td class="text-center">{{ official.POSITION_NAME }}</td>
                          <td><b>{{ official.LAST_NAME }}</b>, {{ official.FIRST_NAME }} {{ official.MIDDLE_NAME }}</td>
                          <td class="text-center">{{ official.STATUS }}</td>
                          <td class="text-center"><b>{{ official.TERM_OFFICE }}</b></td>
                      </tr>
                  </tbody>
              </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>
<style type="text/css">
  #table-show-officials {
    font-size: 11px;
    padding: 2px;
  }
</style>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            modalOfficials: {
                type: Array
            },
            currentLgu: {
                type: Object
            }
        }
    }
</script>
