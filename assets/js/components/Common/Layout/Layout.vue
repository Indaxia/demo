<template>
  <md-app md-waterfall md-mode="fixed">
    <md-app-toolbar class="md-primary">
      <span class="md-title">{{ $route.name }}</span>
    </md-app-toolbar>

    <md-app-drawer md-permanent="full">
      <md-toolbar class="md-transparent" md-elevation="0">
        Demo App
      </md-toolbar>


      <md-list>
        <md-list-item>
          <md-icon>home</md-icon>
          <span class="md-list-item-text"><router-link to="/">Home</router-link></span>
        </md-list-item>

        <md-list-item>
          <md-icon>send</md-icon>
          <span class="md-list-item-text"><router-link to="/some-page">Some Page</router-link></span>
        </md-list-item>
      </md-list>
    </md-app-drawer>

    <md-app-content>
      <router-view></router-view>
    </md-app-content>
  </md-app>
</template>

<script>
    
    export default {
      computed: {
      }
    }
</script>

<style lang="scss" scoped>
  .md-app {
      max-height: 400px;
      border: 1px solid rgba(#000, .12);
  }
</style>