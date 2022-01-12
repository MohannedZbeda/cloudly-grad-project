<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register an Admin', 'إضافة مشرف')}}</v-toolbar-title>
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

                            <v-text-field
                              :label="$translate('Password', 'الرقم السري')"
                              :placeholder="$translate('Enter a password', 'أدخل الرقم السري')"
                              outlined
                              v-model="form.password"
                            ></v-text-field>

                            <v-autocomplete
                            v-model="form.role"
                            :items="roles"
                            item-text="display_name"
                            item-value="id"
                            outlined
                            chips
                            small-chips
                            :label="$translate('Admin Role', 'دور المشرف')"
                          ></v-autocomplete>

                          <v-checkbox
                            v-model="form.state"
                            :label="$translate('Activate Account ? ', 'تفعيل الحساب ؟')"
                          ></v-checkbox>
                            
                           
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register', 'إضافة')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import AdminService from '../../services/Admin'
export default {
    name: 'admin.create',
    data() {
        return {
         roles: [],
         form: {
           role: null,  
           name: '',
           email: '',
           username: '',
           password: '',
           state : 0
         }
        }
    },
    beforeMount() {
      AdminService.GetRoles().then(response => {
        this.roles = response.data.roles;
      });
    },
    methods: {
        async create() {
          AdminService.CreateUser(this.form).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Admin registered successfully', 'تمت إضافة المشرف بنجاح'), 
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