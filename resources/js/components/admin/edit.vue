<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Update an Admin', 'تعديل بيانات مشرف')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                            <v-text-field
                              :label="$translate('Name', 'الإسم')"
                              :placeholder="$translate('Full Name', 'الإسم الثلاثي')"
                              outlined
                              v-model="form.name"
                            ></v-text-field>
                            <v-text-field
                              :label="$translate('Email', 'البريد الإلكتروني')"
                              :placeholder="$translate('Enter an email', 'أدخل البريد الإلكتروني')"
                              outlined
                              v-model="form.email"
                            ></v-text-field>

                            <v-text-field
                              :label="$translate('Username', 'إسم المستخدم')"
                              :placeholder="$translate('Enter a username', 'أدخل إسم المستخدم')"
                              outlined
                              v-model="form.username"
                            ></v-text-field>
                            <v-autocomplete
                              v-model="form.role_id"
                              :items="roles"
                              item-text="display_name"
                              item-value="id"
                              outlined
                              chips
                              small-chips
                              :label="$translate('Admin Role', 'دور المشرف')"
                            ></v-autocomplete>
                            <v-text-field
                              :label="$translate('Password', 'الرقم السري')"
                              :placeholder="$translate('Enter a password', 'أدخل الرقم السري')"
                              outlined
                              v-model="form.password"
                            ></v-text-field> 
                             
                             <v-checkbox
                             v-model="form.state"
                             :label="$translate('Activate Account ? ', 'تفعيل الحساب ؟')"
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
import AdminService from '../../services/Admin'
export default {
    name: 'admin.edit',
    data() {
        return {
         id: this.$route.params.id,
         roles: [],
         form: {
           name: '',
           email: '',
           username: '',
           role_id: null,
           password: '',
           state : 0
         }
        }
    },
    beforeMount() {
     AdminService.GetUser(this.id).then(response => {
        this.form = response.data.admin;
     }); 

     AdminService.GetRoles().then(response => {
        this.roles = response.data.roles;
      });
    },
    methods: {
        async update() {
          AdminService.UpdateUser({payload : this.id, ...this.form}).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Admin Updated successfully', 'تم تعديل بيانات المشرف بنجاح'), 
              'success').then(() => {
             this.$router.push('/admins') 
            });
          });
        }
    }
}
</script>

<style>

</style>