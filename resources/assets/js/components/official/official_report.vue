<template>
            <div>
                <div class="panel panel-primary">
                    <div class="panel-heading">ELOPDS Report </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                          <li class="active"><a  data-toggle="tab" href="#home">Report by LGU</a></li>
                          <li><a  data-toggle="tab" href="#menu1">Report by Province</a></li>
                          <!-- <li><a data-toggle="tab" href="#menu2">No Reports</a></li> -->
                        </ul>
                        <div class="tab-content">
                          <div id="home" class="tab-pane fade in active">
                            <table id="table-reports" class="table table-hover table-condensed table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>PROVINCE</th>
                                        <th>CITY/MUNICIPALITY</th>
                                        <th class="text-center">DRAFT</th>
                                        <th class="text-center">APPROVED</th>
                                        <td class="text-center">PERCENTAGE</td>
                                        <td width="10">OFFICIALS</td>
                                        <th width="10">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lgu in citymuns">
                                        <td><a @click="">{{ getProvince(lgu) }}</a></td>
                                        <td>{{ lgu.name.toUpperCase() }}</td>
                                        <td class="text-center">{{ getTotalDrafted(lgu) }}</td>
                                        <td class="text-center">{{ getTotalApproved(lgu) }}</td>
                                        <td>{{ getPercentage(lgu) }} %</td>
                                        <td class="text-center">{{ getTotalOfficials(lgu) }}</td>
                                        <td class="text-center"><i style="cursor:pointer" @click="showInvolvedPerson(lgu)" class="fa fa-folder-open text-info"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>
                          <div id="menu1" class="tab-pane fade">
                              <report-by-province></report-by-province>
                          </div>
                          <div id="menu2" class="tab-pane fade">
                              <completed-report @completedlguslength="notifCompletedLgus" :citymuns="citymuns" :officials="officials" ></completed-report>
                          </div>
                        </div>
                        
                    </div>
                </div>
                <modal-involved-officials :modal-officials="modalOfficials"></modal-involved-officials>
            </div>
</template>
<style type="text/css">
    #table-reports {
        font-size: 12px;
    }
