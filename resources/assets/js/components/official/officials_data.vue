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
                        <option :value="arr.PROVINCE" v-for="arr in provinces">
                             {{ arr.PROVINCE }}
                        </option>
                    </select>
                    <input type="text" class="form-control" style="width: 250px; display: inline-block; border-radius: 15px; margin-bottom: 10px" placeholder="Search for local official">
                    <table class="table table-condensed table-striped table-bordered" id="table-officials">
                        <thead>
                            <tr>
                                <th>PROVINCE</th>
                                <th>CITY / MUN</th>
                                <th>LASTNAME</th>
                                <th>FIRSTNAME</th>
                                <th>MIDDLENAME</th>
                                <th>AFFILIATE</th>
                                <th>POSITION NAME</th>
                                <th>STATUS</th>
                                <th>BIRTH DATE</th>
                                <th>SEX</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="official in officials">
                                <th>{{ official.PROVINCE }}</th>
                                <th>{{ official.CITYMUN }}</th>
                                <th>{{ official.LAST_NAME }}</th>
                                <th>{{ official.FIRST_NAME }}</th>
                                <th>{{ official.MIDDLE_NAME }}</th>
                                <th>{{ official.AFFILIATE }}</th>
                                <th>{{ official.POSITION_NAME }}</th>
                                <th>{{ official.STATUS }}</th>
                                <th>{{ getBirthDate(official.BIRTH_DATE) }}</th>
                                <th>{{ official.SEX }}</th>
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
    table th {
        text-align: center;
    }
</style>
<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetch();
            this.fetchProvince();
        },
        data(){
            return {
                skip: 100,
                take: 100,
                officials: [],
                provinces: [],
                /* criteria */
                province: 0,
                citymun: 0,
                search: ''
            }
        },
        methods: {
            getBirthDate(){
                let self = this;

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
            }
        },
        watch: {
            'province': function(newVal){
                let self = this;
                console.log(newVal);
            }
        }
    }
</script>
