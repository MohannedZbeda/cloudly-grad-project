<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register a Category', 'إضافة فئة')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                            <v-text-field
                              :label="$translate('Name', 'الإسم')"
                              :placeholder="$translate('Category Name', 'إسم الفئة')"
                              outlined
                              v-model="form.name"
                              :error-messages="errors.name ? $translate(errors.name[0].en, errors.name[0].ar) : null"
                            ></v-text-field>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register Category', 'إضافة فئة')}}</v-btn>
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
           name: ''
         },
         errors: []
        }
    },
    methods: {
        create() {
          CategoryService.CreateCategory(this.form).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Category registered successfully','تمت إضافة الفئة بنجاح'), 
              'success').then(() => {
             this.$router.push('/categories') 
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