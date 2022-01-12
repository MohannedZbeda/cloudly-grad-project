<template>
  <div>
    
    <h2 style="text-align:center; margin-top: 2em">{{$translate('Product Variants', 'تفرعات المنتج')}}</h2> <br> 
<v-btn style="margin-start: 4em"  class="primary" @click="goToCreate">{{$translate('Add a Variant', 'إضافة تفرع')}}</v-btn> <br> <br> 
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
          text: 'Discount',
          sortable: false,
          value: 'discount',
        },
        {
          text: 'Price Before Discount',
          sortable: false,
          value: 'price',
        },
        {
          text: 'Price After Discount',
          sortable: false,
          value: 'new_price',
        },
        {
          text: 'Product',
          sortable: false,
          value: 'product',
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
          text: 'التخفيض',
          sortable: false,
          value: 'discount',
        },
        {
          text: 'السعر قبل التخفيض',
          sortable: false,
          value: 'price',
        },
        {
          text: 'السعر بعد التخفيض',
          sortable: false,
          value: 'new_price',
        },
        {
          text: 'المنتج',
          sortable: false,
          value: 'product',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false }
      ])"
    :items="variants"
    sort-by="created_at"
    calculated-width="true"
    :no-data-text="$translate(`There's no variants..`, 'لا يوجد منتجات..')"
  >

    

    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right: 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
      <v-icon style="margin-right: 10px" @click="showVoucherForm(item)">mdi-gift</v-icon>
      <v-icon v-if="item.vouchers.length" style="margin-right: 10px" @click="copyVouchers(item.vouchers)">mdi-content-copy</v-icon>

    </template>

    <template v-slot:[`item.attributes`]="{ item }">
       <v-btn class="primary" @click="showAttributes(item)">{{$translate('View Attributes', 'عرض الخصائص')}}</v-btn>  
    </template>

    <template v-slot:[`item.discount`]="{ item }">  
       <div v-if="item.discount">
         <b>{{item.discount}}  |  </b>
        <v-icon style="margin-right: 10px; color: #c0392b" @click="showDiscountDeleteForm(item)">mdi-delete</v-icon>
       </div>
       <div v-else>
         <b>{{$translate('There is none', 'لايوجد')}}   | </b>
        <v-icon style="color: #2ecc71" @click="showDiscountForm(item, false)">mdi-plus</v-icon>
       </div>
    </template>

    
    
  </v-data-table>
  <template>
      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`${variant.en_name}'s Attributes`, `خصائص ${variant.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <ul>
                <li v-for="attribute in variant.attributes" :key="attribute.id">
                  <b>{{$translate(attribute.attribute_en_name, attribute.attribute_ar_name)}}</b> : {{attribute.value}}
                </li>
              </ul>
                <v-divider style="background-color: black"></v-divider>
                <br> <br>
                <h2>{{$translate('Payment Cycles', 'دورات الدفع')}}</h2>
                <ul>
                <li v-for="cycle in variant.cycles" :key="cycle.id">
                  <b>{{$translate(cycle.en_name, cycle.ar_name)}}</b>
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
          v-model="delete_discount_dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate('Delete Discount', 'حذف تخفيض')}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              {{$translate(`Are you sure you want to delete ${variant.en_name}'s Discount ?`, `  هل أنت متأكد من أنك تريد حذف تخفيض المنتج ${variant.ar_name} `)}}
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="removeDiscount">{{$translate('Yes', 'نعم')}}</v-btn>
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
            <v-card-title class="text-h5">{{$translate(`Add Vouchers to ${variant.en_name}`, `إضافة هدايا ل ${variant.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <b>{{$translate('Gifs are codes to be given to chosen users, When used, The codes give the chosen variant',
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

      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="add_discount_dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`Add Discount to ${variant.en_name}`, `إضافة تخفيض ل ${variant.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <v-form>
                <v-text-field
                  :label="$translate('Discount Percentage', 'نسبة التخفيض')"
                  outlined
                  type="number"
                  v-model="form.discount_percentage"
                ></v-text-field>
  
              </v-form>
                
            </v-card-text>
             <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="addDiscount">{{$translate('Add Discount', 'إضافة التخفيض')}}</v-btn>
              </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
   
    </template>
</div>
</template>
<script>    
import Variantservice from '../../services/Variant';
  export default {
    name : 'variant.index',
    data() {
      return {
      id: this.$route.params.id,
      sending: false,
      dialog : false,
      delete_discount_dialog: false,
      add_vouchers_dialog : false,
      add_discount_dialog: false,
      variants: [],
      variant : {},
      form: {
        quantity: null,
        discount_percentage: null
      }

      } 
    },

    beforeMount() {
      Variantservice.GetVariants(this.id).then(response => {
        this.variants = response.data.variants
      });
    },
    methods: {
    goToCreate() {
      return this.$router.push(`/products/${this.id}/variants/create`);
    },  
    showAttributes(item) {
    this.variant = item;
    this.dialog = true;
    },
    showDiscountForm(item) {
    this.variant = item;
    this.add_discount_dialog = true;
    },
    showVoucherForm(item){
    this.variant = item;
    this.add_vouchers_dialog = true;
    },
    showDiscountDeleteForm(item){
    this.variant = item;
    this.delete_discount_dialog = true;
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

    removeDiscount(){
      if(this.sending)
      return; 
      const payload = {
        variant_id: this.variant.id,
      };
      this.sending = true;
     Variantservice.RemoveDiscount(payload).then(response => {
       this.variants = response.data.variants;
     }).finally(() => {
       this.sending = false;
       this.closeDialog();
       });
    },

    addDiscount(){
      if(this.sending)
      return; 
      const payload = {
        variant_id: this.variant.id,
        discount_percentage: this.form.discount_percentage
      };
      this.sending = true;
     Variantservice.AddDiscount(payload).then(response => {
       this.variants = response.data.variants;
       this.$swal(
          this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
          this.$translate('Discount Added successfully', 'تم إضافة التخفيض بنجاح'), 
          'success');
     }).finally(() => {
       this.sending = false;
       this.closeDialog();
       });
    },

   addVouchers() {
    if(this.sending)
     return;
    this.sending = true;
    const payload = {
      quantity: this.form.quantity,
      variant_id: this.variant.id
    }
    Variantservice.AddVouchers(payload).then(response => {
      this.variants = response.data.variants;
      this.$swal(
          this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
          this.$translate('Vouchers generated successfully', 'تمت توليد الهدايا بنجاح'), 
          'success');
    }).finally(() => {
      this.sending = false;
      this.closeDialog();
    });
    },
    closeDialog() {
    this.dialog = 
    this.discounts_dialog = 
    this.add_vouchers_dialog = 
    this.add_discount_dialog = 
    this.delete_discount_dialog = false;
    this.variant = {};
    },
    goToEdit(id) {
     this.$router.push(`/products/${this.id}/variants/edit/${id}`);
    },
      

  }  
}
</script>