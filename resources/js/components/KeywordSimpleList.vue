<template>

    <b-container fluid>


        <div v-if="checkHasData === true">
            <!-- Using modifiers -->
            <b-button v-b-toggle.collapse2 class="m-1" variant="primary">Search Filters</b-button>

            <!-- Element to collapse -->
            <b-collapse id="collapse2">
                <b-card>


                    <b-row>

                        <b-col md="6" class="my-1">

                            <label>Compare From</label>
                            <VueCtkDateTimePicker v-model="compareFromRange" :range="true" :formatted="'ll'"/>

                        </b-col>


                        <b-col md="6" class="my-1">

                            <label>Compare To</label>
                            <VueCtkDateTimePicker v-model="compareToRange" :range="true" :formatted="'ll'"/>

                        </b-col>

                        <b-col md="6" class="my-1">

                            <b-form-group label-cols-horizontal label="URL Search" class="mb-0">
                                <b-input-group>
                                    <b-form-select v-model="paramFilter.url_filter" :options="compareFilterList"
                                                   slot="prepend"></b-form-select>
                                    <b-form-input v-model="paramFilter.url" placeholder="Type to Search"/>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col md="6" class="my-1">
                            <b-form-group label-cols-horizontal label="keyword Search" class="mb-0">
                                <b-input-group>
                                    <b-form-select v-model="paramFilter.keyword_filter" :options="compareFilterList"
                                                   slot="prepend"></b-form-select>
                                    <b-form-input v-model="paramFilter.keyword" placeholder="Type to Search"/>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col md="6" class="my-1">
                            <b-form-group label-cols-horizontal label="Device" class="mb-0">
                                <b-input-group>
                                    <b-form-select v-model="paramFilter.device" :options="deviceOptions">
                                        <option slot="first" :value="null">-- none --</option>
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col md="6" class="my-1">
                            <b-form-group label-cols-horizontal label="Per page" class="mb-0">
                                <b-form-select :options="pageOptions" v-model="perPage"/>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row class="mx-auto">
                        <div>
                            <button type="button" class="btn btn-success btn-sm" @click.stop="searchNow()">Search
                            </button>
                            <button type="button" class="btn btn-dark btn-sm" @click.stop="resetNow()">Reset</button>
                        </div>

                    </b-row>
                </b-card>

            </b-collapse>
            <br>
            <b-card>
                <b-col md="6" class="my-1">
                    <b-form-checkbox
                            v-model="paramFilter.is_primary"
                            value="1"
                            unchecked-value="0"
                            @change="searchPrimary($event)"
                    >
                        Only Primary
                    </b-form-checkbox>
                </b-col>
            </b-card>
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
                    <div class="float-right">
                        <b-form-group label-cols-horizontal class="mb-0 mr-3">
                            <b-input-group>
                                <b-form-select :options="currentPageOptions" v-model="currentPage"
                                               slot="append"/>
                            </b-input-group>
                        </b-form-group>
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

                <template slot="ctr_benchmark" slot-scope="row">
                    <div v-if="row.item.profile">
                        {{row.item.profile.ctr_benchmark}}%
                        <span v-if="row.item.avg_ctr*100 - row.item.profile.ctr_benchmark >0"
                              class="badge badge-pill badge-success">↑{{ Number(row.item.avg_ctr*100 - row.item.profile.ctr_benchmark).toFixed(2)}}%</span>
                        <span v-if="row.item.avg_ctr*100 - row.item.profile.ctr_benchmark <0"
                              class="badge badge-pill badge-danger">↓{{ Number(row.item.avg_ctr*100 - row.item.profile.ctr_benchmark).toFixed(2)}}%</span>

                    </div>
                </template>
                <template slot="keyword" slot-scope="row">

                    <div v-if="row.item.profile&&row.item.profile.is_primary == true">
                        {{row.value}}
                        <b-badge
                                variant="success">Primary
                        </b-badge>
                    </div>

                    <div v-else> {{row.value}}

                    </div>


                </template>


                <template slot="click_potential" slot-scope="row">
                    {{row.item.profile ? row.item.profile.click_potential : ''}}

                </template>


                <!--<template slot="ctr_benchmark" slot-scope="row">-->
                <!--{{row.value}}-->
                <!--<div>-->
                <!--222-->
                <!--</div>-->
                <!--</template>-->


                <template slot="page" slot-scope="row">
                    <a :href="row.value" target="_blank">{{row.value}}</a>
                </template>


                <template slot="avg_positions" slot-scope="row">
                    {{Number(row.value).toFixed(2)}}
                    <span v-if="row.item.trend && row.item.trend.positions_trend >0"
                          class="badge badge-pill badge-success">↑{{row.item.trend.positions_trend}}</span>
                    <span v-if="row.item.trend && row.item.trend.positions_trend <0"
                          class="badge badge-pill badge-danger">↓{{row.item.trend.positions_trend}}</span>
                </template>


                <template slot="avg_ctr" slot-scope="row">
                    {{Number(row.value * 100).toFixed(2)}}%
                    <span v-if="row.item.trend && row.item.trend.ctr_trend >0" class="badge badge-pill badge-success">↑{{row.item.trend.ctr_trend}}%</span>
                    <span v-if="row.item.trend && row.item.trend.ctr_trend <0" class="badge badge-pill badge-danger">↓{{row.item.trend.ctr_trend}}%</span>
                    <span v-if="row.item.trend && row.item.trend.ctr_trend ==='∞'"
                          class="badge badge-pill badge-warning">∞</span>
                </template>

            </b-table>


            <b-row class="float-right">
                <div class="my-1">
                    <b-form-group label-cols-horizontal class="mb-0">
                        <b-input-group>
                            <b-form-select :options="currentPageOptions" v-model="currentPage"
                                           slot="append"/>
                        </b-input-group>
                    </b-form-group>
                </div>
                <b-col md="6" class="my-1">
                    <b-pagination :total-rows="totalRows" :per-page="perPage"
                                  v-model="currentPage"
                                  class="my-0"/>
                </b-col>
            </b-row>
        </div>


        <div v-else>
            No data can be found from Google Search Console.
        </div>

    </b-container>


