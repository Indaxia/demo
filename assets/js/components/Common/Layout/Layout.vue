<template>
  <div class="layout-container">
    <md-app>
      <md-app-toolbar class="md-primary">
        <md-button class="md-icon-button menu-open-button" aria-label="Open Menu" @click="toggleMenu" v-if="!menuVisible">
          <md-icon>more_vert</md-icon>
        </md-button>
        <span class="md-title">{{ $root.pageMeta.title }}</span>
      </md-app-toolbar>

      <md-app-drawer :md-active.sync="menuVisible" md-permanent="full" :md-swipeable="true">
        <md-toolbar class="md-transparent" md-elevation="0">
          <span class="md-title">{{ $root.pageMeta.brand }}</span>

          <div class="md-toolbar-section-end">
            <md-button class="md-icon-button md-dense menu-close-button" aria-label="Close Menu" @click="toggleMenu">
              <md-icon>keyboard_arrow_left</md-icon>
            </md-button>
          </div>
        </md-toolbar>
 
        <md-list class="menu-items" role="navigation">
          <md-list-item exact :to="{name: 'index'}">
            <md-icon>home</md-icon>
            <span class="md-list-item-text">Home</span>
          </md-list-item>

          <md-list-item :to="{name: 'some-page'}">
            <md-icon>send</md-icon>
            <span class="md-list-item-text">Some Page</span>
          </md-list-item>
        </md-list>
      </md-app-drawer>

      <md-app-content role="main">
        <router-view></router-view>
      </md-app-content>
    </md-app>
  </div>
</template>

<script>
    
    export default {
      data: () => ({
        menuVisible: false
      }),
      methods: {
        toggleMenu () {
          this.menuVisible = !this.menuVisible
        }
      }
    }
</script>

<style lang="scss" scoped>
  .md-app {
    min-height: 350px;
    border: 1px solid rgba(#000, .12);
  }

  @media (min-width: 600px) {
    .menu-open-button, .menu-close-button {
      display: none;
    }
  }
</style>