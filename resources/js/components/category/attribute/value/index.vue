<template>
<div>
  
<v-btn style="margin : 1.7em" class="primary" @click="prepareDialog()">{{$translate('Add a Value', 'إضافة قيمة للخاصية')}}</v-btn>  
  <v-data-table
    :headers="headers"
    :items="values"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no values..`, 'لا يوجد قيم لهذه الخاصية..')">
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
             {{editMode ? $translate('Edit Value', 'تعديل قيمة') : $translate('Add Value', 'إضافة قيمة')}}
            </v-card-title>
            <v-card-text>
          <v-form>
            <v-text-field
              :label="$translate('Value', 'القيمة')"
              :placeholder="$translate('Insert value', 'أدخل القيمة')"
              outlined
              v-model="form.value"
            ></v-text-field>
          </v-form>       
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-btn color="blue darken-1" text @click="editMode ? updateValue() : addValue()">{{editMode ? $translate('Save Changes', 'حفظ التغييرات') : $translate('Add Value', 'إضافة القيمة')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="prepareDialog(item)">mdi-pencil</v-icon>
    </template>
  
  </v-data-table>
</div>
</template>
<script>    
import CategoryService from '../../../../services/Category';

  export default {
    name : 'value.index',
    data() {
      return {
      attribute_id: this.$route.params.attribute_id,
      activateDialog: false,
      editMode: false,
      values: [],
      form: {
        value: ''
      },
      headers: [
        {
          text: this.$translate('Value', 'القيمة'),
          align: 'start',
          sortable: false,
          value: 'value',
        },
        { text: this.$translate('Addition Date', 'تاريخ الإضافة'), value: 'created_at', sortable : true },
        { text: this.$translate('Actions', 'العمليات'), value: 'actions', sortable: false }
      ]
      
      } 
    },

    beforeMount() {
      CategoryService.GetValues(this.attribute_id).then(response => {
        this.values = response.data.values;
      });
    },
    methods: {
    prepareDialog(item = null) {
      item ? this.form = item : '';
      this.editMode = item ? true : false;
      this.activateDialog = true;

    },
    closeDialog() {
      this.activateDialog = false;
      this.editMode = false;
      this.form = { value : ''};
    },
    addValue() {
      const payload = {
        attribute_id : this.attribute_id,
        ...this.form
      };
        CategoryService.AddValue(this.attribute_id, payload).then(response => {
         this.values.push(response.data.value);
         this.$swal(
            this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
            this.$translate('Value registered successfully', 'تمت إضافة القيمة بنجاح'), 
          'success').then(() => {
            this.closeDialog();    
          });

        }).catch(() =>  this.$unexpectedError());
    },

    updateValue() {
      const payload = {
        attribute_id : this.attribute_id,
        ...this.form
      };
        CategoryService.UpdateValue(this.attribute_id, payload).then(response => {
         this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Value updated successfully', 'تمت تعديل الخاصية بنجاح'), 
              'success').then(() => {
             this.values.map(value => {
               if(value.id == response.data.value.id)
                value = response.data.value;
             });
            this.closeDialog();    
         });

        }).catch(() =>  this.$unexpectedError());
    }      
      

    }  
  }
</script>