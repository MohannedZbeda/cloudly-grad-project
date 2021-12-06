<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register a Discount', 'إضافة تخفيض')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                      
                        <v-form>
                          <v-autocomplete
                            v-model="form.products"
                            :items="products"
                            item-text="en_name"
                            item-value="id"
                            outlined
                            dense
                            chips
                            small-chips
                            :label="$translate('Discount Products', 'المنتجات الداخلة في التخفيض')"
                            multiple
                          ></v-autocomplete>
                          
                          <v-autocomplete
                            v-model="form.packages"
                            :items="packages"
                            item-text="en_name"
                            item-value="id"
                            outlined
                            dense
                            chips
                            small-chips
                            :label="$translate('Discount Packages', 'الباقات الداخلة في التخفيض')"
                            multiple
                          ></v-autocomplete>

                            <v-select
                               @input="setType()"
                              :items="$translate([{id: 'AMOUNT', title: 'Amount Discount'}, {id: 'PERCENT', title: 'Percentage Discount'}], 
                              [{id: 'AMOUNT', title: 'خصم قيمة'},{id: 'PERCENT', title: 'خصم بالنسبة' }])"
                              :label="$translate('Discount Type', 'نوع الخصم')"
                              outlined
                              item-text="title"
                              item-value="id"
                              v-model="discount_type"
                            ></v-select>

                            <v-text-field
                              :disabled="!typeSelected"
                              type="number"
                              :label="$translate(en.label, ar.label)"
                              :placeholder="$translate(en.placeholder, ar.placeholder)"
                              outlined
                              v-model="amount"
                            ></v-text-field>
                            <br> <br>
                               <!-- <p class="mt-2" style="color:#d63031" v-if="errors.end_date">  <v-icon color="#d63031" >mdi-calendar-range</v-icon> تاريخ إنتهاء الصلاحية - Expiry Date</p>  -->
                              <p class="mt-2">  <v-icon >mdi-calendar-range</v-icon>{{$translate('End Date', 'تاريخ إنتهاء الصلاحية')}}</p> 
                              <!-- <p v-if="errors.end_date" style="color : #d63031">{{errors.end_date}}</p>  -->

                              <v-date-picker
                                  v-model="form.end_date"
                                  color="primary"
                                  header-color="primary"
                                  :min="new Date().toJSON()"
                              ></v-date-picker>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register Discount', 'إضافة خصم')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import DiscountService from '../../services/Discount'
export default {
    name: 'discount.create',
    data() {
        return {
         typeSelected: false,
         products: [],
         packages: [],
         ar: {
          placeholder: 'إختر نوع الخصم',
          label: 'إختر نوع الخصم'
        },
        en: {
          placeholder: 'Choose Discount Type',
          label: 'Choose Discount Type' 
        },
         amount: null,
        discount_type: null,
         form: {
          products: null,
          packages: null,
          end_date: ''  
         }
        }
    },
    beforeMount() {
      DiscountService.GetItems().then(response => {
        this.products = response.data.products;
        this.packages = response.data.packages;
      });
    },
    methods: {
        setType() {
          if(this.discount_type == 'AMOUNT') {
            this.ar.placeholder = 'يرجى إدخال قيمة التخفيض'; 
            this.en.placeholder = 'Enter discount amount'; 
            this.ar.label = 'قيمة التخفيض'; 
            this.en.label = 'Discount Amount'; 
          }
          else {
            this.ar.placeholder = 'يرجى إدخال نسبة التخفيض'; 
            this.en.placeholder = 'Enter discount percentage'; 
            this.ar.label = 'نسبة التخفيض'; 
            this.en.label = 'Discount Percentage';
          }
          this.typeSelected = true;
        },
        create() {
          const payload = {
            ...this.form,
            discount_amount: this.discount_type == 'AMOUNT' ? this.amount : null,
            discount_percentage: this.discount_type == 'PERCENT' ? this.amount : null
          };
          DiscountService.CreateDiscount(payload).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Discount registered successfully', 'تمت إضافة الخصم بنجاح'), 
              'success').then(() => {
             this.$router.push('/discounts') 
            });
          });
        }
        
    }
}
</script>

<style>

</style>