<template>
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
                                    `${request.name}'s Descreption`,
                                    `وصف  ${request.name}`
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

        <template v-slot:[`item.actions`]="{ item }">
            <v-icon style="margin-right : 10px" @click="goToEdit(item.id)"
                >mdi-pencil</v-icon
            >
        </template>

    </v-data-table>
</template>
<script>
import RequestService from "../../services/Request";

export default {
    name: "request.index",
    data() {
        return {
            sending: false,
            dialog: false,
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
        closeDialog() {
            this.dialog = false;
            this.request = {};
        }
    }
};
</script>
