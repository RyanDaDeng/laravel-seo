<template>

    <b-container fluid>

        <b-row>
            <b-col md="6" class="my-1">
                <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0"/>
            </b-col>
        </b-row>

        <b-table
                :items="myProvider"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                @filtered="onFiltered"
        >
            <template slot="Current vs Draft" slot-scope="data">

                <table class="table table-responsive-sm">
                    <thead>
                    <tr>

                        <th scope="col">#</th>
                        <th scope="col">Current</th>
                        <th scope="col">Draft</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Title</th>

                        <td class="table-success wrap"> {{data.item.current_data.meta.defaults.title }}</td>
                        <td class="table-danger wrap"> {{data.item.draft_data.meta.defaults.title }}</td>

                    </tr>
                    <tr>
                        <th scope="row">Description</th>
                        <td class="table-success"> {{data.item.current_data.meta.defaults.description }}</td>
                        <td class="table-danger"> {{data.item.draft_data.meta.defaults.description }}</td>

                    </tr>
                    <tr>
                        <th scope="row">Canonical</th>
                        <td class="table-success"> {{data.item.current_data.meta.defaults.canonical }}</td>
                        <td class="table-danger"> {{data.item.draft_data.meta.defaults.canonical }}</td>

                    </tr>
                    </tbody>
                </table>
            </template>
            <template slot="current_data" slot-scope="row">{{row.value.meta.defaults}}</template>
            <template slot="draft_data" slot-scope="row">{{row.value}}</template>
        </b-table>
    </b-container>
</template>

<script>

    export default {
        data() {
            return {
                items: [],
                fields: [
                    {key: 'id', label: 'ID'},
                    {key: 'path', label: 'Path'},
                    'Current vs Draft',
                    {key: 'last_approved_at', label: 'Last Approved', sortable: true}
                ],
                currentPage: 1,
                perPage: 5,
                totalRows: 0
            }
        },
        created() {
        },
        methods: {
            myProvider() {
                let app = this;

                // console.log(ctx);
                let promise = axios.get('seoagent/web/draft-data', {
                    params: {page: this.currentPage, per_page: this.perPage}
                });


                    // Must return a promise that resolves to an array of items
                    return promise.then((resp) => {
                        // Pluck the array of items off our axios response
                        app.items = resp.data.data;
                        app.currentPage = resp.data.currentPage;
                        app.perPage = resp.data.perPage;
                        app.totalRows = resp.data.total;
                        // Must return an array of items or an empty array if an error occurred
                        return (app.items || [])
                    }).catch(function (resp) {
                        console.log(resp);
                        // app.$notify({
                        //     type: 'error',
                        //     title: 'ERROR',
                        //     text: resp.response.data.message,
                        //     duration: -1
                        // });
                    });


            },
            onFiltered (filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            }
        }
    }
</script>

<style>
    .wrap {
        word-break: break-word;
    }
</style>