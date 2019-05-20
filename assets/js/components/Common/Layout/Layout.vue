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
        <md-toolbar class="md-transparent brand-toolbar" md-elevation="0">
          <img class="logo" alt="Logo" src="/images/logo/logo_02_64.png">
          <span class="md-headline brand">{{ $root.pageMeta.brand }}</span>

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
        <router-view v-if="!initializing"></router-view>
        <div class="loading-message" role="img" v-if="initializing" aria-label="Loading...">
            <md-icon class="md-size-5x spin-slow">autorenew</md-icon>
        </div>
      </md-app-content>
    </md-app>
  </div>
</template>

<script>    
    export default {
      data: () => ({
        menuVisible: false
      }),
      props: {
        initializing: Boolean
      },
      methods: {
        toggleMenu() {
          this.menuVisible = !this.menuVisible
        },
        closeMenu() {
          this.menuVisible = false;
        }
      },
      mounted() {
        this.$router.afterEach((to, from) => {
          this.closeMenu();
        })
      }
    }
</script>

<style lang="scss" scoped>
  .md-app {
    min-height: 100vh;
  }

  @media (min-width: 600px) {
    .menu-open-button, .menu-close-button {
      display: none;
    }
    .md-app-drawer {
      max-width: 280px;
    }
  }

  .loading-message {
      text-align: center;
      padding-top: 120px;

      .md-icon.md-theme-default.md-icon-font {
          color: rgba(127,127,127,0.5);
      }
  }

  .md-list {
    .md-list-item {
      .md-icon {
        margin-right: 16px;
      }
    }
  }

  .brand-toolbar {
    padding-top: 16px;
    padding-left: 16px;

    .brand {
      padding-left: 16px;
    }

    .logo {
      width: 64px;
      height: 64px;
    }
  }

  
</style>