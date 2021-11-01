import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import store from '../store'

Vue.use(Vuetify)

const opts = { 
  rtl : store.state.language == 'ar',
  
}

export default new Vuetify(opts)