import Vue from 'vue'
import Vuex from 'vuex'
import API from '../services/API';
Vue.use(Vuex);


export default new Vuex.Store({

  state: {
    authenticated : false,
    language : 'ar',
    customerName: ''
    
  },
  mutations: {
    authAdmin(state) { 
      state.authenticated = !state.authenticated;   
    },
    setCustomerName(state, name) { 
      state.customerName = name;   
    },

    changeLang(state) {  
      state.language = state.language == 'en' ? 'ar' : 'en';   
    },

    logout() {  
      API.post('/logout').then(() => {
        window.location.reload();
      });
    },
    
  },
  
})
