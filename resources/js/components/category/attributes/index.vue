<template>
  <v-data-table
    :headers="headers"
    :items="attributes"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no attributes..`, 'لا يوجد خصائص..')"

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
             {{$translate(`${category.name}'s Attributes`, 
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

      
      <v-icon style="margin-right : 10px"
      @click="goToEdit(item.id)"
      >
        mdi-pencil
      </v-icon>
    </template>

    
    
  </v-data-table>
</template>
<script>    

import CategoryService from '../../services/Category';

  export default {
    name : 'category.index',
    data() {
      return {
      activateDialog : false,
      categories: [],
      category: {},
      headers: [
        {
          text: this.$translate('Name', 'الإسم'),
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: this.$translate('Addition Date', 'تاريخ الإضافة'), value: 'created_at', sortable : true },
        { text: this.$translate('Actions', 'العمليات'), value: 'actions', sortable: false },
      ]
      
      } 
    },

    beforeMount() {
      CategoryService.GetCategories().then(response => {
        this.categories = response.data.categories
      });
    },
    methods: {
    prepareDialog(item){
    this.category = item;
    this.activateDialog = true;
    },
    closeDialog() {
    this.category = {};
    this.activateDialog = false;
    },

    goToEdit(id) {
     this.$router.push('/categories/edit/' + id);
    },
    // changeState() {
    //   const payload = {
    //       id : this.admin.id,
    //       state : !this.admin.state, 
    //     };
    //     AdminService.ChangeState(payload).then(response => {
    //     this.activateDialog = false;
    //     this.admins = response.data.admins;
    //     this.admin = {}
    //     }).catch(() =>  this.$unexpectedError());
    // }      
      

    }  
  }
</script>