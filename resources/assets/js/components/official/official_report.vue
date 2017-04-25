<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">ELOPDS Report</div>

                    <div class="panel-body">
                        <table class="table table-hover table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>CITY/MUNICIPALITY</th>
                                    <th>OFFICIALS</th>
                                    <th>PERCENTAGE</th>
                                    <th>LACKING</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lgu in citymuns" v-show="lgu.CITYMUN !== ''">
                                    <td>{{ lgu.CITYMUN }}</td>
                                    <td><b>{{ getOfficialCount(lgu) }}</b> / 12</td>
                                    <td><b>{{ getPercentage(lgu) }}</b></td> 
                                    <td>{{ getLacking(lgu) }}</td>
                                    <td><i style="cursor:pointer" @click="showInvolvedPerson(lgu)" class="glyphicon glyphicon-folder-open text-primary"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <modal-involved-officials :modal-officials="modalOfficials"></modal-involved-officials>
    </div>
</template>

<script>
    import accounting from 'accounting'
    import CompModalOfficials from './modal_show_officials.vue'
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetchOfficials();
            this.fetchCityMuns();
        },
        components: {
            'modal-involved-officials': CompModalOfficials
        },
        data(){
            return {
                officials: [],
                citymuns: [],
                shouldBeTheLength: 12,
                modalOfficials: []
            }
        },
        methods: {
            getLacking(lgu){
                let self = this;
                let rsOfficials = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                let rsMembers = _.filter(rsOfficials, function(o) { 
                    return o.POSITION_NAME.toLowerCase().indexOf('member') !== -1; 
                }).length;
                let rsMayors = _.filter(rsOfficials, function(o) { 
                    return o.POSITION_NAME.toLowerCase().indexOf('mayor') !== -1; 
                }).length;
                let rsVice = _.filter(rsOfficials, function(o) { 
                    return o.POSITION_NAME.toLowerCase().indexOf('vice-mayor') !== -1; 
                }).length;
                return 'Members: ' + rsMembers + ', Vice: ' + rsVice + ', Mayor: ' + rsMayors;
            },
            showInvolvedPerson(lgu){
                let self = this;
                let rs = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                let sortedArr = _.sortBy(rs, [function(o) { return o.LAST_NAME; }]);
                self.modalOfficials = sortedArr;
                $('#modalOfficials').modal('show');
            },
            getPercentage(lgu){
                let self = this;
                let rs = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                let percentage = (rs.length / 12) * 100;
                return accounting.formatMoney(percentage,' ', 1) + ' %';
            },
            getOfficialCount(lgu){
                let self = this;
                let rs = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                // console.log(rs.length)
                return rs.length;
            },
            fetchOfficials(){
                let self = this;
                self.$http.get('/fetch_all_officials').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.officials = json;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        console.log(resp);
                    }
                });
            },
            fetchCityMuns(){
                let self = this;
                self.$http.get('/citymun').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.citymuns = json;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        console.log(resp);
                    }
                });
            }
        },
        watch: {

        }
    }
</script>
