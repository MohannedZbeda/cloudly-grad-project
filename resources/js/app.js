require('./bootstrap');
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify';
import swal from 'sweetalert2';

Vue.prototype.$translate =  (en, ar) =>  { 
return store.state.language == 'en' ? en : ar;    
}

Vue.prototype.$swal = (title, text, icon) => {
    return swal.fire({
      title,
      text,
      icon,
      iconColor : '#3498db',
      timer : 2000,
      showConfirmButton : false
    })
  }

Vue.config.productionTip = false
  


new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app')
