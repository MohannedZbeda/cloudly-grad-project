import Vue from 'vue'
import VueRouter from 'vue-router'

import home from '../components/home/home.vue';
import CreateAdmin from '../components/admin/create.vue';
import EditAdmin from '../components/admin/edit.vue';
import AdminIndex from '../components/admin/index.vue';
import CreateProduct from '../components/product/create.vue';
import EditProduct from '../components/product/edit.vue';
import ProductIndex from '../components/product/index.vue';
import CreateVariant from '../components/variant/create.vue';
import EditVariant from '../components/variant/edit.vue';
import VariantIndex from '../components/variant/index.vue';
import CreateCategory from '../components/category/create.vue';
import EditCategory from '../components/category/edit.vue';
import CategoryIndex from '../components/category/index.vue';
import AttributeIndex from '../components/category/attribute/index.vue';
import CreateAttribute from '../components/category/attribute/create.vue';
import EditAttribute from '../components/category/attribute/edit.vue';
import PackageIndex from '../components/package/index.vue';
import CreatePackage from '../components/package/create.vue';
import EditPackage from '../components/package/edit.vue';
import FaqIndex from '../components/faq/index.vue';
import CreateFaq from '../components/faq/create.vue';
import EditFaq from '../components/faq/edit.vue';
import CustomerIndex from '../components/customer/index.vue';
import CreateCustomer from '../components/customer/create.vue';
import EditCustomer from '../components/customer/edit.vue';
import CustomerTransactions from '../components/customer/transactions.vue';
import CycleIndex from '../components/cycle/index.vue'
import CreateCycle from '../components/cycle/create.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component : home,        
  },
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
    path: '/products/:id/variants',
    name: 'variant.index',
    component : VariantIndex,        
  },
  {
    path: '/products/:id/variants/create',
    name: 'variant.create',
    component : CreateVariant,        
  },
  {
    path: '/products/:product_id/variants/edit/:id',
    name: 'variant.edit',
    component : EditVariant,        
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
  {
    path: '/categories/:category_id/attributes',
    name: 'attribute.index',
    component : AttributeIndex,        
  },

  {
    path: '/categories/:category_id/attributes/create',
    name: 'attribute.create',
    component : CreateAttribute,        
  },
  {
    path: '/categories/:category_id/attributes/edit/:id',
    name: 'attribute.edit',
    component : EditAttribute,        
  },

  {
    path: '/packages',
    name: 'package.index',
    component : PackageIndex,        
  },

  {
    path: '/packages/create',
    name: 'package.create',
    component : CreatePackage,        
  },
  {
    path: '/packages/edit/:id',
    name: 'package.edit',
    component : EditPackage,        
  },
  {
    path: '/faqs',
    name: 'faq.index',
    component : FaqIndex,        
  },
  {
    path: '/faqs/create',
    name: 'faq.create',
    component : CreateFaq,        
  },
  {
    path: '/faqs/edit/:id',
    name: 'faq.edit',
    component : EditFaq,        
  },{
    path: '/cycles',
    name: 'cycle.index',
    component : CycleIndex,        
  },
  {
    path: '/cycles/create',
    name: 'cycle.create',
    component : CreateCycle,        
  },
  {
    path: '/customers',
    name: 'customer.index',
    component : CustomerIndex,        
  },
  {
    path: '/customers/create',
    name: 'customer.create',
    component : CreateCustomer,        
  },
  {
    path: '/customers/edit/:id',
    name: 'customer.edit',
    component : EditCustomer,        
  },
  {
    path: '/customers/:id/transactions',
    name: 'customer.transactions',
    component : CustomerTransactions,        
  }
  
]

const router = new VueRouter({
  routes,
  mode: 'history'
});

export default router;
