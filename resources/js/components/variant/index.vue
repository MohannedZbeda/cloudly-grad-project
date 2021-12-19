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
          text: 'Discounts',
          sortable: false,
          value: 'discounts',
        },
        {
          text: 'Price Before Discounts',
          sortable: false,
          value: 'price',
        },
        {
          text: 'Price After Discounts',
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
          text: 'التخفيضات',
          sortable: false,
          value: 'discounts',
        },
        {
          text: 'السعر قبل التخفيضات',
          sortable: false,
          value: 'price',
        },
        {
          text: 'السعر بعد التخفيضات',
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

    <template v-slot:[`item.discounts`]="{ item }">
       <v-btn class="primary" @click="showDiscounts(item)">{{$translate('View Discounts', 'عرض التخفيضات')}}</v-btn>  
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
            <v-card-title class="text-h5">{{$translate(`${variant.en_name}'s Discounts`, `تخفيضات ${variant.ar_name}`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <ul>
                <li v-for="(discount, index) in variant.discounts" :key="discount.id">
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
      discounts_dialog : false,
      add_vouchers_dialog : false,
      variants: [],
      variant : {},
      form: {
        quantity: null
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
    showDiscounts(item){
    this.variant = item;
    this.discounts_dialog = true;
    },
    showVoucherForm(item){
    this.variant = item;
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

    removeDiscount(id){
      if(this.sending)
      return; 
      const payload = {
        variant_id: this.variant.id,
        discount_id: id,
      };
      this.sending = true;
     variantservice.RemoveDiscount(payload).then(response => {
       this.variants = response.data.variants;
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
    this.dialog = false;
    this.discounts_dialog = false;
    this.add_vouchers_dialog = false;
    this.variant = {};
    },
    goToEdit(id) {
     this.$router.push(`/products/${this.id}/variants/edit/${id}`);
    },
    goToVariants(id) {
     this.$router.push('/variants/'+id+'/variants');
    },
      

  }  
}
</script>