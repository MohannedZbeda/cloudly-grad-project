<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Update a Variant', 'تعديل تنوع منتج')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                       <p v-if="noAttributes" style="color:red">{{$translate(
                         'There are no attributes for this product, Please add some', 
                         'لا توجد خصائص لهذا المنتج، يرجى الإضافة')}}</p>
                        <v-form>
                          <v-select
                            :items="products"
                            item-text="en_name"
                            item-value="id"
                            @input="getAttributes"
                            :label="$translate('Product', 'المنتج')"
                            v-model="form.product_id"
                            outlined
                          ></v-select>
                            <v-text-field
                              :label="$translate('AR Name', 'الإسم بالعربي')"
                              :placeholder="$translate('AR Variant Name', 'إسم المنتج بالعربي')"
                              outlined
                              v-model="form.ar_name"
                            ></v-text-field>

                            <v-text-field
                              dir="ltr"
                              :label="$translate('EN Name', 'الإسم بالإنجليزي')"
                              :placeholder="$translate('EN Variant Name', 'إسم المنتج بالإنجليزي')"
                              outlined
                              v-model="form.en_name"
                            ></v-text-field>
                            <v-divider style="background-color: black"></v-divider>
                            <br> <br>
                            <v-text-field
                              v-for="attribute in form.attributes"
                              :key="attribute.id"
                              :label="$translate(attribute.en_name, attribute.ar_name)"
                              outlined
                              v-model="attribute.value"
                            ></v-text-field>

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
                        <v-btn color="primary" @click="update">{{$translate('Update Variant', 'تعديل منتج')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import VariantService from '../../services/Variant'
export default {
    name: 'variant.edit',
    data() {
        return {
         id: this.$route.params.id,
         categories : [],
         noAttributes: false,
         form: {
           ar_name: '',
           en_name: '',
           price: '',
           product_id: '',
         }
        }
    },
    beforeMount() {
      VariantService.GetProduct(this.id).then(response => {
        this.form = response.data.product;
      });
      VariantService.GetProducts().then(response => {
        this.products = response.data.products;
      });
    },
    methods: {
       getAttributes() {
         const payload = {
           id : this.form.product_id
         }
         VariantService.GetAttributes(payload).then(response => {
           if(!response.data.attributes.length) {
             this.noAttributes = true;
             this.form.attributes = [];
             return;
           }
           this.form.attributes = response.data.attributes.map(attribute => {
             return {
               id: attribute.id,
               ar_name: attribute.ar_name,
               en_name: attribute.en_name,
               value: ''
             }
           });
           this.noAttributes = false;
         });
       },
        update() {
          VariantService.UpdateProduct({id : this.id, ...this.form}).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Product registered successfully', 'تمت إضافة المنتج بنجاح'), 
              'success').then(() => {
             this.$router.push('/variants') 
            });
          });
        }
    }
}
</script>

<style>

</style>