<template>
    <div style="margin-top: 10px" class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Officials: {{ officials.length }}, provinces: {{ provinces.length }}</div>
            <div class="panel-body">
                <table class="table table-bordered" id="tbl-province">
                    <thead>
                        <tr>
                            <th>Province</th>
                            <th class="text-center">Governors</th>
                            <th class="text-center">Vice-Governors</th>
                            <th class="text-center">SP Member</th>
                            <th width="10">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="province in provinces">
                            <td class="text-center">{{ province.name }}</td>
                            <td class="text-center">{{ getWhatPosition(province, 'PROVINCIAL GOVERNOR') }}</td>
                            <td class="text-center">{{ getWhatPosition(province, 'PROVINCIAL VICE-GOVERNOR') }}</td>
                            <td class="text-center">{{ getWhatPosition(province, 'SANGGUNIANG PANLALAWIGAN MEMBER') }} / 10</td>
                            <td class="text-center"><i @click="showOfficials(province)" class="fa fa-folder-open text-info" style="cursor: pointer"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <province-official-modal :modal-officials="modalOfficials"></province-official-modal>
    </div>
</template>
<style type="text/css">
    #tbl-province {
        font-size: 12px;
    }
</style>
<script>
    import CompModalShowOfficials from './show_modal_province_officials.vue'
    export default {
        mounted() {
            let self = this;
            self.getAllOfficials();
            setTimeout(function(){
                self.getprovinces();
            }, 1000);
        },
        components: {
            'province-official-modal': CompModalShowOfficials
        },
        data(){
            return {
                provinces: [], officials: [],
                modalOfficials: []
            }
        },
        methods: {
            showOfficials(province){
                let self = this;
                self.modalOfficials = [];
                $('#modal-province-officials').modal('show');
                let rs = self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase();
                });
                self.modalOfficials = rs;
                // self.$http.post('/get_officials_by_province',{
                //     province: province
                // }).then((resp) => {
                //     if (resp.status === 200) {
                //         let json = resp.body;
                //         json = _.uniqBy(json, function (e) {
                //             return e.LAST_NAME && e.FIRST_NAME;
                //         });
                //         for (var i = json.length - 1; i >= 0; i--) {
                //             self.modalOfficials.push(json[i]);
                //         }
                //     }
                // }, (resp) => {
                //     console.log(resp);
                // });  
            },
            getWhatPosition(province, POSITION){
                let self = this;
                let rs= self.officials.filter(function(official){
                    return official.POSITION_NAME.trim().toUpperCase() === POSITION &&
                           official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase() &&
                           official.STATUS === 'approved';
                });
                return rs.length;
            },
            getprovinces(){
                let self = this;
                self.$http.get('/province').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.provinces.push(json[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            getAllOfficials(){
                let self = this;
                self.$http.get('/fetch_all_officials_report_by_province').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.officials.push(json[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            }
        }
    }
</script>
