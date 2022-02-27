<template>
  <v-data-table
    :headers="$translate([
        {
          text: 'Customer Name',
          align: 'start',
          sortable: false,
          value: 'customer_name',
        },
        { text: 'Statament', value: 'show_statement', sortable : false },
        { text: 'State', value: 'shown_text.en', sortable : false },
        { text: 'Actions', value: 'actions', sortable: false },
      ], 
      [
        {
          text: 'إسم الزبون',
          align: 'start',
          sortable: false,
          value: 'customer_name',
        },
        { text: 'الشهادة', value: 'show_statement', sortable : false },
        { text: 'الحالة', value: 'shown_text.ar', sortable : false },
        { text: 'العمليات', value: 'actions', sortable: false },
      ])"
    :items="testimonies"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no testimonies..`, 'لا يوجد شهادات..')"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h6">
             {{$translate(`${testimony.customer_name}'s Testimony`, 
             `شهادة ${testimony.customer_name}`)}}
            </v-card-title>
            <v-card-text>
             {{ testimony.statement }}
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog
          v-model="stateDialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h6">
             {{$translate(`Are you sure you want to ${testimony.shown ? 'disable ' : 'enable '}${testimony.customer_name}'s testimony `, 
             `هل أنت متأكد من أنك تريد ${testimony.shown ? 'إخفاء ' : 'عرض '} شهادة ${testimony.customer_name}`)}}
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
        
        @click="prepareStateDialog(item)"
        :color="item.shown ? '#c0392b' : '#2ecc71'"
      >
      
        {{item.shown  ? 'mdi-eye-off' : 'mdi-eye' }}
      </v-icon>
      <v-icon style="margin-right : 10px"
      @click="goToEdit(item.id)"
      >
        mdi-account-edit
      </v-icon>
    </template>

        <template v-slot:[`item.show_statement`]="{ item }">

      <v-btn
      color="primary"
       @click="showStatement(item)"
      >
        {{$translate("Show Statement", 'عرض الشهادة')}}
      </v-btn>
    </template>

    <template v-slot:[`item.state`]="{ item }">

      <v-icon :color="item.shown ? '#2ecc71' : '#c0392b'">mdi-checkbox-blank-circle</v-icon>
      
    </template>
    
  </v-data-table>
</template>
<script>    

import TestimonyService from '../../services/Testimony';

  export default {
    name : 'testimony.index',
    data() {
      return {
      dialog: false,
      stateDialog: false,
      testimonies: [],
      testimony : {}
      
      } 
    },

    beforeMount() {
      TestimonyService.GetTestimonies().then(response => {
        this.testimonies = response.data.testimonies
      });
    },
    methods: {
    showStatement(item){
    this.testimony = item;
    this.dialog = true;
    },
    prepareStateDialog(item){
    this.testimony = item;
    this.stateDialog = true;
    },
    closeDialog() {
    this.dialog = false;
    this.stateDialog = false;
    this.testimony = {};
    },

    changeState() {
      const payload = {
          id : this.testimony.id,
          shown : !this.testimony.shown, 
        };
        TestimonyService.ChangeState(payload).then(response => {
        this.stateDialog = false;
        this.testimonies = response.data.testimonies;
        this.testimony = {};
        }).catch(() =>  this.$unexpectedError());
    }      
      

    }  
  }
</script>