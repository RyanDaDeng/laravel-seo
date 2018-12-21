<template>

    <b-container fluid>
        <notifications position="top right"/>
        <b-card>
            <b-row>
                <b-col md="6" class="my-1">
                    <b-form-group horizontal label="Search" class="mb-0">
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
                    <b-form-group horizontal label="Per page" class="mb-0">
                        <b-form-select :options="pageOptions" v-model="perPage"/>
                    </b-form-group>
                </b-col>
                <b-col md="6" class="my-1">
                    <b-form-group horizontal label="Sort" class="mb-0">
                        <b-input-group>
                            <b-form-select v-model="sortBy" :options="sortOptions">
                                <option slot="first" :value="null">-- none --</option>
                            </b-form-select>
                            <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
                                <option :value="false">Asc</option>
                                <option :value="true">Desc</option>
                            </b-form-select>
                        </b-input-group>
                    </b-form-group>
                </b-col>


                <b-col md="6" class="my-1">
                    <b-form-group horizontal label="Filters" class="mb-0">
                        <b-form-radio-group id="radios1" v-model="selected" :options="options" name="radioOpenions">
                        </b-form-radio-group>
                    </b-form-group>
                </b-col>

            </b-row>
        </b-card>

        <b-row class="float-right">
            <div class="my-1">
                <b-form-group horizontal class="mb-0">
                    <b-input-group>
                        <b-form-select :options="currentPageOptions" v-model="currentPage" slot="append"/>
                    </b-input-group>
                </b-form-group>
            </div>
            <b-col md="7" class="my-1 float-right">
                <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0"/>
            </b-col>
        </b-row>

        <b-table
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
                <a :href="row.value" target="_blank">{{row.value}}</a>
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

                    <table class="table">
                        <thead>
                        <th></th>
                        <th>Canonical</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Keywords</th>
                        </thead>
                        <tbody>

                        <tr>
                            <th>Current Meta</th>
                            <td>
                                {{row.item.current_data.meta.defaults.canonical}}
                            </td>
                            <td>
                                {{row.item.current_data.meta.defaults.title}}
                            </td>
                            <td>
                                {{row.item.current_data.meta.defaults.description}}
                            </td>
                            <td>
                                {{row.item.current_data.meta.defaults.keywords}}
                            </td>
                        </tr>

                        <tr>
                            <th>Draft Meta</th>
                            <td>
                                {{row.item.draft_data.meta.defaults.canonical}}
                            </td>
                            <td>
                                {{row.item.draft_data.meta.defaults.title}}
                            </td>
                            <td>
                                {{row.item.draft_data.meta.defaults.description}}
                            </td>
                            <td>
                                {{row.item.draft_data.meta.defaults.keywords}}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </b-card>
            </template>
            <template slot="canonical" slot-scope="data">
                {{ data.item.type === 2 ? data.item.draft_data.meta.defaults.canonical :
                data.item.current_data.meta.defaults.canonical }}
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


            <template slot="actions" slot-scope="row">
                <b-button-group vertical>
                    <b-button size="sm" @click.stop="editForm(row.item, row.index, $event.target)" variant="success">
                        Manage Draft
                    </b-button>

                    <b-button size="sm" v-if="row.item.type === 2"
                              @click.stop="deleteDraftData(row.item, row.index, $event.target)" variant="danger">
                        Delete Draft
                    </b-button>

                    <b-button size="sm" @click.stop="row.toggleDetails" variant="primary">
                        {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
                    </b-button>

                </b-button-group>

            </template>
        </b-table>

        <b-row class="float-right">
            <div class="my-1">
                <b-form-group horizontal class="mb-0">
                    <b-input-group>
                        <b-form-select :options="currentPageOptions" v-model="currentPage" slot="append"/>
                    </b-input-group>
                </b-form-group>
            </div>
            <b-col md="6" class="my-1">
                <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0"/>
            </b-col>
        </b-row>


        <!-- Info modal -->
        <!-- Info modal -->
        <b-modal id="modalInfo" size="lg" ref="modal" ok-title="Close" ok-only ok-variant="secondary"
                 :title="modalInfo.title">

            <b-card>
                <b-form @submit="handleOk">

                    <b-form-group horizontal
                                  :label-cols="2"
                                  label="Canonical"
                                  label-for="Canonical">
                        <b-form-input id="exampleInput1"
                                      type="text"
                                      v-model="formItem.canonical"
                                      required
                        >
                        </b-form-input>
                    </b-form-group>


                    <b-form-group horizontal
                                  :label-cols="2"
                                  label="Title"
                                  label-for="meta_title">
                        <b-form-input
                                type="text"
                                v-model="formItem.title"
                                required
                        >
                        </b-form-input>
                        <b-form-text id="inputLiveHelp">
                            <!-- this is a form text block (formerly known as help block) -->
                            Current: {{currentItem.title}}
                        </b-form-text>
                    </b-form-group>


                    <b-form-group horizontal
                                  :label-cols="2"
                                  label="Description"
                                  label-for="meta_description">
                        <b-form-textarea
                                placeholder="Enter something"
                                :rows="3"
                                :max-rows="6"
                                v-model="formItem.description"
                                required
                        >
                        </b-form-textarea>
                        <b-form-text id="inputLiveHelp">
                            <!-- this is a form text block (formerly known as help block) -->
                            Current: {{currentItem.description}}
                        </b-form-text>
                    </b-form-group>


                    <b-form-group horizontal
                                  :label-cols="2"
                                  label="Keywords"
                                  label-for="meta_keywords">
                        <b-form-input
                                placeholder="Enter keywords"
                                :rows="3"
                                :max-rows="6"
                                v-model="formItem.keywords"
                        >
                        </b-form-input>
                        <b-form-text id="inputLiveHelp">
                            <!-- this is a form text block (formerly known as help block) -->
                            Current: {{currentItem.keywords}}
                        </b-form-text>
                    </b-form-group>
                    <b-button type="submit" variant="primary" class="float-right">Submit</b-button>

                </b-form>
            </b-card>
            <div slot="modal-footer">

            </div>

        </b-modal>


    </b-container>
</template>

<script>

    export default {
        data() {
            return {
                selected: 0,
                options: [
                    {text: 'All', value: 0},
                    {text: 'Recent Approved', value: 1},
                    {text: 'Draft Only', value: 2}
                ],
                items: [],
                filter: null,
                fields: [
                    {key: 'id', label: 'ID', class: 'id-table-wrap'},
                    {key: 'path', label: 'Path', 'class': 'path-table-wrap'},
                    // 'Current vs Draft',
                    {key: 'canonical', label: 'Canonical', 'class': 'wrap'},
                    {key: 'title', label: 'Title', 'class': 'wrap'},
                    {key: 'description', label: 'Description', 'class': 'wrap'},
                    {key: 'keywords', label: 'Keywords', 'class': 'keywords-table-wrap'},
                    {key: 'draft_at', label: 'Daft At', sortable: true, 'class': 'date-wrap'},
                    {key: 'last_approved_at', label: 'Last Approved', sortable: true, 'class': 'date-wrap'},
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
                sortDesc: false,
                sortDirection: 'asc',
                sortOptions: ['draft_at', 'last_approved_at']
            }
        },
        created() {
        },
        watch: {
            selected(newValue, OldValue) {
                this.$refs.table.refresh();
            }
        },
        methods: {
            deleteDraftData(item, index, button) {

                if (confirm('Are you sure you want to delete this draft?')) {
                    var app = this;
                    axios.delete('/seoagent/web/draft-data/' + item.id).then(function (resp) {
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
                this.modalInfo.title = `Manage draft for #${item.id}`;
                this.modalInfo.content = JSON.stringify(item, null, 2);
                //
                this.formItem = {
                    id: item.id,
                    canonical: item.draft_data.meta.defaults.canonical,
                    title: item.draft_data.meta.defaults.title,
                    description: item.draft_data.meta.defaults.description,
                    keywords: item.draft_data.meta.defaults.keywords,
                };

                this.currentItem = {
                    canonical: item.current_data.meta.defaults.canonical,
                    title: item.current_data.meta.defaults.title,
                    description: item.current_data.meta.defaults.description,
                    keywords: item.current_data.meta.defaults.keywords,
                };


                this.selectedItem = item;
                this.$root.$emit('bv::show::modal', 'modalInfo', button)
            },
            resetModal() {
                this.modalInfo.title = ''
                this.modalInfo.content = ''
            },
            handleOk(evt) {
                evt.preventDefault();
                console.log(this.formItem);
                var app = this;
                if (this.formItem.keywords)
                    if (typeof this.formItem.keywords === 'string') {
                        this.formItem.keywords = this.formItem.keywords.split(',')
                    }
                axios.put('/seoagent/web/draft-data/' + this.formItem.id, this.formItem).then(function (resp) {
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
                }).catch(function (resp) {
                    console.log(resp);
                    app.$notify({
                        type: 'error',
                        title: 'ERROR',
                        text: 'Cannot update data, please check your data format.',
                        duration: -1
                    });
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
                        type: app.selected
                    }
                });

                // Must return a promise that resolves to an array of items
                return promise.then((resp) => {
                    // Pluck the array of items off our axios response
                    app.items = resp.data.data;
                    ctx.currentPage = resp.data.currentPage;
                    ctx.perPage = resp.data.perPage;
                    app.totalRows = resp.data.total;
                    for (var i = 1; i <= resp.data.last_page; i++) {
                        app.currentPageOptions.push(i)
                    }

                    for (var i = 0; i < app.items.length; i++) {
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
                        text: 'Data retrieved'
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
    .wrap {
        word-break: break-word;
        width: 10%;
    }

    .action-wrap {
        word-break: break-word;
        width: 2%;
    }

    .date-wrap {
        word-break: break-word;
        width: 4.2%;
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
        width: 2%;
    }
</style>