</style>
<script>
    import accounting from 'accounting'
    import CompModalOfficials from   './modal_show_officials.vue'
    import CompCompletedReport from  './completed/completed_inputs.vue'
    import CompReportByProvince from './report-by-province/report_by_province.vue'
    export default {
        mounted() {
            let self = this;
            self.fetchOfficials();
            self.fetchCityMuns();
            self.fetchprovinces();
        },
        components: {
            'modal-involved-officials': CompModalOfficials,
            'completed-report': CompCompletedReport,
            'report-by-province': CompReportByProvince
        },
        data(){
            return {
                search: '',
                officials: [],
                citymuns: [],citymun: 0,
                provinces: [],
                lgus: [],
                shouldBeTheLength: 12,
                modalOfficials: [],
                tabClass: 'col-md-12',
                citymunScrolling: {
                    skip: 0, take: 20
                },
                currentLgu: { name: '' }
            }
        },
        methods: {
            fetchprovinces(){
                let self = this;
                self.$http.get('/province').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.provinces.push(json[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            notifCompletedLgus(json){
                let self = this;
                console.log(json);
            },
            getProvince(lgu){
                let self = this;
                let rs = _.filter(self.provinces, { id: lgu.province_id});
                if (rs.length) {
                    return rs[0].name.toUpperCase();
                }
            },
            
            getSumary(lgu){
                let self = this;
                let rsOfficials = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                let rsMembers = _.filter(rsOfficials, function(o) { 
                    return o.POSITION_NAME.toLowerCase().indexOf('member') !== -1; 
                }).length;
                let rsMayors = _.filter(rsOfficials, function(o) { 
                    return o.POSITION_NAME.toLowerCase().indexOf('city mayor') !== -1 ||
                           o.POSITION_NAME.toLowerCase().indexOf('municipal mayor') !== -1;
                }).length;
                let rsVice = _.filter(rsOfficials, function(o) { 
                    return o.POSITION_NAM.EtoLowerCase().indexOf('city vice-mayor') !== -1 ||
                           o.POSITION_NAME.toLowerCase().indexOf('municipal vice-mayor'); 
                }).length;
                return 'Members: ' + rsMembers + ', Vice: ' + rsVice + ', Mayor: ' + rsMayors;
            },
            showInvolvedPerson(lgu){
                let self = this;
                self.modalOfficials = [];
                $('#modal-current-officials').modal('show');
                let rsProvince = _.filter(self.provinces, {id: lgu.province_id});
                self.$http.post('/get_officials_by_citymun',{
                    lgu: lgu,
                    province: rsProvince[0]
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        json = _.uniqBy(json, function (e) {
                            return e.LAST_NAME && e.FIRST_NAME;
                        });
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.modalOfficials.push(json[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });           
            },
            
            removeDuplicates(rs){
                let self = this;
                return _.uniqBy(rs, function (e) {
                  return e.LAST_NAME && e.FIRST_NAME;
                });
            },
            getTotalApproved(lgu){
                let self = this;
                let rsProvince = _.filter(self.provinces, {id: lgu.province_id});
                if (rsProvince.length) {
                    let province = _.first(rsProvince);
                    let rs = self.officials.filter(function(official){
                        return official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase() &&
                               official.CITYMUN.trim().toUpperCase()  === lgu.name.trim().toUpperCase();
                    });
                    rs = self.removeDuplicates(rs);
                    return _.filter(rs, {STATUS: 'approved'}).length;
                }
            },
            
            getTotalDrafted(lgu){
                let self = this;
                let rsProvince = _.filter(self.provinces, {id: lgu.province_id});
                if (rsProvince.length) {
                    let province = _.first(rsProvince);
                    let rs = self.officials.filter(function(official){
                        return official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase() &&
                               official.CITYMUN.trim().toUpperCase()  === lgu.name.trim().toUpperCase();
                    });
                    rs = self.removeDuplicates(rs);
                    let rsDrafted = _.filter(rs, {STATUS: 'draft'});
                    if (rsDrafted.length > 0) {
                        return rsDrafted.length;
                    }
                }
            },
            getPercentage(lgu){
                let self = this;
                let rsProvince = _.filter(self.provinces, {id: lgu.province_id});
                if (rsProvince.length) {
                    let province = _.first(rsProvince);
                    let rs = self.officials.filter(function(official){
                        return official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase() &&
                               official.CITYMUN.trim().toUpperCase()  === lgu.name.trim().toUpperCase() &&
                               official.STATUS === 'approved';
                    });
                    rs = self.removeDuplicates(rs);
                    let city = self.analyzeIfCityOrNot(lgu.name);
                    if (city == ' 12') {
                        let percentage = (rs.length /12) * 100;
                        if (percentage >= 101) {
                            return 100;
                        }else {
                            return percentage;
                        }
                    }else {
                       let percentage = (rs.length /10) * 100;
                        if (percentage >= 101) {
                            return 100;
                        }else {
                            return percentage;
                        }
                    }
                }
            },
            getTotalOfficials(lgu){
                let self = this;
                let rsProvince = _.filter(self.provinces, {id: lgu.province_id});
                if (rsProvince.length) {
                    let province = _.first(rsProvince);
                    let rs = self.officials.filter(function(official){
                        return official.PROVINCE.trim().toUpperCase() === province.name.trim().toUpperCase() &&
                               official.CITYMUN.trim().toUpperCase()  === lgu.name.trim().toUpperCase();
                    });
                    rs = self.removeDuplicates(rs);
                    return rs.length + ' of ' + self.analyzeIfCityOrNot(lgu.name);
                }
            },
            analyzeIfCityOrNot(city){
                let self = this;
                let str = '';
                str = city.toLowerCase();
                if (str.search('city') != -1) {
                    return ' 12';
                } else {
                    return ' 10';
                }
            },
            initDataTables(){
                setTimeout(function(){
                    $('#table-reports').DataTable({
                        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                    });
                }, 2000);
            },
            fetchOfficials(){
                let self = this;
                self.$http.get('/fetch_all_officials').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.officials.push(json[i])
                        }
                        self.initDataTables();
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
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.citymuns.push(json[i]);
                        }
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        console.log(resp);
                    }
                });
            },
            searchQueryCitYMun(){
                let self = this;
                let headers = {
                    province: 0,
                    citymun: self.citymun,
                    search: ''
                };
                self.$http.post('/search_query_citymun', headers).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.officials = json;
                    };
                }, (resp) => {
                    if (resp.status === 422) {
                        console.log(resp);
                    }
                });
            }
        },
        watch: {
            'citymun': function(newVal){
                let self = this;
                if (newVal == 0) {
                    self.fetchOfficials();
                }else {
                    self.searchQueryCitYMun();
                }
                
            },
            'officials': function(newOfficials){
                 let self = this;
                 // newOfficials.forEach(function(model){
                 //    if (model.PROVINCE === 'LEYTE') {
                 //        if (model.CITYMUN.trim() === 'SAN ISIDRO') {
                 //            // console.log('san isidro')
                 //        }
                 //    }
                 // })
            }
        }
    }
</script>
