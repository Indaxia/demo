<template>
    <div>
        <md-empty-state
            md-icon="highlight"
            md-label="404 Not Found"
            md-description="Sorry, this page doesn't exist.">
            <md-button class="md-primary md-raised" :to="{name: 'index'}">Home Page</md-button>
        </md-empty-state>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$root.$pageMetaSetTitle('Some Page');
        }
    }
</script>

<style scoped>
</style>