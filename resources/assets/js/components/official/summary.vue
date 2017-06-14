<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Summary Component
                    <a style="cursor: pointer" @click="printTable" class="fa fa-print"></a>
                    </div>

                    <div class="panel-body">
                        <h3 id="summaryText"></h3>
                        <h4 id="summaryDateTime"></h4>
                        <table class="table table-hover table-bordered" id="tbl-summary">
                            <thead>
                                <tr>
                                    <th>PROVINCE</th>
                                    <th>PROCLAIMED</th>
                                    <th>DRAFT</th>
                                    <th>FOR APPROVAL</th>
                                    <th>APPROVED</th>
                                    <th>ACTUAL NO. ENCODED</th>
                                    <th>EXPECTED NO. OF RECORDS</th>
                                    <th>DEFICIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="province in provinces">
                                    <th>{{ province.name }}</th>
                                    <th>{{ getProclaimed(province) }}</th>
                                    <th>{{ getTotalDrafts(province) }}</th>
                                    <th>{{ getTotalforapproval(province) }}</th>
                                    <th>{{ getTotalApproved(province) }}</th>
                                    <th class="text-primary">{{ getActualEncoded(province) }}</th>
                                    <th>{{ getExpectedRecords(province) }}</th>
                                    <th class="text-danger">{{ getDeficit(province) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style type="text/css">
    table th {
        text-align: center;
    }
    table td {
        text-align: center;
    }
</style>
<script>
    import moment from 'moment'
    export default {
        mounted() {
            this.fechAllOfficials();
            this.fetchProvinces();
        },
        data(){
            return {
                officials: [],
                provinces: [],
                expected: {
                    samar: 278,
                    biliran: 90,
                    southern_leyte: 203,
                    northern_samar: 252,
                    leyte: 448,
                    eastern_samar: 244
                },
                summaryText: '',
                summaryDateTime: ''
            }
        },
        methods: {
            printTable(){
                let self = this;
                let hide = '.navbar, .panel-heading';
                $('#summaryText').text('ELOPDS DILG8 Build-up Status');
                $('#summaryDateTime').text('as of ' + moment().format('MMMM DD, YYYY ddd, HH:mm a'));
                $(hide).hide();
                setTimeout(function(){
                    window.print();
                    setTimeout(function(){
                        $(hide).show();
                        $('#summaryText, #summaryDateTime').empty();
                    }, 500)
                }, 1000);
                
            },
            getDeficit(province){
                let self = this;
                let expected = self.getExpectedRecords(province);
                let actual = self.getActualEncoded(province);
                let total = expected - actual;
                if (total < 0) {
                    return 0;
                }else {
                    return total;
                }
            },
            getExpectedRecords(province){
                let self = this;
                if (province.name === 'EASTERN SAMAR') {
                    return self.expected.eastern_samar;
                }else if (province.name === 'LEYTE') {
                    return self.expected.leyte;
                }else if (province.name === 'NORTHERN SAMAR') {
                    return self.expected.northern_samar;
                }else if (province.name === 'SAMAR (WESTERN SAMAR)') {
                    return self.expected.samar;
                }else if (province.name === 'SOUTHERN LEYTE') {
                    return self.expected.southern_leyte;
                }else if (province.name === 'BILIRAN') {
                    return self.expected.biliran;
                };
            },
            getActualEncoded(province){
                let self = this;
                return self.officials.filter(function(model){
                    return model.PROVINCE === province.name;
                }).length;
            },
            getTotalApproved(province){
                let self = this;
                return self.officials.filter(function(model){
                    return model.STATUS === 'approved' &&
                           model.PROVINCE === province.name;
                }).length;
            },
            getTotalDrafts(province){
                let self = this;
                return self.officials.filter(function(model){
                    return model.STATUS === 'draft' &&
                           model.PROVINCE === province.name;
                }).length;
            },
            // forapproval
            getTotalforapproval(province){
                let self = this;
                return self.officials.filter(function(model){
                    return model.STATUS === 'forapproval' &&
                           model.PROVINCE === province.name;
                }).length;
            },
            getProclaimed(){
                let self = this;
                return 0;
            },
            fetchProvinces(){
                let self = this;
                self.$http.get('/province').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.provinces.push(json[i]);
                        };
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            },
            fechAllOfficials(){
                let self = this;
                self.$http.get('/fetch_all_officials').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.officials.push(json[i]);
                        };
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            }
        }
    }
</script>
