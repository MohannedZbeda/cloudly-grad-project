<template>
  <v-data-table
    :headers="$translate([
        {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Username', value: 'username', sortable : false },
        { text: 'Role', value: 'role', sortable : false },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Account State', value : 'state',  sortable : false },
        { text: 'Actions', value: 'actions', sortable: false },
      ], 
      [
        {
          text: 'الإسم',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'إسم المستخدم', value: 'username', sortable : false },
        { text: 'الدور', value: 'role', sortable : false },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'حالة الحساب', value : 'state',  sortable : false },
        { text: 'العمليات', value: 'actions', sortable: false },
      ])"
    :items="admins"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no admins..`, 'لا يوجد مشرفين..')"

  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        
        <v-spacer></v-spacer>
        <v-dialog
          v-model="activateDialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h6">
             {{$translate(`Are you sure you want to ${admin.state ? 'disable ' : 'enable '}${admin.name} `, 
             `هل أنت متأكد من أنك تريد ${admin.state ? 'تعطيل ' : 'تفعيل '}${admin.name}`)}}
            </v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-btn color="blue darken-1" text @click="changeState">{{$translate('Yes', 'أجل')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
        <template v-slot:[`item.actions`]="{ item }">

      <v-icon
        
        @click="prepareDialog(item)"
        :color="item.state ? '#c0392b' : '#2ecc71'"
      >
      
        {{item.state  ? 'mdi-account-off' : 'mdi-account-check' }}
      </v-icon>
      <v-icon style="margin-right : 10px"
      @click="goToEdit(item.id)"
      >
        mdi-account-edit
      </v-icon>
    </template>

    <template v-slot:[`item.state`]="{ item }">

      <v-icon :color="item.state ? '#2ecc71' : '#c0392b'">mdi-checkbox-blank-circle</v-icon>
      
    </template>
    
  </v-data-table>
</template>
<script>    

import AdminService from '../../services/Admin';

  export default {
    name : 'admin.index',
    data() {
      return {
      activateDialog : false,
      admins: [],
      admin : {}
      
      } 
    },

    beforeMount() {
      AdminService.GetAdmins().then(response => {
        this.admins = response.data.admins
      });
    },
    methods: {
    prepareDialog(item){
    this.admin = item;
    this.activateDialog = true;
    },
    closeDialog() {
    this.admin = {};
    this.activateDialog = false;
    },

    goToEdit(id) {
     this.$router.push('/admins/edit/' + id);
    },
    changeState() {
      const payload = {
          id : this.admin.id,
          state : !this.admin.state, 
        };
        AdminService.ChangeState(payload).then(response => {
        this.activateDialog = false;
        this.admins = response.data.admins;
        this.admin = {}
        }).catch(() =>  this.$unexpectedError());
    }      
      

    }  
  }
</script>