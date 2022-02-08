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
                                  <v-icon v-if="attribute.advanced" style="color:#c0392b; cursor:pointer" @click="removeAttribute(attribute.id)">mdi-window-close</v-icon>                          
                                </template>
                              </v-text-field>
                              
                            </div>
                            <br> <br> 
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
         product_id: this.$route.params.product_id,
         categories : [],
         noAttributes: false,
         form: {
           ar_name: '',
           en_name: '',
           price: '',
           attributes: [],
         }
        }
    },
    beforeMount() {
      VariantService.GetVariant(this.id).then(response => {
        this.form = response.data.variant;
        this.form.attributes = response.data.variant.attributes.map(attribute => {
        return {
          id: attribute.id,
          ar_name: attribute.attribute_ar_name,
          en_name: attribute.attribute_en_name,
          advanced: attribute.advanced,
          value: attribute.value
        }
      });
      });
    },
    methods: {
        update() {
          VariantService.UpdateVariant({id : this.id, product_id: this.product_id, ...this.form}).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Product registered successfully', 'تمت تعديل المنتج بنجاح'), 
              'success').then(() => {
             this.$router.push(`/products/${this.product_id}/variants`); 
            });
          });
        }
    }
}
</script>

<style>

</style>