<template>
<div>
<v-btn style="margin : 1.7em" class="primary" @click="goToCreate">{{$translate('Add an Attribute', 'إضافة خاصية')}}</v-btn>  
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
        {
          text: 'Advanced ?',
          sortable: false,
          value: 'en_type',
        },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false }
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
        {
          text: 'خاصية متقدمة ؟',
          sortable: false,
          value: 'ar_type',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false }
      ])"
    :items="attributes"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no attributes..`, 'لا يوجد خصائص..')">
    
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
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
      }
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