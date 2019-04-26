<template>

    <b-container fluid>
        <b-card v-if="!pathMd5">
            <b-row>
                <b-col md="6" class="my-1">
                    <b-form-group label-cols-horizontal label="Search" class="mb-0">
                        <b-input-group>
                            <b-form-input v-model="filter" placeholder="Type to Search"/>
                            <b-input-group-append>
                                <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
                            </b-input-group-append>
                        </b-input-group>
                        <b-form-text id="inputLiveHelp">
                            Tips: Support wild search for meta and path for both current and draft data.
                        </b-form-text>
                    </b-form-group>
                </b-col>


                <b-col md="6" class="my-1">
                    <b-form-group label-cols-horizontal label="Per page" class="mb-0">
                        <b-form-select :options="pageOptions" v-model="perPage"/>
                    </b-form-group>
                </b-col>
                <!--<b-col md="6" class="my-1">-->
                <!--<b-form-group label-cols-horizontal label="Sort" class="mb-0">-->
                <!--<b-input-group>-->
                <!--<b-form-select v-model="sortBy" :options="sortOptions">-->
                <!--<option slot="first" :value="null">&#45;&#45; none &#45;&#45;</option>-->
                <!--</b-form-select>-->
                <!--<b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">-->
                <!--<option :value="false">Asc</option>-->
                <!--<option :value="true">Desc</option>-->
                <!--</b-form-select>-->
                <!--</b-input-group>-->
                <!--</b-form-group>-->
                <!--</b-col>-->


                <b-col md="6" class="my-1">
                    <b-form-group label-cols-horizontal label="" class="mb-0">
                        <b-form-radio-group id="radios1" v-model="selected" :options="options"
                                            name="radioOpenions">
                        </b-form-radio-group>
                    </b-form-group>
                    <b-form-group label-cols-horizontal label="" class="mb-0">
                        <b-form-radio-group id="radios12" v-model="selectedStatus" :options="statusOptions"
                                            name="radioOpenions2">
                        </b-form-radio-group>
                    </b-form-group>
                </b-col>

            </b-row>
        </b-card>
        <div>
            <b-row class="float-right">
                <div class="my-1">
                    <b-form-group label-cols-horizontal class="mb-0">
                        <b-input-group>
                            <b-form-select :options="currentPageOptions" v-model="currentPage"
                                           slot="append"/>
                        </b-input-group>
                    </b-form-group>
                </div>
                <b-col md="7" class="my-1 float-right">
                    <b-pagination :total-rows="totalRows" :per-page="perPage"
                                  v-model="currentPage"
                                  class="my-0"/>
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

                <template slot="path" slot-scope="row">
                    <a :href="'https://www.inkstation.com.au/'+row.value" target="_blank">{{row.value}}</a>
                </template>
                <!--<template slot="Current vs Draft" slot-scope="data">-->

                <!--<table class="table table-responsive-sm">-->

                <!--<tbody>-->
                <!--<tr>-->
                <!--<td class="nested-table-wrap">Title</td>-->

                <!--<td class="table-success wrap"> {{data.item.current_data.meta.defaults.title }}</td>-->
                <!--<td class="table-danger wrap"> {{data.item.draft_data.meta.defaults.title }}</td>-->

                <!--</tr>-->
                <!--<tr>-->
                <!--<td class="nested-table-wrap">Description</td>-->
                <!--<td class="table-success"> {{data.item.current_data.meta.defaults.description }}</td>-->
                <!--<td class="table-danger"> {{data.item.draft_data.meta.defaults.description }}</td>-->

                <!--</tr>-->
                <!--</tbody>-->
                <!--</table>-->
                <!--</template>-->

                <template slot="row-details" slot-scope="row">
                    <b-card>


                        <table v-if="row.item.type == 2" class="table">
                            <thead>
                            <th></th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Meta Keywords</th>
                            </thead>
                            <tbody>

                            <tr>
                                <th>Current Meta</th>

                                <td>
                                    {{row.item.current_data.meta.defaults.title}}
                                </td>
                                <td style="word-break: break-word;">
                                    {{row.item.current_data.meta.defaults.description}}
                                </td>
                                <td>
                                    {{row.item.current_data.meta.defaults.keywords}}
                                </td>
                            </tr>

                            <tr>
                                <th>Draft Meta</th>
                                <td>
                                    {{row.item.draft_data.meta.defaults.title}}
                                </td>
                                <td style="word-break: break-word;">
                                    {{row.item.draft_data.meta.defaults.description}}
                                </td>
                                <td>
                                    {{row.item.draft_data.meta.defaults.keywords}}
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <hr>

                        <h6><b>History</b></h6>
                        <table class="table">
                            <thead>
                            <th>Created At</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Meta Keywords</th>
                            <th>Status</th>
                            <th>Comments</th>
                            </thead>
                            <tbody>


                            <tr v-for="item in row.item.histories">
                                <td>
                                    {{item.created_at}}
                                </td>
                                <td>
                                    {{item.data.meta.defaults.title}}
                                </td>
                                <td>
                                    {{item.data.meta.defaults.description}}
                                </td>
                                <td>
                                    {{item.data.meta.defaults.keywords}}
                                </td>
                                <td>
                                    {{item.status}}
                                </td>
                                <td>
                                    {{item.comments}}
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </b-card>
                </template>

                <template slot="title" slot-scope="data">
                    {{ data.item.type === 2 ? data.item.draft_data.meta.defaults.title :
                    data.item.current_data.meta.defaults.title }}
                </template>
                <template slot="description" slot-scope="data">
                    {{ data.item.type === 2 ? data.item.draft_data.meta.defaults.description :
                    data.item.current_data.meta.defaults.description }}
                </template>

                <template slot="keywords" slot-scope="data">
                    {{ data.item.type === 2 ? data.item.draft_data.meta.defaults.keywords :
                    data.item.current_data.meta.defaults.keywords }}
                </template>

                <template slot="last_history_status" slot-scope="data">

                    <b-badge v-if="typeMap[data.item.type]"
                             :variant="typeMap[data.item.type].value">
                        {{typeMap[data.item.type].name}}
                    </b-badge>
                    <b-badge v-if="statusMap[data.item.last_history_status]"
                             :variant="statusMap[data.item.last_history_status].value">
                        {{statusMap[data.item.last_history_status].name}}
                    </b-badge>

                </template>


                <template slot="actions" slot-scope="row">
                    <div class="text-center">
                        <b-button-group horizontal>


                            <b-button v-b-popover.hover="'Manage Draft'" size="sm"
                                      @click.stop="editForm(row.item, row.index, $event.target)"
                                      variant="success">
                                <i class="fa fa-edit"></i>
                            </b-button>


                            <b-button v-b-popover.hover="'Delete Draft'" size="sm" v-if="row.item.type === 2"
                                      @click.stop="deleteDraftData(row.item, row.index, $event.target)"
                                      variant="danger">
                                <i class="fa fa-trash"></i>
                            </b-button>

                            <b-button v-b-popover.hover="'Show Details'" size="sm" @click.stop="toggleMetaHistory(row)"
                                      variant="primary">
                                <i class="fa fa-info-circle"></i>
                            </b-button>

                            <!--<b-button v-b-popover.hover="'View keywords'" size="sm"-->
                            <!--@click.stop="viewKeywords(row.item, row.index, $event.target)"-->
                            <!--variant="info">-->
                            <!--<i class="fa fa-tag"></i>-->
                            <!--</b-button>-->

                        </b-button-group>
                    </div>


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


            <!-- Info modal -->
            <!-- Info modal -->
            <b-modal id="modalKeywordDetail" size="lg" ref="modalKeywordDetail" ok-title="Close" ok-only
                     ok-variant="secondary"
            >

                <div slot="modal-title" v-html="modalInfo.title" class="w-100">


                </div>

                <b-card>
                    <b-form @submit="handleOk">


                        <b-form-group label-cols-horizontal
                                      :label-cols="2"
                                      label="Title"
                                      label-for="meta_title">

                            <b-form-textarea
                                    placeholder="Enter something"
                                    :rows="6"
                                    :max-rows="10"
                                    v-model="formItem.title"
                                    required
                            >
                            </b-form-textarea>


                            <b-form-text v-html="titleLeft">
                            </b-form-text>

                            <b-form-text id="inputLiveHelp">
                                <!-- this is a form text block (formerly known as help block) -->
                                Current: {{currentItem.title}}
                            </b-form-text>

                        </b-form-group>


                        <b-form-group label-cols-horizontal
                                      :label-cols="2"
                                      label="Description"
                                      label-for="meta_description">
                            <b-form-textarea
                                    placeholder="Enter something"
                                    :rows="6"
                                    :max-rows="10"
                                    v-model="formItem.description"
                                    required
                            >
                            </b-form-textarea>

                            <b-form-text id="description_counter" v-html="descriptionLeft">

                            </b-form-text>


                            <b-form-text id="inputLiveHelp">
                                <!-- this is a form text block (formerly known as help block) -->
                                Current: {{currentItem.description}}
                            </b-form-text>
                        </b-form-group>


                        <b-form-group label-cols-horizontal
                                      :label-cols="2"
                                      label="Meta Keywords"
                                      label-for="meta_keywords">

                            <tags-input element-id="tags"
                                        v-model="formItem.keywords"></tags-input>
                            <b-form-text id="inputLiveHelp">
                                Use Enter on your keyboard to enter a new tag.
                            </b-form-text>
                            <b-form-text id="inputLiveHelp">
                                <!-- this is a form text block (formerly known as help block) -->
                                Current: {{currentItem.keywords}}
                            </b-form-text>
                        </b-form-group>
                        <b-button type="submit" variant="primary" class="float-right">Submit
                        </b-button>

                    </b-form>
                </b-card>
                <br>
                <div slot="modal-footer">

                </div>

            </b-modal>
        </div>

    </b-container>
