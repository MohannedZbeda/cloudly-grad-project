<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register a Cycle', 'إضافة دورة دفع')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                            <v-text-field
                              :label="$translate('AR Name', ' الإسم بالعربي')"
                              :placeholder="$translate('Cycle AR Name', 'الإسم الدورة بالعربي')"
                              outlined
                              v-model="form.ar_name"
                            ></v-text-field>
                            <v-text-field
                              :label="$translate('EN Name', ' الإسم بالإنجليزي')"
                              :placeholder="$translate('Cycle EN Name', 'الإسم الدورة بالإنجليزي')"
                              outlined
                              v-model="form.en_name"
                            ></v-text-field>
                            <v-text-field
                              :label="$translate('Cycle Months', 'أشهر الخصم في الدورة')"
                              :placeholder="$translate('Months to pass before retrieving payment', 'عدد الأشهر حتى يتم الخصم')"
                              outlined
                              v-model="form.months"
                            ></v-text-field>
                           
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register', 'إضافة')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import CycleService from '../../services/Cycle'
export default {
    name: 'cycle.create',
    data() {
        return {
         form: {
           ar_name: '',
           en_name: '',
           months: null
         }
        }
    },

    methods: {
        async create() {
          CycleService.CreateCycle(this.form).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('Cycle registered successfully', 'تمت إضافة دورة الدفع بنجاح'), 
              'success').then(() => {
             this.$router.push('/cycles') 
            });
          });
        }
    }
}
</script>

<style>

</style>