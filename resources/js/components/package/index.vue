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
          text: 'Products',
          sortable: false,
          value: 'package_products',
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
          text: 'المنتجات',
          sortable: false,
          value: 'package_products',
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
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false }
      ])"
    :items="packages"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no packages..`, 'لا يوجد باقات..')"

  >
  <template v-slot:top>
      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`${pack.en_name}'s Products`, `منتجات  ${pack.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <ul>
                <li v-for="product in pack.products" :key="product.id">
                  <b>{{$translate(product.en_name, product.ar_name)}}</b>
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
            <v-card-title class="text-h5">{{$translate(`${pack.en_name}'s Discounts`, `تخفيضات ${pack.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <ul>
                <li v-for="(discount, index) in pack.discounts" :key="discount.id">
                     <b>{{$translate(`Discount NO ${index + 1}`, `التخفيض رقم ${index + 1}`)}} : 
                      {{discount.discount_percentage ? `${discount.discount_percentage}%` : `${discount.discount_amount}LYD`}}
                       | {{$translate('End Date', 'تاريخ إنتهاء الصلاحية')}} : {{discount.end_date}}
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

    <template v-slot:[`item.package_products`]="{ item }">
       <v-btn class="primary" @click="showProducts(item)">{{$translate('View Products', 'عرض المنتجات')}}</v-btn>  
    </template>

    <template v-slot:[`item.discounts`]="{ item }">
       <v-btn class="primary" @click="showDiscounts(item)">{{$translate('View Discounts', 'عرض التخفيضات')}}</v-btn>  
    </template>

    
    
  </v-data-table>
</template>
<script>    

import PackageService from '../../services/Package';

  export default {
    name : 'package.index',
    data() {
      return {
      sending: false,
      dialog: false,
      discounts_dialog: false,
      packages: [],
      pack: {} 
      } 
    },

    beforeMount() {
      PackageService.GetPackages().then(response => {
        this.packages = response.data.packages
      });
    },
    methods: {
    showProducts(item){
    this.pack = item;
    this.dialog = true;
    },
    showDiscounts(item){
    this.pack = item;
    this.discounts_dialog = true;
    },

    removeDiscount(id){
      if(this.sending)
      return; 
      const payload = {
        package_id: this.pack.id,
        discount_id: id,
      };
      this.sending = true;
     PackageService.RemoveDiscount(payload).then(response => {
       this.packages = response.data.packages;
     }).finally(() => {
       this.sending = false;
       this.closeDialog();
       });
    },


    closeDialog() {
    this.dialog = false;
    this.discounts_dialog = false;
    this.pack = {};
    },
    goToEdit(id) {
     this.$router.push(`/packages/edit/${id}`);
    } 

  }  
}
</script>