<template>


    <b-container fluid>
        <notifications position="bottom right"/>
        <notifications group="job" position="top center"/>
        <div>

            <div>
                <b-tabs content-class="mt-3">
                    <b-tab title="View Keywords" :active="keywordActive">

                        <p>
                            <keyword-list></keyword-list>
                        </p>
                    </b-tab>

                    <b-tab>
                        <template slot="title">

                            <b-spinner v-if="newJobCreated" type="grow" small></b-spinner>

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
                keywordActive: false,
                newJobCreated: false,
                notificationCounter: 0
            }
        },
        created() {
        },
        computed: {},
        watch: {},
        methods: {},
        mounted() {
            window.Echo.channel("job-completed-channel").listen(".job-completed-event", data => {
                this.notificationCounter++;
            });
            EventBus.$on('newJobCreated', () => {
                this.newJobCreated = true;
            });
        }
    }
</script>
