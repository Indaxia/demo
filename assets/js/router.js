import Vue from 'vue'
import Router from 'vue-router'

import NotFound from './common/components/NotFound'
import Home from './common/components/Home'
import SomePage from './common/components/SomePage'
import Login from './access/components/Login'
import Registration from './access/components/Registration'

Vue.use(Router)

export default new Router({
  mode: 'history',  
  routes: [
    {
      name: 'index',
      path: '/',
      component: Home
    },
    {
      name: 'some-page',
      path: '/some-page',
      component: SomePage
    },
    {
      name: 'login',
      path: '/login',
      component: Login
    },
    {
      name: 'registration',
      path: '/registration',
      component: Registration
    },
    { 
      path: '*', 
      component: NotFound 
    }
  ]
})