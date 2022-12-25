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
                              :label="$translate('Question', 'السؤال')"
                              :placeholder="$translate('Question', ' السؤال')"
                              outlined
                              v-model="form.question"
                              :error-messages="errors.question ? $translate(errors.question[0].en, errors.question[0].ar) : null"
                            ></v-text-field>
                            <v-text-field
                              :label="$translate('Answer', 'الجواب')"
                              :placeholder="$translate('Answer', 'الجواب')"
                              outlined
                              v-model="form.answer"
                              :error-messages="errors.answer ? $translate(errors.answer[0].en, errors.answer[0].ar) : null"
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
           question: '',
           answer: ''
         },
         errors: []
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
          }).catch(errors => {
            this.errors = errors.response.data.errors;
          });
        }
    }
}
</script>
