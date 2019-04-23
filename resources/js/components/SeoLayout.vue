<template>


    <b-container fluid>
        <notifications position="bottom right"/>
        <notifications group="job" position="top center"/>
        <div>

            <div>
                <b-tabs content-class="mt-3">
                    <b-tab title="View URLs" :active="urlActive" @click="keywordActive =false;">
                        <p>
                            <draft-data-list @view-keyword="transferKeyword($event)"></draft-data-list>
                        </p>
                    </b-tab>
                    <b-tab title="View Keywords" :active="keywordActive" @click="urlActive =false;">

                        <p>
                            <keyword-list></keyword-list>
                        </p>
                    </b-tab>

                    <b-tab>
                        <template slot="title">
                            Job Histories
                            <b-badge v-if="notificationCounter>0" variant="danger">{{notificationCounter}}</b-badge>
                        </template>
                        <p>
                            <job-history></job-history>
                        </p>
                    </b-tab>
                </b-tabs>
            </div>
        </div>


    </b-container>
</template>

<script>

    export default {
        data() {
            return {
                urlActive: true,
                keywordActive: false,
                externalKeyword: null,
                externalUrl: null,
                pathMd5: null,
                notificationCounter: 0
            }
        },
        created() {
        },
        computed: {},
        watch: {
            urlActive(newValue, OldValue) {
                console.log(newValue);
            },
        },
        methods: {
            transferKeyword($event) {
                // this.externalKeyword = $event.path;
                // this.keywordActive = true;
                // this.urlActive = false;
            }
        },
        mounted() {

            window.Echo.channel("jiaqi-channel").listen(".jiaqi-event", data => {
                alert(JSON.stringify(data));
            });


            window.Echo.channel("job-completed-channel").listen(".job-completed-event", data => {
                this.notificationCounter++;
            });
        }
    }
</script>
