<template>
<div>
  <v-data-table
    :headers="$translate([
         {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        {
          text: 'Category',
          sortable: false,
          value: 'category',
        },
        {
          text: 'Variants',
          sortable: false,
          value: 'attributes',
        },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false },
      ],[
         {
          text: 'الإسم',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        {
          text: 'التصنيف',
          sortable: false,
          value: 'category',
        },
        {
          text: 'التفرعات',
          sortable: false,
          value: 'attributes',
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
      <v-icon style="margin-right: 10px" @click="showImage(item)">mdi-image-size-select-actual</v-icon>
    </template>

    <template v-slot:[`item.attributes`]="{ item }">
       <v-btn class="primary" @click="goToVariants(item.id)">{{$translate('View Variants', 'عرض التفرعات')}}</v-btn>  
    </template>
    
  </v-data-table>

  <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h6">
             {{$translate(`${product.name}'s Image`, 
             `صورة ${product.name}`)}}
            </v-card-title>
            <v-card-text class="text-h6">
             <v-img
              :src="product.image"
              :alt="$translate(product.name, product.name)"
              max-height="300"
              max-width="300"
             >
             </v-img>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
  </div>
</template>
<script>    
import Productservice from '../../services/Product';
  export default {
    name : 'product.index',
    data() {
      return {
      products: [],
      product : {},
      dialog: false
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

    showImage(product) {
      this.dialog = true;
      this.product = product;
    },

    closeDialog() {
      this.dialog = false;
      this.product = {};
    },
      

  }  
}
</script>