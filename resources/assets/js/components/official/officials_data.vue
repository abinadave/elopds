<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetch();
        },
        data(){
            return {
                skip: 100,
                take: 100
            }
        },
        methods: {
            fetch(){
                let self = this;
                let resource = self.$resource('official/skip/take{/}<>')
                self.$http.post('/official/skip/take', {
                    skip: self.skip,
                    take: self.take
                }).then(() => {

                }, () => {

                });
            }
        }
    }
</script>
