<template>
    <div class="app-container">
        <Layout></Layout>
    </div>
</template>

<script>
    import Layout from './Common/Layout/Layout.vue'
    import InitialDataStore from '../store/Common/InitialDataStore'
    
    export default {
        components: { Layout },
        mounted() {
            this.$root.$pageMetaSetBrand("Demo App");

            InitialDataStore.load();
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
</style>