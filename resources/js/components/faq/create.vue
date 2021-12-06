<template>
        <v-container fluid fill-height>
            <v-layout  align-center justify-center>
                  <v-card  style="min-width : 50%"  class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>{{$translate('Register an FAQ', 'إضافة سؤال')}}</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                            <v-text-field
                              :label="$translate('AR Question', 'السؤال بالعربي')"
                              :placeholder="$translate('AR Question', ' السؤال بالعربي')"
                              outlined
                              v-model="form.ar_question"
                            ></v-text-field>
                            <v-text-field
                              dir="ltr"
                              :label="$translate('EN Question', 'السؤال بالإنجليزي')"
                              :placeholder="$translate('EN Question', ' السؤال بالإنجليزي')"
                              outlined
                              v-model="form.en_question"
                            ></v-text-field>
                            <v-text-field
                              :label="$translate('AR Answer', 'الجواب بالعربي')"
                              :placeholder="$translate('AR Answer', 'الجواب بالعربي')"
                              outlined
                              v-model="form.ar_answer"
                            ></v-text-field>
                              <v-text-field
                              dir="ltr"
                              :label="$translate('EN Answer', 'الجواب بالإنجليزي')"
                              :placeholder="$translate('EN Answer', 'الجواب بالإنجليزي')"
                              outlined
                              v-model="form.en_answer"
                            ></v-text-field>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="create">{{$translate('Register FAQ', 'إضافة السؤال')}}</v-btn>
                     </v-card-actions>
                  </v-card>
            </v-layout>
         </v-container>
</template>


<script>
import FaqService from '../../services/Faq';
export default {
    name: 'faq.create',
    data() {
        return {
         form: {
           ar_question: '',
           ar_answer: '',
           en_question: '',
           en_answer: ''
         }
        }
    },
    methods: {
        create() {
          FaqService.CreateFAQ(this.form).then(() => {
            this.$swal(
              this.$translate('Operation done successfully !', 'تمت العملية بنجاح !'), 
              this.$translate('FAQ registered successfully','تمت إضافة السؤال بنجاح'), 
              'success').then(() => {
             this.$router.push('/faqs') 
            });
          });
        }
    }
}
</script>

<style>

</style>