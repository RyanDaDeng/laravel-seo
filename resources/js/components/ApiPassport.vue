<template>


    <b-container fluid>
        <b-card header="API Management">

            <b-col class="my-1">
                <passport-clients></passport-clients>
            </b-col>
            <b-col class="my-1">
                <passport-authorized-clients></passport-authorized-clients>
            </b-col>
            <b-col class="my-1">
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </b-col>

        </b-card>


    </b-container>

</template>


<script>
    import PassportClients from './passport/Clients.vue';
    import PassportAuthorizedClients from './passport/AuthorizedClients.vue';
    import PassportPersonalAccessTokens from './passport/PersonalAccessTokens.vue';

    export default {
        components: {
            PassportClients,
            PassportAuthorizedClients,
            PassportPersonalAccessTokens
        },
        data() {
            return {}
        },
        created() {
        },
        computed: {},
        watch: {},
        methods: {}
    }
</script>
