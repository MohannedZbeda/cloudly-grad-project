<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register a Variant', 'إضافة تنوع من منتج')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                       <p v-if="noAttributes" style="color:red">{{$translate(
                         'There are no attributes for this product, Please add some', 
                         'لا توجد خصائص لهذا المنتج يرجى الإضافة')}}</p>
                        <v-form>
                          <v-select
                            :items="products"
                            item-text="en_name"
                            item-value="id"
                            @input="getAttributes"
                            :label="$translate('Product', 'المنتج')"
                            v-model="form.category_id"
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
                            <div v-for="attribute in form.attributes" :key="attribute.id">
                              <v-text-field
                                :label="$translate(attribute.en_name, attribute.ar_name)"
                                outlined
                                v-model="attribute.value"
                              >
                                <template v-slot:prepend>
                                  <v-icon style="color:#c0392b; cursor:pointer" @click="removeAttribute(attribute.id)">mdi-window-close</v-icon>                          
                                </template>
                              </v-text-field>
                              
                            </div> 
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
                        <v-btn color="primary" @click="create">{{$translate('Register Variant', 'إضافة')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import VariantService from '../../services/Variant'
export default {
    name: 'variant.create',
    data() {
        return {
         products: [],
         noAttributes: false,
         form: {
          ar_name: '',
          en_name: '',
          product_id: '',
          attributes: [],
          price: null
         }
        }
    },
    beforeMount() {
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
           this.noAttributes = false;
           this.form.attributes = response.data.attributes.map(attribute => {
             return {
               id: attribute.id,
               ar_name: attribute.ar_name,
               en_name: attribute.en_name,
               value: ''
             }
           });
         });
       },
       removeAttribute(id) {
         if(this.form.attributes.length <= 1)
          return;
          this.form.attributes = this.form.attributes.filter(attribute => {
            return attribute.id != id;
          });
      },
        create() {
          VariantService.CreateProduct(this.form).then(() => {
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