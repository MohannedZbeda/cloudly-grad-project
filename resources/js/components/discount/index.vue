<template>
  <v-data-table
    :headers="$translate([
         {
          text: 'Discount Amount',
          align: 'start',
          sortable: false,
          value: 'discount_amount',
        },
        {
          text: 'Discount Percent',
          sortable: false,
          value: 'discount_percentage',
        },
        {
          text: 'Discount Products',
          sortable: false,
          value: 'products',
        },
        { text: 'Start Date', value: 'created_at', sortable : true },
        { text: 'End Date', value: 'end_date', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false },
      ],[
         {
          text: 'قيمة التخفيض',
          align: 'start',
          sortable: false,
          value: 'discount_amount',
        },
        {
          text: 'نسبة التخفيض',
          sortable: false,
          value: 'discount_percentage',
        },
        {
          text: 'منتجات التخفيض',
          sortable: false,
          value: 'products',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'تاريخ إنتهاء الصلاحية', value: 'end_date', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false }
      ])"
    :items="discounts"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no discounts..`, 'لا يوجد تخفيضات..')"

  >
  <template v-slot:top>
      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`Discount Products and Products`, `منتجات وباقات التخفيض`)}}<br>
            </v-card-title>
            <v-card-text> 
              <h2><b>{{$translate('Discount Products', 'منتجات التخفيض')}}</b></h2> <br>
              <ul>
                <li v-for="product in discount.products" :key="product.id">
                  <b>{{$translate(product.en_name, product.ar_name)}}</b>
                </li>
              </ul>
              <br> <br>
              <h2><b>{{$translate('Discount Packages', 'باقات التخفيض')}}</b></h2> <br>
              <ul>
                <li v-for="pack in discount.packages" :key="pack.id">
                  <b>{{$translate(pack.en_name, pack.ar_name)}}</b>
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

    <template v-slot:[`item.discount_amount`]="{ item }">
        <p>{{item.discount_amount ? item.discount_amount : $translate('There is none', 'لايوجد')}}</p>
    </template>

     <template v-slot:[`item.discount_percentage`]="{ item }">
        <p>{{item.discount_percentage ? item.discount_percentage : $translate('There is none', 'لايوجد')}}</p>
    </template>

    <template v-slot:[`item.products`]="{ item }">
       <v-btn class="primary" @click="showDiscountables(item)">{{$translate('View Products', 'عرض المنتجات')}}</v-btn>  
    </template>

    <template v-slot:[`item.package_products`]="{ item }">
       <v-btn class="primary" @click="showProducts(item)">{{$translate('View Products', 'عرض المنتجات')}}</v-btn>  
    </template>

    
    
  </v-data-table>
</template>
<script>    

import DiscountService from '../../services/Discount';

  export default {
    name : 'discount.index',
    data() {
      return {
      dialog : false,
      discounts: [],
      discount: {}
      } 
    },

    beforeMount() {
      DiscountService.GetDiscounts().then(response => {
        this.discounts = response.data.discounts
      });
    },
    methods: {
    showDiscountables(item){
    this.discount = item;
    this.dialog = true;
    },

    closeDialog() {
    this.dialog = false;
    this.discount = {};
    },
    goToEdit(id) {
     this.$router.push(`/discounts/edit/${id}`);
    } 

  }  
}
</script>