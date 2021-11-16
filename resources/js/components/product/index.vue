<template>
  <v-data-table
    :headers="headers"
    :items="products"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no products..`, 'لا يوجد منتجات..')"

  >
    
        <template v-slot:[`item.actions`]="{ item }">

      <v-icon style="margin-right : 10px"
      @click="goToEdit(item.id)"
      >
        mdi-pencil
      </v-icon>
    </template>

    
    
  </v-data-table>
</template>
<script>    

import Productservice from '../../services/Product';

  export default {
    name : 'product.index',
    data() {
      return {
      activateDialog : false,
      products: [],
      product : {},
      headers: [
         {
          text: this.$translate('AR Name', 'الإسم بالعربي'),
          align: 'start',
          sortable: false,
          value: 'ar_name',
        },
        {
          text: this.$translate('EN Name', 'الإسم بالإنجليزي'),
          sortable: false,
          value: 'en_name',
        },
          {
          text: this.$translate('Category', 'التصنيف'),
          sortable: false,
          value: 'category',
        },
        { text: this.$translate('Addition Date', 'تاريخ الإضافة'), value: 'created_at', sortable : true },
        { text: this.$translate('Actions', 'العمليات'), value: 'actions', sortable: false },
      ]
      
      } 
    },

    beforeMount() {
      Productservice.GetProducts().then(response => {
        this.products = response.data.products
      });
    },
    methods: {
    prepareDialog(item){
    this.product = item;
    this.activateDialog = true;
    },
    closeDialog() {
    this.product = {};
    this.activateDialog = false;
    },

    goToEdit(id) {
     this.$router.push('/products/edit/' + id);
    },
    // changeState() {
    //   const payload = {
    //       id : this.product.id,
    //       state : !this.product.state, 
    //     };
    //     productservice.ChangeState(payload).then(response => {
    //     this.activateDialog = false;
    //     this.products = response.data.products;
    //     this.product = {}
    //     }).catch(() =>  this.$unexpectedError());
    // }      
      

     }  
  }
</script>