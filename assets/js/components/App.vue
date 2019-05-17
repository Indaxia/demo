<template>
    <Layout></Layout>
</template>

<script>
    import Layout from './Common/Layout/Layout.vue'
    
    export default {
        components: { Layout }
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