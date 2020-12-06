require('./bootstrap');
import Vue from 'vue'
import router from './router'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import common from './common'
import moment from 'moment'
import  store from './store'
Vue.filter('formatDate', function(value) {
    if (value) {
      return moment(String(value)).format('DD/MM/YYYY hh:mm')
    }
  })
Vue.mixin(common)
Vue.use(ViewUI);
Vue.config.productionTip = false
Vue.component('app', require('./App.vue').default);
const app = new Vue({
    el: '#app',
    router,
    store
})

