<template>

    <b-container fluid>

        <b-card>


            <b-row>

                <b-col md="6" class="my-1">

                    <label>Compare From
                        <small style="color:red;">(required)</small>
                    </label>
                    <VueCtkDateTimePicker v-model="compareFromRange" :range="true" :format="'YYYY-MM-DD'"
                                          :formatted="'ll'"/>

                </b-col>

                <b-col md="6" class="my-1">

                    <label>Compare To</label>
                    <VueCtkDateTimePicker v-model="compareToRange" :format="'YYYY-MM-DD'" :range="true"
                                          :formatted="'ll'"/>

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
                    <b-form-group label-cols-horizontal label="Sort by" class="mb-0">
                        <b-input-group>
                            <b-form-select v-model="paramFilter.sort_order" :options="sortOrderOptions"
                                           slot="prepend">
                                <option slot="first" :value="null">-- none --</option>
                            </b-form-select>
                            <b-form-select v-model="paramFilter.sort_by" :options="sortOptions">
                                <option slot="first" :value="null">-- none --</option>
                            </b-form-select>
                        </b-input-group>
                    </b-form-group>
                </b-col>


                <b-col md="6" class="my-1">
                    <b-form-group label-cols-horizontal label="ID Search" class="mb-0">
                        <b-form-input v-model="paramFilter.path_md5" placeholder="Search Page ID"/>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row class="mx-auto">
                <div>
                    <button type="button" class="btn btn-success btn-sm" @click.stop="searchNow()"
                            :disabled="searchButtonDisabled">Search
                    </button>
                </div>

            </b-row>
        </b-card>
        <br>
        <b-card>
            <b-row>
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

                <b-col md="6" class="my-1">
                    <b-form-group label-cols-horizontal class="mb-0">
                        <b-form-select :options="pageOptions" v-model="perPage"/>
                    </b-form-group>
                </b-col>
            </b-row>
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

                {{row.item.ctr_benchmark}}%
                <span v-if="row.item.ctr_difference>0"
                      class="badge badge-pill badge-success">↑{{ Number( row.item.ctr_difference).toFixed(2)}}%</span>
                <span v-if="row.item.avg_ctr*100 - row.item.ctr_benchmark <0"
                      class="badge badge-pill badge-danger">↓{{ Number( row.item.ctr_difference).toFixed(2)}}%</span>
                <div>
                    <b-button v-b-popover.hover="'Edit'" variant="info" size="sm"
                              @click.stop="openCtrModal(row.item, row.index, $event.target)">
                        <i class="fa fa-edit"></i>
                    </b-button>
                </div>
            </template>
            <template slot="keyword" slot-scope="row">


                <div v-if="row.item.is_primary == true">
                    {{row.value}}
                    <b-badge
                            variant="success">Primary
                    </b-badge>
                    <div>
                        <b-button v-b-popover.hover="'Remove Primary'" variant="info" size="sm"
                                  @click.stop="setPrimaryAlert(row.item, row.index, $event.target,false)"><i
                                class="fa fa-times"></i>
                        </b-button>
                    </div>

                </div>

                <div v-else> {{row.value}}
                    <div>
                        <b-button v-b-popover.hover="'Set Primary'" variant="info" size="sm"
                                  @click.stop="setPrimaryAlert(row.item, row.index, $event.target,true)"><i
                                class="fa fa-check"></i>
                        </b-button>
                    </div>
                </div>


            </template>


            <template slot="click_potential" slot-scope="row">
                {{row.item.click_potential}}
                <div>
                    <b-button v-b-popover.hover="'Edit'" variant="info" size="sm"
                              @click.stop="openClickModal(row.item, row.index, $event.target)">
                        <i class="fa fa-edit"></i>
                    </b-button>
                </div>
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
                <span v-if="row.item.trend && row.item.trend.positions_trend >0" class="badge badge-pill badge-success">↑{{row.item.trend.positions_trend}}</span>
                <span v-if="row.item.trend && row.item.trend.positions_trend <0" class="badge badge-pill badge-danger">↓{{row.item.trend.positions_trend}}</span>
            </template>


            <template slot="avg_ctr" slot-scope="row">
                {{Number(row.value * 100).toFixed(2)}}%
                <span v-if="row.item.trend && row.item.trend.ctr_trend >0" class="badge badge-pill badge-success">↑{{row.item.trend.ctr_trend}}%</span>
                <span v-if="row.item.trend && row.item.trend.ctr_trend <0" class="badge badge-pill badge-danger">↓{{row.item.trend.ctr_trend}}%</span>
                <span v-if="row.item.trend && row.item.trend.ctr_trend ==='∞'"
                      class="badge badge-pill badge-warning">∞</span>
            </template>


            <template slot="actions" slot-scope="row">
                <b-button-group>
                    <b-button variant="primary" size="sm"
                              @click.stop="openViewUrlModal(row.item, row.index, $event.target)">
                        View URL
                    </b-button>
                    <b-button variant="info" size="sm"
                              @click.stop="viewAllKeyword(row.item, row.index, $event.target)">
                        View Keywords
                    </b-button>

                </b-button-group>

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


        <!-- Modal Component -->
        <b-modal
                id="modalPrevent"
                ref="modal"
                title="Update Click Potential"
        >
            <b-form @submit="updateClickPotential">
                <b-form-group label-cols-horizontal
                              :label-cols="2"
                              label="Click Potential"
                              label-for="meta_title">
                    <b-form-input v-model="clickPotentialForm.click" type="number"
                                  placeholder="Enter your click potential"/>
                </b-form-group>
                <b-button type="submit" variant="primary" class="float-right">Submit
                </b-button>
            </b-form>
            <div slot="modal-footer">
            </div>
        </b-modal>


        <!-- Modal Component -->
        <b-modal
                id="ctrmodal"
                ref="ctrModal"
                title="Update CTR Benchmark"
        >
            <b-form @submit="updateCtrBenchmark">
                <b-form-group label-cols-horizontal
                              :label-cols="2"
                              label="CTR Benchmark"
                              label-for="meta_title">
                    <b-form-input v-model="ctrBenchmarkForm.benchmark" type="text"
                                  placeholder="Enter your click potential"/>
                </b-form-group>
                <b-button type="submit" variant="primary" class="float-right">Submit
                </b-button>
            </b-form>
            <div slot="modal-footer">
            </div>
        </b-modal>


        <!-- Modal Component -->
        <b-modal
                id="vieUrlModal"
                ref="urlModal"
                title="View URL"
                size="lg"
        >

            <draft-data-simple-list :pathMd5="pathMd5"></draft-data-simple-list>
        </b-modal>
    </b-container>


