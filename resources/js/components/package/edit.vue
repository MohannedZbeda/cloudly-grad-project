<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Update a Package', 'تعديل باقة')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                       <!-- <p v-if="noAttributes" style="color:red">{{$translate(
                         'There are no attributes for this category, Please add some', 
                         'لا توجد خصائص لهذا التصنيف، يرجى الإضافة')}}</p> -->
                        <v-form>
                          <v-autocomplete
                            v-model="form.variants"
                            :items="variants"
                            item-text="en_name"
                            item-value="id"
                            outlined
                            dense
                            chips
                            small-chips
                            :label="$translate('Package variants', 'منتجات الباقة')"
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
                            <v-autocomplete
                            v-model="form.cycles"
                            :items="cycles"
                            :item-text="$translate('en_name', 'ar_name')"
                            item-value="id"
                            outlined
                            dense
                            chips
                            small-chips
                            :label="
                                $translate(
                                    'Available Payment Cycles',
                                    'دورات الدفع المتاحة'
                                )
                            "
                            multiple
                        >
                        </v-autocomplete>
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
                        <v-btn color="primary" @click="update">{{$translate('Save Changes', 'حفظ التغييرات')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import PackageService from '../../services/Package'
import CycleService from '../../services/Cycle'
export default {
    name: 'package.edit',
    data() {
        return {
         id: this.$route.params.id,
         cycles: [],
         variants: [],
         form: {
          ar_name: '',
          en_name: '',
          cycles: [],
          variants: [],
          price: null
         },
        }
    },
    beforeMount() {
      PackageService.GetPackage(this.id).then(response => {
        this.form = response.data.package;
      });
      PackageService.GetVariants().then(response => {
        this.variants = response.data.variants;
      });
      CycleService.AllCycles().then(response => {
        this.cycles = response.data.cycles;
      });
    },
    methods: {
        update() {
          this.form.variants = this.form.variants.map(variant => variant['id'] ? variant['id'] : variant);
          this.form.cycles = this.form.cycles.map(cycle => cycle['id'] ? cycle['id'] : cycle);
          const payload = {
            id : this.id,
            ...this.form,
          }
          PackageService.UpdateProduct(payload).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Package updated successfully', 'تمت تعديل الباقة بنجاح'), 
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