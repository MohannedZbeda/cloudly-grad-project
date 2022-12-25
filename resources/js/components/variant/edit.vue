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
                            :label="$translate('Name', 'الإسم')"
                            :placeholder="
                                $translate(
                                    'Variant Name',
                                    'إسم المنتج'
                                )
                            "
                            outlined
                            v-model="form.name"
                            :error-messages="errors.name ? $translate(errors.name[0].en, errors.name[0].ar) : null"
                        ></v-text-field>
                        
                        <v-divider style="background-color: black"></v-divider>
                        <br />
                        <br />
                        <v-row
                                v-for="attribute in form.attributes"
                                :key="attribute.id"
                            >
                                <v-col cols="2" sm="2" md="1">
                                    <h3 style="margin-top: 1em">
                                        {{
                                            attribute.name
                                            
                                        }}
                                        : 
                                    </h3>
                                </v-col>
                                        
                                <v-col cols="2" sm="2" md="3">
                                    <v-select
                                        :label="
                                            $translate(
                                                'Value',
                                                'القيمة'
                                            )
                                        "
                                        outlined
                                        :items="attribute.values"
                                        item-text="value"
                                        item-value="id"
                                        v-model="attribute.value_id"
                                    ></v-select>
                                </v-col>
                                <br />
                                <br />
                            </v-row>
                        <br />
                        <br />
                        <v-divider style="background-color: black"></v-divider>
                        <br />
                        <br />
                        <v-text-field
                            :label="$translate('Price', 'السعر')"
                            outlined
                            type="number"
                            min="0"
                            v-model="form.price"
                            :error-messages="errors.price ? $translate(errors.price[0].en, errors.price[0].ar) : null"
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
           name: '',
           price: '',
           attributes: []
         },
         errors: []
        }
    },
    beforeMount() {
      VariantService.GetVariant(this.id).then(response => {
        this.form = response.data.variant;
        console.log(response.data.variant.attributes);
        this.form.attributes = response.data.variant.attributes.map(attribute => {
        return {
          id: attribute.id,
          name: attribute.name,
          advanced: attribute.advanced,
          values: attribute.values,
          value_id: attribute.value_id
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
          }).catch(errors => {
            this.errors = errors.response.data.errors;
          });
        }
    }
}
</script>

<style>

</style>