</template>

<script>
    export default {
        data() {
            return {
                searchButtonDisabled: false,
                itemTo: 0,
                itemTotal: 0,
                pathMd5: null,
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
                    is_primary: '',
                    path_md5:''
                },
                fields: [
                    // {key: 'id', label: 'ID', class: 'id-table-wrap',sortable: true},
                    {key: 'device_name', label: 'Device', 'class': 'device-wrap'},
                    {key: 'page', label: 'Path', 'class': 'path-table-wrap'},
                    {key: 'meta.current_data.meta.defaults.title', label: 'Title', 'class': 'path-table-wrap'},
                    {
                        key: 'meta.current_data.meta.defaults.description',
                        label: 'Description',
                        'class': 'desc-wrap'
                    },
                    {key: 'page', label: 'Path', 'class': 'path-table-wrap'},
                    {key: 'keyword', label: 'Keyword', 'class': 'keywords-table-wrap'},
                    {key: 'sum_clicks', label: 'Total Clicks', 'class': 'path-table-wrap'},
                    {key: 'avg_positions', label: 'Avg. Rank Position (AU)', 'class': 'status-wrap'},
                    {key: 'avg_ctr', label: 'CTR', 'class': 'date-wrap'},
                    {key: 'ctr_benchmark', label: 'CTR Benchmark', 'class': 'path-table-wrap'},
                    {key: 'click_potential', label: 'Click Potential', 'class': 'path-table-wrap'},
                    {key: 'actions', label: 'Action(s)', 'class': 'path-table-wrap'}
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
                deviceOptions: ['desktop', 'mobile', 'tablet'],
                sortOptions: ['ctr_difference', 'click_potential', 'ctr_benchmark'],
                sortOrderOptions: ['asc', 'desc'],
                selectedItemIndex: null,
                isFirstInit: false
            }
        },
        props: [
            'externalKeyword'
        ],
        created() {
            this.isFirstInit = true;
            // this.compareFromRange.start = moment().subtract(28, 'days').format('YYYY-MM-DD HH:MM:SS');
            // this.compareFromRange.end = moment().format('YYYY-MM-DD HH:MM:SS');
            //
            // this.compareToRange.start = moment().subtract(57, 'days').format('YYYY-MM-DD HH:MM:SS');
            // this.compareToRange.end = moment().subtract(29, 'days').format('YYYY-MM-DD HH:MM:SS');
        },
        computed: {},
        watch: {
            externalKeyword(newValue, OldValue) {
                this.paramFilter.url = newValue;
                this.paramFilter.url_filter = 'contains';
                this.$refs.table.refresh();
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
            compareCtrBenchmark(newValue, oldValue) {
                return Math.round((Math.abs(newValue - oldValue) / oldValue * (newValue - oldValue)) * 100, 2);
            },
            setPrimaryAlert(item, index, button, isPrimary) {

                if (!item.keyword || !item.page) {
                    alert('This record does not have proper keyword or page mapping in system database.')
                    return;
                }

                if (confirm('Are you sure you want to set it as primary?')) {
                    let app = this;
                    let loader = this.$loading.show();
                    axios.put('/keywords/web/pages/' + item.page_id + '/keywords/' + item.keyword_id + '/primary', {is_primary: isPrimary}).then(function (resp) {
                        app.items[index].profile = resp.data;
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
            openCtrModal(item, index, button) {
                if (!item.keyword || !item.page) {
                    alert('This record does not have proper keyword or page mapping in system database.')
                    return;
                }
                this.$refs.ctrModal.show();
                this.selectedItem = item;
                this.selectedItemIndex = index;
                this.ctrBenchmarkForm.benchmark = item.ctr_benchmark;
            },

            openViewUrlModal(item, index, button) {
                this.$refs.urlModal.show();
                this.pathMd5 = item.path_md5;
            },
            openClickModal(item, index, button) {
                if (!item.keyword || !item.page) {
                    alert('This record does not have proper keyword or page mapping in system database.')
                    return;
                }
                this.$refs.modal.show();
                this.selectedItem = item;
                this.selectedItemIndex = index;
                this.clickPotentialForm.click = item.click_potential;
            },
            updateCtrBenchmark(evt) {
                evt.preventDefault();
                let app = this;
                let loader = this.$loading.show();
                axios.put('/keywords/web/pages/' + this.selectedItem.page_id + '/keywords/' + this.selectedItem.keyword_id + '/benchmark', this.ctrBenchmarkForm).then(function (resp) {

                    app.items[app.selectedItemIndex].ctr_difference = app.items[app.selectedItemIndex].avg_ctr * 100 - resp.data.ctr_benchmark;
                    app.items[app.selectedItemIndex].ctr_benchmark = resp.data.ctr_benchmark;
                    console.log(app.items[app.selectedItemIndex]);
                    app.$notify({
                        type: 'success',
                        title: 'SUCCESS',
                        text: 'Data updated'
                    });
                    app.$refs.ctrModal.hide();
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
            },
            updateClickPotential(evt) {
                evt.preventDefault();

                let app = this;
                let loader = this.$loading.show();

                axios.put('/keywords/web/pages/' + this.selectedItem.page_id + '/keywords/' + this.selectedItem.keyword_id + '/click', this.clickPotentialForm).then(function (resp) {
                    app.items[app.selectedItemIndex].click_potential = resp.data.click_potential;
                    app.$notify({
                        type: 'success',
                        title: 'SUCCESS',
                        text: 'Data updated'
                    });
                    app.$refs.modal.hide();
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
            },
            searchPrimary($event) {
                this.searchNow();
            },
            searchNow() {
                this.isFirstInit = false;
                this.currentPage = 1;
                this.$refs.table.refresh();
            },
            viewAllKeyword(item, index, button) {
                this.pathMd5 = item.path_md5;
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
                    path_md5: item.path_md5,
                    is_primary: ''
                };
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
                    is_primary: ''
                };
                this.searchNow();
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
                if (app.isFirstInit === true) {
                    return;
                }
                let promise = axios.get('keywords/web/keywords', {
                    params: {
                        page: this.currentPage,
                        per_page: this.perPage,
                        url: this.paramFilter.url,
                        url_filter: this.paramFilter.url_filter,
                        keyword: this.paramFilter.keyword,
                        keyword_filter: this.paramFilter.keyword_filter,
                        device: this.paramFilter.device,
                        sort_by: this.paramFilter.sort_by,
                        sort_order: this.paramFilter.sort_order,
                        a_date_from: this.compareFromRange.start,
                        a_date_to: this.compareFromRange.end,
                        b_date_from: this.compareToRange.start,
                        b_date_to: this.compareToRange.end,
                        path_md5: this.paramFilter.path_md5,
                        is_primary: this.paramFilter.is_primary
                    }
                });
                app.searchButtonDisabled = true;
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
                    app.searchButtonDisabled = false;
                    for (let i = 1; i <= resp.data.last_page; i++) {
                        app.currentPageOptions.push(i)
                    }
                    app.$notify({
                        type: 'success',
                        title: 'SUCCESS',
                        text: 'Keywords retrieved'
                    });
                    // Must return an array of items or an empty array if an error occurred
                    return (app.items || [])
                }).catch(function (error) {
                    console.log(error.response);
                    app.searchButtonDisabled = false;
                    if (error.response.data && error.response.data.warning) {
                        app.$notify({
                            type: 'warn',
                            title: 'Warning',
                            text: error.response.data.message,
                            duration: -1
                        });
                    } else {
                        app.$notify({
                            type: 'error',
                            title: 'ERROR',
                            text: error.response.data && error.response.data.message ? error.response.data.message : 'Cannot retrieve data, please contact system manager.',
                            duration: -1
                        });
                    }

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
        width: 15%;
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

    .device-wrap {
        word-break: break-word;
        width: 2%;
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