<template>
    <div>
        <p>Don't have an account? <router-link :to="{name: 'registration'}">Sign Up now!</router-link></p>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$root.$pageMetaSetTitle('Sign In');

            
        }
    }
</script>

<style scoped>
</style>