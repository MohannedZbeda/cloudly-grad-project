<template>
      <div>
    <br> <br>
  <h1 class="text-center">{{$translate('Customer Requests', 'طلبات الزبائن')}}</h1>
  <br> <br>
    <v-data-table
        :headers="
            $translate(
                [
                    {
                        text: 'Customer Name',
                        align: 'start',
                        sortable: false,
                        value: 'customer_name'
                    },
                    {
                        text: 'Subscription Name',
                        align: 'start',
                        sortable: false,
                        value: 'sub_name'
                    },
                    {
                        text: 'Descreption',
                        sortable: false,
                        value: 'des'
                    },
                    {
                        text: 'Request Date',
                        value: 'created_at',
                        sortable: true
                    },
                    {
                        text: 'Status',
                        value: 'stat',
                        sortable: true
                    },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                [
                    {
                        text: 'إسم الزبون ',
                        align: 'start',
                        sortable: false,
                        value: 'customer_name'
                    },
                    {
                        text: 'إسم الإشتراك ',
                        align: 'start',
                        sortable: false,
                        value: 'sub_name'
                    },
                    {
                        text: 'الوصف',
                        sortable: false,
                        value: 'des'
                    },
                    {
                        text: 'تاريخ الطلب',
                        value: 'created_at',
                        sortable: true
                    },
                    {
                        text: 'حالة الطلب',
                        value: 'stat',
                        sortable: true
                    },
                    { text: 'العمليات', value: 'actions', sortable: false }
                ]
            )
        "
        :items="requests"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="$translate(`There's no requests..`, 'لا يوجد طلبات..')"
    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{
                                $translate(
                                    `${request.customer_name}'s Descreption`,
                                    `وصف  ${request.customer_name}`
                                )
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            <h3>
                                {{ request.descreption }}
                            </h3>
                            
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
            </v-toolbar>
        </template>
        <template v-slot:[`item.des`]="{ item }">
            <v-btn class="primary" @click="showDescreption(item)">{{
                $translate("Show Descreption", "عرض الوصف")
            }}</v-btn>
        </template>
        <template v-slot:[`item.stat`]="{ item }">
            {{item.status ? $translate('Excuted', 'تم التنفيذ') : $translate('Not Excuted', 'لم يتم التنفيذ') }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
        <v-tooltip v-if="!item.status" top>
      <template v-slot:activator="{ on, attrs }">
            <v-icon style="margin-right : 10px"  v-bind="attrs"
                v-on="on"
                slot="append"
                @click="showStatDialog(item)"
                >mdi-check-bold</v-icon
            >
            </template>
        <span>{{$translate('Set as excuted', 'تحديد الطلب كمنفذ') }}</span>
    </v-tooltip>
        </template>

    </v-data-table>
    <v-dialog
          v-model="statDialog"
          max-width="500px"
        >
          
          <v-card>
            <v-card-title class="text-h6">
             {{$translate(`Are you sure you want to set the request as excuted ?`, 
             `هل أنت متأكد من أنك تريد تحديد الطلب كمنفذ؟`)}}
            </v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialog">{{$translate('Cancel', 'إلغاء')}}</v-btn>
              <v-btn color="blue darken-1" text @click="changeStatus">{{$translate('Yes', 'أجل')}}</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
    </div>
</template>
<script>
import RequestService from "../../services/Request";

export default {
    name: "request.index",
    data() {
        return {
            sending: false,
            dialog: false,
            statDialog: false,
            requests: [],
            request: {}
        };
    },

    beforeMount() {
        RequestService.GetRequests().then(response => {
            this.requests = response.data.requests;
        });
    },
    methods: {
        showDescreption(item) {
            this.request = item;
            this.dialog = true;
        },
        changeStatus() {
            RequestService.ChangeStatus(this.request.id).then(response => {
                this.requests = response.data.requests;
                this.closeDialog();
            });
        },
        showStatDialog(item) {
            this.request = item;
            this.statDialog = true;
        },
        closeDialog() {
            this.dialog = false;
            this.statDialog = false;
            this.request = {};
        }
    }
};
</script>
