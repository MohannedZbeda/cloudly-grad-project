import Vue from 'vue'
import Vuex from 'vuex'
//import API from '../services/API';
import axios from 'axios';
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

    logout(state) {  
      axios.post('/logout').then(response => {
        console.log(response);
        window.location.reload();
      });
    },
    
  },
  
})
