<template>
  <v-data-table
    :headers="$translate([
         {
          text: 'AR Name',
          align: 'start',
          sortable: false,
          value: 'ar_name',
        },
        {
          text: 'EN Name',
          sortable: false,
          value: 'en_name',
        },
        {
          text: 'Product attributes',
          sortable: false,
          value: 'attributes',
        },
        {
          text: 'Category',
          sortable: false,
          value: 'category',
        },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false },
      ],[
         {
          text: 'الإسم بالعربي',
          align: 'start',
          sortable: false,
          value: 'ar_name',
        },
        {
          text: 'الإسم بالإنجليزي',
          sortable: false,
          value: 'en_name',
        },
        {
          text: 'خصائص المنتج',
          sortable: false,
          value: 'attributes',
        },
        {
          text: 'التصنيف',
          sortable: false,
          value: 'category',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false }
      ])"
    :items="products"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no products..`, 'لا يوجد منتجات..')"

  >
      

    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right: 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
      <v-icon style="margin-right: 10px" @click="showVoucherForm(item)">mdi-gift</v-icon>
      <v-icon v-if="item.vouchers.length" style="margin-right: 10px" @click="copyVouchers(item.vouchers)">mdi-content-copy</v-icon>
    </template>

    <template v-slot:[`item.attributes`]="{ item }">
       <v-btn class="primary" @click="goToVariants(item.id)">{{$translate('View Attributes', 'عرض الخصائص')}}</v-btn>  
    </template>
    
  </v-data-table>
</template>
<script>    
import Productservice from '../../services/Product';
  export default {
    name : 'product.index',
    data() {
      return {
      products: [],
      product : {}
    } 
    },

    beforeMount() {
      Productservice.GetProducts().then(response => {
        this.products = response.data.products
      });
    },
    methods: {
    goToEdit(id) {
      this.$router.push('/products/edit/' + id);
    },
    goToVariants(id) {
      this.$router.push('/products/'+id+'/variants');
    },
      

  }  
}
</script>