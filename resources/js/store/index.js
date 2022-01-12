import Vue from 'vue'
import Vuex from 'vuex'
import API from '../services/API';
Vue.use(Vuex);


export default new Vuex.Store({

  state: {
    authenticated : false,
    language : 'ar',
    
  },
  mutations: {
    authAdmin(state) { 
      state.authenticated = !state.authenticated;   
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
