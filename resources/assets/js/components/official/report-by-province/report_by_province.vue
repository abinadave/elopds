<template>
    <div style="margin-top: 10px" class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Officials: {{ officials.length }}, provinces: {{ provinces.length }}</div>
            <div class="panel-body">
                <table class="table table-bordered" id="tbl-province">
                    <thead>
                        <tr>
                            <th>Province</th>
                            <th class="text-center">Draft</th>
                            <th class="text-center">Approved</th>
                            <th class="text-center">Governors</th>
                            <th class="text-center">Vice-Governors</th>
                            <th class="text-center">SP Member</th>
                            <th width="10">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="province in provinces">
                            <td class="text-center">{{ province.name }}</td>
                            <td class="text-center">{{ getTotalDrafted(province) }}</td>
                            <td class="text-center">{{ getTotalApproved(province) }}</td>
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
            getTotalDrafted(province){
                let self = this;
                return self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase() &&
                    official.STATUS === 'draft'; 
                }).length;
            },
            getTotalApproved(province){
                let self = this;
                return self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase() &&
                    official.STATUS === 'approved'; 
                }).length;
            },
            showOfficials(province){
                let self = this;
                self.modalOfficials = [];
                $('#modal-province-officials').modal('show');
                let rs = self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase() &&
                    official.STATUS === 'approved'; 
                });
                self.modalOfficials = _.sortBy(rs, [function(o) { return o.POSITION_NAME; }]);
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
