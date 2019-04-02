<template>


    <b-container fluid>
        <notifications position="bottom right"/>

        <div>

            <div>
                <b-tabs content-class="mt-3">
                    <b-tab title="View URLs" :active="urlActive" @click="keywordActive =false;">
                        <p>
                            <draft-data-list @view-keyword="transferKeyword($event)"></draft-data-list>
                        </p>
                    </b-tab>
                    <b-tab :active="keywordActive" @click="urlActive =false;">
                        <template slot="title">
                            <b-spinner type="grow" small/>
                            <strong>View Keywords</strong>
                        </template>
                        <p>
                            <keyword-list></keyword-list>
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
                pathMd5: null
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
        }
    }
</script>
