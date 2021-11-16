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
                            
                             <v-radio-group v-model="form.avatar">
                                <v-radio
                                  key="1"
                                  :label="$translate('Male Avatar', 'أفاتار رجالي')"
                                  value="/images/man.png"
                                ></v-radio>
                                <v-radio
                                  key="2"
                                  :label="$translate('Female Avatar', 'أفاتار نسائي')"
                                  value="/images/woman.png"
                                ></v-radio>
                                <v-img height="40px" width="40px" :src="form.avatar"></v-img>
                              </v-radio-group>
                           
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Save Changes', 'حفظ التغييرات')}}</v-btn>
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
         form: {
           name: '',
           email: '',
           username: '',
           password: '',
           state : 0,
           avatar : '/images/man.png'
         }
        }
    },
    beforeMount() {
     AdminService.GetUser(this.id).then(response => {
        this.form = response.data.admin;
     });  
    },
    methods: {
        async create() {
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