</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                itemTo: 0,
                itemTotal: 0,
                checkHasData: false,
                externalUrl: null,
                clickPotentialForm: {
                    click: '',
                },
                ctrBenchmarkForm: {
                    benchmark: ''
                },
                curTime: null,
                profileMaps: {},
                compareFromRange: {start: '', end: ''},
                compareToRange: {start: '', end: ''},
                descriptionMaxLength: 160,
                titleMaxLength: 55,
                selected: 0,
                selectedStatus: 0,
                options: [
                    {text: 'All', value: 0},
                    {text: '<b class="text-primary">Draft Only</b>', value: 2},
                ],
                items: [],
                compareFilterList: [
                    {
                        text: 'contains',
                        value: 'contains'
                    },
                    {
                        text: 'does not contain',
                        value: 'does_not_contain'
                    },
                    {
                        text: 'is exactly',
                        value: 'is_exactly'
                    },
                ],
                filter: null,
                paramFilter: {
                    url: '',
                    url_filter: 'contains',
                    keyword: '',
                    keyword_filter: 'contains',
                    device: '',
                    sort_by: '',
                    sort_order: '',
                    is_primary:''
                },
                fields: [
                    // {key: 'id', label: 'ID', class: 'id-table-wrap',sortable: true},

                    {key: 'page', label: 'Path', 'class': 'path-table-wrap'},
                    {key: 'keyword', label: 'Keyword', 'class': 'keywords-table-wrap'},
                    {key: 'avg_positions', label: 'Avg. Rank Position (AU)', sortable: true, 'class': 'status-wrap'},
                    {key: 'avg_ctr', label: 'CTR', sortable: true, 'class': 'date-wrap'},
                    {key: 'sum_clicks', sortable: true, label: 'Total Clicks', 'class': 'path-table-wrap'},
                    {key: 'ctr_benchmark', label: 'CTR Benchmark', 'class': 'path-table-wrap'},
                    {key: 'click_potential', label: 'Click Potential', 'class': 'path-table-wrap'},
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
                perPage: 5,
                totalRows: 0,
                pageOptions: [5, 50, 100, 200, 500],
                modalInfo: {title: '', content: ''},
                sortBy: 'sum_clicks',
                sortDesc: true,
                sortDirection: 'desc',
                deviceOptions: ['desktop', 'mobile', 'tablet']
            }
        },
        props: [
            'pathMd5'
        ],
        created() {
            this.compareFromRange.start = moment().subtract(28, 'days').format('YYYY-MM-DD HH:MM:SS');
            this.compareFromRange.end = moment().format('YYYY-MM-DD HH:MM:SS');

            this.compareToRange.start = moment().subtract(57, 'days').format('YYYY-MM-DD HH:MM:SS');
            this.compareToRange.end = moment().subtract(29, 'days').format('YYYY-MM-DD HH:MM:SS');
        },
        computed: {},
        watch: {
            pathMd5(newValue, OldValue) {
                console.log('view keyword changed');
                this.checkMd5();
            },
            compareFromRange(newValue, OldValue) {
                console.log(newValue);
            },
            selected(newValue, OldValue) {
                this.$refs.table.refresh();
            },
            selectedStatus(newValue, OldValue) {
                this.$refs.table.refresh();
            }
        },
        methods: {
            checkMd5() {
                let app = this;
                axios.get('/keywords/web/keywords/' + this.pathMd5 + '/md5').then(function (resp) {

                    if (resp.data.success === true) {
                        app.checkHasData = true;
                        app.$refs.table.refresh();
                    } else {
                        app.checkHasData = false;
                    }

                }).catch(function (resp) {
                });
            },
            compareCtrBenchmark(newValue, oldValue) {
                return Math.round((Math.abs(newValue - oldValue) / oldValue * (newValue - oldValue)) * 100, 2);
            },
            searchNow() {
                this.currentPage = 1;
                this.$refs.table.refresh();
            },
            searchPrimary($event){
                this.searchNow();
            },
            resetNow() {
                this.paramFilter = {
                    url: '',
                    url_filter: 'contains',
                    keyword: '',
                    keyword_filter: 'contains',
                    device: '',
                    sort_by: '',
                    sort_order: '',
                    a_date_from: '',
                    a_date_to: '',
                    b_date_from: '',
                    b_date_to: '',
                    per_page: '',
                    path_md5: '',
                    is_primary:''
                };
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },

            getSortByName(value) {
                switch (value) {
                    case 'avg_positions':
                        return 'sum_positions';
                    default:
                        return value;
                }
            },
            myProvider(ctx) {
                let app = this;
                let promise = axios.get('keywords/web/keywords', {
                    params: {
                        page: this.currentPage,
                        per_page: this.perPage,
                        url: this.paramFilter.url,
                        url_filter: this.paramFilter.url_filter,
                        keyword: this.paramFilter.keyword,
                        keyword_filter: this.paramFilter.keyword_filter,
                        device: this.paramFilter.device,
                        sort_by: this.getSortByName(ctx.sortBy),
                        sort_order: ctx.sortDesc === true ? 'desc' : 'asc',
                        a_date_from: this.compareFromRange.start,
                        a_date_to: this.compareFromRange.end,
                        b_date_from: this.compareToRange.start,
                        b_date_to: this.compareToRange.end,
                        path_md5: this.pathMd5,
                        is_primary:this.paramFilter.is_primary
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
                    for (let i = 1; i <= resp.data.last_page; i++) {
                        app.currentPageOptions.push(i)
                    }

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
    .title-wrap {
        word-break: break-word;
        width: 8%;
    }

    .desc-wrap {
        word-break: break-word;
        width: 20%;
    }

    .action-wrap {
        word-break: break-word;
        width: 2%;
    }

    .date-wrap {
        word-break: break-word;
        width: 4.2%;
    }

    .status-wrap {
        word-break: break-word;
        width: 2.2%;
    }

    .nested-table-wrap {
        word-break: break-word;
        width: 2%;
    }

    .id-table-wrap {
        word-break: break-word;
        width: 0.8%;
    }

    .path-table-wrap {
        word-break: break-word;
        width: 4%;
    }

    .button-table-wrap {
        word-break: break-word;
        width: 4%;
    }

    .keywords-table-wrap {
        word-break: break-word;
        width: 3%;
    }

    .vld-overlay.is-full-page {
        z-index: 9999 !important;
    }

    .modal-lg {
        max-width: 80%;
    }
</style>