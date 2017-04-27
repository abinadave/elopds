<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>
                    <div class="panel-body">
                        <form name="yourformname">
                            <input name="excel_file" type="file">
                            <button class="btn btn-xs btn-info" @click.prevent="uploadTemporaryFiles">Import officials</button>
                        </form>
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
        },
        methods: {
            uploadTemporaryFiles(){
                // let self = this;
                var form = document.forms.namedItem("yourformname"); // high importance!, here you need change "yourformname" with the name of your form
                var formdata = new FormData(form); // high importance!
                $.ajax({
                    async: true,
                    type: "POST",
                    dataType: "json", // or html if you want...
                    contentType: false, // high importance!
                    url: 'fileuploader', // you need change it.
                    data: formdata, // high importance!
                    processData: false, // high importance!
                    success: function (data) {
                        //do thing with data....
                        if (data.filename != '') {
                            alert('success')
                        }
                    },
                    timeout: 10000
                });
            },
        }
    }
</script>
