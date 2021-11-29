<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register a Package', 'إضافة باقة')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                       <!-- <p v-if="noAttributes" style="color:red">{{$translate(
                         'There are no attributes for this category, Please add some', 
                         'لا توجد خصائص لهذا التصنيف، يرجى الإضافة')}}</p> -->
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
                            :label="$translate('Package Products', 'منتجات الباقة')"
                            multiple
                          ></v-autocomplete>

                            <v-text-field
                              :label="$translate('AR Name', 'الإسم بالعربي')"
                              :placeholder="$translate('AR Package Name', 'إسم الباقة بالعربي')"
                              outlined
                              v-model="form.ar_name"
                            ></v-text-field>

                            <v-text-field
                              dir="ltr"
                              :label="$translate('EN Name', 'الإسم بالإنجليزي')"
                              :placeholder="$translate('EN Package Name', 'إسم الباقة بالإنجليزي')"
                              outlined
                              v-model="form.en_name"
                            ></v-text-field>
                            <v-divider style="background-color: black"></v-divider>
                            <br> <br>
                            <v-text-field
                              :label="$translate('Price', 'السعر')"
                              outlined
                              type="number"
                              v-model="form.price"
                            ></v-text-field>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register Package', 'إضافة باقة')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import PackageService from '../../services/Package'
export default {
    name: 'package.create',
    data() {
        return {
         products: [],
         form: {
          ar_name: '',
          en_name: '',
          products: [],
          price: null
         }
        }
    },
    beforeMount() {
      PackageService.GetProducts().then(response => {
        this.products = response.data.products;
      });
    },
    methods: {
        create() {
          PackageService.CreateProduct(this.form).then( response => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Package registered successfully', 'تمت إضافة الباقة بنجاح'), 
              'success').then(() => {
             this.$router.push('/packages') 
            });
          });
        }
        
    }
}
</script>

<style>

</style>