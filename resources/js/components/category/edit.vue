<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Update a Category', 'تعديل تصنيف')}}</v-toolbar-title>
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
                              dir="ltr"
                              :label="$translate('EN Name', 'الإسم بالإنجليزي')"
                              :placeholder="$translate('EN Category Name', 'إسم التصنيف بالإنجليزي')"
                              outlined
                              v-model="form.en_name"
                            ></v-text-field>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="update">{{$translate('Update Category', 'تعديل تصنيف')}}</v-btn>
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
         id : this.$route.params.id, 
         form: {
           ar_name: '',
           en_name: ''
         }
        }
    },
    beforeMount() {
      CategoryService.GetCategory(this.id).then(response => {
            this.form = response.data.category;
          });
    },
    methods: {
        update() {
          CategoryService.UpdateCategory(this.form).then((response) => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Category updated successfully', 'تم تعديل التصنيف بنجاح'), 
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