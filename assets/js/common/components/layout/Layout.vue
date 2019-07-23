<template>
  <div class="layout-container">    
    <md-app>      
      <md-app-toolbar class="md-primary headbar" v-if="!$root.$data.hideHeadbar && !initializing">
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

          <md-divider class="md-inset"></md-divider>

          <md-list-item :to="{name: 'login'}">
            <md-icon>input</md-icon>
            <span class="md-list-item-text">Sign In</span>
          </md-list-item>
        </md-list>
      </md-app-drawer>

      <md-app-content role="main" class="md-layout">
        <md-button class="md-icon-button 
                          menu-open-button 
                          menu-open-button-floating 
                          md-raised 
                          md-primary" 
                    aria-label="Open Menu" 
                    @click="toggleMenu" 
                    v-if="$root.$data.hideHeadbar && !menuVisible">
          <md-icon>more_vert</md-icon>
        </md-button>

        <router-view v-if="!initializing" class="md-layout-item md-size-100 md-large-size-80 md-xlarge-size-60"></router-view>
        <div class="loading-message md-layout-item md-size-100 md-large-size-80 md-xlarge-size-60" role="img" v-if="initializing" aria-label="Loading...">
            <md-icon class="md-size-5x spin-slow" v-html="'autorenew'"></md-icon>
        </div>
      </md-app-content>
    </md-app>
  </div>
</template>

<script>
    export default {
      data() {
        return {
          menuVisible: false
        }
      },
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
          this.$root.$data.hideHeadbar = false;
          this.closeMenu();
        });
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

  .menu-open-button-floating {
    position: fixed;
    bottom: 12px;
    left: 6px;
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