import Vue from 'vue'
import VueRouter from 'vue-router'
import CreateAdmin from '../components/admin/create.vue';
import EditAdmin from '../components/admin/edit.vue';
import AdminIndex from '../components/admin/index.vue';
Vue.use(VueRouter)

const routes = [
 
    {
    path: '/admins',
    name: 'admin.index',
    component : AdminIndex,        
  },
  {
    path: '/admins/create',
    name: 'admin.create',
    component : CreateAdmin,        
  },
  {
    path: '/admins/edit/:id',
    name: 'admin.edit',
    component : EditAdmin,        
  },
  
]

const router = new VueRouter({
  routes,
  mode: 'history'
});
// router.beforeEach((to, from, next) => {
//   if(to.name == 'Login') {
//     if(store.state.authenticated)
//        next({name : 'sections'});
//    else next();
//   }
//   else if(to.name == 'admins' || to.name == 'createAdmin' || to.name == 'editAdmin') {
//     if(!store.state.superAdmin)
//        next({name : 'sections'});
//     else next();  
//   }
//   else {
//     if(!store.state.authenticated)
//       next({name : 'Login'});
//     else next(); 
//   }
         
// })

export default router;
