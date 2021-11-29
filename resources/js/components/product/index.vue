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
          text: 'Attributes',
          sortable: false,
          value: 'attributes',
        },
         {
          text: 'Discounts',
          sortable: false,
          value: 'discounts',
        },
        {
          text: 'Price Before Discounts',
          sortable: false,
          value: 'old_price',
        },
        {
          text: 'Price After Discounts',
          sortable: false,
          value: 'new_price',
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
          text: 'الخصائص',
          sortable: false,
          value: 'attributes',
        },
        {
          text: 'التخفيضات',
          sortable: false,
          value: 'discounts',
        },
        {
          text: 'السعر قبل التخفيضات',
          sortable: false,
          value: 'old_price',
        },
        {
          text: 'السعر بعد التخفيضات',
          sortable: false,
          value: 'new_price',
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
  <template v-slot:top>
      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`${product.en_name}'s Attributes`, `خصائص ${product.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <ul>
                <li v-for="attribute in product.attributes" :key="attribute.id">
                  <b>{{$translate(attribute.attribute_en_name, attribute.attribute_ar_name)}}</b> : {{attribute.value}}
                </li>
              </ul>
                
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>

      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="discounts_dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`${product.en_name}'s Discounts`, `تخفيضات ${product.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <ul>
                <li v-for="(discount, index) in product.discounts" :key="discount.id">
                     <b>{{$translate(`Discount NO ${index + 1}`, `التخفيض رقم ${index + 1}`)}} : 
                      {{discount.discount_percentage ? `${discount.discount_percentage}%` : `${discount.discount_amount}LYD`}}
                        |  {{$translate('End Date', 'تاريخ إنتهاء الصلاحية')}} : {{discount.end_date}}
                     <v-icon style="color:#c0392b" @click="removeDiscount(discount.id)">mdi-delete</v-icon>
                     </b>
                        
                      
                </li>
              </ul>
                
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>

    

    <template v-slot:[`item.actions`]="{ item }">

      <v-icon style="margin-right : 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
    </template>

    <template v-slot:[`item.attributes`]="{ item }">
       <v-btn class="primary" @click="showAttributes(item)">{{$translate('View Attributes', 'عرض الخصائص')}}</v-btn>  
    </template>

    <template v-slot:[`item.discounts`]="{ item }">
       <v-btn class="primary" @click="showDiscounts(item)">{{$translate('View Discounts', 'عرض التخفيضات')}}</v-btn>  
    </template>

    
    
  </v-data-table>
</template>
<script>    

import Productservice from '../../services/Product';

  export default {
    name : 'product.index',
    data() {
      return {
      sending: false,
      dialog : false,
      discounts_dialog : false,
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
    showAttributes(item){
    this.product = item;
    this.dialog = true;
    },
    showDiscounts(item){
    this.product = item;
    this.discounts_dialog = true;
    },

    removeDiscount(id){
      if(this.sending)
      return; 
      const payload = {
        product_id: this.product.id,
        discount_id: id,
      };
      this.sending = true;
     Productservice.RemoveDiscount(payload).then(response => {
       this.products = response.data.products;
     }).finally(() => {
       this.sending = false;
       this.closeDialog();
       });
    },


    closeDialog() {
    this.dialog = false;
    this.discounts_dialog = false;
    this.product = {};
    },
    goToEdit(id) {
     this.$router.push('/products/edit/' + id);
    },
    goToVariants(id) {
     this.$router.push('/products/'+id+'/variants');
    },
      

  }  
}
</script>