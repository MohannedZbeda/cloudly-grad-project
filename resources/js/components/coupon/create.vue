<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register Coupons', 'إضافة كوبونات')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>                   
                        <v-form>
                            <v-text-field
                              type="number"
                              :label="$translate('Coupons discount percentage', 'نسبة خصم الكوبونات')"
                              :placeholder="$translate('Enter coupons discount percentage', 'يرجى إدخال نسبة خصم الكوبونات')"
                              outlined
                              v-model="form.discount_percentage"
                            ></v-text-field>

                             <v-text-field
                              type="number"
                              :label="$translate('Coupons count', ' عدد الكوبونات')"
                              :placeholder="$translate('How many coupons to generate', 'يرجى إدخال عدد الكوبونات المراد توليدها')"
                              outlined
                              v-model="form.amount"
                            ></v-text-field>
                            <br> <br>
                               <!-- <p class="mt-2" style="color:#d63031" v-if="errors.end_date">  <v-icon color="#d63031" >mdi-calendar-range</v-icon> تاريخ إنتهاء الصلاحية - Expiry Date</p>  -->
                              <p class="mt-2">  <v-icon >mdi-calendar-range</v-icon>{{$translate('Start Date', 'تاريخ بدء الصلاحية')}}</p> 
                              <!-- <p v-if="errors.end_date" style="color : #d63031">{{errors.end_date}}</p>  -->

                              <v-date-picker
                                  v-model="form.start_date"
                                  color="primary"
                                  header-color="primary"
                                  :min="new Date().toJSON()"
                              ></v-date-picker>

                               <br> <br>
                               <!-- <p class="mt-2" style="color:#d63031" v-if="errors.end_date">  <v-icon color="#d63031" >mdi-calendar-range</v-icon> تاريخ إنتهاء الصلاحية - Expiry Date</p>  -->
                              <p class="mt-2">  <v-icon >mdi-calendar-range</v-icon>{{$translate('End Date', 'تاريخ إنتهاء الصلاحية')}}</p> 
                              <!-- <p v-if="errors.end_date" style="color : #d63031">{{errors.end_date}}</p>  -->

                              <v-date-picker
                                  v-model="form.end_date"
                                  color="primary"
                                  header-color="primary"
                                  :min="new Date(form.start_date).toJSON()"
                              ></v-date-picker>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register Coupons', 'إضافة الكوبونات')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import CouponService from '../../services/Coupon';
export default {
    name: 'discount.create',
    data() {
        return {
        form: {
          discount_percentage: null,
          amount: null,
          start_date: '',
          end_date: ''
         }
        }
    },
    methods: {
        create() {
          CouponService.CreateCoupon(this.form).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Coupons registered successfully', 'تمت إضافة الكوبونات بنجاح'), 
              'success').then(() => {
             this.$router.push('/coupons') 
            });
          });
        }
        
    }
}
</script>

<style>

</style>