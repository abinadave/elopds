<template>
    <div style="margin-top: 20px">
        <!-- completed report {{ officials.length }} -->
        <div class="col-md-3 panel panel-default">
            <table class="table table-hover table-condensed" id="tbl-completed">
                <thead>
                    <tr>
                        <th>CITY/MUN</th>
                        <th style="text-align: center">OFFICIALS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="cursor: pointer" @click="showOfficials(lgu)" v-for="lgu in completedLgus" v-show="lgu.CITYMUN !== ''">
                        <td class="text-primary">{{ lgu.CITYMUN }}</td>
                        <td style="text-align: center">{{ getOfficialCount(lgu) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-9" style="overflow: auto; height: 500px">
            <table style="width: 1000px" id="tbl-completed" class="tabel table-condensed table-bordered table-hover">
                <thead>
                    <tr>
                        <th>PROVINCE</th>
                        <th>CITY/MUN</th>
                        <th>FULLNAME</th>
                        <th>POSITION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="official in foundOfficials">
                        <td>{{ official.PROVINCE }}</td>
                        <td>{{ official.CITYMUN }}</td>
                        <td><b>{{ official.LAST_NAME }},</b> {{official.FIRST_NAME }} {{official.MIDDLE_NAME }}</td>
                        <td>{{official.POSITION_NAME }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style type="text/css">
    #tbl-completed {
        font-size: 12px;
    }
</style>
<script>
    export default {
        mounted() {
            let self = this;
            console.log('Component mounted.');
        },
        props: {
            officials: {
                type: Array
            },
            citymuns: {
                type: Array
            }
        },
        data(){
            return {
                initialDelay: 1000,
                completedLgus: [],
                foundOfficials: []
            }
        },
        methods: {
            showOfficials(lgu){
                let self = this;
                let rs = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                rs = _.uniqBy(rs, function (e) {
                   return e.LAST_NAME && e.FIRST_NAME && e.MIDDLE_NAME;
                });
                self.foundOfficials = _.sortBy(rs, 'POSITION_NAME');

            },
            getOfficialCount(lgu){
                let self = this;
                let rs = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                rs = _.uniqBy(rs, function (e) {
                    return e.LAST_NAME && e.FIRST_NAME && e.MIDDLE_NAME;
                });
                // console.log(rs.length)
                return rs.length;
            },
            getCompleted(){
                let self = this;
                let rsOfficials = []
                let completedLgus = self.citymuns.filter(function(lgu) {
                    rsOfficials = _.filter(self.officials, {CITYMUN: lgu.CITYMUN});
                    // return rsOfficials.length >= 12;
                    console.log(rsOfficials.length)
                });
                self.completedLgus = completedLgus;
            }
        },
        watch: {
            'officials': function(){
                let self = this;
                setTimeout(function(){
                    self.getCompleted();
                }, self.initialDelay);
            }
        }
    }
</script>
