<template>
            <div :class="tabClass">
                <div class="panel panel-primary">
                    <div class="panel-heading">ELOPDS Report </div>
                    
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                          <li class="active"><a @click="tabClass = 'col-md-12'" data-toggle="tab" href="#home">Report by LGU</a></li>
                          <li><a @click="tabClass = 'col-md-10 col-md-offset-0'" data-toggle="tab" href="#menu1">Completed</a></li>
                          <li><a data-toggle="tab" href="#menu2">No Reports</a></li>
                        </ul>

                        <div class="tab-content">
                          <div id="home" class="tab-pane fade in active">
                            <label style="padding: 10px">Select CITY/MUN
                                <select  class="form-control" v-model="citymun">
                                    <option :value="0">All</option>
                                    <option v-for="city in citymuns">
                                        {{ city.CITYMUN }}
                                    </option>
                                </select>
                            </label>
                            <table class="table table-hover table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>CITY/MUNICIPALITY</th>
                                        <th>OFFICIALS</th>
                                        <th>PERCENTAGE</th>
                                        <!-- <th>SUMMARY</th> -->
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lgu in citymuns" v-show="lgu.CITYMUN !== '' && citymun == 0">
                                        <td>{{ lgu.CITYMUN }}</td>
                                        <td><b>{{ getOfficialCount(lgu) }}</b> / 12</td>
                                        <td><b>{{ getPercentage(lgu) }}</b></td> 
                                        <!-- <td>{{ getSumary(lgu) }}</td> -->
                                        <td><i style="cursor:pointer" @click="showInvolvedPerson(lgu)" class="fa fa-folder-open"></i></td>
                                    </tr>
                                    <tr v-for="lgu in citymuns" v-show="getOfficialCount(lgu) > 0 && citymun !== 0">
                                        <td>{{ lgu.CITYMUN }}</td>
                                        <td><b>{{ getOfficialCount(lgu) }}</b> / 12</td>
                                        <td><b>{{ getPercentage(lgu) }}</b></td> 
                                        <!-- <td>{{ getSumary(lgu) }}</td> -->
                                        <td><i style="cursor:pointer" @click="showInvolvedPerson(lgu)" class="fa fa-folder-open"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>
                          <div id="menu1" class="tab-pane fade">
                              <completed-report :citymuns="citymuns" :officials="officials" ></completed-report>
                          </div>
                          <div id="menu2" class="tab-pane fade">
                              <h3>Menu 2</h3>
                              <p>Some content in menu 2.</p>
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
    import CompCompletedReport from './completed/completed_inputs.vue'
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetchOfficials();
            this.fetchCityMuns();
        },
        components: {
            'modal-involved-officials': CompModalOfficials,
            'completed-report': CompCompletedReport
        },
        data(){
            return {
                officials: [],
                citymuns: [],citymun: 0,
                shouldBeTheLength: 12,
                modalOfficials: [],
                tabClass: 'col-md-12'
            }
        },
        methods: {
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
            'officials': function(){
                 $(function() {
                    let currentClass = '';
                    let loading = 'fa fa-spinner fa-pulse fa-fw';
                    let folder = 'fa fa-folder-open';
                    // $('td i').click(function(event) {
                    //     if ($(this).hasClass('fa-folder-open')) {
                    //         $(this).removeClass();
                    //         $(this).addClass(loading);
                    //     }else {
                    //         $(this).removeClass();
                    //         $(this).addClass(folder);
                    //     }
                    // });
                });
            }
        }
    }
</script>
