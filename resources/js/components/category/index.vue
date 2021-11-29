<template>
  <v-data-table
    :headers="$translate([
        {
          text: 'AR Name',
          align: 'start',
          sortable: false,
          value: 'ar_name',
        },
        {
          text: 'EN Name',
          sortable: false,
          value: 'en_name',
        },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false },
      ], [
        {
          text: 'الإسم بالعربي',
          align: 'start',
          sortable: false,
          value: 'ar_name',
        },
        {
          text: 'الإسم بالإنجليزي',
          sortable: false,
          value: 'en_name',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false },
      ])"
    :items="categories"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no categories..`, 'لا يوجد تصنيفات..')"

  >
        
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
      <v-icon style="margin-right : 10px" @click="goToAttributes(item.id)">mdi-script-text</v-icon>   
    </template>
  
  </v-data-table>
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
      category: {},
      form: {
        ar_name: '',
        en_name: ''
      }

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
    },
    goToAttributes(id) {
     this.$router.push('/categories/'+id+'/attributes');
    },  

    }  
  }
</script>