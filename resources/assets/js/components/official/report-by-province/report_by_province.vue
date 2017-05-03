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
                            <td class="text-center">
                                <a @click="showDraftedOfficials(province)" style="cursor: pointer;">{{ getTotalDrafted(province) }}</a>
                            </td>
                            <td class="text-center">
                                <a @click="showTotalApproved(province)" style="cursor: pointer;">{{ getTotalApproved(province) }}</a>
                            </td>
                            <td class="text-center">
                                <a @click="showTotalGovernors(province)" style="cursor: pointer;">
                                    {{ getWhatPosition(province, 'PROVINCIAL GOVERNOR') }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a style="cursor: pointer" @click="showTotalViceGovernors(province)">
                                    {{ getWhatPosition(province, 'PROVINCIAL VICE-GOVERNOR') }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a style="cursor: pointer" @click="showTotalSangguniangPanlalawiganMemger(province)">
                                {{ getWhatPosition(province, 'SANGGUNIANG PANLALAWIGAN MEMBER') }} 
                                </a>
                                / 10</td>
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
                modalOfficials: [],
                currentProvince: { name: '...' }
            }
        },
        methods: {

            showDraftedOfficials(province){
                let self = this;
                $('#modal-province-officials').modal('show');
                self.modalOfficials = self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase() &&
                    official.STATUS === 'draft'; 
                });
            },
            getTotalDrafted(province){
                let self = this;
                return self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase() &&
                    official.STATUS === 'draft'; 
                }).length;
            },
            showTotalApproved(province){
                let self = this;
                $('#modal-province-officials').modal('show');
                self.modalOfficials = self.officials.filter(function(official){
                    return official.PROVINCE.toUpperCase() === province.name.toUpperCase() &&
                    official.STATUS === 'approved'; 
                });
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
            showTotalGovernors(province){
                let self = this;
                $('#modal-province-officials').modal('show');
                let rs = self.officials.filter(function(official){
                    return official.POSITION_NAME.trim().toUpperCase() === 'PROVINCIAL GOVERNOR' &&
                           official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase()
                });
                self.modalOfficials = rs;
            },
            showTotalViceGovernors(province){
                let self = this;
                $('#modal-province-officials').modal('show');
                let rs = self.officials.filter(function(official){
                    return official.POSITION_NAME.trim().toUpperCase() === 'PROVINCIAL VICE-GOVERNOR' &&
                           official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase()
                });
                self.modalOfficials = rs;
            },
            showTotalSangguniangPanlalawiganMemger(province){
                let self = this;
                $('#modal-province-officials').modal('show');
                let rs = self.officials.filter(function(official){
                    return official.POSITION_NAME.trim().toUpperCase() === 'SANGGUNIANG PANLALAWIGAN MEMBER' &&
                           official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase()
                });
                self.modalOfficials = rs;
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
