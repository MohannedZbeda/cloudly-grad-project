<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Update a Product', 'تعديل منتج')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                          <v-select
                            :items="categories"
                            item-text="en_name"
                            item-value="id"
                            @input="getAttributes"
                            :label="$translate('Product Category', 'تصنيف المنتج')"
                            v-model="form.category_id"
                            outlined
                          ></v-select>
                            <v-text-field
                              :label="$translate('AR Name', 'الإسم بالعربي')"
                              :placeholder="$translate('AR Product Name', 'إسم المنتج بالعربي')"
                              outlined
                              v-model="form.ar_name"
                            ></v-text-field>

                            <v-text-field
                              dir="ltr"
                              :label="$translate('EN Name', 'الإسم بالإنجليزي')"
                              :placeholder="$translate('EN Product Name', 'إسم المنتج بالإنجليزي')"
                              outlined
                              v-model="form.en_name"
                            ></v-text-field>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="update">{{$translate('Update Product', 'تعديل منتج')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import ProductService from '../../services/Product'
export default {
    name: 'product.edit',
    data() {
        return {
         id: this.$route.params.id,
         form: {
           ar_name: '',
           en_name: '',
           category_id: ''
         }
        }
    },
    beforeMount() {
      ProductService.GetProduct(this.id).then(response => {
        this.form = response.data.product;
      });
    },
    methods: {
        update() {
          ProductService.UpdateProduct({id : this.id, ...this.form}).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Product registered successfully', 'تمت إضافة المنتج بنجاح'), 
              'success').then(() => {
             this.$router.push('/products') 
            });
          });
        }
    }
}
</script>

<style>

</style>