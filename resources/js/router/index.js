import Vue from 'vue'
import VueRouter from 'vue-router'
import CreateAdmin from '../components/admin/create.vue';
import EditAdmin from '../components/admin/edit.vue';
import AdminIndex from '../components/admin/index.vue';
import CreateProduct from '../components/product/create.vue';
import EditProduct from '../components/product/edit.vue';
import ProductIndex from '../components/product/index.vue';
import CreateCategory from '../components/category/create.vue';
import EditCategory from '../components/category/edit.vue';
import CategoryIndex from '../components/category/index.vue';
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

  {
    path: '/products',
    name: 'product.index',
    component : ProductIndex,        
  },
  {
    path: '/products/create',
    name: 'product.create',
    component : CreateProduct,        
  },
  {
    path: '/products/edit/:id',
    name: 'product.edit',
    component : EditProduct,        
  },

  {
    path: '/categories',
    name: 'category.index',
    component : CategoryIndex,        
  },
  {
    path: '/categories/create',
    name: 'category.create',
    component : CreateCategory,        
  },
  {
    path: '/categories/edit/:id',
    name: 'category.edit',
    component : EditCategory,        
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
