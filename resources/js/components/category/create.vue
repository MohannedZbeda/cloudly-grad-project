<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register a Category', 'إضافة تصنيف')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                            <v-text-field
                              :label="$translate('AR Name', 'الإسم بالعربي')"
                              :placeholder="$translate('AR Category Name', 'إسم التصنيف بالعربي')"
                              outlined
                              v-model="form.ar_name"
                            ></v-text-field>

                            <v-text-field
                              :label="$translate('EN Name', 'الإسم بالإنجليزي')"
                              :placeholder="$translate('EN Category Name', 'إسم التصنيف بالإنجليزي')"
                              outlined
                              v-model="form.en_name"
                            ></v-text-field>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register Category', 'إضافة تصنيف')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import CategoryService from '../../services/Category';
export default {
    name: 'category.create',
    data() {
        return {
         form: {
           ar_name: '',
           en_name: ''
         }
        }
    },
    methods: {
        create() {
          CategoryService.CreateCategory(this.form).then((response) => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Category registered successfully','تمت إضافة التصنيف بنجاح'), 
              'success').then(() => {
             this.$router.push('/categories') 
            });
          });
        }
    }
}
</script>

<style>

</style>