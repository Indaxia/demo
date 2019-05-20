<template>
    <div>
        <h2>This is a home page</h2>
        <p>You can check the <a href="https://github.com/ScorpioT1000/demo">source code</a> of this app</p>
    </div>
</template>

<script> 

    export default {
        mounted() {
            this.$root.$pageMetaSetTitle('Home Page');
        }
    }
</script>

<style scoped>
</style>