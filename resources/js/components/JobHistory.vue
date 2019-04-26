<template>

    <b-container fluid>

        <b-card>

            <b-row>
                <b-col md="12" class="my-1">

                    <small>
                        *Sometimes the google search data might not be synced entirely because of potential sync delay.
                        You might need to re-run the job again.
                        Also, use settings page to see what the latest updated date is.
                    </small>
                </b-col>
            </b-row>

            <b-row>

                <b-col md="6" class="my-1">

                    <label>Date From</label>
                    <VueCtkDateTimePicker v-model="compareFromRange" :format="'YYYY-MM-DD'" :only-date="true"/>

                </b-col>

                <b-col md="6" class="my-1">

                    <label>Date To</label>
                    <VueCtkDateTimePicker v-model="compareToRange" :format="'YYYY-MM-DD'" :only-date="true"/>

                </b-col>


                <b-col md="6" class="my-1">
                    <b-form-group label-cols-horizontal label="Status" class="mb-0">
                        <b-input-group>
                            <b-form-select v-model="paramFilter.status" :options="deviceOptions">
                            </b-form-select>
                        </b-input-group>
                    </b-form-group>
                </b-col>


            </b-row>
            <b-row class="mx-auto">
                <div>
                    <button type="button" class="btn btn-success btn-sm" @click.stop="searchNow()">Search</button>
                </div>

            </b-row>
        </b-card>
        <br>

        <b-row>
            <b-col md="4" class="my-1">
                <label>Total Items: {{itemTo}}/{{itemTotal}}</label>
            </b-col>
            <b-col md="8" class="my-1">
                <div class="float-right">
                    <b-pagination :total-rows="totalRows" :per-page="perPage"
                                  v-model="currentPage"
                                  class="my-0"/>
                </div>

            </b-col>
        </b-row>

        <b-table
                style="font-size:14px;"
                ref="table"
                responsive
                :hover="true"
                :small="true"
                :bordered="true"
                :items="myProvider"
                :filter="filter"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                @filtered="onFiltered"
        >


            <template slot="actions" slot-scope="row">
                <b-button-group>

                    <b-spinner v-if="row.item.status === 1" small variant="primary" label="Spinning"></b-spinner>

                    <b-button v-if="row.item.status !==1 &&row.item.status !==0  " variant="primary" size="sm"
                              @click.stop="reRunJob(row.item, row.index, $event.target)">
                        Re-run
                    </b-button>

                    <b-button v-if="row.item.status !==1 &&row.item.status !==0  " variant="danger" size="sm"
                              @click.stop="deleteAllData(row.item, row.index, $event.target)">
                        Delete All Data
                    </b-button>

                    <b-spinner v-if="row.item.status === 0" small label="Small Spinner" type="grow"></b-spinner>
                </b-button-group>

            </template>
        </b-table>


        <b-row class="float-right">
            <b-col md="6" class="my-1">
                <b-pagination :total-rows="totalRows" :per-page="perPage"
                              v-model="currentPage"
                              class="my-0"/>
            </b-col>
        </b-row>


    </b-container>


</template>

