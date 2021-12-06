<template>
  <v-data-table
    :headers="$translate([
        {
          text: 'AR Question',
          align: 'start',
          sortable: false,
          value: 'ar_question',
        },
        {
          text: 'AR Answer',
          sortable: false,
          value: 'ar_answer',
        },
        {
          text: 'EN Question',
          align: 'start',
          sortable: false,
          value: 'en_question',
        },
        {
          text: 'EN Answer',
          sortable: false,
          value: 'en_answer',
        },
        { text: 'Addition Date', value: 'created_at', sortable : true },
        { text: 'Actions', value: 'actions', sortable: false },
      ], [
        {
          text: 'السؤال بالعربي',
          align: 'start',
          sortable: false,
          value: 'ar_question',
        },
        {
          text: 'الجواب بالعربي',
          align: 'start',
          sortable: false,
          value: 'ar_answer',
        },
        {
          text: 'السؤال بالإنجليزي',
          sortable: false,
          value: 'en_question',
        },
        {
          text: 'الجواب بالإنجليزي',
          sortable: false,
          value: 'en_answer',
        },
        { text: 'تاريخ الإضافة', value: 'created_at', sortable : true },
        { text: 'العمليات', value: 'actions', sortable: false },
      ])"
    :items="faqs"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no FAQs..`, 'لا يوجد أسئلة..')"

  >
  <template v-slot:top>
      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h5">{{$translate(`Delete ${faq.en_question}  ?`, `حذف ${faq.ar_question} ؟`)}}<br>
            </v-card-title>
            <v-card-text dir="rtl"> 
              <b>{{$translate(`Are you sure you want to delete ${faq.en_question} ? `,
                 `هل أنت متأكد أنك تريد حذف ${faq.ar_question}`
                )}}
              
              </b>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="deleteFAQ">{{$translate('Yes', 'أجل')}}</v-btn>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
  
    </template>
        
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="goToEdit(item.id)">mdi-pencil</v-icon>
      <v-icon style="color:#c0392b" @click="prepareDialog(item)">mdi-delete</v-icon>
    </template>
  
  </v-data-table>
</template>
<script>    

import FaqService from '../../services/Faq';

  export default {
    name : 'faq.index',
    data() {
      return {
      dialog: false,
      faqs: [],
      faq: {}
      } 
    },

    beforeMount() {
      FaqService.GetFAQS().then(response => {
        this.faqs = response.data.faqs;
      });
    },
    methods: {    
    goToEdit(id) {
     this.$router.push('/faqs/edit/' + id);
    },
    prepareDialog(item) {
     this.faq = item;
     this.dialog = true;
    },
    closeDialog() {
     this.faq = {};
     this.dialog = false;
    },
    deleteFAQ() {
      FaqService.DeleteFAQ(this.faq.id).then(response => {
        this.faqs = response.data.faqs;
        this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('FAQ deleted successfully', 'تم حذف السؤال بنجاح'), 
              'success');
      }).finally(() => {
        this.faq = {};
        this.dialog = false;
      });
    }


    }  
  }
</script>