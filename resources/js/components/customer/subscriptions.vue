<template>
     <div>
    <br> <br>
  <h1 class="text-center">{{$translate('Subscriptions For ( '+this.$store.state.customerName+' )', 'إشتراكات الزبون ( '+this.$store.state.customerName+' )')}}</h1>
  <br> <br>
    <v-data-table
        :headers="
            $translate(
                [
                    {
                        text: 'Name',
                        align: 'start',
                        sortable: false,
                        value: 'name'
                    },
                    { text: 'Record', value: 'sub_record', sortable: false },
                    { text: 'Cycle', value: 'cycle', sortable: true },
                    { text: 'Start Date', value: 'start_date', sortable: false },
                    { text: 'Expiry Date', value: 'expiry_date', sortable: false },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                [
                    {
                        text: 'الإسم',
                        align: 'start',
                        sortable: false,
                        value: 'name'
                    },
                    { text: 'السجل', value: 'sub_record', sortable: false },
                    { text: 'دورة الدفع', value: 'cycle', sortable: true },
                    { text: 'تاريخ البدء', value: 'start_date', sortable: false },
                    { text: 'تاريخ النهاية', value: 'expiry_date', sortable: false },
                    { text: 'العمليات', value: 'actions', sortable: false }
                ]
            )
        "
        :items="subs"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="
            $translate(`There are no subscriptions..`, 'لا يوجد إشتراكات..')
        "
    >
        <template v-slot:[`item.sub_record`]="{ item }">
            <v-btn class="primary" @click="prepareDialog(item)">{{
                $translate("Show Record", "عرض السجل")
            }}</v-btn>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
   <v-tooltip top>
      <template v-slot:activator="{ on, attrs }">
            <v-icon
                style="margin-right : 10px"
                :color="item.status ? '#2ecc71' : '#c0392b'" 
                @click="prepareStatusDialog(item)"
                v-bind="attrs"
                v-on="on"
                slot="append"
            >
                mdi-power
            </v-icon>
        </template>
        <span>{{item.status ? $translate('Disable Subscription', 'تعطيل الإشتراك') : $translate('Enable Subscription', 'تفعيل الإشتراك')}}</span>
    </v-tooltip>         
        </template>
    </v-data-table>
    <v-dialog
          v-model="activateDialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h6">
             {{$translate(`Are you sure you want to ${sub.status ? 'disable ' : 'enable '}${sub.name} `, 
             `هل أنت متأكد من أنك تريد ${sub.status ? 'تعطيل ' : 'تفعيل '}${sub.name}`)}}
            </v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-btn color="blue darken-1" text @click="changeStatus">{{$translate('Yes', 'أجل')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
    <v-dialog v-model="dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{
                                $translate(
                                    `${sub.name}'s Record`,
                                    `سجل  ${sub.name}`
                                )
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            <div v-html="sub.record"></div>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="closeDialog"
                                >{{ $translate("Cancel", "إلغاء") }}</v-btn
                            >
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
     </div>
</template>
<script>
import CustomerService from "../../services/Customer";

export default {
    name: "customer.subscriptions",
    data() {
        return {
            dialog: false,
            activateDialog: false,
            subs: [],
            sub: {}
        };
    },

    beforeMount() {
        CustomerService.GetSubs(this.$route.params.id).then(response => {
            this.subs = response.data.subs;
        });
    },
    methods: {
        prepareDialog(item) {
            this.sub = item;
            this.dialog = true;
        },
        showRecord(record) {
            let recordArray = record.split("\n");
            let finalShape = `<p>`; 
            recordArray.each(record => {
              finalShape+= `${record} <br>`;
            });
            finalShape+= '</p>';
            
        },
        prepareStatusDialog(item) {
            this.sub = item;
            this.activateDialog = true;
        },
        changeStatus() {
            CustomerService.ChangeSubStatus(this.$route.params.id,{id:this.sub.id}).then(response => {
            this.subs = response.data.subs;
            this.closeDialog();
        });
        },
        closeDialog() {
            this.dialog = this.activateDialog = false;
        }
    }
};
</script>