<script>
    export default {
        data() {
            return {
                itemTo: 0,
                itemTotal: 0,
                curTime: null,
                compareFromRange: '',
                compareToRange: '',
                selected: 0,
                selectedStatus: 0,
                items: [],
                filter: null,
                paramFilter: {
                    status: ''
                },
                fields: [
                    {key: 'id', label: 'ID'},
                    {key: 'date_from', label: 'Date From'},
                    {key: 'date_to', label: 'Date To'},
                    {key: 'status_name', label: 'Status Name'},
                    {key: 'actions', label: 'Action(s)', 'class': 'action-wrap'}
                ],
                formItem: {
                    canonical: '',
                    title: '',
                    description: '',
                    keywords: []
                },
                selectedItem: {},
                currentItem: {
                    canonical: '',
                    title: '',
                    description: '',
                    keywords: []
                },
                currentPage: 1,
                currentPageOptions: [],
                perPage: 20,
                totalRows: 0,
                pageOptions: [20, 50, 100, 200, 500],
                modalInfo: {title: '', content: ''},
                sortBy: '',
                sortDesc: true,
                sortDirection: '',
                deviceOptions: [
                    {value: null, text: 'Please select an option', disabled: true},
                    {value: null, text: 'All'},
                    {value: 0, text: 'PENDING'},
                    {value: 1, text: 'IN_PROCESSING'},
                    {value: 2, text: 'FINISHED'},
                    {value: 3, text: 'ERROR'},
                ],
                selectedItemIndex: null
            }
        },
        created() {
            // this.compareFromRange.start = moment().subtract(28, 'days').format('YYYY-MM-DD HH:MM:SS');
            // this.compareFromRange.end = moment().format('YYYY-MM-DD HH:MM:SS');
            //
            // this.compareToRange.start = moment().subtract(57, 'days').format('YYYY-MM-DD HH:MM:SS');
            // this.compareToRange.end = moment().subtract(29, 'days').format('YYYY-MM-DD HH:MM:SS');
        },
        mounted() {
            window.Echo.channel("job-completed-channel").listen(".job-completed-event", data => {
                this.$notify({
                    type: 'warn',
                    title: 'SUCCESS',
                    text: data.message,
                    group: 'job',
                    duration: -1
                });
                this.$refs.table.refresh();
            });

            EventBus.$on('newJobCreated', () => {
                this.$refs.table.refresh();
            });
        },
        computed: {},
        watch: {},
        methods: {
            searchNow() {
                this.currentPage = 1;
                this.$refs.table.refresh();
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            deleteAllData(item, index, target) {
                if (confirm('Are you sure you want to delete all data for this job?')) {
                    let app = this;
                    let loader = this.$loading.show();
                    axios.delete('job-history/web/job-histories/' + item.id).then(function (resp) {
                        console.log(resp);
                        console.log(index);
                        app.$refs.table.refresh();
                        app.$notify({
                            type: 'success',
                            title: 'SUCCESS',
                            text: 'Successfully deleted.'
                        });
                        loader.hide();
                    }).catch(function (resp) {
                        app.$notify({
                            type: 'error',
                            title: 'ERROR',
                            text: 'Cannot delete data.',
                            duration: -1
                        });
                        loader.hide();
                    });
                }
            },
            reRunJob(item, index, target) {

                if (confirm('Are you sure you want to set it as primary?')) {
                    let app = this;
                    let loader = this.$loading.show();
                    axios.put('job-history/web/job-histories/' + item.id + '/rerun').then(function (resp) {
                        console.log(resp);
                        item.status = resp.data.status;
                        item.status_name = resp.data.status_name;
                        app.$notify({
                            type: 'success',
                            title: 'SUCCESS',
                            text: 'Successfully re-running the task'
                        });
                        loader.hide();
                    }).catch(function (resp) {
                        console.log(resp);
                        app.$notify({
                            type: 'error',
                            title: 'ERROR',
                            text: 'Cannot update data, please check your data format.',
                            duration: -1
                        });
                        loader.hide();
                    });
                }
            },
            myProvider(ctx) {
                let app = this;
                let promise = axios.get('job-history/web/job-histories', {
                    params: {
                        date_from: this.compareFromRange,
                        date_to: this.compareToRange,
                        status: this.paramFilter.status
                    }
                });

                // Must return a promise that resolves to an array of items
                return promise.then((resp) => {
                    // Pluck the array of items off our axios response
                    app.items = resp.data.data;
                    ctx.currentPage = resp.data.currentPage;
                    ctx.perPage = resp.data.perPage;
                    app.totalRows = resp.data.total;
                    app.currentPageOptions = [];
                    app.itemTo = resp.data.to;
                    app.itemTotal = resp.data.total;
                    app.$notify({
                        type: 'success',
                        title: 'SUCCESS',
                        text: 'History retrieved'
                    });
                    // Must return an array of items or an empty array if an error occurred
                    return (app.items || [])
                }).catch(function (resp) {
                    console.log(resp);
                    app.$notify({
                        type: 'error',
                        title: 'ERROR',
                        text: 'Cannot retrieve data, please contact system manager.',
                        duration: -1
                    });
                });


            }
        }
    }
</script>

<style>
    .action-wrap {
        word-break: break-word;
        width: 20%;
    }
</style>