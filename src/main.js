import 'babel-polyfill'
import Vue from 'vue'
import App from './App'
import router from '@/router/index.js'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './vuex/store'
import filter from './assets/js/filter'
import vueKanban from 'vue-kanban'
import 'assets/css/kanban.css'
import 'assets/css/global.css'
import 'assets/css/base.css'
import './.htaccess'
// import $ from 'jquery'
// Vue.prototype.$ = jquery
Vue.use(ElementUI)
Vue.use(vueKanban)

new Vue({
  el: '#app',
  template: '<App/>',
  filters: filter,
  router,
  store,
  components: { App }
}).$mount('#app')
