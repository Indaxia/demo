<template>
    <div class="app-container">
        <Layout v-if="!globalError" :initializing="initializing" :hide-headbar="$root.$data.hideHeadbar"></Layout>
        <md-dialog-confirm :md-active="globalErrorDialog" 
                           md-title="Global Error" 
                           :md-content="globalError" 
                           md-confirm-text="Close"
                           md-cancel-text="Try Again"
                           @md-cancel="onTryAgain"
                           @md-confirm="globalErrorDialog = false" />
        <div class="global-error" v-if="!!globalError">We're sorry for the inconvenience. Please contact administration.</div>
    </div>
</template>

<script>
    import Layout from './common/components/layout/Layout.vue'
    import InitialDataStore from './common/store/InitialDataStore' 
    import HttpSecurity from './access/services/HttpSecurity'
    
    export default {
        data() {
            return {
                initializing: true,
                globalError: null,
                globalErrorDialog: false
            }
        },
        components: { Layout }, 
        mounted() {
            this.$root.$pageMetaSetBrand("Demo App");
            this.$root.$data.env = '';
            this.$root.$data.hideHeadbar = false;

            this.initialize();
        },
        methods: {
            initialize() {
                this.initializing = true;
                this.globalError = null;
                this.globalErrorDialog = false;

                InitialDataStore.subscribe((event) => {
                    this.globalError = null;
                    this.globalErrorDialog = false;
                    this.$root.$pageMetaSetBrand(event.data.Common.productName);
                    this.$root.$data.env = event.data.Common.env;
                });

                InitialDataStore.load().then(response => {
                    this.initializing = false;
                    this.globalErrorDialog = false;
                }, error => {
                    this.initializing = false;
                    this.globalError = error.response.statusText;
                    this.globalErrorDialog = true;
                });
            },
            onTryAgain() {
                this.initialize();
            }
        }
    }
</script>

<style lang="scss">
    @import "~vue-material/dist/theme/engine"; // Import the theme engine

    @include md-register-theme("default", (
        primary: md-get-palette-color(lightgreen, A200), // The primary color of your application
        accent: md-get-palette-color(lightblue, A700), // The accent or secondary color
        theme: dark // This can be dark or light
    ));

    @import "~vue-material/dist/theme/all"; // Apply the theme

    .app-container {
        .global-error {
            text-align: center;
        }
    }
</style>