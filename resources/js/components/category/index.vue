<template>
  <div>
    <br> <br>
  <h1 class="text-center">{{$translate('Categories', 'الفئات')}}</h1>
  <br> <br>
  
  <v-data-table
    :headers="$translate([
        {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false },
      ], [
        {
          text: 'الإسم',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false },
      ])"
    :items="categories"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no categories..`, 'لا يوجد فئات..')"

  >
        
    <template v-slot:[`item.actions`]="{ item }">
      <v-tooltip top>
      <template v-slot:activator="{ on, attrs }">
        <v-icon v-bind="attrs"
      v-on="on"
      slot="append" 
      style="margin-right : 10px" 
      @click="goToEdit(item.id)">mdi-pencil</v-icon>
    </template>
      <span>{{$translate('Edit Category', 'تعديل الفئة')}}</span>
    </v-tooltip>
      
    </template>
  
  </v-data-table>
</div>
</template>
<script>    

import CategoryService from '../../services/Category';

  export default {
    name : 'category.index',
    data() {
      return {
      activateDialog: false,
      additionMode: false,
      categories: [],
      category: {}
      } 
    },

    beforeMount() {
      CategoryService.GetCategories().then(response => {
        this.categories = response.data.categories;
      });
    },
    methods: {

    prepareDialog(item){
    this.category = item;
    this.activateDialog = true;
    },

    closeDialog() {
    this.activateDialog = false;
    this.category = {};
    },

    goToEdit(id) {
     this.$router.push('/categories/edit/' + id);
    }  

    }  
  }
</script>