<template>
<div>
<v-btn style="margin : 1.7em" class="primary" @click="goToCreate">{{$translate('Add an Attribute', 'إضافة خاصية')}}</v-btn>  
  <v-data-table
    :headers="headers"
    :items="attributes"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no attributes..`, 'لا يوجد خصائص..')">
    
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
      <v-icon style="margin-right : 10px" @click="goToValues(item.id)">mdi-counter</v-icon>
    </template>
  
  </v-data-table>
  </div>
</template>
<script>    
import CategoryService from '../../../services/Category';

  export default {
    name : 'attribute.index',
    data() {
      return {
      id: this.$route.params.category_id,
      activateDialog: false,
      attributes: [],
      attribute: {},
      form: {
        ar_name: '',
        en_name: ''
      },
      headers: [
        {
          text: this.$translate('AR Name', 'الإسم بالعربي'),
          align: 'start',
          sortable: false,
          value: 'ar_name',
        },
        {
          text: this.$translate('EN Name', 'الإسم بالإنجليزي'),
          sortable: false,
          value: 'en_name',
        },
        { text: this.$translate('Addition Date', 'تاريخ الإضافة'), value: 'created_at', sortable : true },
        { text: this.$translate('Actions', 'العمليات'), value: 'actions', sortable: false }
      ]
      
      } 
    },

    beforeMount() {
      CategoryService.GetAttributes(this.id).then(response => {
        this.attributes = response.data.attributes;
      });
    },
    methods: {
    goToCreate() {
     this.$router.push('/categories/'+this.id+'/attributes/create');
    },
    goToEdit(id) {
     this.$router.push('/categories/'+this.id+'/attributes/edit/' + id);
    },
    goToValues(id) {
     this.$router.push('/categories/'+this.id+'/attributes/'+ id + '/values');
    },
    addAttribute() {
        const payload = {
          category_id: this.id,
          ...this.form
        };
        CategoryService.AddAttribute(payload).then(response => {
         this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Attribute registered successfully', 'تمت إضافة الخاصية بنجاح'), 
              'success').then(() => {
            this.categories.map(cat => {
              if(cat.id == this.category.id)
                cat.attributes.push(response.data.attribute);
            });
            this.additionMode = false;
            this.closeDialog();    
         });

        }).catch(() =>  this.$unexpectedError());
    }      
      

    }  
  }
</script>