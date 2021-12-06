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


<v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="add_vouchers_dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`Add Vouchers to ${pack.en_name}`, `إضافة هدايا ل ${pack.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <b>{{$translate('Gifs are codes to be given to chosen users, When used, The codes give the chosen product',
                'الهدايا هي عبارة عن رموز تعطى لمستخدمين مختارين, عندما يتم إستعمال هذه الأكواد, ستعطي المنتج المختار مجانا'
                )}}</b><br><br>
              <v-form>
                <v-text-field
                  :label="$translate('Quantity', 'الكمية')"
                  outlined
                  type="number"
                  v-model="form.quantity"
                ></v-text-field>
  
              </v-form>
                
            </v-card-text>
             <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="addVouchers">{{$translate('Add Vouchers', 'إضافة الهدايا')}}</v-btn>
              </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>

    </template>

    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
      <v-icon style="margin-right: 10px" @click="showVoucherForm(item)">mdi-gift</v-icon>
      <v-icon v-if="item.vouchers.length" style="margin-right: 10px" @click="copyVouchers(item.vouchers)">mdi-content-copy</v-icon>
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
      add_vouchers_dialog: false,
      packages: [],
      pack: {},
       form: {
        quantity: null
      }
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
   showVoucherForm(item){
    this.pack = item;
    this.add_vouchers_dialog = true;
    },
    copyVouchers(vouchers){
      const el = document.createElement('textarea');
      el.value = vouchers;
      el.setAttribute('readonly', '');
      el.style.position = 'absolute';
      el.style.left = '-9999px';
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    },
    addVouchers() {
    if(this.sending)
     return;
    this.sending = true;
    const payload = {
      quantity: this.form.quantity,
      package_id: this.pack.id
    }
    PackageService.AddVouchers(payload).then(response => {
      this.packages = response.data.packages;
      this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Vouchers generated successfully', 'تمت توليد الهدايا بنجاح'), 
              'success');
    }).finally(() => {
      this.sending = false;
      this.closeDialog();
    });
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
    this.add_vouchers_dialog = false;
    this.pack = {};
    },
    goToEdit(id) {
     this.$router.push(`/packages/edit/${id}`);
    } 

  }  
}
</script>