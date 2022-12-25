<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Update an Attribute', 'تعديل خاصية')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                          <v-text-field
                            :label="$translate('Name', 'الإسم')"
                            :placeholder="
                                $translate(
                                    'Attribute Name',
                                    'إسم الخاصية'
                                )
                            "
                            outlined
                            v-model="form.name"
                            :error-messages="errors.name ? $translate(errors.name[0].en, errors.name[0].ar) : null"
                        ></v-text-field>

                            <v-checkbox
                             v-model="form.advanced"
                             :label="$translate('Advanced Attribute ? ', 'خاصية غير أساسية ؟')"
                            ></v-checkbox>
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
import CategoryService from '../../../services/Category';
export default {
    name: 'category.edit',
    data() {
      return {
        id: this.$route.params.id,
        category_id: this.$route.params.category_id,
        form: {
           name: '',
           advanced: false
         },
         errors: []
      }
    },
    beforeMount() {
      CategoryService.GetAttribute(this.category_id, this.id).then(response => {
        this.form.name = response.data.attribute.name;
        this.form.advanced = response.data.attribute.advanced;
     });
    },
    methods: {
        update() {
          const payload = {
          id: this.id,
          category_id: this.category_id,
          ...this.form
          };
          CategoryService.UpdateAttribute(payload).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Attribute Updated successfully','تم تعديل الخاصية بنجاح'), 
              'success').then(() => {
              this.$router.push('/categories/'+this.category_id+'/attributes') 
            });
          }).catch(errors => {
              this.errors = errors.response.data.errors;
          });
        }
  }
}
</script>

