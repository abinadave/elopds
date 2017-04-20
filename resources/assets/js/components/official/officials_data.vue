<template>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">ELOPDS Regional Government Officials</div>
                
                <div class="panel-body">
                    <select v-model="province" class="form-control" style="width: 300px; display: inline-block;">
                        <option :value="0">Select Province</option>
                        <option :value="arr.PROVINCE" v-for="arr in provinces">
                             {{ arr.PROVINCE }}
                        </option>
                    </select>
                    <select v-model="citymun" class="form-control" style="width: 300px; display: inline-block;">
                        <option :value="0">Select City/Mun</option>
                        <option :value="arr.CITYMUN" v-for="arr in citymuns">
                             {{ arr.CITYMUN }}
                        </option>
                    </select>
                    <input @keyup.enter="searchQuery" v-model="search" type="text" class="form-control" style="width: 250px; display: inline-block; border-radius: 15px; margin-bottom: 10px" placeholder="Search for local official">
                    <table class="table table-condensed table-striped table-bordered" id="table-officials">
                        <thead>
                            <tr>
                                <th lass="text-center">PROVINCE</th>
                                <th lass="text-center">CITY / MUN</th>
                                <th width="300">FULLNAME</th>
                                <th lass="text-center">AFFILIATE</th>
                                <th lass="text-center">POSITION NAME</th>
                                <th lass="text-center">STATUS</th>
                                <th>BIRTH DATE</th>
                                <th lass="text-center">SEX</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="official in officials">
                                <th class="text-center">{{ official.PROVINCE }}</th>
                                <th class="text-center">{{ official.CITYMUN }}</th>
                                <th>{{ official.LAST_NAME }}, {{ official.FIRST_NAME }} {{ official.MIDDLE_NAME }}</th>
                                <th class="text-center">{{ official.AFFILIATE }}</th>
                                <th class="text-center">{{ official.POSITION_NAME }}</th>
                                <th class="text-center">{{ official.STATUS }}</th>
                                <th>{{ getBirthDate(official.BIRTH_DATE) }}</th>
                                <th class="text-center">{{ official.SEX }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</template>
<style type="text/css">
    #table-officials {
        font-size: 11px;
    }
    
</style>
<script>
    import moment from 'moment'
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetch();
            this.fetchProvince();
            this.fetchCityMuns();
        },
        data(){
            return {
                skip: 100,
                take: 100,
                officials: [],
                provinces: [],
                citymuns: [],
                /* criteria */
                province: 0,
                citymun: 0,
                search: ''
            }
        },
        methods: {
            fetchCityMuns(){
                let self = this;
                self.$http.get('/citymun').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.citymuns = json;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            },
            getBirthDate(date){
                let self = this;
                let str = date.split(' ');
                if (str.length) {
                    let dob = str[0];
                    return moment(dob).format('MMMM DD, YYYY')
                }else {
                    return date;
                }
            },
            fetch(){
                let self = this;
                self.$http.post('/officials_fetch', {
                    skip: self.skip,
                    take: self.take
                }).then((resp) => {
                    let json = resp.body;
                    self.officials = json;
                }, (resp) => {
                    console.log(resp);
                });
            },
            fetchProvince(){
                let self = this;
                self.$http.get('/province').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.provinces = json;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            },
            searchQuery(){
                let self = this;
                let headers = {
                    province: self.province,
                    citymun: self.citymun,
                    search: self.search
                };
                self.$http.post('/search_query_all', headers).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.officials = json;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            },
            searchQueryProvince(){
                let self = this;
                let headers = {
                    province: self.province,
                    citymun: self.citymun,
                    search: self.search
                };
                self.$http.post('/search_query_province', headers).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.officials = json;
                        if (!json.length) {
                            alert('As of now, there is no data found in: ' + self.province + ': ' + self.citymun);
                        }
                    };
                }, (resp) => {
                    if (resp.status === 422) {
                        console.log(resp);
                    }
                });
            },
            searchQueryCitYMun(){
                let self = this;
                let headers = {
                    province: self.province,
                    citymun: self.citymun,
                    search: self.search
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
            },
            fetchCityMunsInpRovince(){
                let self = this;
                self.$http.post('/get_relared_mun', {
                    province: self.province
                }).then((resp) => {
                     if (resp.status === 200) {
                         let json = resp.body;
                         self.citymuns = json;
                     }
                 }, (resp) => {
                     if (resp.status === 422) {
                       console.log(resp)
                     }
                 }); 
            }
        },
        watch: {
            'province': function(newVal){
                let self = this;
                self.searchQueryProvince();
                self.fetchCityMunsInpRovince();
            },
            'citymun': function(newVal){
                let self = this;
                self.searchQueryCitYMun();
            }
        }
    }
</script>