</template>

<script>

    export default {
        data() {
            return {
                externalPathMd5: null,
                descriptionMaxLength: 160,
                titleMaxLength: 55,
                selected: 0,
                selectedStatus: 0,
                statusMap: {
                    2: {
                        value: 'danger',
                        name: 'Recent Rejected'
                    },
                    1: {
                        value: 'success',
                        name: 'Recent Approved'
                    },
                },
                typeMap: {
                    2: {
                        value: 'primary',
                        name: 'In Draft'
                    },
                    1: {
                        value: 'warning',
                        name: 'New'
                    }
                },
                options: [
                    {text: 'All', value: 0},
                    {text: '<b class="text-primary">Draft Only</b>', value: 2},
                ],
                statusOptions: [
                    {text: 'All', value: 0},
                    {text: '<b class="text-success">Recent Approved</b>', value: 1},
                    {text: '<b class="text-danger">Recent Rejected</b>', value: 2},
                ],
                items: [],
                filter: null,
                fields: [
                    // {key: 'id', label: 'ID', class: 'id-table-wrap',sortable: true},
                    {key: 'path', label: 'Path', 'class': 'path-table-wrap'},
                    // 'Current vs Draft',
                    {key: 'title', label: 'Title', 'class': 'title-wrap'},
                    {key: 'description', label: 'Description', 'class': 'desc-wrap'},
                    {key: 'keywords', label: 'Meta Keywords', 'class': 'keywords-table-wrap'},
                    {key: 'last_history_status', label: 'Status', sortable: true, 'class': 'status-wrap'},
                    {key: 'updated_at', label: 'Last Updated', sortable: true, 'class': 'date-wrap'},
                    {key: 'actions', label: 'Actions', 'class': 'action-wrap'}
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
                sortBy: null,
                sortDesc: true,
                sortDirection: 'asc',
                sortOptions: ['status', 'updated_at']
            }
        },
        created() {
        },
        props: [
            'externalUrl',
            'pathMd5'
        ],
        computed: {
            descriptionLeft() {
                let char = this.formItem.description.length,
                    limit = this.descriptionMaxLength;
                if (char > limit) {
                    return "<b class='text-warning'>" + char + " / " + limit + " characters counter" + "</b>";
                } else {
                    return char + " / " + limit + " characters counter";
                }
            },
            titleLeft() {
                let char = this.formItem.title.length,
                    limit = this.titleMaxLength;
                if (char > limit) {
                    return "<b class='text-warning'>" + char + " / " + limit + " characters counter" + "</b>";
                } else {
                    return char + " / " + limit + " characters counter";
                }
            }
        },
        watch: {
            externalUrl(newValue, OldValue) {
                this.filter = newValue;
            },
            pathMd5(newValue, OldValue) {
                this.$refs.table.refresh();
            },
            selected(newValue, OldValue) {
                this.$refs.table.refresh();
            },
            selectedStatus(newValue, OldValue) {
                this.$refs.table.refresh();
            }
        },
        methods: {
            toggleMetaHistory(row) {
                let app = this;
                if (!row.detailsShowing) {
                    axios.get('seoagent/web/draft-data/' + row.item.hash + '/histories').then(function (resp) {
                        app.items[row.index].histories = resp.data;
                        row.toggleDetails();
                    }).catch(function (resp) {
                    });
                } else {
                    row.toggleDetails();
                }
            },
            viewKeywords(item, index, button) {
                this.$emit('view-keyword', item);
            },
            deleteDraftData(item, index, button) {

                if (confirm('Are you sure you want to delete this draft?')) {
                    let app = this;
                    axios.delete('seoagent/web/draft-data/' + item.id).then(function (resp) {
                        console.log(resp.data);
                        item.type = 0;
                        item._rowVariant = '';
                        item.draft_data = resp.data.draft_data;
                        app.$notify({
                            type: 'warn',
                            title: 'SUCCESS',
                            text: 'Data deleted'
                        });
                    }).catch(function (resp) {
                        console.log(resp);
                        app.$notify({
                            type: 'error',
                            title: 'ERROR',
                            text: 'Cannot delete data, please contact system manager.',
                            duration: -1
                        });
                    });
                }
            },
            editForm(item, index, button) {
                this.externalPathMd5 = item.hash;
                if (item.current_data.meta.defaults.canonical) {
                    this.modalInfo.title = `Manage draft for <a target="_blank" href="${item.current_data.meta.defaults.canonical}">${item.current_data.meta.defaults.canonical}</a>`;
                } else {
                    this.modalInfo.title = 'Missing canonical link';
                }

                this.modalInfo.content = JSON.stringify(item, null, 2);
                //

                if (item.type === 2) {
                    this.formItem = {
                        id: item.id,
                        canonical: item.draft_data.meta.defaults.canonical,
                        title: item.draft_data.meta.defaults.title,
                        description: item.draft_data.meta.defaults.description,
                        keywords: item.draft_data.meta.defaults.keywords,
                    };
                } else {
                    this.formItem = {
                        id: item.id,
                        canonical: item.current_data.meta.defaults.canonical,
                        title: item.current_data.meta.defaults.title,
                        description: item.current_data.meta.defaults.description,
                        keywords: item.current_data.meta.defaults.keywords,
                    };
                }

                this.currentItem = {
                    canonical: item.current_data.meta.defaults.canonical,
                    title: item.current_data.meta.defaults.title,
                    description: item.current_data.meta.defaults.description,
                    keywords: item.current_data.meta.defaults.keywords,
                };
                this.selectedItem = item;
                // this.$root.$emit('bv::show::modal', 'modalKeywordDetail', button)
                this.$refs.modalKeywordDetail.show();
            },
            resetModal() {
                this.modalInfo.title = ''
                this.modalInfo.content = ''
            },
            handleOk(evt) {
                evt.preventDefault();
                console.log(this.formItem);
                let app = this;
                let loader = this.$loading.show();
                if (this.formItem.keywords)
                    if (typeof this.formItem.keywords === 'string') {
                        this.formItem.keywords = this.formItem.keywords.split(',')
                    }
                axios.put('seoagent/web/draft-data/' + this.formItem.id, this.formItem).then(function (resp) {
                    app.selectedItem.draft_data.meta.defaults.keywords = resp.data.draft_data.meta.defaults.keywords;
                    app.selectedItem.draft_data.meta.defaults.description = resp.data.draft_data.meta.defaults.description;
                    app.selectedItem.draft_data.meta.defaults.canonical = resp.data.draft_data.meta.defaults.canonical;
                    app.selectedItem.draft_data.meta.defaults.title = resp.data.draft_data.meta.defaults.title;
                    app.selectedItem.draft_at = resp.data.draft_at;
                    app.selectedItem.type = resp.data.type;
                    app.selectedItem._rowVariant = 'danger';
                    console.log(resp.data);
                    app.$notify({
                        type: 'success',
                        title: 'SUCCESS',
                        text: 'Data updated'
                    });
                    loader.hide();
                    app.$refs.modalKeywordDetail.hide();
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
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            myProvider(ctx) {
                let app = this;

                console.log(ctx);
                let promise = axios.get('seoagent/web/draft-data', {
                    params: {
                        page: ctx.currentPage,
                        per_page: ctx.perPage,
                        order_by: ctx.sortBy,
                        order_desc: ctx.sortDesc === true ? 1 : 0,
                        wild_search: ctx.filter,
                        type: app.selected,
                        status: app.selectedStatus,
                        path_md5: app.pathMd5
                    }
                });

                // Must return a promise that resolves to an array of items
                return promise.then((resp) => {
                    // Pluck the array of items off our axios response
                    app.items = resp.data.data;
                    ctx.currentPage = resp.data.currentPage;
                    ctx.perPage = resp.data.perPage;
                    app.totalRows = resp.data.total;
                    for (let i = 1; i <= resp.data.last_page; i++) {
                        app.currentPageOptions.push(i)
                    }

                    for (let i = 0; i < app.items.length; i++) {
                        if (app.items[i].type === 1) {
                            app.items[i]._rowVariant = "success";
                        }
                        if (app.items[i].type === 2) {
                            app.items[i]._rowVariant = "danger";
                        }
                    }
                    app.$notify({
                        type: 'success',
                        title: 'SUCCESS',
                        text: 'URL Data retrieved'
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
        width: 1%;
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


</style>
