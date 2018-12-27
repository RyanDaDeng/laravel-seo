<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Schedule Settings</div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Last Updated</th>
                                <th scope="col">Timezone</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in items">
                                <th scope="row">{{item['id']}}</th>
                                <td>{{item['name']}}</td>
                                <td>{{item['description']}}</td>
                                <td>{{item['last_updated']}}</td>
                                <td>{{item['timezone']}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: []
            }
        },
        mounted() {
        },
        created() {
            this.getAllSettings();
        },
        methods: {
            getAllSettings() {
                var app = this;
                axios.get('/setting/web/all-settings').then(function (resp) {
                    app.items = resp.data;
                }).catch(function (resp) {
                    console.log(resp);
                });
            }
        }
    }
</script>
