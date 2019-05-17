import '../css/app.scss'
import 'vue-material/dist/vue-material.min.css'
import Vue from 'vue'
import App from './components/App'
import router from './router'
import VueMaterial from 'vue-material'

Vue.use(VueMaterial)

const app = new Vue({
  el: '#app',
  render: h => h(App),
  router
})